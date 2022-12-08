Some programming patterns I follow are slightly unorthodox, at least in the sense that they are not patterns followed by the majority of PHP programmers. One of these unorthodoxies is that I do not believe controller should be aware of HTTP Request objects.

Instead I use ['interface segregation'](https://en.wikipedia.org/wiki/Interface_segregation_principle) to make it so that the controllers can be decoupled from Request objects.

<!-- end_preview -->

The reason to do this are:

* It makes your controllers easier to reason about.

* It makes it easier to write tests for your controllers.

* It decouples your controller code from the concept of HTTP request/response, which makes it easier to re-use that code.

The example below hopefully shows wtf I mean.

## Example controller

Imagine you have a SearchController that allows users to search some sort of data source. First I want you to look at the signature of the controller, without the actual body of the method:


```
class SearchController {
    function search(DataSource $dataSource, Request $request) {
    ...
    }
}
```



Q: Can you tell how the 'Request' object is being used in the controller method?

A: Nope.

To be able to understand how the request object is being used by the controller you need to inspect all of the lines of code inside the controller. This isn't a massive burden for a single controller, but:

* it makes it harder to write tests. To be able to create a mock/stub request object that can be used to test the various aspects of the controller, you need to hold that info in your mind.

* It couples the controller directly with the request object. Imagine we wanted to change this controller code to be a background task.

Ok, so now let's look at what the controller is actually using the request object for.



```
class SearchController
{
    function search(Request $request, DataSource $dataSource)
    {
        $queryParams = $request->getQueryParams();
        if (!array_key_exists('searchTerms', $queryParams)) {
            $message = "Parameter [searchTerms] is not available";
            throw new ParamsMissingException($message);
        }
        $searchTerms = $queryParams['searchTerms'];

        $searchOptions = [];
        $searchOptions['keywords'] = explode(',', $searchTerms);

        return $dataSource->searchForItems($searchOptions);
    }
}
```


Ok, now you can see that the only thing that the controller is using the server request for, is to be able to pull variables from the query params of the request.

This means that of the approximtely 30 methods that are available on the Request object, only the `getQueryParams` method is being used.

The controller doesn't actually need access to all of the methods that are availble on the request class. So let's extract a simple interface that provides only the required functionality.

### Extracting an interface

We'll extract an interface called VariableMap and also refactor the controller code to use it.



```
interface VariableMap
{
    /**
    * @throws ParamsMissingException
    */
    public function getVariable(string $variableName) : string;
}

class SearchController
{
    function search(VariableMap $variableMap, DataSource $dataSource)
    {
        $searchTerms = $variableMap->getVariable('searchTerms');
        $searchOptions = [];
        $searchOptions['keywords'] = explode(',', $searchTerms);

        return $dataSource->searchForItems($searchOptions);
    }
}
```


I hope you can agree that this is not a complex interface. In fact for a lot of interfaces that are 'extracted' from alrady written code, a large proportion of them will be single method interfaces like this one.

Ok, we now need to create an implementation that implements this interface. The first we'll create is the one that would be used in production to allow the `searchTerms` to be pulled out of the HTTP request.


```
use Psr\\Http\\Message\\ServerRequestInterface;

class PSR7VariableMap implements VariableMap
{
    /** @var ServerRequestInterface */
    private $serverRequest;

    public function __construct(ServerRequestInterface $serverRequest)
    {
        $this->serverRequest = $serverRequest;
    }

    public function getVariable(string $variableName) : string
    {
        $queryParams = $this->serverRequest->getQueryParams();
        if (array_key_exists($variableName, $queryParams) === false) {
            $message = "Parameter [$variableName] is not available";
            throw new ParamMissingException($message);
        }

        return $queryParams[$variableName];
    }
}

// If you are using Auryn or any other DIC system, you would
// need to alias the VariableMap to the specific implementation.
$injector->alias('VariableMap', 'PSR7VariableMap');

```


However for testing we can don't need to touch an actual request object.

Instead let's make a class that implements the VariableMap interface, but instead of reading the values from a complex object like Request, instead it will take an array of key-values as the sole construction parameter.



```
class ArrayVariableMap implements VariableMap
{
    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }

    public function getVariable(string $variableName) : string
    {
        if (array_key_exists($variableName, $this->variables) === false) {
            $message = "Parameter [$variableName] is not available";
            throw new ParamMissingException($message);
        }

        return $this->variables[$variableName];
    }
}
```


The ArrayVariableMap implementation makes it much easier to write unit tests for this controller.

Instead of having to create a mock object for the Request, and have to both understand __and__ remember which of the methods are being used by the controller, we can instead just use the ArrayVariableMap implementation directly.

## Testing with Auryn

Here is the code needed to test this controller before extracting and segregating the interface:



```
/**
* Returns the keywords of the search terms as the results.
* @package Article\\InterfaceSegregation\\Step2
*/
class EchoDataSource implements DataSource
{
    public function searchForItems(array $searchOptions)
    {
        return $searchOptions['keywords'];
    }
}

class SearchControllerTest extends \\PHPUnit_Framework_TestCase
{
    function testSearchControllerWorks()
    {
        $varMap = new ArrayVariableMap(['searchTerms' => 'foo,bar']);
        $injector = createTestInjector(
            [VariableMap::class => $varMap],
            [DataSource::class => EchoDataSource::class]
        );
        $result = $injector->execute([SearchController::class, 'search']);
        $this->assertEquals(['foo', 'bar'], $result);
    }

    function testSearchControllerException()
    {
        $varMap = new ArrayVariableMap([]);
        $injector = createTestInjector(
            [VariableMap::class => $varMap],
            [DataSource::class => EchoDataSource::class]
        );
        $this->setExpectedException(ParamMissingException::class);
        $injector->execute([SearchController::class, 'search']);
    }
}

```


## Is doing this worth it?

One of the downsides of writing 'good' code is that it usually takes a bit more time to produce than just writing the first code that comes to mind.

So is refactoring the code worth it? In the sense that it provides enough benefit to be worth the extra code and thought needed?

#### Not tied to HTTP implementation

Because the controller is now decoupled from the Request object, the controller is no longer coupled to the specific

#### Not tied to HTTP at all

Actually, the controller is now completely decoupled from knowing about the HTTP layer at all. We could use it in a program that is running from the CLI as, without having to serialize/deserialize the Request object.

#### Testing is easier

The tests have become easier to write. Instead of having to understand which methods of the Request object are used by the controller.

#### Testing is quicker

Because the 'stub' implementations used for testing are smaller (in lines of code) than the size of the Request implementations, the tests for the controller will be quicker to run. Although the difference for just a couple of tests will be trivial, when you have a large project and the number of unit tests for the whole project starts to reach thousands, or tens of thousands, saving even just a few milliseconds per test, means that the overall time spent waiting for your tests to run is signigicantly reduced.

## Summary

Although creating interfaces increases the number of components in your code base, the benefit is that your code is easier to reason about, and easier to test.

These two things are vital in any non-trivial project, as it is easy for code to grow to be more complex than you can easily understand. Or as [Edsger W. Dijkstra](https://www.cs.utexas.edu/~EWD/transcriptions/EWD03xx/EWD340.html) put it:

> It has been suggested that there is some kind of law of nature telling us that
> the amount of intellectual effort needed grows with the square of program length...
> “The purpose of abstracting is not to be vague, but to create a new semantic level
> in which one can be absolutely precise. The intellectual effort needed to ... understand
> a program need not grow more than proportional to program length.” - Edsger W. Dijkstra

Avoiding the trap of writing code that is too complex to understand should be a priority for programmers, and interface segregation is one tool that can help with that.

