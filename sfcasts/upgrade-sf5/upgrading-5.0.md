# Upgrading to Symfony 5.0

We've done it! We fixed *all* the deprecations in our app... except for the
`doctrine/persistence` stuff, which we don't need to worry about because we're
not upgrading that library. That means... we are ready for Symfony5!

## Changing composer.json for Symfony 5.0

How... do we actually upgrade? We already know: it's the *exact* same process
we used to upgrade from 4.3 to 4.4.

Open up `composer.json`. Our goal is to update *all* of these `symfony/` libraries
to 5.0:

[[[ code('70d3479779') ]]]

Well, not quite *all* of them - a few are not part of the main Symfony
library, like `monolog-bundle`. But basically, everything that has `4.4.*` now
needs to be `5.0.*`.

We also need to update one more thing: the `extra.symfony.require` value:

[[[ code('9d565c38a3') ]]]

This is primarily a performance optimization that helps Composer filter out extra
Symfony versions when it's trying to resolve packages. This *also* needs to change
to `5.0.*`.

Let's... do it all at once: Find `4.4.*`, replace it with `5.0.*` and hit
"Replace all":

[[[ code('3cb3759685') ]]]

And then... make sure that this didn't accidentally replace any non-Symfony
packages that may have had the same version.... looks good.

## Updating Symfony Packages in Composer

We're ready! At your terminal... I'll hit `Ctrl`+`C` to stop the log tail....
and run the same command we used when upgrading from Symfony 4.3 to 4.4:

```terminal skip-ci
composer update "symfony/*"
```

That's it! It's that easy! We're done! Kidding - it's never *that* easy: you will
almost *definitely* get some dependency errors. Probably... several. Ah, here's
our first.

## Composer: Many Packages Need to Update

These errors are always a *little* hard to read. This says that the current version
of `doctrine/orm` in our project is not compatible with Symfony 5... which means
that it *also* needs to be updated. Specifically we need a newer version
that's compatible with `symfony/console` version 5.

And... it's possible that there is *not* yet a release of `doctrine/orm` that
supports Symfony 5 - we hit that problem earlier with StofDoctrineExtensionsBundle.
But... let's blindly try it! Add `doctrine/orm` to our update list and try again:

```terminal-silent skip-ci
composer update "symfony/*" doctrine/orm
```

And... another error. Actually, the *same* error but this time for
`knplabs/knp-markdown-bundle`. We don't *know* if this bundle has a
Symfony5-compatible release either... and even if it does... it might require a
*major* version upgrade. But the easiest thing to do is add it to our list and hope
for the best. Try it:

```terminal-silent skip-ci
composer update "symfony/*" doctrine/orm knplabs/knp-markdown-bundle
```

So... this is going to happen *several* more times - this is the same error for
`knplabs/knp-snappy-bundle`. Little-by-little, we're discovering *all* the packages
that we need to upgrade to be compatible with Symfony 5. *Instead* of doing this
one-by-one, you *can* also choose the easy route: just run `composer update` with
no arguments and allow Composer to update *everything*.

I prefer to upgrade more cautiously than that... but it's not a bad option. After
all, our Composer version constraints don't allow any *major* version upgrades:
so running `composer update` *still* won't allow any new major package versions
unless you tweaked your `composer.json` file.

Let's keep going with my cowardly, I mean, cautious way: copy the package name and
add it to the update command:

```terminal-silent skip-ci
composer update "symfony/*" \
                doctrine/orm \
                knplabs/knp-markdown-bundle \
                knplabs/knp-snappy-bundle
```

Let's keep trying and... I'll fast-forward through a few more of these: this is
for `liip/imagine-bundle` - add that to the update command - then
`oneup/flysystem-bundle`... and now `sensio/framework-extra-bundle`: add that to
our very-long update command:

```terminal-silent skip-ci
composer update "symfony/*" \
                doctrine/orm \
                knplabs/knp-markdown-bundle \
                knplabs/knp-snappy-bundle \
                liip/imagine-bundle \
                oneup/flysystem-bundle \
                sensio/framework-extra-bundle
```

## Updating --with-dependencies

Hmm, but this *next* error looks a bit different: it's something about
`doctrine/orm` and `doctrine/instantiator`. If you look closely, this says that
in order to get Symfony 5 support, we need `doctrine/orm` version 2.7, but
version 2.7 requires `doctrine/instantiator` 1.3... and our project is currently
*locked* at version 1.2.

Our app doesn't require `doctrine/instantiator` directly: it's a dependency of
`doctrine/orm`. We saw this earlier when we were updating
`doctrine-migrations-bundle` and we *also* needed to allow its dependency -
`doctrine/migrations` to update.

We allow that by adding `--with-dependencies` to the update command:

```terminal-silent skip-ci
composer update "symfony/*" \
                doctrine/orm \
                knplabs/knp-markdown-bundle \
                knplabs/knp-snappy-bundle \
                liip/imagine-bundle \
                oneup/flysystem-bundle \
                sensio/framework-extra-bundle \
                --with-dependencies
```

## Updating our PHP Version

And... this gets us to our next error. Oh, interesting! Apparently
`nexylan/slack-bundle` version 2.2.1 requires PHP 7.3! We saw a similar error
earlier, which caused us to decide that our production app would *now* need to
at *least* run PHP 7.2. We enforced that by adding a `config.platform.php` setting
in `composer.json` to 7.2.5. This says:

