# Autowiring Aliases

The way we coded in Symfony 3 is a bit different than Symfony 4. And... well... we
need to learn just a *little* bit about the Symfony 3 way. Why? Because, when you
find bundles with outdated docs, or old StackOverflow answers, I want you to be able
to *translate* that into Symfony 4.

## Public Versus Private Services

In Symfony 3, services were defined as *public*. This means that you could use a
`$this->get()` shortcut method in your controller to fetch a service by its id.
Or, if you got the container object itself - yep, that's totally possible - you
could say `$container->get()` to do the same thing.

But in Symfony 4, most services are *private*. And when a service is private, it
simply means that you *cannot* use the `$this->get()` shortcut to fetch it.

At first, it might seem like we're just making life more difficult! But actually,
it's just a different *philosophy*.

Open `services.yaml` and, below `_defaults`, check out the `public: false` config.
Thanks to this, any service that *we* create is *private*. And so, we *cannot* fetch
our services with `$this->get()`. Increasingly, more and more third-party bundles
are *also* making their services private.

Thanks to this, instead of using `$this->get()`, we fetch services via "dependency
injection" - a fancy, scary-sounding word that describes what we've been doing
this entire tutorial: passing services and config as arguments. This is considered
a better coding practice than `$this->get()`, which means that *we* get to write
nice code. Woo!

And actually... it *also* makes your app faster! It's not huge, but private services
are faster than public services.

## If you DO Want to use $this->get()

Side note, if you *do* want to use the `$this->get()` shortcut to fetch a public
service - which you should *not* - you'll need to change your base controller class
to `Controller` instead of `AbstractController`. It's not important why... and you
shouldn't do it anyways :p.

## Fetching a Service by id

Okay okay, so if we *can't* use the `$this->get()` shortcut, how the heck can we
fetch this service? Let's experiment! Copy the service id, find your terminal, and
run:

```terminal
php bin/console debug:container nexy_slack.client
```

Apparently the class for this object is `Nexy\Slack\Client`. We didn't see *any*
autowiring type-hints that would work in `debug:autowiring`... but... maybe it will
work if we use *this* class?

Let's try it! In `ArticleController::show()`, add another argument: `Client` - make
sure you get the one from `Nexy\Slack` - `$slack`.

Add an `if` statement: if `$slug == 'khaaaaaan'`, then we need to know about this!
Go copy some code from the docs. Then, let's simplify a bit - we don't need the
attachment stuff, and this is coming fro `Khan` and the text should be
"Ah, Kirk, my old friend.".

Cool! Copy the slug. Then go to that page in your browse. And... it *totally* didn't
work: the `$slack` argument is missing.

Well... I guess `debug:autowiring` does not lie. Copy the `$slack` argument to the
constructor. No, it won't work here either... but we *will* get a better error message.
Actually, this is due to another shortcoming with controller autowiring: if it fails,
the error isn't great. That will hopefully *also* be improved in the future. Again,
a few of these features are still brand new!

Refresh! Ah, much better:

> Cannot autowire service ArticleController: argument $slack of method `__construct()`
> references class `Nexy\Slack\Client`, but no such service exists.

This basically means that we're missing configuration to tell the container *which*
services to pass for this type-hint. So... are we dead? Totally not! We are in
*total* control of autowiring.

Open `services.yaml`, then go copy the full class name for the client:
`Nexy\Slack\Client`. Back under `bind`, instead of using the argument *name*,
like `$slack`, put the *class* name: `Nexy\Slack\Client`. Set this to the target
service id: `@nexy_slack.client`.

That's it! Bind has *two* super-powers: you can bind by the argument name *or* you
can bind by class or interface. We're defining our *own* autowiring rules!

Let's see if it works - refresh! Yes! There's our Slack notification.

## Autowiring Aliases

But I want to make just *one* small tweak. In `services.yaml`, instead of putting
this beneat `_defaults` and `bind`, let's un-indent it so that it's at the root
of services.

Refresh again. It works *exactly* like before!

The difference is subtle. Config beneath `_defaults` *only* affects services that
are added in this *file*. But when you put this same config directly under `services`,
it will affect *all* services in the system. In practice... that makes no difference:
only *our* code will try to autowire this. The *biggest* reason I did this is that,
when you run `debug-container`:

```terminal-silent
php bin/console debug:container
```

and search for "Slack", there it is! When it's under `bind`, it won't show up here.

## About Autowiring Logic

But... this trick *also* shows us a bit more about how autowiring *works*. By
putting this config directly under `services`, we're creating a *new* service
in the container with the id `Nexy\Slack\Client`. But this is not a real service,
it's just an "alias" - a "shortcut" - to fetch the existing `nexy_slack.client`
service.

Here's the important question: when an argument to a service hasn't been configured
under `bind` or `arguments`, how does the autowiring know *which* service to pass?
The answer is *super* simple: it just looks for a service whose id *exactly* matches
the type-hint. Yep, *now* that there is a service whose id is `Nexy\Slack\Client`,
we can use that class as a type-hint. That's *also* why *our* classes - like
`MarkdownHelper` can be autowired: each class in `src/` is auto-registered as a
service with an `id` matching the class name.

Ok, it's time to turn to something different, but *very* important in Symfony 4:
environment variables. This will help us to *not* hardcode our secret Slack URL
inside our code.
