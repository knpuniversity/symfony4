# Managing Flex, extra.symfony.require & Version Constraints

We just ran:

```terminal
composer update "symfony/*"
```

Thanks to the `extra.symfony.require` key in our `composer.json` file:

[[[ code('6149fccb88') ]]]

Which is currently set to `4.3.*`, it only upgraded things to the latest 4.3
version - not 4.4. Let's change this to `4.4.*`:

[[[ code('8697469c52') ]]]

But wait... why are we upgrading to Symfony 4.4? Isn't this a tutorial about upgrading
to Symfony 5? Why not just go straight there? The reason is due to Symfony's, honestly,
*incredible* upgrade policy. Symfony *never* breaks backwards compatibility for a
minor release - like from `4.3` to `4.4`. Instead, it *deprecates* code... and
you can *see* what deprecated code you're using with some special tools. By upgrading
to 4.4, we'll be able to see the *full* list of deprecated things we need to fix.
Then we can fix them before upgrading to Symfony 5. We'll see this later.

Anyways, find your terminal and, once again, run:

```terminal
composer update "symfony/*"
```

Yea! This time it *is* updating the Symfony packages to 4.4. That was easy!

## composer.json Version Constraints for symfony/ Packages

Except... come on... it's never *quite* that easy. In fact, *some* Symfony packages
did *not* upgrade. Check it out. Run:

```terminal
composer show symfony/mailer
```

Scroll up: woh! This is still on version 4.3! Why?

Open up the `composer.json` file and find `symfony/mailer`:

[[[ code('bde3823699') ]]]

Interesting: some packages - like `symfony/form` or `symfony/framework-bundle`
are set to `^4.0` - which more or less means `4.*`. But the `symfony/mailer`
version is `4.3.*`.

## Symfony Flex: composer.json Version Formatting

There are two things I need to say about this. First, *usually* when you run
`compose require some/package`, when Composer updates your `composer.json`
file, it uses the "caret" (`^`) format. That's why you see `^3.0` and `^1.1`.

*But*, when you use Symfony Flex and `composer require` a Symfony package, it
*changes* that to use the `*` format - like `4.3.*`. That's not a huge deal. In
fact, it's almost an accidental feature - but it *is* nice because the best-practice
is typically to control the "minor" version of your Symfony packages - that's the
middle number - so that you can upgrade them all at the same time.

But... Flex didn't *always* do this. That's why, in my project, you see a mixture:
some libraries like `symfony/form` have the "caret" format and other libraries - that
were installed more recently like `symfony/mailer` - use the "star" format.

## Symfony Flex: symfony.extra.require is a "Soft" Requirement

The *second* thing I need to tell you is that the `extra.symfony.require` config -
set to `4.4.*` now - is... more of a "suggestion". It doesn't *force* all Symfony
packages to this version. More accurately it says:

> When any symfony/ package is updated, its upgrade will be restricted to
> a version matching 4.4.*

But if you have a package that is *specifically* locked to `4.3.*`, it won't
*override* that and *force* it to `4.4.*`. *That* is why `symfony/mailer`
didn't upgrade.

## Changing symfony/ composer.json Versions

If all this explanation doesn't make total sense... or you just done care - Hey,
that's ok! Here is what you need to know: whenever you upgrade Symfony to a new
minor version - like 4.3 to 4.4, you need to do two things: (1) update the
`extra.symfony.require` value *and* (2) update *all* the package versions to
`4.4.*`.

If that seems a bit redundant, it... kinda is! But changing the version
next to the package to `4.4.*` gives you *clear* control of what's going on... and
it's how Composer *normally* works. And then, the `extra.symfony.require` config
gives us a big performance boost in the background.

Let's do this next, upgrade to Symfony 4.4 and fix a few packages that ended up
inside our "dev" dependencies incorrectly.
