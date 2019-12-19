# Selectively Committing Recipe Updates

We just... sort of... *accidentally* updated the MonologBundle recipe by removing
that package and reinstalling it. Doing that modified *several* files.

Let's add the changes we *know* we want to keep to git:

```terminal
git add composer.json composer.lock symfony.lock
```

For the *other* changes - the ones the recipes made - we need to be *very* careful.
Why? Because recipes don't really "upgrade" in some clean way. Nope, when we
removed the packages, some config files we *deleted*... which means any custom
code we had in those was *removed*. When we reinstalled the packages, the recipes
re-added these files... but any custom code we had is *gone*.

So, we *do* want to "accept" any new, cool changes to these files that a newer
version of the recipe may have added. But we do *not* want our custom code to
disappear.

## Changeset Swiss Army Knife: git add -p

My favorite way to sort all of this out is to run:

```terminal
git add -p
```

This interactive command looks at *every* change one-by-one and asks whether or
not you want to add it. For `bundles.php`... this isn't a file that we usually
add custom code to - so it should be safe. It *looks* like it's *removing*
MonologBundle, but it actually just *moved* this line. Hit `y` to add this change.
And... yep! Here is the line being added-back. Hit `y` again. That change was
meaningless.

The next change is in `config/packages/dev/monolog.yaml`: it wants to remove
a `markdown_logging` handler. Hey! No! This is *our* custom code. Say `n` to *not*
add this change.

## Updated Recipes Show New Features

Finally, in the production `monolog.yaml` file, it changed `excluded_404s` to
`excluded_http_codes`. This is *awesome*. The `excluded_http_codes` - which is
basically a way to help you log errors... but not things like 404 errors - is
a relatively new feature that didn't exist when we originally installed MonologBundle.
The updated recipes is telling us about a feature that we *may* not know about it!

Should we accept this change? It's up to you. Do you like this new way of filtering
logs? I do: because I don't like having 405 errors in my logs: that's when someone,
for example, makes a GET request to a URL that only allows POST requests. Sometimes
a bot will do that. Let's hit "y" to add this change.

And... it's done! Run:

```terminal
git status
```

to see what it did. Cool. All the changes we *do* want are up in the "staged"
area and ready for commit. The one change that is *not* staged - down here in
red - was the one change that we did *not* want to commit.

## Removing Unwanted Changes

To *undo* that change - so it goes back to the way it was before - run:

```terminal
git checkout config/packages/dev/monolog.yaml
```

Now you can safely commit these changes however you want, like:

```terminal
git commit -m "moving packages into require-dev"
```

I'll let you make that commit.

Congrats! You just got your first experience *upgrading* a recipe. Was it
*necessary*? Not really. The newer version of MonologBundle would have worked
*fine* if we had kept our existing config files *exactly* like they were. But
it *did* teach us about a new feature... which was kind of awesome.

Next: let's start updating our recipes for *real*. We'll learn about some new
commands that Flex adds to composer to help with this.
