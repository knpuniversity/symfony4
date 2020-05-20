# Upgrading to Symfony 4.4

To upgrade from Symfony 4.3 to 4.4 - that's a "minor" version upgrade - we need
to change the `extra.symfony.require` value to `4.4.*` - done! - *and* update
each Symfony package version to that same value.

## Updating Versions of Individual Symfony Packages

Let's get to work! I'll start with `symfony/asset`: change it to `4.4.*`. Copy
that and start repeating it:

[[[ code('85ad576dc9') ]]]

I *will* skip a *few* packages that start with `symfony/` because they are *not*
part of the main Symfony repository - like `symfony/flex`:

[[[ code('f97beae84f') ]]]

These follow their own release schedules... so they usually have a version that's
very different than everything else.

All "packs" - those are the, sort of, "fake" packages that just require other
packages for convenience - are another example:

[[[ code('9d3f92e486') ]]]

These usually allow pretty much any version of the libraries inside of them - so
any Symfony packages *will* update correctly. If you want more control over the
versions, remember that you can run:

```terminal
composer unpack symfony/orm-pack
```

When you do that, Flex will remove this line and replace it with the individual
packages so you can manage their versions. That's not required, but also not a
bad idea.

WebpackEncoreBundle is another example of a package that isn't part of the main
repository - you can see that its version is totally different:

[[[ code('6e631baf1a') ]]]

Don't forget to also check the `require-dev` section: there are a bunch here:

[[[ code('032319a652') ]]]

Including `symfony/debug-bundle`, which has a funny-looking version because
I unpacked it from a `debug-pack` in one of our courses. And both MakerBundle and
MonologBundle are not in the main repository:

[[[ code('64c0144965') ]]]

If you're not sure, you can search Packagist.org for `symfony/symfony`. That package
lists *all* of the packages that make up this "main" repository I keep talking about.

Update `phpunit-bridge`, leave the `profile-pack` version and update `var-dumper`:

[[[ code('2fabdf7079') ]]]

Perfect! We have `4.4.*` everywhere up here *and* `4.4.*` for `extra.symfony.require`
so that everything matches *and* we get that performance boost in Composer.

Let's do this! Find your terminal and run:

```terminal
composer update "symfony/*"
```

And... yea! It's upgrading the last few libraries that were *previously* locked
to `4.3`. Congratulations! You just upgraded *all* Symfony packages to 4.4.

## Fixing some require-dev Packages

Before we move on, I noticed a small problem in `composer.json`: the `symfony/dotenv`
package is in my `require-dev` section:

[[[ code('39d9c375d9') ]]]

When we put something in `require-dev`, we're saying:

> This package is *not* needed when I run my code on production.

It *was* true that when Symfony 4.0 was released, the DotEnv component was used
in the development environment only - as a way to help set environment variables
more easily. That's not true anymore: Symfony apps now *always* load the `.env`
files.

The `symfony/monolog-bundle` package - which gives us the `logger` service -
should *also* live under `require` - along with its supporting package:
`easy-log-handler`:

[[[ code('6105998897') ]]]

Logging is something we *always* want.

Let's fix these. Copy the `symfony/dotenv` package name, find your terminal,
and *remove* these three packages:

```terminal
composer remove --dev symfony/dotenv symfony/monolog-bundle easycorp/easy-log-handler
```

An easy way to move a package from `require-dev` to `require` and make sure that
Composer notices, is to remove the package and re-add it.

When we do that... our code explodes! No problem: our app *totally* needs the DotEnv
component... so it's temporarily freaking out. You'll also notice that, if you run:

```terminal
git status
```

Removing these packages *also* removed their recipes. Re-add the libraries by
using that same command, but replacing `remove` with `require` and getting rid
of the `--dev` flag:

```terminal-silent
composer require symfony/dotenv symfony/monolog-bundle easycorp/easy-log-handler
```

***TIP
The `easycorp/easy-log-handler` package is abandoned, so it's probably even better
to remove it from this list and leave it out of your app
***

This should add those back under the `require` section - yep, here is one - *and*
it will reinstall the *latest* version of their recipes... which means that the
recipe *could* be slightly newer than the one we had before:

[[[ code('f5e06cb7f8') ]]]

This is... accidentally... the first example of upgrading a recipe. Run:

```terminal
git status
```

Cool. Should we commit all of these changes? Not so fast. When a recipe is updated,
you need to *selectively* add each change. Let's learn how next.
