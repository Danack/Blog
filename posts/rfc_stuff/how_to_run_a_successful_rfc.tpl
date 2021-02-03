## How to run a successful RFC

(This is part of a series of posts that I'm writing at the same time. There will be overlap between them, and I may need to refactor them to make more sense.)

This guide is a suggestion on how to go about making an RFC for PHP. Although it is aimed at people who are relatively new to the project, there may be lessons for everyone.

If you've submitted RFCs before, the current RFC process is an adequate way to get your RFC being considered.

The current RFC process is a bit shit in various ways, particularly for people new to the project. A significant number of the RFCs that have been passed in the past couple of years have gone through these additional steps. But they've mostly been done away from the internals list.

## Before creating an RFC document

The next few bits should be done before creating an RFC document on wiki.php.net

### Scoping the idea

Figuring out the scope of an RFC is a more difficult thing to do than people realise.

For many ideas, you need to do some research on:

* previous attempts to solve the problem.
* issues that are related to your idea that probably need to be included.
* issues that are related to your idea that probably need to be excluded.

### Drafting of RFC text

Writing technical documents is generally a harder task than people realise.

Writing an RFC document that lays out a clear problem, a solution, and presents compelling argument is much harder than people realise.

I strongly recommend having the document on github or similar place where changes can be suggested, commented on and merged easily. Using docuwiki is an obvious shitshow.

The two particular things that need to be done in an RFC text are describing the problem clearly, and explaining the solution clearly.

#### Describing the problem

Explaining why something is a problem that needs to be solved is half the battle of getting an RFC passed.

#### Describing the solution

After the problem has been introduced, your proposed solution can be introduced

Generally laying the solution out as:

* a brief piece of text describing the solution.
* some examples of the solution being used.
* notes about why particular choices were made.

is a good layout.

## After creating an RFC docuement

After the initial work of scoping and drafting the RFC has been done, that's an appropriate time to raise it for discussion on internals.

That will still be a bit of a shitshow but the following recommendations will help.

### Go at a slow pace

Take at least an hour to respond to emails. Even better take at least one day.

The discussions are meant to take two weeks minimum, but there is no reason why they can't take longer.

Unless there is a deadline of a release imminently, it's fine for RFCs to take much longer than the two week minimum.

### Accept feedback even if you don't agree with it.

One mistake people make is to try to 'win' an argument.

Instead of responding to try to directly contradict the point the other person is saying, try to turn it around convert to something that makes the RFC stronger e.g.

#### "This BC break is too big"

Explain why the BC break is justified.

#### "I don't think this is a problem"

Explain why it might not be a problem for them, but other people do consider it a problem.

#### "I don't like this solution"

Explain why you like a particular solution, and why it's better than others.

### Feel free to ignore useless feedback

Some of the feedback on internals is useless. For example the complaints that someone's twenty year old code base might not work on the next version of PHP while true, are just a waste of time.

We all know that BC breaks are a pain, but sometimes they are justified based on how they affect people writing code in the future.
Having people from Symfony or Laravel projects say that certain BC breaks might be painful, and we need to discuss mitigation tactics is a useful discussion to be had. Having one person complain about their precious code base is not useful data.

Also, the people picking random-ass syntaxes when the RFC author has spent a lot of time figuring out why those syntaxes aren't possible are the worst form of bike-shedding.

Useful link [constructive vs Destructive feedback](https://uxdesign.cc/constructive-vs-destructive-feedback-fbf6a1032889) and [Identifying good vs. bad feedback](https://uxdesign.cc/dont-take-design-critique-as-an-insult-6cf187ca6308)

### Accept that you might be wrong

People do legitimately disagree about things, particular when it comes to making tradeoffs. If people don't agree with your belief that something is a problem that needs solving, then no matter how well you make an argument based on that belief, it's unlikely that they will be convinced about the reasons for the RFC.




### Say thank you to it and move on



https://www.huffingtonpost.co.uk/entry/marie-kondo-saying-thank-you_l_5c49ebc9e4b06ba6d3bb31e6

https://konmari.com/marie-kondo-gratitude/