## Using Non-Standard Services: Logger Channels

Let's add some logging to `MarkdownHelper`. As always, we just need to find *which*
type-hint to use. Run:

```terminal
./bin/console debug:autowiring
```

And look for log. We've seen this before: `LoggerInterface`. To get this in
`MarkdownHelper`, just add a *third* argument: `LoggerInterface $logger`:

[[[ code('72e06d4ccc') ]]]

Like before, we need to create a new property and set it below. Great news! PhpStorm
has a *shortcut* for this! With your cursor on `$logger`, press `Alt`+`Enter`, select
"Initialize fields" and hit OK:

[[[ code('65db990d47') ]]]

Awesome! Down in `parse()`, if the source contains the word `bacon`... then of course,
we need to know about that! Use `$this->logger->info('They are talking about bacon again!')`:

[[[ code('cf9aed2d5e') ]]]

Ok, try it! This article *does* talk about bacon. Refresh! To see if it logged,
open the profiler and go to "Logs". Yes! Here is our message. I *love* autowiring.

## The Other Loggers

Go back to your terminal. The `debug:autowiring` output say that `LoggerInterface`
is an alias to `monolog.logger`. That is the *id* of the service that's being passed
to us. Fun fact: you can get a bit more info about a service by running:

```terminal
./bin/console debug:container monolog.logger
```

This is cool - but you could also learn a lot by dumping it. Anyways, we *normally*
use `debug:container` to list *all* of the services in the container. But we can
also get a filtered list. Let's find *all* services that contain the word "log":

```terminal
./bin/console debug:container --show-private log
```

There are about 6 services that I'm *really* interested in: these `monolog.logger.`
something services.

## Logging Channels

Here's what's going on. Symfony uses a library called Monolog for logging. And
Monolog has a feature called *channels*, which are kind of like *categories*.
Instead of having just *one* logger, you can have *many* loggers. Each has a unique
name - called a channel - and each can do *totally* different things with their
logs - like write them to different log files.

In the profiler, it even shows the channel. Apparently, the *main* logger uses a
channel called `app`. But other parts of Symfony are using other channels, like
`request` or `event`. If you look in `config/packages/dev/monolog.yaml`, you can
see different *behavior* based on the channel:

[[[ code('1d614ee548') ]]]

For example, most logs are saved to a `dev.log` file. But, thanks to this
`channels: ["!event"]` config, which means "not event", anything logged
to the "event" logger is *not* saved to this file.

This is a *really* cool feature. But mostly... I'm telling you about this because
it's a *great* example of a new problem: how could we access one of these *other*
Logger objects? I mean, when we use the `LoggerInterface` type-hint, it gives us
the *main* logger. But what if we need a *different* Logger, like the "event" channel
logger?

## Creating a new Logger Channel

Actually, let's create our own *new* channel called `markdown`. I want anything
in this channel to log to a different file.

To do this, inside `config/packages`, create a file: `monolog.yaml`. Monolog
is interesting: it doesn't normally have a main configuration file: it only has
environment-specific config files for `dev` and `prod`. That makes sense: we log
things in completely different ways based on the environment.

But *we're* going to add some config that will create a *new* channel, and we want
that to exist in *all* environments. Add `monolog`, then `channels` set to `[markdown]`:

[[[ code('aaa58dbd3b') ]]]

That's it!

Because of a Symfony bug - which, is *now* fixed (woo!) - but won't be available
until the next version - Symfony 4.0.5 - we need to clear the cache manually when
adding a new config file:

```terminal-silent
./bin/console cache:clear
```

As *soon* as that finishes, run `debug:container` again:

```terminal-silent
./bin/console debug:container log
```

Yea! Suddenly we have a new logger service - `monolog.logger.markdown`! So cool.

Go back to the "dev" `monolog.yaml` file. Copy the first log handler, paste, and
give it a key called `markdown_logging` - that's just a meaningless internal name.
Change the path to `markdown.log` and *only* log the `markdown` channel:

[[[ code('5f7318d3a3') ]]]

Ok! If you go to your browser *now* and refresh... it *does* work. But if you check
the logs, we are - of course - *still* logging to the `app` channel Logger. Yep,
there's no `markdown.log` file yet.

## Fetching a Non-Standard Service

So how can we tell Symfony to *not* pass us the "main" logger, but instead to pass
us the `monolog.logger.markdown` service? This is our *first* case where autowiring
doesn't work.

That's *no* problem: when autowiring doesn't do what you want, just... correct it!
Open `config/services.yaml`. Ignore all of the configuration on top for now. But
notice that we're under a key called `services`. Yep, *this* is where we configure
how *our* services work. At the bottom, add `App\Service\MarkdownHelper`, then
below it, `arguments`:

[[[ code('2f08297989') ]]]

The argument we want to configure is called `$logger`. Use that here: `$logger`.
We are telling the container what *value* to pass to that argument. Use the service
id: `monolog.logger.markdown`. Paste!

[[[ code('bb587c50f9') ]]]

Find your browser and... try it! Bah! A big error:

> Argument 3 passed to `MarkdownHelper::__construct()` must implement `LoggerInterface`,
> string given.

Ah! It's *totally* legal to set an argument to a string value. But we don't want
to pass the *string* `monolog.logger.markdown`! We want to pass the *service*
with this id!

To do that, use a special Symfony syntax: add an `@` symbol:

[[[ code('447ca59fd1') ]]]

This tells Symfony not to pass us that *string*, but to pass us the *service* with that id.

Try it again! It works! Check out the `var/log` directory... boom! We have a `markdown.log`
file!

Next, I'll show you an even *cooler* way to configure this. And we'll learn more
about what all this config in `services.yaml` does.
