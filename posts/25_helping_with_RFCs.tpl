
One thing I do to help the PHP project is help other people write RFCs. For those that don't know, RFCs are the way that the PHP language and core libraries are changed.

Some of the reasons I do this are:

## Enjoyable intellectual exercise

Sitting down and thinking through a problem deeply enough to be able to write convincing arguments about it is quite a rewarding exercise. And it is an _exercise_ for my brain.

Doing it helps me get better at analyzing problems and come up with sensible solutions for them, as well as getting better at being able to explain why a particular solution is sensible.

## Incremental progress is something I can do

On a project like PHP, there are two types of progress:

* big changes that dramatically improve the language.
* small changes that incrementally improve the language.

Most of the time, I don't have the energy to do the big changes. Also, I'm not that good at design, so probably shouldn't even if I could.

But the small changes are still useful things to do! Each small improvement makes life better for people who use PHP. For example the [RFC: get_class() disallow null parameter'](https://wiki.php.net/rfc/get_class_disallow_null_parameter) is literally a one character change in PHP.

<img src="/images/SmallestPossibleChange.png" width="100%"/>

That change isn't going to revolutionise the language, but it's going to save a many individual programmers from having to debug why the code is 'acting crazy' when they accidentally pass null to that function.

## Helps new people get started into the PHP project

One of the problems that the PHP project has is a lack of contributors.

Quite often people new to contributing to the project have an idea for an RFC that they would like to work on.

If their first experience on the project is running an RFC, there are two things that are likely to happen:

* they don't have the experience needed to write a convincing RFC.
* the are not going to enjoy the experience of having their RFC criticised.

This leads to people who would otherwise contribute being driven away from the project.

By helping people new to the project run a successful RFC, not only does it help the project in the short term, it also makes it more likely that those new contributors will stick around.

## Lowers the barrier of getting stuff done

Even for people who are not new to PHP internals, the skill needed to write RFCs is outside the normal skillset that most developers have.

This is an even greater problem for people who are non-native English speakers.

Having improvements to a project be within our reach, but not able to achieve them due to a lack of skill sharing would be one of the dumbest ways to fail.


## Makes internals discussions less painful

Although people writing emails to the internals email list are trying to help, a lot of the conversations are just not productive.

Drafting an RFC well enough that it makes a complete argument about a problem really cuts down on the amount of messages sent. That saves time of internal contributors (as each email sent takes time to read) and also makes it easier to see the more important emails.

This is also why I maintain the [RFC Codex](https://github.com/Danack/RfcCodex/blob/master/rfc_codex.md), a list of ideas that people have discussed on internals, that haven't come to fruition. It helps people pickup previous discussions, without having to email internals "hey why hasn't this been done yet?".

## Benefits me personally

I like using PHP, but the limitations it has do annoy me from time-to-time.

The RFCs I've helped with actually make my programming experience a little bit better.


