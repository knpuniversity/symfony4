# Bonus! LoggerTrait & Setter Injection

What if we need to send Slack messages from somewhere else in our app? This is the
*same* problem we had before with markdown. We just need to take these few lines
of code and isolate them into their own service. Let's do this quickly: in the
`Service/` directory - though we could put this anywhere - create a new class:
`SlackClient`. Give it a public function called, how about, `sendMessage()` with
arguments `$to` and `$message`.

Now, copy the code from the controller, paste, and make the from and message parts
dynamic. Oh, but let's rename the variable to `$slackMessage` - having two `$message`
variables is confusing.

At this point, we just need the Slack client service. You know the drill: create
a constructor! Type-hint the argument with `Client` from `Nexy\Slack`. Then press
Alt+Enter and select "Initialize Fields" to create that property and set it. Finally,
below, use `$this->slack`.

In about one minute, we have a completely functional new service. Woo! Back in the
in controller, type-hint the new `SlackClient`. And below... simplify:
`$slack->sendMessage()` and pass it the from - `Khan` - and our message. Clean up
the rest of the code.

And I don't need to, but I'll remove the old `use` statement.

Yay refactoring! Does it work? Refresh! Of course - we *rock*!

## Setter Injection

Let's go a step further... In `SlackClient`, I want to log a message. But, we
*already* know how to do this: add a second constructor argument, type-hint it
with `LoggerInterface` and, we're good!

But... there's *another* way to autowire your dependencies: *setter* injection.
Ok, that's just a fancy-sounding word for a simple concept. Setter injection is
less common than passing things through the constructor, but sometimes it makes
sense for *optional* dependencies - like a logger. I mean, *technically*, if a
logger wasn't passed to this class, we could write our code so that it still works.
It's not *required* like the Slack client.

Anyways, here's how setter injection works: create a `public function setLogger()`
with the normal `LoggerInterface $logger` argument.

Create the property for this: there's no shortcut to help us this time. Inside,
say `$this->logger = $logger`.

In `sendMessage()`, let's use it! Start with `if ($this->logger)`. And inside,
`$this->logger->info()`. Bah! No auto-complete: with setter injection, we need to
help PhpStorm by adding some PhpDoc in the property: it will be a `LoggerInterface`
or `null`.

*Now* it auto-completes `->info()`. Say, "Beaming a message to Slack!".

The `if` statement isn't *technically* needed: when we're done, Symfony *will*
pass us the logger. But... I'm coding defensively because, from the perspective
of this class, there's no guarantee that someone will call `setLogger()`.

So... if Symfony smart enough to call this method? Let's find out - refresh! Our
class still works... but check out the profiler and go to "Logs". Bah! Nothing
is being logged yet.

## The @required Directive

Yep, Symfony's autowiring is not *that* magic - and that's on purpose: it only
autowires the `__construct()` method. But... it would be *pretty* cool if we could
somehow say:

> Hey container! How are you? Oh, I'm *wonderful* - thanks for asking. Anyways, after
> you instantiate `SlackClient`, could you *also* call `setLogger()`?

And... yep! That's no only *possible*, it's *easy*. Above `setLogger()`, add
`/**` to create PHPDoc. You can keep or delete the `@param` stuff - that's *only*
documentation. But here's the magic: add `@required`.

As *soon* as you put `@required` above a method, Symfony will *call* that method
before giving it to us. And thanks to autowiring, it will pass the the logger service
to the argument.

Ok, move over and... try it! There's the Slack message. But... in the logs...
yes! There's our new log message.

## The LoggerTrait

But... I have *one* more trick to show you. I like logging, so I need this service
pretty often. What if we used the `@required` feature to create... a `LoggerTrait`?
That would let us log messages with just *one* line of code!

Check this out: in `src/`, create a new `Helper` directory. But again... this directory
could be named *anything*. Inside, add a new PHP Class. Actually, change this to
be a trait, and call it `LoggerTrait`.

Ok, let's move the `logger` property to the trait... as well as the `setLogger()`
method. I'll retype the "e" on `LoggerInterface` and hit tab to get the `use` statement.

Next, add a new function called `logInfo()` that has two arguments: a `$message`
and an array argument called `$context` - make it optional. We haven't used it yet,
but all the log methods - like `info()` - have an optional second argument where
you can pass extra information. Inside the method: let's *keep* coding defensively:
`if ($this->logger)`, then `$this->logger->info($message, $context)`.

*Now*, go back to `SlackClient`. Thanks to the trait, if we *ever* need to log
something, all we need to do is add `use LoggerTrait`. Then, below, use `$this->info()`.
Pass the message... and, let's even pass some extra information - how about a message
key with our text.

Yep! That's it! Thanks to the trait, Symfony will automatically call the `setLoggeR()`
method. Try it! Move over and... refresh!

We get the Slack message and... in the profiler, yes! And this time the log message
has a bit more information.

I hope you *love* the `LoggerTrait` idea.
