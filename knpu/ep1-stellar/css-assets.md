# Assets: CSS & JavaScript

Even astronauts - who *generally* spend their time staring into the black absyss -
demand a site that is *less* ugly than this! Let's fix that!

If you download the course code from the page that you're watching this video on
right now, inside the zip file, you'll find a `start/` directory. And inside *that*,
you'll see the same `tutorial/` directory that I have here. And inside *that*...
I've created a new `base.html.twig`. Copy that and overwrite our version in `templates/`:

[[[ code('2633464924') ]]]

On a technical level, this is basically the same as before: it has the same blocks:
`title` `stylesheets`, `body` and `javascripts` at the bottom. But now, we have a
nice HTML layout that's styled with Bootstrap. 

If you refresh, it should look better. Woh! No change! Weird! Actually... this
is *more* weird than you might think. Find your terminal and remove the `var/cache/dev`
directory:

```terminal-silent
rm -rf var/cache/dev/*
```

What the heck is this? Internally, Symfony caches things in this directory. And...
you normally don't need to think about this at *all*: Symfony is smart enough during
development to automatically rebuild this cache whenever necessary. So... why am
I *manually* clearing it? Well... because we copied *my* file... and because its
"last modified" date is *older* than our original `base.html.twig`, Twig gets confused
and thinks that the template was *not* updated. Seriously, this is *not* something
to worry about in any other situation.

## Referencing CSS Files

And when we refresh... there it is! Ok, it's still *pretty* ugly. That's because
we're missing some CSS files!

In the `tutorial/` directory, I've also prepped some `css/`, `fonts/` and `images/`.
All of these files need to be accessed by the user's browser, and that means they
must live inside `public/`. Open that directory and paste them there.

By the way, Symfony has an *awesome* tool called [Webpack Encore][webpack_encore]
that helps process, combine, minify and generally do *amazing* things with your CSS
and JS files. We *are* going to talk about Webpack Encore... but in a different
tutorial. For now, let's get things setup with normal, static files.

The two CSS files we want to include are `font-awesome.css` and `styles.css`. And
we don't need to do *anything* complex or special! In `base.html.twig`, find the
`stylesheets` block and add a `link` tag.

But wait, why exactly are we adding the `link` tag *inside* the `stylesheets` block?
Is that important? Well, technically... it doesn't matter: a `link` tag can live
anywhere in `head`. But later, we might want to add additional CSS files on specific
*pages*. By putting the `link` tags inside this block, we'll have more flexibility
to do that. Don't worry: we're going to see an example of this with a JavaScript
file soon.

So... what path should we use? Since `public/` is the document root, it should
just be `/css/font-awesome.css`:

[[[ code('10454bcffb') ]]]

Do the same thing for the other file: `/css/styles.css`:

[[[ code('021f90b1af') ]]]

It's that simple! Refresh! Still not perfect, but *much* better!

## The Not-So-Mystical asset Function

And *now* I'm going to *slightly* complicate things. Go back into PhpStorm's
Preferences, search for "Symfony" and find the "Symfony" plugin. Change the "web"
directory to `public` - it was called `web` in Symfony 3.

This is *not* required, but it will give us more auto-completion when working with
assets. Delete the "font-awesome" path, re-type it, and hit tab to auto-complete:

[[[ code('cc39fcfd80') ]]]

Woh! It wrapped the path in a Twig `asset()` function! Do the same thing below
for `styles.css`:

[[[ code('bcac8db177') ]]]

Here's the deal: whenever you link to a static asset - CSS, JS or images - you
*should* wrap the path in this `asset()` function. But... it's not *really* that
important. In fact, right now, it doesn't *do* anything: it will print the same
path as before. But! In the future, the `asset()` function will give us more flexibility
to *version* our assets or store them on a CDN.

In other words: don't worry about it too much, but *do* remember to use it!

## Installing the asset Component

Actually, the `asset()` function *does* do something immediately - it breaks our
site! Refresh! Ah!

The `asset()` function comes from a part of Symfony that we don't have installed yet.
Fix that by running:

```terminal
composer require asset
```

This installs the `symfony/asset` component. And as soon as Composer is done...
we can refresh, and it works! To prove that the `asset()` function isn't doing anything
magic, you can look at the `link` tag in the HTML source: it's the same boring `/css/styles.css`.

There is one other spot where we need to use `asset()`. In the layout, search for
`img`. Ah, an `img` tag! Remove the `src` and re-type `astronaut-profile`:

[[[ code('64f7697c89') ]]]

Perfect! Refresh and enjoy our new avatar on the user menu. There's a lot of hardcoded
data, but we'll make this dynamic over time.

## Styling the Article Page

The layout is looking great! But the *inside* of the page... yea... that's still
pretty terrible. Back in the `tutorial/` directory, there is *also* an `article.html.twig`
file. Don't copy this entire file - just copy its contents. Close it and open
`show.html.twig`. Paste the new code at the top of the `body` block:

[[[ code('15f947f1bf') ]]]

Check it out in your browser. Yep! It looks cool... but *all* of this info is
hardcoded. I mean, that article name is just static text.

Let's take the dynamic code that we have at the bottom and work it into the new HTML.
For the title, use `{{ title }}`:

[[[ code('f468e82073') ]]]

Below, it prints the number of comments. Replace that with `{{ comments|length }}`:

[[[ code('143f61892e') ]]]

Oh, and at the bottom, there is a comment box and one *actual* comment. Let's find
this and... add a loop! For `comment in comments` on top, and `endfor` at the bottom.
For the actual comment, use `{{ comment }}`:

[[[ code('39391247f8') ]]]

Delete the old code from the bottom... oh, but don't delete the `endblock`:

[[[ code('322192c580') ]]]

Let's try it - refresh! It looks awesome! A bunch of things are still hardcoded,
but this is *much* better.

It's time to make our homepage less ugly and learn about the *second* job of routing:
route *generation* for linking.


[webpack_encore]: https://github.com/symfony/webpack-encore
