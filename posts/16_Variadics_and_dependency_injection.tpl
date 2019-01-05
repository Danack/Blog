PHP has supported variadics since version 5.6. Recently the question of how to support them in dependency injection containers such as [Auryn](https://github.com/rdlowrey/auryn) was raised; should they be supported in a way similar to how Auryn handles scalar parameters?

Although supporting the ability to inject parameters by name is a useful thing to do (even if it is a bit of a hack), supporting injecting variadic parameters is a different kettle of fish entirely. One that would, in my opinion, be a bad choice.

<!-- end_preview -->

## Why Auryn supports injecting params by name at all

Auryn supports injecting parameters by name because PHP is missing a feature; the ability to have strongly typed scalars. If that feature was added, something similar to the code below would be possible:

{% set code_to_highlight %}
class DatabaseUsername extends string {}

function foo(string $username) {
    // ...
}

function connectToDB(DatabaseUsername $dbUsername) {
    foo($username);
}
{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}

However we don't have that feature in PHP. Because of that if we want to create strong scalar types like that, we have to do more typing on the keyboard:

{% set code_to_highlight %}
class DatabaseUsername {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }
    public function getValue() {
        return $this->value;
    }
}

function foo(string $username) {
// ...
}

function connectToDB(DatabaseUsername $dbUsername) {
    foo($username->getValue());
}
{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}

This is not an immense burden, but it is one that is quite annoying to have to do continuously. It's particularly annoying when you're upgrading a legacy code base to use Auryn, and you have a large number of scalar values that need to be encapsulated in types.

To make it less of a burden Auryn supports defining parameters through defining paramters by name:
{% set code_to_highlight %}
    $injector->defineParam('dbUsername', getEnv('dbUsername'))
{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}

Or by to define multiple parameters for a single class:

{% set code_to_highlight %}
    $injector->define($classname, [...])
{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}


## Variadics are a different problem

Here is some example code where one of the parameters is variadic:

{% set code_to_highlight %}
class Foo {
    public function __construct(Repository ...$repositories) {
    // ...
    }
}
{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}

In this code `$repositories` does not represent a single scalar variable and so Auryn's ability to make life a bit easier for the programmer by being able to inject scalar values by name does not apply.

Instead '...$repositories' represents a complex type. Resolving what needs to be injected for this parameter is a more difficult problem than just making parameters be injectable by name, and so will need a more advanced technique to solve.

Two possible solutions are to either use 'delegate methods' to setup the depedency injection or to encapsulate the `...$repositories` inside a 'context'.

## Use a delegate method

The simplest way to be able to create an object that depends on a variadic parameter is to use a delegate function to create the variable dependency.

{% set code_to_highlight %}
function createFoo(RepositoryLocator $repoLocator)
{
    //Or whatever code is needed to find the repos.
    $repositories = $repoLocator->getRepos('Foo');

    return new Foo($repositories);
}

// Instruct the injector to call the function 'createFoo'
// whenever an object of type 'Foo' needs to be created
$injector->delegate('Foo', 'createFoo')
{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}

This achieves the aim of being able to create an object that has a variadic dependency, however it has some downsides. One particular problem is it can only be used for constructor injection; it cannot be used to inject the dependencies as a parameter in normal functions or methods.


## Refactor the code to use a context

Using a 'context' doesn't have these downsides. Or to give it the full name, using the ['Encapsulated context patten'](http://www.allankelly.net/static/patterns/encapsulatecontext.pdf)

The trade-off is that it would require us to refactor your code a little bit. This is a *good* trade-off to make, in this case. In fact it's a fantastic trade-off. It makes the code far easier to reason about.

{% set code_to_highlight %}
// This is the context that holds the 'repositories'
class FooRepositories {
    private $repositories;

    private function __construct(RepositoryLocator $repoLocator)
    {
        //Or whatever code is needed to find the repos.
        $repositories = $repoLocator->getRepos('Foo');
    }
}

class Foo {
    // Change the dependency to be on the context
    public function __construct(FooRepositories $fooRepositories) {
    // ...
    }
}
{% endset %}
{{ syntaxHighlighter(code_to_highlight, 'php') }}

There are a couple of reasons why using a context is a superior solution:

* The dependency is now a named type, which means that you can see how it is used in your codebase without having to know which class it is used in.

* If some other code has a dependency on a separate set of repositories, you can create a separate context for that code. It is far easier to understand that `FooRepositories` is separate from `BarRepositories` compared to trying to reason about `...$repositories` used in one place, and `...$repositories` used in a separate place.

* If you're using a framework such as [Tier](https://github.com/danack/tier) that allows multiple levels of execution then it's much easier to create specific context types and pass them around as dependencies rather creating a generically named variadic parameter and hoping for the best when passing that around.

## Summary

In my opinion Auryn, or any other dependency injection container, shouldn't handle variadics at all. They aren't a type and so can't be reasoned about by a DIC.

People should use either delegation or contexts to achieve being able to inject variable dependencies such as variadics, as those are 'easy to reason about' ways of achieving that goal.
