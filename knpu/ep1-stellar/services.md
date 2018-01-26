# Services

It's time to talk about the most fundamental part of Symfony: services!

Honestly, Symfony is nothing more than a bunch of useful objects that work together.
For example, there's a router object that matches routes and generates URLs. There's
a Twig object that renders templates. And there's a Logger object that Symfony
is already using internally to store things in a `var/log/dev.log` file.

Actually, *everything* in Symfony - I mean *everything* - is done by one of these
useful objects. And these useful objects have a special name: *services*.

## What's a Service?

But don't get too excited about that word - service. It's a special word for a
*really* simple idea: a service is any object that does *work*, like generating URLs,
sending emails or saving things to a database.

Symfony comes with a *huge* number of services, and I want you to think of services
as your *tools*.

Like, if I gave you the logger service, or object, then you could use it to log messages.
If I gave you a mailer service, you could send some emails! Tools!

The *entire* second half of Symfony is all about learning where to find these services
and how to use them. Every time you learn about a new service, you get a new tool,
and become just a *little* bit more dangerous!

## Using the Logger Service

Let's check out the logging system. Find your terminal and run:

```terminal
tail -f var/log/dev.log
```

I'll clear the screen. Now, refresh the page, and move back. Awesome! This *proves*
that Symfony has some sort of logging system. And since *everything* is done by
a service, there must be a logger object. So here's the question: how can *we*
get the logger service so that *we* can log our *own* messages?

Here's the answer: inside the controller, on the method, add an additional argument.
Give it a `LoggerInterface` type hint - hit tab to auto-complete that and call it
whatever you want, how about `$logger`:

[[[ code('3f726459ce') ]]]

Remember: when you autocomplete, PhpStorm adds the `use` statement to the top for you.

Now, we can use one of its methods: `$logger->info('Article is being hearted')`:

[[[ code('b922641119') ]]]

Before we talk about this, let's try it! Find your browser and click the heart.
That hit the AJAX endpoint. Go back to the terminal. Yes! There it is at the bottom.
Hit `Ctrl`+`C` to exit `tail`.

## Service Autowiring

Ok cool! But... how the heck did that work? Here's the deal: before Symfony executes
our controller, it looks at each argument. For simple arguments like `$slug`, it
passes us the wildcard value from the router:

[[[ code('4afae713e5') ]]]

But for `$logger`, it looks at the *type-hint* and *realizes* that we *want* Symfony
to pass us the logger object. Oh, and the order of the arguments does *not* matter.

This is a *very* powerful idea called autowiring: if you need a service object,
you just need to know the correct *type-hint* to use! So... how the heck did I know
to use `LoggerInterface`? Well, of course, if you look at the official Symfony
docs about the logger, it'll tell you. But, there's a *cooler* way.

Go to your terminal and run:

```terminal
./bin/console debug:autowiring
```

Boom! This is a full list of *all* of the type-hints that you can use to get a
service. Notice that most of them say that they are an *alias* to something. Don't
worry about that too much: like routes, each service has an internal name you can
use to reference it. We'll learn more about that later. Oh, and whenever you install
a *new* package, you'll get more and more services in this list. More tools!

## Using Twig Directly

And check this out! If you want to get the Twig service, you can use either of
these two type-hints.

And remember how I said that *everything* in Symfony is done by a service? Well,
when we call `$this->render()` in a controller, that's just a shortcut to fetch
the Twig service and call a method on it:

[[[ code('ac75dbaf9b') ]]]

In fact, let's pretend that the `$this->render()` shortcut does *not* exist. How
could we render a template? No problem: we just need the Twig service. Add a second
argument with an `Environment` type-hint, because that's the class name we saw
in `debug:autowiring`. Call the arg `$twigEnvironment`:

[[[ code('bd092ca645') ]]]

Next, change the `return` statement to be `$html = $twigEnvironment->render()`:

[[[ code('e9dd2a8868') ]]]

The method we want to call on the Twig object is coincidentally the same as the
controller shortcut.

Then at the bottom, return `new Response()` and pass `$html`:

[[[ code('ba7bb2196c') ]]]

Ok, this is *way* more work than before... and I would *not* do this in a real
project. But, I wanted to prove a point: when you use the `$this->render()` shortcut
method on the controller, all it *really* does is call `render()` on the Twig service
and then wrap it inside a `Response` object for you.

Try it! Go back and refresh the page. It works exactly like before! Of course we
*will* use shortcut methods, because they make our life *way* more awesome. I'll
change my code back to look like it did before. But the point is this: *everything*
is done by a service. If you learn to master services, you can do *anything* from
*anywhere* in Symfony.

There's a lot more to say about the topic of services, and *so* many other parts
of Symfony: configuration, Doctrine & the database, forms, Security and APIs, to
just name a few. The Space Bar is far from being the galactic information source
that we know it will be!

But, congrats! You just spent an hour getting an *awesome* foundation in Symfony.
You will *not* regret your hard work: you're on your way to building *great* things
and, as always, becoming a better and better developer.

Alright guys, seeya next time!
