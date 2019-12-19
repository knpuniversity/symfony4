# Upgrading Recipes: New Commands!

Fun fact time! When you start a brand new Symfony project, behind the scenes, what
you're *actually* doing is cloning from this repository: `symfony/skeleton`. Yep,
your app *literally* starts as a single `composer.json` file. But *seconds* later,
a new Symfony app will be filled with the directory structure and about 15 files.
15 other files.

## All Config Files Come from a Recipe

*All* of those things are added by different *recipes*. So even the most "core"
files - for example, `public/index.php`, the file that our web browser executes,
is added by a recipe! We pretty much *never* need to look inside here or do anything,
even though it's *critical* to our app working.

Another example is `config/bootstrap.php`: the boring, low-level file that
initializes and normalizes environment variables. It's important that all Symfony
projects have the same version of this file. If they didn't, some apps might work
different than others, even if they use the same version of Symfony. That also means
you might get weird behavior even if you follow the documentation perfectly.

All of the configuration files were *also* originally added by recipes. For example,
`cache.yaml` comes from - surprise! - the recipe for `symfony/cache`.

## Why Recipes Update

Over time, the recipes *themselves* tend to change. If we installed the
`symfony/cache` component today, it *might* give us a slightly *different*
`cache.yaml` file.

There are three reasons that a recipe might change. First, someone might update
a recipe *just* because they want to add more examples or add some more documentation
comments to a config file. Those changes aren't super important.

Second, a recipe might update when someone add a config key in a configuration file
to activate a new feature that's probably from a new version of that library. These
changes aren't *critical* to know about... but it *is* nice to know if a new feature
is available. We saw that a few minutes ago when the updated MonologBundle told
us about a cool new option for filtering logs by status code.

The third reason a recipe might update is because something needs to be fixed,
or we decide that we want to change some behavior. These changes *are* important.

Let me give you an example: during the first year after Symfony 4.0, several small
but important tweaks were made to the  `bootstrap.php` file to make sure that the
environment variables have *just* the right behavior. If you started your project
on Symfony 4.0 and never "updated" the `bootstrap.php` file, your app will be
handling environment variables in a different way than other apps. That's... not
great: we want our `bootstrap.php` file to look *exactly* like it should.

## New Recipe Commands!

A few minutes ago, when we did all the composer updating stuff, one of the packages
that we upgraded was `symfony/flex` itself: we upgraded it to `1.6.0`. Well guess
what?! Starting in Flex `1.6.0`, there are some brand new fancy, amazing, incredible
commands inside Composer to help us inspect & upgrade our recipes. It still takes
a little bit of work and care, but the process is now very possible. A big thanks to
community member and friend [maxhelias](https://github.com/maxhelias) who really
helped get this done.

Let's go check this out! Move over to your terminal and run:

```terminal
composer recipes
```

Cool! This lists *every* recipe that we have installed and whether or not there is
an update available. Heck, it will even show you if a package that's installed
*has* a recipe that you're missing - maybe because it was added later.

Because my project was originally created on Symfony 4.0, it's fairly old and a
*lot* of recipes have updates. The recipe system is *also* relatively new, so I
think there were more updates during the first 2 years of that system than there
will be in the *next* two years. We've got some work to do. Of course, we could
just *ignore* these recipe updates... but we're risking our app not working quite
right or missing info about new features.

## Inspecting the twig/extension Recipe

Let's look at one of these more closely. How about `twig/extensions`. This is not
a particularly important library, but it's a nice one to start with. Run:

```terminal
composer recipes twig/extensions
```

to see more details. Interesting: it has a link to the installed version of the
recipe. Let's go check that out in the browser. Paste and... this is what the
recipe looked like the *moment* we installed it. We can also go grab the URL to
see what the *latest* version of the recipe looks like.

Check out the commit history. The version of the recipe *we* have installed has
a commit hash starting with `c986`. Back on the history, hey! That commit is
right here! So this recipe *is* out of date, but the *only* change that's been
made is this *one* commit. Inside it... search for `twig/extensions` to find
its changes. Ha! It's completely superficial: we changed from using tilde (`~`)
to `null`, but just for clarity: those are equivalent in YAML.

Yep! The update to `twig/extension` is not important *at all*. We *could* still
update it - and I'll show you how next. But, I'm going to skip it for now. Because...
this is a tutorial about upgrading Symfony! So I want to focus on upgrading the
recipes for everything that starts with `symfony/`.

Let's start that process next by focusing on, surprisingly, one of the *most*
important recipes: `symfony/console`.
