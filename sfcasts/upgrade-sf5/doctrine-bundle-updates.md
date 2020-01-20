# DoctrineBundle Updates & Recipe Upgrade

We just upgraded from DoctrineBundle version 1 to version 2 and... it broke our
app. That's lame! Hmm:

> Cannot autowire service `ApiTokenRepository`: argument `$registry` references
> interface `RegistryInterface` but no such service exists.

## Checking the CHANGELOG

Hmm. Ya know, instead of trying to figure this out... and digging for any
other breaking changes... let's just go look at the bundle's CHANGELOG. Go back
to the DoctrineBundle GitHub homepage. And... ah! Even better: upgrade files!
Open `UPGRADE-2.0.md`.

There's a lot here: dropping old versions of PHP & Symfony and changes to
commands. But if you look closely, you'll find that *most* of these are pretty
minor. The *most* important changes are under "Services aliases". Previously,
if you wanted to get the `doctrine` service, you could use the `RegistryInterface`
type-hint for autowiring. Now you should use `ManagerRegistry`.

## From RegistryInterface to ManagerRegistry in Repository Classes

Where do we use `RegistryInterface`? Move over to your terminal and run:

```terminal
git grep RegistryInterface
```

We use it in *every* single repository class. This is code that the
`make:entity` command generated *for* us. The newest version of that bundle
uses `ManagerRegistry`.

Fixing this is as simple as changing a type-hint... it's just tedious. Open up
*every* repository class. And, one-by-one, I'll change `RegistryInterface` to
`ManageRegistry` in the constructor:

[[[ code('57a72c9b20') ]]]

Use `ManagerRegistry` from `Doctrine\Persistence`. There is *also* one
from `Doctrine\Common\Persistence`:

[[[ code('d3a46a66bb') ]]]

## doctrine/common Split into doctrine/persistence (and other Packages)

That's another Doctrine change that's happening right now. Doctrine originally
had a package called `doctrine/common`, which contained a lot of... well...
"common" classes that other Doctrine libraries needed. Doctrine is now
splitting `doctrine/common` into *smaller*, individual packages. Basically,
the `Persistence` directory of `doctrine/common` is now its own package and you
should use *its* classes: the old ones are deprecated.

What makes this a bit more confusing is that the UPGRADE log references the old
class name. Like I said: there are a lot of moving pieces right now.

I'll also remove the old `RegistryInterface` use statement. Repeat this a
*bunch* more times: change to `ManagerRegistry`, remove the `use` statement
and keep going:

[[[ code('4b36f2171c') ]]]

[[[ code('0726d13aca') ]]]

Do you want to see how fast I can type?

[[[ code('ce4058cb8b') ]]]

[[[ code('337de80472') ]]]

Suuuuuuper faaaaaaaast. Ah! I sprained a finger.

Let's see if we're good! Spin over and just run:

```terminal
php bin/console
```

*Before* those changes, running this would have caused an *explosion* - the same
one that we saw after running `composer update`. So we are good: we're using a
Symfony5-compatible version of DoctrineBundle.

## Upgrading the DoctrineBundle Recipe

But... because this library is *so* important... and because we just did a *major*
version upgrade, I also want to upgrade its recipe. Run:

```terminal
composer recipes
```

Ok, yea, DoctrineBundle is one of the *few* recipes that still have an update
available. Run:

```terminal
composer recipes doctrine/doctrine-bundle
```

to get more info and copy the update command. Run it!

```terminal-silent
composer recipes:install doctrine/doctrine-bundle --force -v
```

Ok, it looks like this updated several files. Let's step through the changes.
Clear the screen and run:

```terminal
git add -p
```

## .env Changes and serverVersion

The first changes are inside `.env`: it added a PostgreSQL example and, oh, this
comment is important: it mentions that the `serverVersion` setting is
*required* in this file or in `config/packages/doctrine.yaml`. That's actually
not a new thing, but the new recipe now gives you a bit more info about this.

The setting tells Doctrine what *version* of your database you're using, like
MySQL 5.7 or `mariadb-10.2.12`. Doctrine uses that to know which features are
*supported* by your database.

The point is: this is something Doctrine *needs* to know and you can add that
config inside your `DATABASE_URL` environment variable *or* in `doctrine.yaml`,
which is what *I* prefer. I like to set this to my production database version
and commit it inside `doctrine.yaml` so that the project works the same on any
machine.

