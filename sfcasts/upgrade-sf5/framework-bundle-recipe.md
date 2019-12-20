# Upgrading the FrameworkBundle Recipe (Part 1)

Run:

```terminal
composer recipes
```

## Updating symfony/flex

Our goal is to update all of the recipes for `symfony/` repositories. The hardest
ones are in the beginning: `symfony/console` and `symfony/framework-bundle`. But
the next on the list is `symfony/flex` itself. Run:

```terminal
composer recipes symfony/flex
```

... because that's an easy to get the update command. Run that:

```terminal
composer recipes:install symfony/flex --force -v
```

Hmm, it looks like it only modified one file: `.env`. Take a look with:

```terminal
git status
```

Yup! Just that one. Check that out:

```terminal
git diff
```

Ok: two changes. The first on is a fix for a typo in a comment. Then... it deleted
a bunch of *my* code. That's... to be expected: this is not a true *update* process:
the `.env` file from the recipe *overrode* mine completely.

So this recipe update was to fix a meaningless typo. That's super minor, but I
guess want that change. Hit "Q" to get out of this mode. Then run:

```terminal
git add -p
```

I will accept the typo change - `y` - but not the rest - `n`. Accept the
`symfony.lock` changes as usual. Run:

```terminal
git status
```

Two changes staged and ready to commit, one change to `.env` unstaged. Let's
commit the staged changes:

```terminal
git commit -m "updating symfony/flex"
```

Cool! Now `git diff` tells us that the only remaining change is the removal of
the stuff we *do* want in `.env`. Revert all of that by running:

```terminal
git checkout .env
```

Woo! Done!

## Upgrading the symfony/framework-bundle Recipe

Let's check our progress:

```terminal
composer recipes
```

Another one done! So let's take a deep breath and move onto the *biggest*, most
important recipe: `symfony/framework-bundle`. Run:

```terminal
composer recipes symfony/framework-bundle
```

Hmm, yea, we're upgrading from version `3.3` of the recipe to `4.4`: that might
be a fairly big upgrade. Copy the `recipes:install` command and run it:

```terminal-silent
composer recipes:install symfony/framework-bundle --force -v
```

As you can see, this modified *several* files. You know the drill: let's start
walking through the changes by running:

```terminal
get add -p
```

## Changes to .env

The first change is inside `.env` - it updated `APP_SECRET`. This recipe has a
special power: each time you install it, it generates a *new* unique value for
`APP_SECRET`, which is used to generate some cryptographic keys. We don't really
need or want to change this value.

## Hunting Down the Reason for a Change

What about the change right below it - for `TRUSTED_PROXIES`? We're not using
that value anyways - you can see that both are just commented-out examples.

But, as a challenge, let's see if we can find *what* this change is all about.
Let's go back to the homepage of the `symfony/recipes` repository and then
navigate to `symfony/framework-bundle/`. We're installing the `4.4` recipe, so
go there.

*Most* of the time, a recipe simply copies files into your project. And so we're
*usually* comparing the contents of a file between two recipes.

B there are a couple of things that a recipe can do, like *modify* your `.env`
or `.gitignore` files. In those cases, you won't see a `.env` or `.gitignore`
file: those changes are described in this `manifest.json` file.

Ah! A symlink - this points to the 4.2 version. I'll take a shortcut and change
the URL to jump to that file.

`manifest.json` is the config file that describes the entire recipe and everything
it does. The `env` part says:

> Hey! I want you to *update* the `.env` file to add `APP_ENV`, `APP_SECRET`
> and these two `TRUSTED` comment lines.

Let's "blame" this file. The `TRUSTED_PROXIES` line was modified about three
months ago. Click that commit and jump to the pull request - 654 - to get the
full details.

Ok: "Trusted proxies on private and local IPs". This links to *another* issue
on the main code repository where someone proposes that private IP address ranges
should be trusted by default.

If you're not familiar with `TRUSTED_PROXIES`, then you probably don't care much
about this and... you might as well just accept the update. If you *do* care,
you'll understand that this PR marks *private* IP ranges as "trusted", which may
or may not be useful for you. The point is: we figured out the reason for this
change.

And you can see this is trusted proxies on
private and local IPS. And this comes from a Symfony thing where it says trust
proxies and the private IP range automatically. So basically these IP ranges that
were added here are IP ranges that are considered local or private IP ranges, so
trusted proxies. If this is a feature you care about, this might make sense to you.
If it's not, you don't care, but basically if you use trusted proxies, this new value
is a better safe value.

Because we *don't* want the `APP_SECRET` change... and I don't really care about
the updated comment line, I'll say "n" to skip this changes.

The next file that's modified is `.gitignore`. Let's talk about this next as
well as some changes in `framework.yaml` and *super* important updates in
the `Kernel` class.
