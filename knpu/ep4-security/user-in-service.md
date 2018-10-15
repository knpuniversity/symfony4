# Fetching the User In a Service

We know how to get the user in a template:

[[[ code('32d03d1c93') ]]]

And... we know how to get the user from a controller with `$this->getUser()`:

[[[ code('e62471f7d2') ]]]

But... what about from inside a service? Because this nice `$this->getUser()`
shortcut will *only* work in controllers.

To show you what I mean, I need to remind you of a feature we built a long time
ago, like 3 screencasts ago. Click on any article. Then, click anywhere on the
web debug toolbar to open the profiler. Find the "Logs" section and click on
"Info & Errors". There it is!

> They are talking about bacon again!

This is a super-informative log message that *we* added from inside our markdown
service: `src/Service/MarkdownHelper.php`:

[[[ code('f71d9d8894') ]]]

This code parses the article content through markdown and caches it. But also,
*if* it sees the word "bacon" in the content ... which *every* article has in our
fixtures, it logs this message.

So here's our challenge: I want to add information about *who* is currently logged
in to this message. To do that, we need to answer one question: how can we access
the current User object from inside a service?

## The Security Service

The answer is... of *course* - by using another service. The name of the service
that gives you access to the `User` object is easy to remember. Add another argument:
`Security $security`:

[[[ code('32b76688a2') ]]]

I'll hit `Alt`+`Enter` and click "Initialize Fields" to create that property and set it:

[[[ code('fa43855a44') ]]]

So how can we use this service? Well... let's just look inside! Hold `Command`
or `Control` and click to open the `Security` class. It has just two important
methods: `getUser()` and `isGranted()`. Hey! That makes a lot of sense! Remember,
once you set up authentication, there are only *two* things you can do with security:
get the user object or figure out whether or not the user should have access to
something, like a role. That's what `isGranted()` does.

Close that and move down to the log message. Ok, we *could* get the user object,
maybe call `getEmail()` on it, and concatenate that onto the end of the log string.
But! There's a *cooler* way. Add a 2nd argument to `info`: an array. Give it a
`user` key - I'm just making that up - and set it to the user *object*:
`$this->security->getUser()`:

[[[ code('dcbaf494e8') ]]]

*Unrelated* to security, every method on the logger, like `info()`, `debug()` or
`alert()`, has *two* arguments. The first is the message string. The *second* is
an optional array called a "context". This is just an array of any extra info that
you want to include with the log message. I invented a `user` key and set it to 
the `User` object.

Let's go see what it looks like! Refresh! Then, click back into the profiler,
find logs, and check out "Info & Errors". The message looks the same, but now we
have a "Show Context" link. Click that! Nice! There is our *entire* `User` object
in all of its glory. That's pretty sweet. And *now*, you know how to get the
`User` object from anywhere.

Next, we get to talk about a feature called "role hierarchy". A little feature
that will make you *love* working with roles, especially if you have complex
access rules.
