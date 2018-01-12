# The Twig Recipe

Do you remember the *only* rule for a controller? It must return a Symfony Response
object! But Symfony doesn't care *how* you do that: you could render a template,
make API requests or make database queries and build a JSON response.

***TIP
Technically, a controller can return *anything*. Eventually, you'll learn how and
why to do this.
***

Really, *most* of learning Symfony involves learning to install and use a *bunch*
of powerful, but optional, tools that make this work easier. If your app needs to
return HTML, then one of these great tools is called Twig.

## Installing Twig

First, make sure you commit all of your changes so far:

```terminal-silent
git status
```

I already did this. Recipes are *so* much more fun when you can see what they do!
Now run:

```terminal
composer require twig
```

By the way, in future tutorials, our app will become a mixture of a traditional HTML
app and an API with a JavaScript front-end. So if you want to know about building
an API in Symfony, we'll get there!

This installs TwigBundle, a few other libraries and... configures a recipe! What did
that recipe do? Let's find out:

```terminal
git status
```

Woh! Lot's of good stuff! The first change is `config/bundles.php`:

[[[ code('b65bd10885') ]]]

Bundles are the "plugin" system for Symfony. And whenever we install a third-party
bundle, Flex adds it here so that it's used automatically. Thanks Flex!

The recipe also *created* some stuff, like a `templates/` directory! Yep, no need
to guess where templates go: it's pretty obvious! It even added a base layout file
that we'll use soon.

Twig also needs some configuration, so the recipe added it in `config/packages/twig.yaml`:

[[[ code('f7deb4c365') ]]]

But even though this file was added by Flex, it's *yours* to modify: you can make
whatever changes you want.

Oh, and I *love* this! *Why* do our templates need to live in a `templates/` directory.
Is that hardcoded deep inside Symfony? Nope! It's right here!

[[[ code('22bf999239') ]]]

Don't worry about this percent syntax yet - you'll learn about that in a future
episode. But, you can probably guess what's going on: `%kernel.project_dir%` is a
variable that points to the root of the project.

Anyways, looking at what a recipe did is a *great* way to learn! But the main lesson
of Flex is this: install a library and it takes care of the rest.

Now, let's go *use* Twig!