So... I guess I want these new comment changes, except that I want to keep my
*existing* `DATABASE_URL`. Copy it, hit "y" to accept the changes, then "q" to
quit the patch mode.

Back in our editor... find `.env`, look for `DATABASE_URL`, and paste our original
value:

[[[ code('b28e939d8f') ]]]

Let's keep going!

```terminal
git add -p
```

Accept the change we just made to `.env`. The next update is in `composer.json`,
we definitely want this. Then... actually, hit "q". Let's add the files we
*know* we want:

```terminal
git add composer.json composer.lock symfony.lock src/Repository
```

Run:

```terminal
git status
```

Much better! Back to:

```terminal
git add -p
```

## Updates to doctrine.yaml

In `bundles.php`, it removed DoctrineCacheBundle - that's a good change - and
now we're inside of `doctrine.yaml`.

There are a *bunch* of interesting updates here. First, there *used* to be a
parameter called `env(DATABASE_URL)`. This was a workaround to prevent Doctrine
from exploding in some edge cases. It's no longer needed. Progress!

Next, the `driver` setting isn't needed inside here because that part is *always*
contained inside `DATABASE_URL`. It's just extra, so we can remove it. Oh, and
`server_version` was just moved further down.

The recipe *also* removed these `charset` options, and that *is* interesting.
If you use MySQL, these settings are needed to make sure that you can store
unicode characters. Starting in DoctrineBundle 2.0, these values are no longer
needed because they are the *default*. That's a nice cleanup.

Below, the `server_version` is *now* commented-out by default: you need to *choose*
to put it in this file or inside your environment variable. I'll uncomment this
in a minute.

Finally, this `naming_strategy` is a minor change: it controls how table and
column names are generated from class and property names. The new setting handles
situations when there is a number in the name. It's a good change... and the old
setting is deprecated. However it *is* possible that this could cause Doctrine
to try to rename some columns. You can run:

```terminal
php bin/console doctrine:schema:update --dump-sql
```

after making this change to be sure. Enter "y" to accept *all* these changes,
then "q" to quit. Find this file - `config/packages/doctrine.yaml` - uncomment
`server_version` and adjust it to whatever you need for your app:

[[[ code('31668f52ec') ]]]

## Production doctrine.yaml Cache Changes

Back to work!

```terminal
git add -p
```

Enter "y" for our `server_version` change. The *last* big update is in
`config/packages/prod/doctrine.yaml`. This file configures cache settings
in the `prod` environment: this is stuff you *do* want. When we originally
installed the bundle, the old recipe created several cache services down here
under the `services` key... and then used them above for the different cache
drivers.

Basically, in DoctrineBundle 2.0, these services are created for you. This means
that the config can be *drastically* simplified. Say "y" to this change.

And... we're done! Phew! Commit this:

```terminal
git commit -m "upgrading to DoctrineBundle 2.0"
```

And celebrate!

## The doctrine/persistence 1.3 Deprecations

Let's go see how the deprecations look now. When I refresh the homepage... down
to 11 deprecations! Check them out.

Huh: a lot of them are *still* from doctrine... they all mention a deprecation
in `doctrine/persistence` 1.3. `doctrine/persistence` is one of the libraries
that was extracted from `doctrine/common`.

Ok, but why are we getting all these deprecations? Where are they coming from?

I have 2 things to say about this. First, because this is a deprecation warning
about a change in `doctrine/persistence` 2.0... and because we're focusing right
now on upgrading to Symfony 5.0, this is *not* a deprecation we need to fix. We
can save it for later.

Second, if you Google'd this deprecation, you'd find that it is *not* coming
from our code: it's coming from Doctrine *itself*, specifically
`doctrine/orm`.

There's currently a pull request open on `doctrine/orm` -
[number 7953](https://github.com/doctrine/orm/pull/7953) - that fixes these.
Basically, `doctrine/orm` is using some deprecated code from `doctrine/persistence`,
but the fix hasn't been merged yet. The fix is targeted for version 2.8 of
`doctrine/orm`. So hopefully when that's released in the future, you'll be able
to update to it to remove this deprecation. But, as I said... it's not a problem
right now: we can keep working through the Symfony-related deprecations and
ignore these.

And... *that* list is getting pretty short! Time to finish them.
