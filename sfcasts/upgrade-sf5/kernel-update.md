# FrameworkBundle Recipe Part 2: The Kernel Class

We're *right* in the middle of upgrading the FrameworkBundle recipe.

## Updates to .gitignore

It apparently added a new line to our `.gitignore` file: some
`config/secrets/prod/prod.decrypt.private.php` file.

We don't have that file yet. What is it? Oh, I can't *wait* to talk about it! It's
part of Symfony's new secrets management system. OooOOooo. For now, say yes:
we *will* want to ignore this file later when we create it.

## Updates to cache.yaml

The next change is inside `cache.yaml`. It... yea... just updated some comments.
We don't need that, but it's nice: type "y" to add them. Oh, and the next change
is *also* from `cache.yaml` - further down. It looks like they changed the
example config... but we've already customized this. So, we do *not* want
this. Enter "n" to *not* add it.

## Updates to framework.yaml

Next up is `framework.yaml`. Interesting, two changes here: it removed the
`default_locale` and added two new cookie settings. I won't make you dig through
the commit history to find the explanation behind these. `default_locale` was
removed from here because it is *also* defined in `translation.yaml`... and someone
realized it was pointless and a bit confusing to have it in both places.

The cookie settings are a bit more interesting: they activate two security-related
features. The first is `cookie_secure`. The `auto` setting means that *if* a
visitor comes to your site via HTTPS, then it will create an HTTPS-only cookie.
It's a no brainer and you should exclusively be using HTTPs on your production
site anyway.

The `cookie_samesite` option activates a feature on your cookies called... well...
"samesite". It's a relatively new security-related feature that's quickly been
adopted by most browsers. We talk more about it inside our
[API Platform Security](https://symfonycasts.com/screencast/api-platform-security/samesite-csrf)
tutorial - but this setting shouldn't cause problems in most setups and is
definitely more secure.

So let's say "y" for *all* of these changes.

## Updates to services.yaml

Keep going! Now we're inside `services.yaml`! Hmm... it looks like it's just removing
a bunch of stuff! That's because we've customized *most* of this file. If you
look closely, there *is* one change: the recipe apparently removed the
`public: false` line along with the comments describing it.

Why? Because since Symfony 4.0, `public: false` is the *default* value. We actually
*never* needed this config! It was included originally... mostly for historical
reasons.

So we *do* want this change... but... we can't say "yes" to this because it would
kill *all* our code. Enter "q" to get out of the `git add -p` system. We'll need
to make this change manually. First, undo *all* of the changes by running:

```terminal
get checkout config/services.yaml
```

Move back and look at the file in our editor... the custom code is back!
*Now* manually take out the `public: false` line and the comments below it:

[[[ code('32ca7a5d94') ]]]

Let's continue the process. Start again with:

```terminal
git add -p
```

It's going to ask us about a few changes we've already said no to - say no
again. And this time, for the `services.yaml` change, enter "y" to add it.

## Updates to public/index.php

The next change is inside `public/index.php`. Hey! It's that namespace change
from `Debug` to `ErrorHandler`. We *know* that's a good change. If we *did*
skip this, we would see a deprecation warning telling us to make that change.
So upgrading the recipes is... actually saving us time later!

## Updates to Kernel.php

Finally, we get to the *most* important file of the recipe: `src/Kernel.php`.
This is another file that you *probably* haven't added custom changes to. And
so, it's *probably* save to accept all these updates. But let's look carefully
and I'll highlight the reason behind a few changes. It looks like a lot, but it's
all minor.

For example, PHP 7.1 allows you to have *private* constants. The recipe update
uses that. No big deal. The `getCacheDir()` and `getLogDir()` methods aren't
needed anymore because they're implemented by the *parent* class with the
same logic. Removing them is a nice cleanup.

And `registerBundles()` now has an `iterable` return type.

I'll clear my screen then answer "y" to add these changes.

Next, it *added* a `getProjectDir()` method. This *used* to not be needed, because
Symfony determined it automatically by searching for your `composer.json` file.
But since they didn't work correctly in some edge-cases, it's added directly
in our class now. *Probably* not a super important thing for us, but we'll accept
this change.

Next, `configureContainer()` has a `void` return type and some parameters
got tweaked. The `autowiring.strict_mode` parameter was removed because it was
something that made Symfony *3* behave like Symfony *4* does by default. It's
not needed anymore... and never was. Clean up!

Then, `inline_factories` is a performance thing - cool - and there's a *slight*
tweak to how the config files are loaded to make life faster in the development
environment: it no longer looks recursively for files inside the environment
config directories - like `config/packages/dev`.

At the bottom, `configureRoutes()` has a `void` return type and a similar recursive
tweak. Say "y" to add all of this.

And... we're done! This is `symfony.lock`: definitely accept these changes.

## Adding config/routes/dev/framework.yaml

Let's check out how things look:

```terminal
git status
```

Oh! The recipe added a *new* file: `config/routes/dev/framework.yaml`. Interesting.
Let's go open that: `config/routes/dev/framework.yaml`:

[[[ code('9c26384e7d') ]]]

You may or may not know, but Symfony has a feature that allows you to test what your
production error pages look like. Just go to `/_error/404` to see the 404 page
or `/_error/500` to see the 500 error page... though... ha, *that*, uh, never
happens on production.

*This* file loads an `errors.xml` file that adds this route in the `dev` environment
only.

Previously, if you open the `twig.yaml` file in the same directory, this feature
came from TwigBundle:

[[[ code('ff521262e7') ]]]

Now it lives inside FrameworkBundle. *That* is why the framework-bundle recipe
added the new file.

Hmm... but since we haven't updated the TwigBundle recipe yet, we temporarily
have *two* routing files that are trying to add a route to the *same* `/_error`
URL. We'll update the TwigBundle recipe next to fix this.

Right now, add this file:

```terminal
git add config/routes/dev/framework.yaml
```

And run:

```terminal
git status
```

Hmm... yep! These last changes are the ones we do *not* want. Revert them with:

```terminal
git checkout .
```

We're ready to commit the *biggest* recipe upgrade we're going to have:

```terminal
git commit -m "upgrading symfony/framework-bundle recipe"
```

Phew! Next, let's update the TwigBundle recipe then keep going onto the Mailer
recipe and then the rest. Home stretch people!
