# Updating security, translation & validator Recipes

The `composer recipes` command tells us that we only have *three* more main Symfony
recipes to update. Let's get to it! The next one is for
`security-bundle`. Update it:

## Updating symfony/securtity-bundle Recipe

```terminal
composer recipes:install symfony/security-bundle --force -v
```

And then run:

```terminal
git add -p
```

Woh! It looks like it made a *lot* of changes! But... like we've learned, what
we're *really* seeing is it *replacing* all of our custom logic with the updated
file from the recipe.

And... we want to keep pretty much *all* of our stuff: our custom encoder, user
provider and firewall config. Let's look closely to see if there's anything
interesting in the *new* code. Oh, there is *one* change: `anonymous: true`
was changed to `anonymous: lazy`.

This is a new feature from Symfony 4.4. It basically means that, instead of
Symfony figuring out *who* you're logged in as at the beginning of each request,
it will do it *lazily*: it will *wait* until the moment your code tries to ask
"who" is logged in. If your code *never* asks, then the authentication logic
*never* runs. This was done to help make HTTP caching easier for pages that
don't need any user info.

So, we *do* want this change. Hit "q" to exit this and... revert the changes
with:

```terminal
git checkout config/packages/security.yaml
```

Now, open that file in your editor, find `anonymous` and change it to `lazy`:

[[[ code('ed7429b6fa') ]]]

Let's keep going:

```terminal
git add -p
```

This time, say "y" to add the change... and "y" again for `symfony.lock`.
Let's commit!

```terminal
git commit -m "upgrading security recipe"
```

Done!

## Upgrading the symfony/translation Recipe

What's next? Let's find out:

```terminal
composer recipes
```

Ah, translation! Update it:

```terminal-silent
composer recipes:install symfony/translation --force -v
```

And walk through the changes:

```terminal
git add -p
```

In `translation.yaml`, all the `%locale%` parameters were replaced with just `en`.
The `locale` parameter is set in our `config/services.yaml` file:

[[[ code('e8555b7f68') ]]]

This was *originally* added by a recipe.

So... what's going on here? *Purely* for simplification, instead of setting that
parameter and then using it in this *one* file, the recipe was updated to remove
the parameter and set the locale directly. You don't need to make this change if
you don't want to.

But I'll say "y" and then "y" again for the `symfony.lock` file. Back in
services.yaml, manually remove the `locale` parameter:

[[[ code('9284fc5f11') ]]]

Why didn't the recipe remove that for me? Well, again, *removing* things - like
old files or even old parameters - is not something the recipe update system
*currently* handles.

Run:

```terminal
git status
```

Then:

```terminal
git add -p
```

And accept this *one* change. Commit!

```terminal
git commit -m "updating translation recipe"
```

## Updating the symfony/validator recipe

We're on a roll!

```terminal
composer recipes
```

Oh, *so* close. Next is the validator recipe. Update it:

```terminal-silent
composer recipes:install symfony/validator --force -v
```

And walk through the changes:

```terminal
git add -p
```

The first change is in `config/packages/validator.yaml`: it adds some new config
that's commented out. This activates a new validation feature called auto-mapping.
It's *really* cool - and we're going to talk about it later. Hit "y" to add
these comments and... yep! This is the `symfony.lock` file. Press "y" again.

That was easy! Let's commit. Actually, I *should* have run `git status`, because
this recipe *also* added a *new* file. We'll see it in a minute:

```terminal
git commit -m "updating validator recipe"
```

## Updating webpack-encore-bundle Recipe

Are we done? Run:

```terminal
composer recipes
```

We *are*! Well, there is *one* more that starts with `symfony/`:
`webpack-encore-bundle`. But that bundle *isn't* part of the main Symfony
repository... so you can update it now or later. If you're interested, let's
update it next. If you're not, skip ahead one chapter to start finding and
fixing deprecations.
