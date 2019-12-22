# Updating the webpack-encore-bundle Recipe

Our goal was to upgrade all of the recipes for the main Symfony packages. And...
we've done it! Victory! The *last* one that starts with `symfony/` is *not* part
of the main repository... so if the goal is to upgrade to Symfony 5... we don't
really need to do this now. But if our goal is to be an over-achiever and earn
extra credit... well... then we should *crush* this last Symfony recipe update.

Let's do it:

```terminal
composer recipes:install symfony/webpack-encore-bundle --force -v
```

Start checking out the updates with:

```terminal
git add -p
```

Change one: it added `/public/build/` to the `.gitignore` file. We *definitely*
want that... I'm not sure why it was missing. Next is `assets/js/app.js`. There
are a *lot* of changes here... but we don't want *any* of them. The
WebpackEncoreBundle recipe gives you an "example" `app.js` file to start with.
We don't want that example to overwrite our custom code.

The next change is a missing line at the end of the file - that's meaningless -
and then... let's see... this is `config/packages/webpack_encore.yaml`. It didn't
actually *change* anything... it just added a lot more comments. Let's hit "y"
to add it - comments are nice.

Next is `package.json`. The recipe gives us a *starting* `package.json` file.
But *we* want our custom code - so hit "n". Hit "n" again to *also* keep our custom
`browserslist` config at the bottom.

## Updating webpack.config.js

The next file is `symfony.lock` - hit "y" to accept - and the *last* is
`webpack.config.js`. This is *another* file that *we* customize. So we definitely
do *not* want to accept *everything*. But, there *may* be some nice new suggestions.

This first new code looks like it helps out with some edge-case... let's accept
this. But the next overwrites all of our custom entries. Definitely hit "n".

The third change adds a commented-out example of `disableSingleRuntimeChunk()` -
I don't need that - and then... woh! The last "chunk" contains a *bunch* of stuff.
I'll clear the screen and hit "s" to "split" this big change into smaller pieces.

Much better! The first relates to configuring Babel. You *should* now have some
config that looks like this in your `webpack.config.js` but I won't go into the
details *why* right now. Both the old and new code are effectively identical...
but the new version is recommended, so hit "y" to add it.

Next, we don't want to change any of our `sass-loader` stuff... say no to that.
This changes some commented-out example code - might as well say "y". And we
*are* using `autoProvidejQuery()`, so keep that. Finally, we're apparently missing
a new line at the end of the file - that's meaningless, but I'll hit "y".

Phew! Run:

```terminal
git status
```

Oh! And there are *three* new files too!

The first - `app.css` - is an example CSS file that the Encore recipe adds.
We're not using it in our app - so we don't need it. Delete it!

```terminal
git rm assets/css/app.css
```

The next new file - `config/packages/validator.yaml` is one I missed earlier from
the validator recipe. Let's check it out. It's super minor: it *disables* a validator
in the test environment that makes a network request and is a security-related
feature that just *isn't* needed in your tests.

The last new file is in the same directory - `webpack_encore.yaml` which... contains
some commented-out example code. Let's add both of these new files:

```terminal
git add config/packages/test
```

And see how things look:

```terminal
git status
```

Perfect! Commit time!

```terminal
git commit -m "updating webpack encore recipe + missing validator file"
```

We can revert *all* of the changes we don't want with:

```terminal
git checkout .
```

Ah! I think we're done! Check out the recipes:

```terminal
composer recipes
```

*Gorgeous*! All the symfony recipes are now up-to-date. I know that was a lot of
work... but mostly because we were being extra careful and doing our research
to find the *reason* a change was made.

The benefit is *huge*. Not only can we keep upgrading our app forever thanks to
the smart way that Symfony handles new major versions, but by updating our recipes,
we can make sure our app *truly* continues to look & act like all apps. Plus,
we get to find out about new features and this gave us a head-start on fixing
deprecations.

Now that we're using Symfony 4.4 with a set of up-to-date recipes, let's start
finding and fixing the deprecations in our app. That's the *last* step before
going to Symfony 5.
