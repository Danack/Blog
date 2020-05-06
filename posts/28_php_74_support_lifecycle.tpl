
There is one major inaccuracy in the article [PHP showing its maturity in release 7.4](https://lwn.net/SubscriberLink/818973/507f4b5e09ab9870/). In particular, the part "PHP is likely to continue with releases in the 7.x branch, adding incremental improvements,".

That is not the case.

<!-- end_preview -->

There is no 7.5 planned, and currently the support plans for PHP 7.4 are:

* Active Support until 28 Nov 2021
* Security Support until 28 Nov 2022

From now, that is:

* 1 year, 6 months
* 2 years, 6 months

respectively.

This is a relatively short time compared to other software languages.

The thing that balances this out imo, is that the PHP project also takes greater care than typically done to maintain backwards-compatbility. Although there will be some deliberate breaks (and some accidental ones) in the PHP 8 release, the vast majority of projects will be able to upgrade with much less effort than, for example, upgrading from ANSI C to C99, or definitely less work than Python 2.x to 3.x

Not only is the work less than compared to other languages, we also have some better tools for upgrades than other languages.

Rector is a good example. https://getrector.org/ It is an automated refactoring tool that understands PHP code, and has plugin based rules for how code should be refactored. When a PHP version is released, some Rector rules are written that:

* understands which bits of the code are subject to a BC break
* how to refactor those bits into equivalent code that works on the new version, or when possible into a version that works on both versions.
* gives an error for parts it can't refactor.

More info about Rector, [text form](https://www.tomasvotruba.com/blog/2018/02/19/rector-part-1-what-and-how/), [video form](https://www.youtube.com/watch?v=S6fg7sJfh20), [homepage](https://getrector.org/).

The PHP ecosystem has also seen a rapid improvement in the static code analyzers that are available to PHP users:

* [phpstan.org](https://phpstan.org/)
* [psalm.dev](https://psalm.dev/)
* [github.com/phan/phan](https://github.com/phan/phan)
* [github.com/Roave/BackwardCompatibilityCheck](https://github.com/Roave/BackwardCompatibilityCheck)

Sometimes healthy competition is better than projects working together. But I digress, back to the matter of the short time period of PHP 7.4 support.

In my opinion, the main reason for the short support lifetime, is the limited amount of people who are able and willing to work on maintaining PHP. This is not just a lack of volunteers, it's also a communications problem in scaling how many people can work on core PHP. The current communication methods aren't working that well for various reasons.

There is time between now and the planned end of support for PHP 7.4 for an alternative plan.

If any group could be found or formed now, separate to the current core maintainers, and could do the work to come up with a plan to maintain a LTS version of PHP 7.4, then there is plenty of time over the next 18 months to discuss and implement that plan.

That would be strongly preferable to having a drama filled conversation close to the end of support deadline. In that scenario, I suspect people might use emotionally charged language to try to pressure the current core maintainers into maintaining a version of PHP that they don't want to support.
