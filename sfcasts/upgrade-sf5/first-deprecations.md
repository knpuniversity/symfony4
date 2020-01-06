# Fixing the First Deprecations

We've upgraded to Symfony 4.4 *and* updated all of our Symfony recipes. *Now*,
our path to Symfony 5 is pretty straightforward. We need to find and fix all of
the deprecated code that we're using. As *soon* as we've done that, it will be
safe to tweak our `composer.json` file and go to 5.0.

## Finding Deprecated Code

So how *do* we figure out what deprecated functions, config options or classes
we might be using? There are two *main* ways. The best is down here on the web debug
toolbar. This *literally* tells us that during the loading of this page, we called
49 deprecated things. We'll look at these in a minute and start eliminating them.

But *even* once you get this number down to zero... you can't be *totally* sure
that you've fixed *all* your deprecated. Like, what if you're using a deprecated
function *only* on some obscure AJAX call... and you forgot to check that.

That's why Symfony has a *second* way to find deprecated code: a deprecations
log file on production. Open up `config/packages/prod/monolog.yaml`. This has two
handlers - `deprecation` and `deprecation_filter` that, together, add a log entry
each time you hit deprecated code on production. So once you *think* you've fixed
all your deprecated code, just deploy it to production, wait a few hours or days,
and check the log to make sure it doesn't contain anything new. *Then* you know it's
safe to upgrade.

By the way, Symfony Insight has special integration to identify and fix
deprecation warnings. So if you want some extra help... or an "easy" button,
give it a try.

## Removing WebServerBundle

So let's start crushing our deprecations. I'll refresh the homepage and open the
deprecations counter link in a new window. Fixing deprecated code can be... a bit
of an adventure because you might need to change a class name, method name, remove
a bundle, upgrade a 3rd party library, tweak some config or anything else you
can think of.

The first deprecation is pretty simple: it says the `WebServerBundle` is deprecated
since Symfony 4.4.

At the beginning of this tutorial, we started a local web server by running:

```terminal
symfony serve
```

This `symfony` thing is the Symfony binary: a nice development tool that, in addition
to other things, is able to start a development server. Before this existed, we
*used* to start a local web server by running:

```terminal
php bin/console server:run
```

A console command that came from the WebServerBundle. That's now deprecated...
because the Symfony binary is better.

So this deprecation is easy to fix. Inside `composer.json`, find the
`symfony/web-server-bundle` line, copy it, find your terminal and remove it:

```terminal
composer remove symfony/web-server-bundle
```

Oh! It's over-achieving! In addition to removing that bundle, it's upgrading a
few related packages to the latest bug fix version. This also *unconfigured*
the recipe: it removed the bundle from `bundles.php` *and* removed an entry from
`.gitignore` that we don't need anymore.

## Updating a 3rd Party Bundle

Hey! One deprecation is gone! Let's go find another one! Hmm, the second one
is something about:

> calling the `EventDispatcher::dispatch()` method with the event name as the
> first argument is deprecated since Symfony 4.3.

One of the trickiest things about fixing deprecations is that you need to find
out *where* this is actually coming from. To make things even *more* interesting...
pretty often, a deprecation won't be triggered directly by *our* code, but by
a third-party bundle that were using.

In this case, if you show the trace, it gives info about where this is coming from.
It's not super obvious... but if you closely up here, it mentions LiipImagineBundle.

Ok, so LiipImagineBundle appears to be calling some deprecated code, which means
that our current version will *definitely* not be compatible with Symfony 5.
Fortunately, there's only *one* way to fix deprecated code that's called from
a vendor library: upgrade it!

Let's do this in the *laziest* way possible. Inside `composer.json`, find that
package, copy its name, and run:

```terminal
composer update liip/imagine-bundle
```

What we're *hoping* is that this deprecation *has* been fixed and - *ideally* -
that we only need to upgrade a "minor" version to get that fix - like maybe upgrading
from 2.1 to 2.2 will have the fix.

And actually... yea! It *did* upgrade from 2.1 to 2.2. Did that fix the deprecation?
I have no idea! Let's find out! Close the profiler tab and refresh the homepage.
Good sign: the deprecations went from 48 to 29. I'll open the deprecations in a
new tab and... awesome: it looks like that specific deprecation is gone.

Next: let's keep going! We're going to focus on these `TreeBuilder::root()`
deprecations next. These are *also* coming from third-party libraries. But upgrading
them will be bit more complex.
