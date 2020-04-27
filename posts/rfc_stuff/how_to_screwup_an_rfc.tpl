
# How to screw-up an RFC

(This is part of a series of posts that I'm writing at the same time. There will be overlap between them, and I may need to refactor them to make more sense.)

Sometimes it is easier to explain what can go wrong on a project, rather than how to carry out a successful project.

This document lists some of the things you can do that make it less likely to have a successful RFC.

# Not write the RFC clearly

When someone has an idea for an RFC, usually both the problem they are seeking to solve, and the solution for that problem are quite clear in their head.

# Go against 'established' attitudes

The PHP project has been going for many years. Over that time the project has accummulated a set of 'attitudes' of how things should be, i.e. what has worked in the past, and what has not worked so well.

My interpretation of these attitudes is [kept here](https://github.com/Danack/RfcCodex/blob/master/rfc_attitudes.md).

In general, the more of the established 'attitudes' that an RFC goes against, the stronger the argument for the RFC will need to be.

# Open the voting early

Conversations before the voting opens are much less stressful than conversations during the voting period.

Before the vote opens, there is plenty of time to present and consider different parts of the problem, and people can be reasonably open to hear other people's opinions.

When the vote has been opened there is a deadline for trying to persuade the 'other side', and where everyone involved feels that "if they don't win the argument now PHP will be ruined".

And to be clear, I think this is one area of the RFC process that could be improved. Moving the initial discussion to github, where we could use separate issues to discuss separate parts of the RFC would make it easier for people to see the state of a discussion.


# Not weighing people's opinions correctly

There are some people who should have a lot of weight given to their opinion. For example, the release managers are people who have been selected for their ability to think deeply about how to manager PHP released. In addition, they think deeply about the right and wrong way to manage releases.

If there is a question about whether a bugfix/change is appropriate to release in a bugfix release, or if the BC break is big enough should it wait until the next minor PHP release, more weight should be put onto the opinions of the release managers than say the opinion of a random person on reddit.


# Having the RFC be only just good enough

The PHP project has a very long timeline for supporting the core parts of PHP, and a very slow timeline for fixing problems with things that are added to core.

The recent 'request and response' RFC was an RFC that although it wasn't terrible many of the core contributors could see that it would be very likely to have multiple flaws identified with it, if it shipped with core PHP.

RFCs need to be clearly the right thing to do before they can be accepted.





















