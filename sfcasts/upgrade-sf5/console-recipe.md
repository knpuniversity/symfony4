# Recipe Upgrade: symfony/console & bootstrap.php

The first recipe I want to update is `symfony/console`. Let's get some more info
about it:

```terminal
composer recipes symfony/console
```

Just like before, it shows us some links: a link to what the recipe looked like
at the moment we installed it - I'll paste that into my browser - and also a
version of what the recipe looks like right now. Let's open up that one too.

## Recipe Organization & History

We're not going to *always* study the history of a recipe like this, but I need
to show you something. Because of the way that recipes are organized, if you want
see what changed in a recipe since you installed it, it's not *always* as easy
as looking at the history.

This is what the recipe looks like today. Click to look at the commit history.
You *might* think:

> Okay, I'll just go back and see what commits have been made to this recipe
> since my version.

That's a great idea, because sometimes knowing *why* something changed can be a
huge help. But... the installed version of my recipe lives in a
`symfony/console/3.3/` directory. The new one lives in `symfony/console/4.4/`.

Let's look at this *entire* `symfony/console` directory. Oh, and make sure that
you're looking at the master branch - the latest commits. Each recipe is allowed
to have multiple versions. If you installed `symfony/console` version 3.3, Flex
would install the 3.3 recipe. If you installed 4.2, you would get the 4.2 recipe.
What if you installed `symfony/console` 4.1? You would get the 3.3 recipe.
A new directory is created *only* when a recipe needs to be updated to show off a
feature that's only available in a newer version.

So, it's kind of a strange versioning mechanism. When we installed this recipe,
we installed the `3.3` version. Updating it would install the `4.4` version. So
if you want to see the full history... it's tricky: you kind of need to look at
the history of what commits have been made to the `4.4` branch... and also maybe
the history of what's been done to the `4.2` branch.... and also maybe the
commit history for the `3.3` branch.

But, it's not as bad as it sounds - I'm trying to deliver the bad news first.
Most of the time, the *reason* a file was updated in a recipe will be pretty
obvious. And when it's not, with a little digging, we can find the reason.

## Let's update a Recipe

Okay, so how do we *actually* upgrade a recipe to the latest version? You can see
the answer down here: `composer recipes install symfony/console --force -v`.

But, it's not *really* a smart "update" system. That command tells Flex to
completely re-install the `symfony/console` recipe using the latest version. Try
it:

```terminal-silent
composer recipes install symfony/console --force -v
```

Nice! Thanks to the `-v` flag, it tells us what files it worked on. It says:

> created bin/console
> create config/bootstrap.php

Well, really, it *modified* those files... but at least in the version of Flex
I'm using, it always says "created".

Let's see how things look. Run:

```terminal
git status
```

Cool! 3 changed files. Just like with the MonologBundle recipe, we need to add
these changes *carefully*: if we had any custom code in the files, the update
process just *replaced* it. Run:

```terminal
git add -p
```

## The bin/console changes

The first file is `bin/console`... and it's a namespace change from `Debug`
to `ErrorHandler`. This is updating our code to use a new `ErrorHandler`
component - some features of the `Debug` component were moved there. So that's a
good change.

It also... I don't know, added some if statement that prints a warning... it looks
like it's just making sure we don't try to run this from inside a web server. Enter
"y" to add both changes.

## Investigating a Change

Next, woh! It removed a huge block of code from the bottom and tweaked an if
statement further up. These changes are on `config/bootstrap.php`.

This... *looks* like some low-level, edge-case normalization of environment
variables. So *probably* we want this. But let's pretend we don't know: we want
to find out *why* this change was made.

How? By doing some digging! Go back to `symfony/recipes/console`. Start by looking
in the `4.4/` directory - the version we're installing. Find `config/bootstrap.php`.

Wait... see the `config/` directory? What does that little arrow mean? It means
that this is actually a *symbolic link* to another directory: the `4.4/config`
directory is identical to `4.2/config`.

Ok, let's go look there! Head into the `4.2/` directory, then `config/`. Woh!
Another arrow! This time the `bootstrap.php` file is a symlink - pointing to,
wow! A totally different recipe - a `bootstrap.php` file in `framework-bundle`.

The `bootstrap.php` file is *the* most complex file in the recipe system and
it's shared across several recipes. Yep, I'm showing you the ugliest case.

Let's go find that: `symfony/framework-bundle/4.2/config/bootstrap.php`.
*Here* is the file. To find the change, use the "blame" feature. Ok, the block
of code we're looking at - lines 9 through 12 - have two different commits. Let's
look at just one of them:

> Allow correct environment to be loaded when `.env.local.php` exists

And we can even click to see the pull request:
[#647](https://github.com/symfony/recipes/pull/647) if you want *really* dive
into the details. In this case, the change fixes a bug if you use the
`.env.php.local` file.

But, *really*, `config/bootstrap.php` is a low-level file that should almost
*always* be identical in every project. So unless you're doing something super
advanced, you will probably want to accept *all* of these changes.

This big removal of code? That was because in an earlier version of this recipe,
your project may or may *not* have had this `loadEnv()` method on `DotEnv`: it was
added in Symfony 4.2. If your app did *not* have that method, it added a *bunch*
of code to "imitate" its behavior. We don't need that anymore. Thank you recipe
update!

The last change is for the `symfony.lock` file. We don't even need to look at this:
always accept these changes. This marks the recipe as *updated* and sometimes saves
extra debugging info that might be useful later.

That *may* have seemed like a small step. But other than trying to figure out the
*reasons* a file changed, this was a home run! We were able to update two low-level
files, which will help make sure our app continues to work like we expect.

Go ahead and commit these changes. Then let's keep going. Next, we'll update the
biggest and most important recipe: the one for `symfony/framework-bundle`.
