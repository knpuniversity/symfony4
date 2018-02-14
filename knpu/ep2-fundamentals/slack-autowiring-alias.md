# Autowiring Aliases

The way we coded in Symfony 3 was a bit different than Symfony 4. And... well... we
need to learn just a *little* bit about the Symfony 3 way. Why? Because, when you
find bundles with outdated docs, or old StackOverflow answers, I want you to be able
to *translate* that into Symfony 4.

## Public Versus Private Services

In Symfony 3, services were defined as *public*. This means that you could use a
`$this->get()` shortcut method in your controller to fetch a service by its id.
Or, if you had the container object itself - yep, that's totally possible - you
could say `$container->get()` to do the same thing.

But in Symfony 4, most services are *private*. What does that mean? Very simply,
when a service is *private*, you *cannot* use the `$this->get()` shortcut to fetch
it.

At first, it might seem like we're just making life more difficult! But actually,
Symfony 4 simply has a new philosophy.

Open `services.yaml` and, below `_defaults`, check out the `public: false` config:

[[[ code('acc89f3246') ]]]

Thanks to this, any service that *we* create is *private*. And so, we *cannot* fetch
our services with `$this->get()`. Increasingly, more and more third-party bundles
are *also* making their services private.

And because so many services are now private, instead of using `$this->get()`, we
need to fetch services via "dependency injection" - a fancy, scary-sounding term
that describes what we've been doing... this entire tutorial: passing services and
config as arguments. This is considered a better coding practice than `$this->get()`,
which means that *we* get to write nice code. Woo!

And actually... it *also* makes your app faster! It's not huge, but private services
are faster than public services.

## If you DO Want to use $this->get()

Side note, if you *do* want to use the `$this->get()` shortcut to fetch a public
service - which you should *not* - you'll need to change your base controller class
to `Controller` instead of `AbstractController`:

[[[ code('7b189d6ef4') ]]]

It's not important why... and you shouldn't do it anyways :p.

## Fetching a Service by id

Okay okay, so if we *can't* use the `$this->get()` shortcut, how the heck can we
fetch this service? Let's experiment! Copy the service id, find your terminal, and
run:

```terminal
php bin/console debug:container nexy_slack.client
```

Apparently the class for this object is `Nexy\Slack\Client`. We didn't see *any*
autowiring type-hints that would work in `debug:autowiring`... but... maybe it *will*
work if we type-hint this class?

Let's try it! In `ArticleController::show()`, add another argument: `Client` - make
sure you get the one from `Nexy\Slack` -  then `$slack`:

[[[ code('e2bb4c86d8') ]]]

Add an `if` statement: if `$slug === 'khaaaaaan'`:

[[[ code('e4402b3ccf') ]]]

Then we need to know about this! Go copy some code from the docs. Then, simplify
a bit - we don't need the attachment stuff, this is coming from `Khan` and the text
should be "Ah, Kirk, my old friend.":

[[[ code('695d45da3b') ]]]

Excellent! Copy the slug. Then go to that page in your browser. And... it *totally*
did *not* work: the `$slack` argument is missing.

Well... I guess `debug:autowiring` doesn't lie. Copy the `$slack` argument to the
constructor:

[[[ code('b38871c23c') ]]]

No, it won't work here either... but we *will* get a better error message. Actually,
this is due to another shortcoming with controller autowiring: when it fails, the error
isn't great. That will hopefully *also* be improved in the future. Again, a few of these
features are still brand new!

Refresh! Ah, much better:

> Cannot autowire service `ArticleController`: argument `$slack` of method `__construct()`
> references class `Nexy\Slack\Client`, but no such service exists.

This basically means that we're missing configuration to tell the container *which*
services to pass for this type-hint. So... are we dead? No way! We are in *total*
control of autowiring.

Open `services.yaml`, then go copy the full class name for the client:
`Nexy\Slack\Client`. Back under `bind`, instead of using the argument *name*,
like `$slack`, put the *class* name: `Nexy\Slack\Client`. Set this to the target
service id: `@nexy_slack.client`:

[[[ code('c325b409bd') ]]]

That's it! Bind has *two* super-powers: you can bind by the argument name *or* you
can bind by a class or interface. We're defining our *own* rules for autowiring!

Let's make sure I'm not lying: refresh! Yes! There's our Slack notification.

## Autowiring Aliases

But I want to make just *one* small tweak. In `services.yaml`, instead of putting
this beneath `_defaults` and `bind`, let's un-indent it so that it's at the root
of services:

[[[ code('25271e7e13') ]]]

Refresh again. It works *exactly* like before!

The difference is subtle. Config beneath `_defaults` *only* affects services that
are added in this *file*. But when you put this same config directly under `services`,
it will affect *all* services in the system. In practice... that makes *no* difference:
only *our* code uses autowiring: third-party libraries do *not* using autowiring,
to keep things predicatable.

The *biggest* reason I did this is that, when you run `debug:autowiring`:

```terminal-silent
php bin/console debug:autowiring
```

and search for "Slack", there it is! When it's under `bind`, it won't show up here.

## About Autowiring Logic

But... this trick *also* shows us a bit more about how autowiring *works*. By
putting this config directly under `services`, we're creating a *new* service
in the container with the id `Nexy\Slack\Client`. But this is not a real service,
it's just an "alias" - a "shortcut" - to fetch the existing `nexy_slack.client`
service.

Here's the important question: when an argument to a service hasn't been configured
under `bind` or `arguments`, how does the autowiring figure out *which* service to
pass? The answer is *super* simple: it just looks for a service whose id *exactly*
matches the type-hint. Yep, *now* that there is a service whose id is `Nexy\Slack\Client`,
we can use that class as a type-hint. That's *also* why *our* classes - like
`MarkdownHelper` can be autowired: each class in `src/` is auto-registered as a
service and given an `id` that matches the class name.

Ok, it's time to turn to something different, but *very* important in Symfony 4:
environment variables. This will help us to *not* hardcode our secret Slack URL
inside our code.
