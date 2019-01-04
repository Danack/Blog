
A common anti-pattern (aka mistake) people make when trying to write re-usable code, is to try and force either an interface or abstract class where it isn't appropriate. The question below is paraphrased from a [Stackoverflow question](http://stackoverflow.com/q/33605630/778719)

<!-- end_preview -->

### The question

<i>
    I have some 'market place' classes that interact with the APIs of marketplaces like Ebay, Amazon etc.

    I want to make these classes define some mandatory functions such as createProduct, updateProduct, getCategories, getOrders. Each of these marketplaces requires different format of data and so the functions require different types and numbers of parameters. For example:
</i>
{highlightCode}
// Interface for Ebay
interface Marketplace {
    public function createProduct($product, $multi);
}

// Interface for Amazon
interface Marketplace {
    public function createProduct(array $products, $multi, $variations);
}
{/highlightCode}

<i>
In such case I cannot implement a single 'Marketplace' interface as each of the implementations is different. How should I create an interface for these classes?
</i>

### Analysis of the problem

The original poster's code is trying to put both of these things into a single interface:

{highlightCode}
interface Marketplace {
    public function createProduct($product, $multi);
    // or
    public function createProduct(array $products, $multi, $variations);
}
{/highlightCode}


The fundamental problem is that these are just inherently incompatible interfaces.

Each 'market place' has different capabilities (e.g. Amazon allows 'multiple' purchase) and so the methods for creating products need to have different parameters.

Although you could theoretically come up with an abstracted interface, that would abstract away all of the details, that would almost certainly be horrible as the abstraction would just be incorrect and misleadingly so for at least one of those objects.

What we need to recognise is that this is a case where the application is building up information
during it's operation. In particular, the functions that are required to do the uploading cannot
be determined until some other code is run. The OP is trying to solve this problem by making the two
different sets of function have the same interface, so that it is irrelevant which is chosen to
be run during the operation of the application, as either set of functionality can be swapped in
for another.

