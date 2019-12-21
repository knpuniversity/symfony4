# Updating the TwigBundle Recipe

The updated `framework-bundle` recipe gave us this new routing file:
`config/routes/dev/framework.yaml`, which loads a `/_error/{statusCode}` route
where we can test what our *production* error pages look like.

This feature *used* to live in TwigBundle... which is why `twig.yaml` has
basically the exact same import. This is a minor problem. In your terminal, run:

```terminal
php bin/console debug:router
```

and scroll up to the top. Yep! We have *two* routes for the *exact* same URL.
The first one - which *by chance* is the one from FrameworkBundle - would win,
but we *still* don't want the old one sitting there. Plus, it's deprecated and
will disappear in Symfony 5.

We need to delete this `twig.yaml` file. But... we *probably* also need to update
the TwigBundle recipe... which will *probably* delete it for us. Run:

```terminal
composer recipes
```

Yep! The recipe for `symfony/twig-bundle` has an update.

## Updating symfony/twig-bundle Recipe

Get some info about it:

```terminal
composer recipes symfony/twig-bundle
```

... then copy the `recipes:install` command and run it:

```terminal-silent
composer recipes:install symfony/twig-bundle --force -v
```

Perfect! It looks like it modified three files. Let's start walking through them:

```terminal
git add -p
```

The first change is inside the `twig.yaml` config file. If you ignore the stuff
that it's removing - that's all *our* custom code - it looks like the updated recipe
*added* a line: `exception_controller: null`.

Ok, so we definitely want to keep our custom changes... and we *probably* want to
keep this new line... except that we don't really know *why* it was added.

## Checking CHANGELOGs

Let's go do some digging! But this time, instead of checking the recipe commit
history, let's try something different. Because this is a config change for
TwigBundle, let's go see if they mention this in a CHANGELOG.

Google for "GitHub TwigBundle" to find its GitHub page. Scroll down and... yea!
It has a `CHANGELOG.md` file.

Open it up and look at the 4.4.0 changes. Actually, this `exception_controller`
change *could* even be from an earlier version - but we'll start here. And... yea,
it *does* talk about it:

> deprecated twig.exception_controller configuration option, set it to "null"
> and use `framework.error_controller` configuration instead.

## The deprecated twig exception_controller Option

This is *another* feature that was deprecated inside TwigBundle and *moved*
to FrameworkBundle. The exception, or "error", controller is the controller that's
responsible for rendering an error page.

To *disable* - basically "stop using" the deprecated *old* code - we need to
set `exception_controller` to `null`. *That* is why the recipe added this change.
This *is* a good change. Of course, if your config file already has
an `exception_controller` option... because you're using a *custom*
exception controller, you'll need to *move* that value to
`framework.error_controller` and do some reading to see if your controller code
needs any other updates.

So we *do* want this change... but we can't accept this patch without killing
our custom code. Copy the new config, hit "q" to quit this mode, and then... let's
see - undo those changes by running:

```terminal
git checkout config/packages/twig.yaml
```

Oh, and I guess I should spell "checkout" right.

Now, spin back over, open that file - `config/packages/twig.yaml` - and add
`exception_controller: null`.

Nice! Let's... keep going: start the `git add -p` system again:

```terminal-silent
git add -p
```

This time we *do* want to accept the change to `twig.yaml` - "y" - and the
next change is inside `symfony.lock`. Accept that too.

## base.html.twig and the new test/twig.yaml

The *last* updated file is `templates/base.html.twig` and we definitely do *not*
want to accept this change and kill our custom layout. Looking at the new code...
I don't see anything super important that we might want to add. In fact, if you
checked the recipe history, there haven't been *any* updates to this file in years.
Hit "n" to ignore this.

Run:

```terminal
git status
```

to see how things look. Oh! A new file: `config/packages/test/twig.yaml` - a
config file that's *only* loaded in the `test` environment. Before we see what's
inside it, let's revert the changes we don't want:

```terminal
git checkout templates/base.html.twig
```

Go open the new file: `config/packages/test/twig.yaml`. Ah, super minor:
it sets `strict_variables` to `true` for our tests. This settings tells Twig
to throw an exception if we try to use an undefined variable in a template. If
we ever did that, we probably *would* want Twig to explode in our tests so we
know about it. That's a good change. Add that file:

```terminal
git add config/packages/test/twig.yaml
```

## Manually Deleting config/routes/dev/twig.yaml

We're done! But... wait a second. We *expected* that the updated recipe would delete
the extra `config/routes/dev/twig.yaml` file... but it didn't. Hmm... is it *still*
in the recipe for some reason? Run:

```terminal
composer recipes symfony/twig-bundle
```

Copy the URL to the recipe... and paste it in your browser. Huh. No - there is
*no* `config/routes` directory at *all* in here. The file *is* gone! Why wasn't
it deleted?

This is a shortcoming of the recipe update system: it's not smart enough. In a
perfect world, it would realize that there *used to be* a
`config/routes/dev/twig.yaml` file in the *old* version of the recipe... and
since it is *not* there in the new version, it would delete it. But, that does
*not* happen, at least not yet.

So, we need to delete it manually. This doesn't happen very often, but it
*is* something you should be aware of.

Back at the terminal, run:

```terminal
git status
```

one more time - things look good - and let's commit:

```terminal
git commit -m "updating symfony/twig-bundle"
```

Nice! Now run:

```terminal
composer recipes
```

We're getting close! Let's do any easy one next: let's upgrade `symfony/mailer`
*and* `symfony/sendgrid-mailer`.
