# Upgrading to DoctrineBundle 2.0

Let's look at the latest list of deprecated code. Hmm... there's a lot of stuff
related to Doctrine. Ok: two tricky are happening in the Doctrine world that
make upgrading to Symfony 5 a *bit* more confusing. First, DoctrineBundle has a
new *major* version - 2.0 - and Doctrine itself is being split into smaller packages.
Both things are great... but there are a lot of "moving pieces" right now.

## DoctrineBundle & Symfony 5 Compatibility

If you search this page for DoctrineBundle, there's one deprecation: some missing
`getMetadataDriverClass()` method in a `DoctrineExtension` class. So far... this
is nothing new: a third-party library is using some deprecated code... which means
that *we* need to upgrade it.

Google for DoctrineBundle and find its GitHub page. If you did some digging, you'd
learn that if you want Symfony 5 support, you need version *2* or higher of this
bundle. There's also a version 1.2 that's being maintained... but it won't work
with Symfony 5.

## Debugging the DoctrineBundle Version

So let's start with our normal, lazy way o upgrading. In your terminal, run:

```terminal
composer update doctrine/doctrine-bundle
```

It *does* upgrade... but only to 1.12.6. So *probably* we need to go into
`composer.json` and update its version constraint. Search for
`doctrine/doctrine-bundle`. Huh... it's actually *not* inside our `composer.json`
file. That means it's a dependency of something else. Let's find out more. Run:

```terminal
composer why doctrine/doctrine-bundle
```

Ok: `doctrine-bundle` is required by both `fixtures-bundle` and `migration-bundle`.
But the *original* reason that it was installed was because of `symfony/orm-pack`,
which allows version 1 *or* 2. Remember: `orm-pack` is a sort of "fake" library
that requires *other* libraries. It was an easy way to install Doctrine and some
other Doctrine-related libraries.

So I want to have a *more* control over `doctrine/doctrine-bundle` so that I can
*force* version 2 to be used, instead of just 1 *or* 2. To do that, we can "unpack"
the pack. Run:

```terminal
composer unpack symfony/orm-pack
```

Very simply, this removes `symfony/orm-pack` from our `composer.json` file and
*instead* adds the libraries that it requires. Change the `doctrine-bundle`
version to *just* `^2.0`.

## Handling Composer Update Problems

Now that we're *forcing* version 2, let's see if it will update! Run:

```terminal
composer update doctrine/doctrine-bundle
```

And... this does *not* work. I should've seen this coming. `doctrine-bundle`
can't be updated to version 2 because our project currently has
`doctrine-fixtures-bundle` 3.2, which requires `doctrine-bundle` 1.6. So apparently
we *also* need to update `doctrine-fixtures-bundle`. Ok! Copy the library name
and say:

```terminal
composer update doctrine/doctrine-bundle doctrine/doctrine-fixtures-bundle
```

We *may* need to change the bundle's version constraints to allow a new major
version... I honestly don't really know. My hope is that a newer 3.something
version will allow `doctrine-bundle` 2.0. But when we check on Composer... bah!
It didn't work! But *this* time because of a *different* library:
`doctrine-migrations-bundle`. That *also* needs to be updated. Ok, copy its name,
and add it to the end of our `composer update` line:

```terminal-silent
composer update doctrine/doctrine-bundle \
				doctrine/doctrine-fixtures-bundle \
                doctrine/doctrine-migrations-bundle
```

We're now allowing `doctrine-bundle`, `doctrine-fixtures-bundle` *and*
`doctrine-migrations-bundle` all to update and... it *still* doesn't work. Sheesh.
Let's see: this time it's because `doctrine-migrations-bundle` requires
`doctrine/migrations` 2.2 and apparently we're locked at a lower version of
that... yea we have 2.1.1.

It's the *same* problem... again. Well, it's *slightly* different. We *could*
add `doctrine/migrations` to the end of our `composer update` command - or even
`doctrine/*` at this point - and try it again. That should work.

*Or* we can add `--with-dependencies`. This says: allow any of these three bundles
to update *and* allow their dependencies to update. `doctrine/migrations` is
*not* in our `composer.json` file: it's a dependency or `doctrine-migrations-bundle`.
Oh, and if you *really* want the "easy" way out, we could have just ran
`composer update` with no arguments to allow *anything* to update. But I prefer to
update as *little* as possible at one time. Try the command:

```terminal-silent
composer update doctrine/doctrine-bundle \
				doctrine/doctrine-fixtures-bundle \
                doctrine/doctrine-migrations-bundle \
                --with-dependencies
```

And... it worked! Then... exploded at the bottom. We'll talk about that in a minute.
If we look back up... it upgraded `doctrine-migrations-bundle` and
`doctrine-fixtures-bundle` both to new *minor* versions. So there *shouldn't* be
any breaking changes in those.

The `doctrine-bundle` upgrade *was* over a major version - from 1 to 2 - so it
shouldn't be a *huge* surprise that it made our code explode: the new version
*does* have some breaking changes.

One other thing I want to point out is that `doctrine-cache-bundle` was removed.
That's no longer needed by doctrine and you shouldn't use it anymore either: use
Symfony's cache.

Next, let's fix our app to work with DoctrineBundle 2.0 *and* update its recipe,
which contain a few important config changes.
