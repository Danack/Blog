I want to get something off my chest; I am not a big fan of [PSR-7](http://www.php-fig.org/psr/psr-7/), the PSR about 'HTTP message interfaces'.

Although I do use it, I do so in a way where my code is barely aware of it at all, and so I can swap to using a different representation very easily.

<!-- end_preview -->

Rather than just saying *ewww, I don't like it*, I ought to be clear about why it's something that I use reluctantly.

### Normal web requests

For normal HTTP requests that come in with a complete body attached to the request, PSR-7 works okay actually.

There are, in my opinion, a few rough spots in the API where either the functions are not fantastically named, or the return values (particular of `ServerRequestInterface::getParsedBody()`) are not particularly clear.

But apart from those minor quibbles though, PSR-7 solves the problem of how to represent an incoming HTTP request pretty well.


### Streaming/incomplete requests

The real problems with the PSR-7 start with how it tries to abstract away the difference between 'complete' HTTP requests, where the whole body is available with the request, and 'incomplete' HTTP requests where the body isn't available. 'Incomplete' requests are used in a couple of different places, for example when dealing with large file uploads, you don't want a 100MB file to be loaded into memory by PHP.

I've written before about how I am [really not a big fan](http://blog.basereality.com/blog/15/Stop_trying_to_force_interfaces) of forcing a common interface to two different concepts.

It would have been far better if the 'streaming' part of the interface had been removed, into a separate interface. That would avoid any confusion about whether a request is of the 'complete' type, or whether it represents an 'incomplete' request.


It also would have made the implementation for the 'complete' requests be simpler. This apparently is the code you need to write if you want to create a body for a mock response:

```
$body = new Stream('php://temp', 'wb+');
$body->write(\"Hello world\");
$body->rewind();
```
which is a bit more complex than it ought to be:

```
new TextBody(\"Hello world\");
```

That's not the biggest deal in the world....it's just a little bit less good than it could be.


### Response implementation

The response implementation.....oh, the response implementation.

I certainly see that it makes it easier to write plugins for frameworks and for 'middlewares' if there is a 'Response' object passed around. However this is using a global mutable object to hold information. That is just not good programming practice. In this case it makes it hard to reason about which bits of an application are going to be modifying the response object....which makes it hard to write reliable code.

But the more fundamental problem is that having a response object is a bad abstraction as the response doesn't actually exist on the server. Certainly a response 'body' can exist before the response is sent, and additionally some headers that should be sent with the response can exist. But the actual response _does not exist until you start sending bytes to the client_.

The complete response only exists after the sending of the bytes has finished and the connection has been closed. This means that trying to represent the response as an object before it is sent is an inherently bad abstraction.

Bad abstractions can be useful (and I can see people will find the response object in PSR-7 useful) but they seem to be a poor trade-off between how easy it is to write code, and how easy it is to test that code, and also reason about what the code is doing in an application.


## Y U NO MAKE IMPLEMENTATION?!

By tradition, the PHP-FIG group likes to promote interfaces as the way of advancing interoperability in the PHP ecosystem. However for a large standard like PSR-7 where there are likely to be edge-cases in the implementation, it would have been a better choice for it to be released as a standard implementation.

The benefit that would bring is that all code interacting with the PSR-7 standard could rely on the standard behaviour in any edge-cases that may exist. Instead people will need to test their code against the particular implementation being used.

In the future if and when people decide that the edge-case behaviour needs to be changed, a new PSR-7.1 implementation could be released, and people could switch en masse to that new standard implementation, rather than the change having to be made in each separate implementation.


## So.....?

Realistically, the fact that PSR 7 isn't perfect isn't going to stop people from being able to write applications.

It just means that frameworks and other code that is written, particularly the wave of PHP 'middleware' that people are making, aren't going to be quite as good as they could be.

You can protect yourself from this by using interface segregation to separate your code from the PSR-7 implementation, so that if and when you realise you need a better representation of HTTP requests, it is easy to migrate to it, without needing to rewrite a significant portion of your application. But that will have to be a separate blog post.

