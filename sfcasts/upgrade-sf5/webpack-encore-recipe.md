# Updating the webpack-encore-bundle Recipe

Our goal was to upgrade all of the recipes for the main Symfony packages. And...
we've done it! The *last* one that start with `symfony/` is *not* part of the
main repository... so if the goal is to upgrade to Symfony 5... we don't really
need to do this now. But since we're here, let's just get it done!

Update the package:

```terminal
composer recipes:install symfony/webpack-encore-bundle --force -v
```

And run:

```terminal
git add -p
```

Change one: it added `/public/build/` to the `.gitignore` file. We *definitely*
want that - I'm not sure why it was missing. Next is `assets/js/app.js`. There
are a *lot* of changes here... but we don't want *any* of them. The
WebpackEncoreBundle recipe gives you an "example" `app.js` file to start with.
We don't want that example to overwrite our custom code.

The next change is a missing line at the end of the file - that's meaningless -
and the... let's see... this is `config/packages/webpack_encore.yaml`. It didn't
actually *change* anything... it just added a lot more comments. You can add
or skip this one - I'll hit "y" to add it.

Next is `package.json`. The recipe gives us a *starting* `package.json` file.
But *we* want our custom code - so hit "n". Hit "n" again to *keep* our custom
`browserslist` config at the bottom.

The next file is `symfony.lock` - hit "y" to accept - and the *last* file is
`webpack.config.js`. This is *another* file that *we* customize. So we definitely
do *not* want to accept *everything*. But, there *may* be some nice new suggestions.

This first change looks like it helps out with some edge-case - let's accept
this. But the next overwrites all of our custom entries - definitely hit "n".

The next change adds a commented-out example of `disableSingleRuntimeChunk()` -
I don't need that - and then... woh! The last "chunk" contains a *bunch* of stuff.
I'll clear the screen and hit "s" to "split" this big change into smaller pieces.

Much better! The first change is relating to configuring Babel. You *should*
now have some config that looks like this, but I won't go into the details *why*
right now. Both the old and new code are effectively identical - but the new
version is recommended - so hit "y" to add this.

Next, we don't want to change any of our `sass-loader` stuff... say no to that.
This changes some commented-out example code - might as well say "y". And we
*are* using `autoProvidejQuery()` in this app, so keep that. Finally, we're
apparently missing a new line at the end of the file - that's meaningless, but
I'll hit "y".

Phew! Run:

```terminal
git status
```

Oh! And there are *three* new files too!

The first - `app.css` - is an example CSS file that the Encore recipe added.
We're not using it in our app - so we don't need it. Delete it!

```terminal
git rm assets/css/app.css
```

The next new file - `config/package/validator.yaml` is one I missed earlier from
the validator recipe. Open it up. It's super minor: it *disables* a validator
in the test environment that makes a *network* request.

The last new file is in the same directory - `webpack_encore.yaml` which... contains
some example code. Let's add both of these new files:

```terminal
git add config/packages/test
```

And see how things look:

```terminal
git status
```

Perfect! Let's commit:

```terminal
git commit -m "updating webpack encore recipe + missing validator file"
```

We can revert *all* of the change we don't want with:

```terminal
git checkout .
```

Ah! I think we're done! Check out the recipes:

```terminal
composer recipes
```

*Beautiful*! All the symfony recipes are now up-to-date. I know that was a lot of
work... but mostly because we were being extra careful and doing our research
to look into the *reason* a change was made.

The benefit is *huge*. Not only can you keep upgrading your forever app thanks to
the smart way that Symfony deprecates features, but by updating your recipes,
you can make sure your app *truly* continues to look & act like all apps. Plus,
you get to find out about new features.

Now that we're using Symfony 4.4 and have fully up-to-date recipes, let's start
finding and fixing the deprecations in our app. That's the *last* step before
going to Symfony 5.
