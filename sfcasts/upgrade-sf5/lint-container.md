# Is your Container Running? Catch It! lint:container

Symfony's service container is special... like *super-powers* special. Why? Because
it's "compiled". That's a fancy way of saying that, instead of Symfony figuring
out how to instantiate each service at runtime, when you build your cache, it
figures out *every* argument to *every* service and dumps that info into a cache
file - called the "compiled container". That's a major reason why Symfony is so
fast.

But it has another benefit: if you misconfigured a service - like used a wrong
class name - you don't have to go to a page that *uses* that service to notice the
problem. Nope, *every* page of your app will be broken. That means less surprise
bugs on production.

Another type of error that Symfony's container will catch immediately is a missing
argument. For example, imagine you registered a service and forgot to configure
an argument. Or, better example, Symfony couldn't figure out what to pass to this
`Mailer` argument for some reason:

[[[ code('f1c7a8ffdf') ]]]

If that happened, you'll get an error when the container builds... meaning
that *every* page will be broken - even if a page doesn't use this service.

## Detecting Type Problems

Starting in Symfony 4.4, Symfony can now *also* detect if the wrong *type* will
be passed for an argument. For example, if we type-hint an argument with
`MailerInterface`, but due to some misconfiguration, some *other* object - or maybe
a string or an integer - will be passed here, we can find out immediately. But
this type of problem won't break the container build. Instead, you need to *ask*
Symfony to check for type problems by running:

```terminal
php bin/console lint:container
```

And... oh! Woh! This is a perfect example!

> Invalid definition for service `nexy_slack.client`. Argument 1 of
> `Nexy\Slack\Client` accepts a Psr `ClientInterface`, `HttpMethodsClient` passed.

Apparently the container is configured to pass the wrong type of object to this
service! This service comes from NexylanSlackBundle - I broke something when I
upgraded that bundle... and didn't even realize it because I haven't navigated
to a page that uses that service!

## Fixing our lint Problem

After some digging, it turns out that the bundle has a *tiny* bug that allowed
us to accidentally use a version of a dependency that is too old. Run:

```terminal
composer why php-http/httplug
```

I won't bore you with the details, but basically the problem is that this library
needs to be at version 2 to make the bundle happy. We have version 1 and a few
other libraries depend on it.

The fix is to go to `composer.json` and change the `guzzle6-adapter` to
version 2:

[[[ code('7843d9a5df') ]]]

Why? Again, if you dug into this, you'd find that we need version 2
of `guzzle6-adapter` in order to be compatible with version 2 of `httplug`...
which is needed to be compatible with the bundle. Sheesh.

Now run `composer update` with all three of these libraries:
`php-http/httplug`, `php-http/client-commmon` - so that it can upgrade to a new
version that allows version 2 of HTTPlug - and `guzzle6-adapter`:

```terminal-silent
composer update php-http/httplug php-http/client-commmon php-http/guzzle6-adapter
```

And... cool! Now run:

```terminal
php bin/console lint:container
```

We get no output because *now* our container is happy. And because a few libraries
had major version upgrades, if you looked in the CHANGELOGs, you'd find that we
*also* need one more package to *truly* get things to work:

```terminal
composer require "http-interop/http-factory-guzzle:^1.1"
```

The point is: `lint:container` is a *free* tool you can add to your continuous
integration system to help catch errors earlier. The more type-hints you use
in your code, the more it will catch. It's a win win!

And........ that's it! We upgraded to Symfony 4.4, fixed deprecations,
upgraded to Symfony 5, jumped into some of the *best* new features and, ultimately,
I think we became friends. Can you feel it?

If you have any upgrade problems, we're here for you in the comments. Let us
know what's going on, tell us a funny story, or leave us a Symfony 5 Haiku:

Reading your comments
After a long weekend break
Brings joy to keyboards

Alright friends, seeya next time!