The much better solution is just to acknowledge that this is an application that needs to build up
information of what needs to be executed _INTERNALLY_ to the program's execution, and to work with a
'framework' that has the capability to handle this build up of information, such as [Tier](https://github.com/danack/tier).


### How to refactor the code - 1st stage

We should acknowledge that the interface isn't common and should be done by two separate
functions. Lets imagine that the use-case is that someone has a webpage where they can upload
some images and text, with prices and then select to upload that to either Amazon or Ebay or both.

So the first part of the program is to figure out which uploaders need calling, and the uploading part
is separate:

{highlightCode}
// Upload a list of product to Amazon
function uploadAmazonProducts(AmazonClient $ac, ProductList $productList) {
...
}
// Upload a list of products to Ebay
function uploadEbayProducts(EbayClient $ec, ProductList $productList) {
...
}

// Figure out which uploaders need to be run.
function determineUploaders(UserInput $userInput) {
    $uploaderList = [];
    if ($userInput->isAmazonSelected() == true) {
        $uploaderList[] = 'uploadAmazonProducts'
    }
    if ($userInput->isEbaySelected() == true) {
        $uploaderList[] = 'uploadEbayProducts'
    }

    return $uploaderList;
}

// The product list would need to come from the users input.
$injector->delegate(ProductList::class, 'createProductListFromUserInput');

// Get the list of uploaders to run.
$uploaderList = $injector->execute('determineUploaders');

// Run each of them.
foreach ($uploaderList as $uploader) {
    // We execute each of the uploaders as appropriate. The first stage of
    // 'determineUploaders' has no knowledge of what the 'uploader' callables
    // require, only their name. The injector does all the work to provide
    // the dependencies for each uploader.
    $injector->execute($uploader);
}
{/highlightCode}


### How to refactor the code - 2nd stage

So separating the functions that uploaded to Amazon/Ebay is nice...but I would strongly suspect that the 'ProductList' is also a bad abstraction. There are probably features for product lists that are possible to do on Amazon, that are not possible to do on Ebay and vice-versa. So again, using a common abstraction between the two leads to at least one of the abstraction being either misleading or flat out wrong.

So let us separate the two of them as well, using the delegate functionality. These factory functions are not abstract at all, they create ProductLists specific to each retailer.

{highlightCode}
{literal}

function createAmazonProductListFromUserInput(UserInput $ui) : AmazonProductList {
    return AmazonProductList::fromUserInput($ui);
}

function createEbayroductListFromUserInput(UserInput $ui) : EbayProductList {
    return EbayProductList::fromUserInput($ui);
}

// Now set the uploading functions to have dependencies on their specific ProductList
// The Amazon uploader depends on an AmazonProductList
function uploadAmazonProducts(
    AmazonClient $ac,
    AmazonProductList $productList
) {...}

// The Ebay uploader depends on an EbayProductList
function uploadEbayProducts(
    EbayClient $ec,
    EbayProductList $productList
) {...}


// Tell the injector how to create each of those specific product lists.
$injector->delegate('AmazonProductList', 'createAmazonProductListFromUserInput');
$injector->delegate('EbayProductList', 'createEbayProductListFromUserInput');

// This code is the same as before.
function determineUploaders(UserInput $userInput) {
    $uploaderList = [];
    if ($userInput->isAmazonSelected() == true) {
        $uploaderList[] = 'uploadAmazonProducts'
    }
    if ($userInput->isEbaySelected() == true) {
        $uploaderList[] = 'uploadEbayProducts'
    }

    return $uploaderList;
}

// The first part of the program execution determines what needs to be run.
$uploaderList = $injector->execute('determineUploaders');

// The second part of the program ex
foreach ($uploaderList as $uploader) {
    $injector->execute($uploader);
}

{/literal}
{/highlightCode}

Yay! We have perfectly understandable code, without any need for abstractions!

Don't get me wrong - abstractions are lovely when they are correct. But when they are inherently the wrong solution to a problem, you shouldn't force yourself to use them..

Note - the solution above is completely analyzable by static code analysis tools, which is great, as static analysis can help prevent a whole class of errors in programs. For example, if any call to a function is missing a parameter, the static analyzer would detect that.

This solution requires that you use a framework that allows you to run multiple pieces of code through the DIC. That might be a bit difficult with traditional frameworks like Symfony or Zend. With the {linkTier() | nofilter} framework it is trivial to run the separates bit of code, with the DIC able to inject the dependencies for each of them.

### Bad solutions to this problem

I find myself forced to comment on the accepted answer for that question.

<i>
    A usual way to resolve this kind of problem is to define an optionsResolver which is passed to each one of your class in order to be initialized...To see a full example of implementation, <a href=\"http://symfony.com/doc/current/components/options_resolver.html\" target='_blank'>take a look at Symfony2.</a>
</i>

This is known as [Yo' Dawgging](http://www.urbandictionary.com/define.php?term=Yo+Dawg) as in \"<i>Yo dawg, I heard yo like coding so we put a language in yo language so yo can code while yo code.\"</i>

Instead of solving this with just code, that solution is solving it with 'code that runs code' i.e. creating a meta-level programming solution. This means that simple static code analysis tools cannot be run on the code, as it's not possible to detect whether all the required options for a 'marketplace' object will be set.

Worse than this that, it just makes the code really hard to think about. Without going to look at the internal lines of code that make up the resolver function:

{highlightCode}
protected function setResolver(OptionsResolver $resolver)
{
    $resolver
    ->setRequired(array('product', 'multi'))
    ->setAllowedTypes('product', 'string')
    ->setAllowedTypes('multi', 'boolean')
    ->setDefaults(array('multi' => true));
}
{/highlightCode}

it is impossible to understand what parameters need to be set. Making code be hard to reason about like this is a very bad trade-off.

