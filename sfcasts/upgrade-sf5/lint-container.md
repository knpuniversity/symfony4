# Is your Container Running? Catch It! lint:container

Symfony's service container is special... like *super-powers* special. Why? Because
it's "compiled". That's a fancy way of saying that, instead of Symfony figuring
out how to instantiated each service as you use them at runtime, when you build
your cache, it figures out *every* argument to *every* service and dumps that info
into a cache file - called a "compiled container". That's a big reason why Symfony
is so fast.

But it has another benefit: if you mis-configured a service - like used a wrong
class name - you don't have to go to a page that uses that service to notice the
problem. Nope, *every* page of your app will be broken. That means less surprise
bugs on production.

Another type of error that Symfony's container will catch immediately is a missing
argument. For example, imagine you registered a service and you forgot to configure
an argument or, better example, Symfony couldn't figure out what to pass to this
`Mailer` argument for some reason. If that happened, you'll get an error when the
container builds... meaning that *every* page will be broken - even if a page
doesn't use this service.

## Detecting Type Problems

Starting in Symfony 4.4, Symfony can now *also* detect if the wrong *type* will
be passed for an argument. For example, if we type-hint an argument with
`MailerInterface`, but due to some misconfiguration, some *other* object - or maybe
a string or an integer - is going to be passed here, we can find out immediately.
But this type of problem won't break the container build. Instead, you can ask
Symfony to check for type problems by running:

```terminal
php bin/console lint:container
```

And... oh! Woh! This is a perfect example!

> Invalid definition for service `nexy_slack.client`. Argument one of
> `Nexy\Slack\Client` excepts a Psr `ClientInterface`, `HttpMethodsClient` passed.

Apparently the container is configured to pass the wrong type of object to this
service! This service comes from NexylanSlackBundle - I broke something when I
upgraded that bundle and didn't even realize it because I hadn't yet navigated
to a page that uses this service!

## Fixing our lint Oroblem

After some digging, it turns out that the bundle has a *tiny* bug that allowed
us to accidentally use a version of a dependency that is too old. Run:

```terminal
composer why php-http/httplug
```

I won't bore you with the details, but basically the problem is that this library
needs to be at version 2 in order to work with the bundle. We have version 1
and there are 3 other libraries that depend on this.

The fix is to go to `composer.json` file and change the `guzzle6-adapter` to
version 2. Why? Again, if you dug into this, you'd find that we need version 2
of the `guzzle6-adapter` in order to be compatible with version 2 of `httplug`...
which is needed to be compatible with the bundle. Sheesh.

Now run `composer update` with all tjhree of these libraries:
`php-http/httplug`, `php-http/client-commmon` - so that it can upgrade to a new
version  that allows version 2 of httplug - and the `guzzle6-adapter`:

```terminal-silent
composer update php-http/httplug php-http/client-commmon php-http/guzzle6-adapter
```

And... cool! Now run:

```terminal-silent
php bin/console lint:container
```

We get no output because *now* our container is happy. And because a few libraries
had major version upgrades, if you looked in the CHANGELOGs, you'd find that we
*also* need one more package to *truly* get things to work:

```terminal
composer require http-interop/http-factory-guzzle
```

The point is: `lint:container` is a *free* tool you can add to your continuous
integration system to help catch error earlier. And the more type-hints you use
in your code, the more it will catch. It's a win win!

And........ that's it! We upgraded to Symfony 4.4, we fixed deprecations,
we upgraded to Symfony 5, we jumped into some of the *best* new features and we
developed a deeper friendship. Can you feel it?

If you have any upgrade problems, as always, we're here for you in the comments.
Let us know what's going on, tell us a funny story, or leave us a Symfony 5 Haiku:
no comments will be turned away.

Alright friends, seeya next time!