> Yo Composer! Pretend I'm using PHP 7.2.5 and don't let me use any packages
> that require a higher version of PHP.

So... hmm. Apparently the version of `nexylan/slack-bundle` that supports Symfony 5
*requires* PHP 7.3. Basically... unless we want to stop using that bundle, it
means that *we* need to start using PHP 7.3 as well.

Fortunately, I'm already using PHP 7.3 locally: so I just need to change my
`config.platform.php` setting to 7.3 and also makes sure that we have 7.3 on
production.

Inside `composer.json`, search for `platform`: there it is. Use 7.3.0. And,
even though it doesn't affect anything in a project, also change the version
under the `require` key:

[[[ code('9b51c3b29e') ]]]

Ok, *now* try to update:

```terminal-silent skip-ci
composer update "symfony/*" \
                doctrine/orm \
                knplabs/knp-markdown-bundle \
                knplabs/knp-snappy-bundle \
                liip/imagine-bundle \
                oneup/flysystem-bundle \
                sensio/framework-extra-bundle \
                --with-dependencies
```

Bah! I should've seen that coming: it's *still* complaining about
`nexylan/slack-bundle`: it's reminding us that we need to *also* allow that bundle
to update. Add it to our list:

```terminal-silent skip-ci
composer update "symfony/*" \
                doctrine/orm \
                knplabs/knp-markdown-bundle \
                knplabs/knp-snappy-bundle \
                liip/imagine-bundle \
                oneup/flysystem-bundle \
                sensio/framework-extra-bundle \
                nexylan/slack-bundle \
                --with-dependencies
```

And try it. Surprise! Another package needs to be update. I *swear* we're almost
done. Add that to our *gigantic* update command:

***TIP
We need this for our course CI, just ignore this note and follow the tutorial without executing these commands :)
```terminal-silent
sed -i 's/"4.4.*"/"5.0.*"/g' ./composer.json
sed -i 's/public function getExtendedType()/public static function getExtendedTypes(): iterable/g' ./src/Form/TypeExtension/TextareaSizeExtension.php
sed -i 's/TextareaType::class/\[TextareaType::class\]/g' ./src/Form/TypeExtension/TextareaSizeExtension.php
sed -i 's/extends Controller/extends AbstractController/g' ./src/Controller/CommentAdminController.php
sed -i 's/use Symfony\\Bundle\\FrameworkBundle\\Controller\\Controller/use Symfony\\Bundle\\FrameworkBundle\\Controller\\AbstractController/g' ./src/Controller/CommentAdminController.php
sed -i 's/use Doctrine\\Common\\Persistence\\ObjectManager/use Doctrine\\Persistence\\ObjectManager/g' ./src/DataFixtures/ArticleFixtures.php
sed -i 's/use Doctrine\\Common\\Persistence\\ObjectManager/use Doctrine\\Persistence\\ObjectManager/g' ./src/DataFixtures/BaseFixture.php
sed -i 's/use Doctrine\\Common\\Persistence\\ObjectManager/use Doctrine\\Persistence\\ObjectManager/g' ./src/DataFixtures/CommentFixture.php
sed -i 's/use Doctrine\\Common\\Persistence\\ObjectManager/use Doctrine\\Persistence\\ObjectManager/g' ./src/DataFixtures/TagFixture.php
sed -i 's/use Doctrine\\Common\\Persistence\\ObjectManager/use Doctrine\\Persistence\\ObjectManager/g' ./src/DataFixtures/UserFixture.php
```
***

```terminal-silent
composer update "symfony/*" \
                doctrine/orm \
                knplabs/knp-markdown-bundle \
                knplabs/knp-snappy-bundle \
                liip/imagine-bundle \
                oneup/flysystem-bundle \
                sensio/framework-extra-bundle \
                nexylan/slack-bundle \
                knplabs/knp-time-bundle \
                easycorp/easy-log-handler \
                knplabs/knp-paginator-bundle \
                stof/doctrine-extensions-bundle \
                doctrine/doctrine-bundle \
                doctrine/doctrine-fixtures-bundle \
                doctrine/doctrine-migrations-bundle \
                twig/cssinliner-extra \
                --with-dependencies
```

## Other than Symfony: (Mostly) Only Safe Minor Upgrades

And... whaaaat? It's working! It's upgrading a *ton* of packages, including
the Symfony stuff to 5.0.2. *And*, because we didn't change any other version
constraints inside `composer.json`, we know that all of these upgrades are just
*minor* version upgrades at best. For example, `nexylan/slack-bundle` went from
2.1 to 2.2. Even if there *were* a new version 3 of this bundle, we know that it
wouldn't upgrade to it because its version constraint is `^2.1`, which allows
2.1 or higher, but *not* 3:

[[[ code('02e0358a29') ]]]

Well, that's not *completely* true: check out `nexylan/slack`: it went from
version 2.3 to 3: that *is* a major upgrade. That's because this is one of those
*transitive* dependencies: this package isn't in our `composer.json`, it only
lives in our project because `nexylan/slack-bundle` requires it. So unless we're
using its code directly - which *is* possible, but less likely - the major upgrade
won't affect us. If you're worried, check its CHANGELOG.

Ok, so we are now on Symfony 5. Woo! The little
icon on the bottom right of the web debug toolbar shows 5.0.2.

Next, let's celebrate by trying out some new features! We'll start by talking
about Symfony's new "secrets management".
