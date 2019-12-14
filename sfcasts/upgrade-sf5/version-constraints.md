# Managing Flex, extra.symfony.require & Version Constraints

We just ran:

```terminal-silent
composer update symfony/*
```

Thanks to the `extra.symfony.require` key in our `composer.json` file - which is
currently set to `4.3.*`, it only upgraded things to the latest 4.3 version - not
4.4. Let's change this to `4.4.*`.

But wait... why are we upgrading to Symfony 4.4? Isn't this a tutorial about upgrading
to Symfony 5? Why not just go straight there? The reason is due to Symfony's
*amazing* upgrade system. Symfony *never* breaks backwards compatibility for a
minor release - like from `4.3` to `4.4`. Instead, it *deprecates* code... and
you can *see* what deprecated code you're using with some special tools. By upgrading
to 4.4, we'll be able to see the *full* list of deprecated things we need to fix.
We'll see this later.

Anyways, find your terminal and, once again, run:

```terminal
composer update symfony/*
```

Yea! this time it *is* updating the Symfony packages to 4.4. That was easy!

## composer.json Version Constraints for symfony/ Packages

Except... come on... it's never *quite* that easy. In fact, *some* Symfony packages
did *not* upgrade. Check this out: run

```terminal
composer show symfony/mailer
```

Scroll up: woh! This is still on version 4.3! Why?

Open up the `composer.json` file and find `symfony/mailer`. Interesting:
some packages - like `symfony/form` or `symfony/framework-bundle` are set to
`^4.0` - which more or less means `4.*`. But the `symfony/mailer` version if
`4.3.*`.

## Symfony Flex: composer.json Version Formatting

There are two things I need to say about this. First, *usually* when you run
`compose require some/package`, when Composer updates your `composer.json`
file, it uses the "carrot" (`^`) format. That's why you `^3.0` and `^1.1`.

*But*, when you use Symfony Flex and `composer require` a Symfony package, it
*changes* that to use the `*` format - like `4.3.*`. That's not a huge deal. In
fact, it's almost an accidental feature - but it *is* nice because the best-practice
is typically to control the "minor" version of your Symfony packages - that's the
middle number - so that you can upgrade them all at the same time.

But... Flex didn't *always* do this. That's why, in my project, you see a mixture:
some libraries like `symfony/form` have the "carrot" format and other libraries - that
were installed more recently like `symfony/mailer` - use the "star" format.

## Symfony Flex: symfony.extra.require is a "Soft" Requirement

The *second* thing I need to tell you is that the `extra.symfony.require` value -
set to `4.4.*` now - is... more of a "suggestion". It doesn't *force* all Symfony
packages to this version. More accurately it says:

> When any symfony/ package is updated, its upgrade will be restricted to
> a version matching 4.4.*

But if you have a package that is *specifically* locked to `4.3.*`, it won't
*override* that. *That* is why `symfony/mailer` didn't upgrade.

## Changing symfony/ composer.json Versions

If all this explanation didn't make sense... or you just done care - Hey, that's
fair! - here is what you need to know. Whenever you upgrade Symfony to a new
minor version - like 4.3 to 4.4, you need to do two things: update the
`extra.symfony.require` value *and* update *all* of the package versions to
`4.4.*`.

And if that seems a bit redundant, it... kinda is! But changing the version
next to the package to `4.4.*` gives you *clear* control of what's going on: it's
how Composer *normally* works. And then the `extra.symfony.require` key gives
you a big performance boost in the background.

Let's do this next, upgrade to Symfony 4.4 and fix a few packages that ended up
inside our "dev" dependencies incorrectly.
