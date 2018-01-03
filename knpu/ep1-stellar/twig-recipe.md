# The Twig Recipe

Do you remember the *only* rule for a controller? It must return a Symfony Response
object! But Symfony doesn't care *how* you do that: you could render a template,
make API requests or make some queries and build a JSON response.

***TIP
Technically, a controller can return *anything*. Eventually, you'll learn how and
why to do this.
***

In fact, *most* of Symfony involves learning to install and use a *bunch* of powerful,
but optional, tools that make life easier. If your app needs to return HTML, then
one of these great tools is called Twig.

## Installing Twig

First, make sure you commit all of your changes so far: I already have. Recipes
are *much* more fun when you can see what they do! Now run:

```terminal
composer require twig
```

By the way, eventually, in future tutorials, our app will become a mixture of a
traditional HTML app and an API with a JavaScript front-end. So if you want to know
about building an API in Symfony, we'll get there!

Ok! This installs a few libraries and... configures a recipe! What did that recipe
do? Let's find out:

```terminal
git status
```

Woh! Lot's of good stuff! The first change is `config/bundles.php`. Bundles are
the "plugin" system for Symfony. And whenever we install a third-party bundle,
Flex adds that bundle here so that it's used automatically. Thanks Flex!

The recipe also created some stuff, like a `templates/` directory! Yep, no need
to guess where templates go: it's pretty obvious! It even added a base layout file
that we'll use in a few minutes.

Twig also needs some configuration, so the recipe added it to `config/packages/twig.yaml`.
But even though this file was added by Flex, it's *yours* to modify: you can make
whatever changes you want.

Oh, and I *love* this! Why do your templates need to live in a `templates/` directory.
Is that hardcoded deep inside Symfony? Nope! It's right here!

Don't worry about these percent syntaxes yet - you'll learn about them in a future
episode. But, you can probably guess that `%kernel.project_dir%` is some sort of
variable that points to the root of the project.

Looking at what a recipe did is a *great* way to learn! But the main lesson of Flex
is this: install a library and it takes care of the rest.
