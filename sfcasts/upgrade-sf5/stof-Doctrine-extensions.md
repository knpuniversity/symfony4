# Upgrading/Migrating from StofDoctrineExtensions

Let's see how our deprecation list is looking: refresh the homepage, open the
profiler and... we still have the `TreeBuilder::root()` deprecation coming from
`stof_doctrine_extension`.

You know the drill: try to upgrade this the *layzy* way: find the library name
and copy it. We're *hoping* a minor upgrade - maybe from 1.3 to 1.4 or 1.5 - will
fix things for us. Update!

```terminal
composer update stof/doctrine-extensions-bundle
```

And... once again... *nothing* happens. Let's go hunting for answers! Google
for StofDoctrineExtensionsBundle and... find its GitHub page. The *first* thing
I want to look at is what is the latest version? It's, oh: 1.3.0 - that's the
version *we're* using and it's 2 years old!

This is an example of a bundle that, at least at the time of this recording, does
*not* yet support Symfony 5. So... what do we do? If you look at the issues or
pull requests, of a library in this situation, you will *hopefully* find some
conversation about it - *hopefully* it's something that's coming soon. But in
this case, as much as I like this bundle, it's basically abandoned.

## Hello fork: antishov/doctrine-extensions-bundle

That *does* happen sometimes. After all, most open source maintainers are volunteers.
*However*, if you dig a little bit, you'll find out that someone in the community
has done a *really* nice job of forking this library and creating some new releases.

Copy the library name, Google for it and... let's see... here is its
[GitHub page](https://github.com/antishov/StofDoctrineExtensionsBundle). Click
to view the releases.

Basically, someone forked the library, kept *all* the code and release history,
but started making fixing and creating new releases... including a release that
adds Symfony 5 support.

## Changing to antishov/doctrine-extensions-bundle

Perfect! Let's switch to use this fork. Copy the `stof` package name again, and
remove it:

```terminal
composer remove stof/doctrine-extensions-bundle
```

Composer removes it then... explodes! That's ok: it *was* removed... but because
our app needs this library... it's temporarily not too happy with us. Now go
back to the homepage of the fork, find the `composer require` line, copy it,
and re-install the library:

```terminal
composer require antishov/doctrine-extensions-bundle
```

This *basically* gives us the same library but at a newer version. The author
*also* created an identical recipe for this package - so even the recipe gets
re-installed nicely.

Commit the files we *know* we want to keep:

```terminal
git add composer.json composer.lock symfony.lock
```

*Now* selectively-choose the changes from the update recipe by running:

```terminal
git add -p
```

This is `bundles.php` - it *looks* like it removed the bundle... but if you hit
"y", it just moved it. A meaningless change. And next, because it re-installed
the recipe, it removed our custom changes. Hit "n" to skip those changes.

Let's commit!

```terminal
git commit -m "using doctrine extensions bundle fork"
```

And then, revert the changes to the config file:

```terminal
git checkout .
```

So... that update was weird. Let's close some tabs and fresh. *Yas*! The deprecations
jump from 25 to 16.

Let's keep going! The next deprecations are going to uncover that we *also* need
to upgrade DoctrineBundle from version 1 to 2 - a significant jump.
