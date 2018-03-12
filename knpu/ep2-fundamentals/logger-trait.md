# Bonus! LoggerTrait & Setter Injection

What if we wanna send Slack messages from somewhere *else* in our app? This is the
*same* problem we had before with markdown processing. Whenever you want to re-use
some code - or just organize things a bit better - take that code and move it into
its own *service* class.

Since this is *such* an important skill, let's do it: in the `Service/` directory -
though we could put this anywhere - create a new class: `SlackClient`:

[[[ code('f51f4588ea') ]]]

Give it a public function called, how about, `sendMessage()` with arguments `$from`
and `$message`:

[[[ code('601d9eb122') ]]]

Next, copy the code from the controller, paste, and make the from and message parts
dynamic. Oh, but let's rename the variable to `$slackMessage` - having *two* `$message`
variables is no fun.

[[[ code('3cd3cdaa44') ]]]

At this point, we just need the Slack client service. You know the drill: create
a constructor! Type-hint the argument with `Client` from `Nexy\Slack`:

[[[ code('e0a6587c88') ]]]

Then press `Alt`+`Enter` and select "Initialize fields" to create that property
and set it:

[[[ code('a10f10b37c') ]]]

Below, celebrate! Use `$this->slack`:

[[[ code('a5db5f4379') ]]]

In about one minute, we have a completely functional new service. Woo! Back in the
controller, type-hint the new `SlackClient`:

[[[ code('ddc0f75565') ]]]

And below... simplify: `$slack->sendMessage()` and pass it the from - `Khan` -
and our message. Clean up the rest of the code:

[[[ code('914dac64cc') ]]]

And I don't *need* to, but I'll remove the old `use` statement:

[[[ code('3daf7c3c75') ]]]

Yay refactoring! Does it work? Refresh! Of course - we *rock*!

## Setter Injection

Now let's go a step further... In `SlackClient`, I want to log a message. But, we
*already* know how to do this: add a second constructor argument, type-hint it
with `LoggerInterface` and, we're done!

But... there's *another* way to autowire your dependencies: *setter* injection.
Ok, it's just a fancy-sounding word for a simple concept. Setter injection is
less common than passing things through the constructor, but sometimes it makes
sense for *optional* dependencies - like a logger. What I mean is, if a logger
was not passed to this class, we could *still* write our code so that it works.
It's not *required* like the Slack client.

Anyways, here's how setter injection works: create a `public function setLogger()`
with the normal `LoggerInterface $logger` argument:

[[[ code('7e515e4933') ]]]

Create the property for this: there's no shortcut to help us this time. Inside,
say `$this->logger = $logger`:

[[[ code('4ad4fcbd70') ]]]

In `sendMessage()`, let's use it! Start with `if ($this->logger)`. And inside,
`$this->logger->info()`:

[[[ code('16b72bd35a') ]]]

Bah! No auto-complete: with setter injection, we need to help PhpStorm by adding
some PHPDoc on the property: it will be `LoggerInterface` or - in theory - `null`:

[[[ code('b62f209d87') ]]]

*Now* it auto-completes `->info()`. Say, "Beaming a message to Slack!":

[[[ code('8cd34a720b') ]]]

In practice, the `if` statement isn't needed: when we're done, Symfony *will*
pass us the logger, *always*. But... I'm coding *defensively* because, from the
perspective of this class, there's no guarantee that whoever is using it will call
`setLogger()`.

So... is Symfony smart enough to call this method automatically? Let's find out -
refresh! Our class still works... but check out the profiler and go to "Logs". Bah!
Nothing is logged yet!

## The @required Directive

Yep, Symfony's autowiring is not *that* magic - and that's on purpose: it only
autowires the `__construct()` method. But... it would be *pretty* cool if we could
somehow say:

> Hey container! How are you? Oh, I'm *wonderful* - thanks for asking. Anyways, after
> you instantiate `SlackClient`, could you *also* call `setLogger()`?

And... yeah! That's not only *possible*, it's *easy*. Above `setLogger()`, add
`/**` to create PHPDoc. You can keep or delete the `@param` stuff - that's *only*
documentation. But here's the magic: add `@required`:

[[[ code('d51ce2b14e') ]]]

As *soon* as you put `@required` above a method, Symfony will *call* that method
before giving us the object. And thanks to autowiring, it will pass the logger
service to the argument.

Ok, move over and... try it! There's the Slack message. And... in the logs...
yes! We are logging!

## The LoggerTrait

But... I have *one* more trick to show you. I like logging, so I need this service
pretty often. What if we used the `@required` feature to create... a `LoggerTrait`?
That would let us log messages with just *one* line of code!

Check this out: in `src/`, create a new `Helper` directory. But again... this directory
could be named *anything*. Inside, add a new PHP Class. Actually, change this to
be a trait, and call it `LoggerTrait`:

[[[ code('68d53e9b08') ]]]

Ok, let's move the `logger` property to the trait... as well as the `setLogger()`
method. I'll retype the "e" on `LoggerInterface` and hit `Tab` to get the `use` statement:

[[[ code('74b889257d') ]]]

Next, add a new function called `logInfo()` that has two arguments: a `$message`
and an array argument called `$context` - make it optional:

[[[ code('45662d5c54') ]]]

We haven't used it yet, but all the log methods - like `info()` - have an optional
second argument where you can pass extra information. Inside the method: let's *keep*
coding defensively: `if ($this->logger)`, then `$this->logger->info($message, $context)`:

[[[ code('ccc48f8b0a') ]]]

*Now*, go back to `SlackClient`. Thanks to the trait, if we *ever* need to log
something, all we need to do is add `use LoggerTrait`:

[[[ code('44a2d3b55f') ]]]

Then, below, use `$this->logInfo()`. Pass the message... and, let's even pass some
extra information - how about a message key with our text:

[[[ code('bb9969cd07') ]]]

And that's it! Thanks to the trait, Symfony will automatically call the `setLogger()`
method. Try it! Move over and... refresh!

We get the Slack message and... in the profiler, yes! And this time, the log message
has a bit more information.

I hope you *love* the `LoggerTrait` idea.
