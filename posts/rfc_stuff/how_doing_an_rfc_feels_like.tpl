
## How doing an RFC makes you feel?

(This is part of a series of posts that I'm writing at the same time. There will be overlap between them, and I may need to refactor them to make more sense.)

One of the common complaints about PHP internals is that it is toxic[^toxic] and that people are rude. While those are possibly true, they aren't the reason why most people find doing an RFC stressful.

Instead most of the causes of that bad feeling are due to the PHP RFC process just being setup in a way that naturally produces negative feeling.

## The vast majority of the feedback appears negative

Even when an RFC is a good idea, there are very few :+1: messages on internals, as they are mostly seen as noise, and don't provide much value.

But any problems with the RFC will have a deluge of people popping up to point out those flaws.

## Handling the feedback is emotionally draining

When someone brings up an idea for an RFC, they are going to be enthusiastic about the idea. Any feedback that doesn't match that enthusiasm is going to feel negative, almost like they are attacking your idea.

It is very draining to have people you respect attacking your idea.

## Current communication methods are terrible

The communication methods that are promoted by the PHP project, (email and the IRC chat room) are just not a good fit for a PHP RFC.

I'll write about this separately, but for now, I'd recommend people work on RFCs away from the internals email list, until the RFC is in a state where it is reasonably complete and can be presented to the list as an RFC that is almost ready to be voted on.

## People have legitimate disagreements

There are a couple of things that makes the PHP project special. Most other tech projects start from a clean slate, with a reasonably clear set of goals they have chosen as a goal.

The PHP project started out as, and still has, a more pragmatic approach.

While this has allowed PHP to grow as a language it means that there are just straight up disagreements about what the goal of development of PHP should be.

Some examples of this are:

* web focused vs general programming language

* Keep compatibility vs cleaning up shite.

* what needs to be solved in core vs userland

## Choosing between trade-offs is tiring

Thinking through programming problems is a much more emotive experience than most people realise or acknowledge.

When you come up with an idea it produces a positive emotion. When you encounter a problem with that idea it produces a negative emotion. The following conversation pattern is relatively common between programmers.

Person 1: Hey why don't we try xyz?
Person 2: Because xyz doesn't work with abc.
Person 1: *ugh* fuck!


This problem is even worse when there are no clear correct choices.

Person 1: Hey why don't we try xyz?
Person 2: Because xyz doesn't work with abc.
Person 1: Okay, so what do we do instead?
Person 2: How about trying mno?
Person 1: No, that doesn't work with def...
Person 1 and 2 in unison: *ugh* fuck!

For many RFCs there are multiple concerns and tradeoffs that need to be balanced, where each choice is going to have different downsides this is a really tiring experience as your brain goes from positive to negative emotion rapidly.

It's better to take time during the RFC process to step away from the idea for days at a time. That gives your brain time to process those feelings, as well as just come to terms with the possible solution maybe not being the one you wanted to do.

## PHP is annoying

For many RFCs there are subtle edge-cases or backwards compatibility breaks.

Finding out that something just isn't possible due to the limitations in PHP is a big source of frustration.

## People are abusive

Multiple RFC authors have received very unpleasant messages off list, with people telling them they are terrible for pushing their RFC.

I think this behaviour is unacceptable and is a problem that the PHP project ought to address, though it might quite difficult to avoid.



[^toxic]: The exact definition of toxic varies. Some parts of that meaning I see and agree with. Other times, the phrase is used to just describe other people not agreeing with an idea.


