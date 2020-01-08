# Upgrading/Migrating from StofDoctrineExtensions

Let's see how our deprecation todo list is looking: refresh the homepage, open the
profiler and... we still have the `TreeBuilder::root()` deprecation coming from
`stof_doctrine_extension`.

You know the drill: try to upgrade this the *lazy* way: find the package name
and copy it. We're *hoping* a minor upgrade - maybe from 1.3 to 1.4 - will
fix things. Update!

```terminal
composer update stof/doctrine-extensions-bundle
```

And... once again... *nothing* happens. Let's go hunting for answers! Google
for StofDoctrineExtensionsBundle and... find its GitHub page. The *first* question
I have is: what is the latest version? It's, oh: 1.3.0 - that's the version
*we're* using... and it's 2 years old!

This is an example of a bundle that, at least at the time of this recording, does
*not* yet support Symfony 5. So... what do we do? Panic! Ahhhh.

Now that we've accomplished that, I recommend looking at the package's issues and
pull requests. *Hopefully* you will find some conversation about Symfony 5 support
and, *hopefully*, it's something that's coming soon or you can help with.

But... in this case, as much as I like this bundle, you'll find that it's
basically abandoned.

## Hello fork: antishov/doctrine-extensions-bundle

That *does* happen sometimes. After all, most open source maintainers are volunteers.
*However*, that digging into the pull requests would *also* reveal that someone in
the community has done a *really* nice job of forking this library and creating
some new releases.

Copy the library name, Google for it and... let's see... here is its
[GitHub page](https://github.com/antishov/StofDoctrineExtensionsBundle). Click
to view the releases.

Basically, someone forked the library, kept *all* the code and release history,
but started fixing things and creating new releases... including a release that
adds Symfony 5 support. We're saved!

## Changing to antishov/doctrine-extensions-bundle

So let's switch to use this fork. Copy the `stof` package name again, and
remove it:

```terminal
composer remove stof/doctrine-extensions-bundle
```

Composer removes it and then... explodes! That's ok: it *was* removed... but because
our app, *needs* this library... it's temporarily not speaking to us.

Now go back to the homepage of the fork, find the `composer require` line, copy
it, and re-install the library:

```terminal
composer require antishov/doctrine-extensions-bundle
```

This *basically* gives us the same library but at a newer version. The author
*also* created an identical recipe for this package, so even the recipe gets
re-installed nicely.

Commit the files we *know* we want to keep:

```terminal
git add composer.json composer.lock symfony.lock
```

*Now* selectively-choose the changes from the updated recipe by running:

```terminal
git add -p
```

For `bundles.php` - it *looks* like it removed the bundle... but if you hit
"y", it just moved it. A meaningless change. And next, because it re-installed
the recipe, it removed our custom changes. Hit "n" to skip those.

Let's commit!

```terminal
git commit -m "using doctrine extensions bundle fork"
```

And then, revert the changes to the config file:

```terminal
git checkout .
```

So... that update was weird. Let's close some tabs and refresh. *Yas*! The
deprecations jumped from 25 to 16.

We're killing it! The next deprecations are going to uncover that we *also* need
to upgrade DoctrineBundle... from version 1 to 2 - a significant jump.
