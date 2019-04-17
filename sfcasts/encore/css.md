# Importing 3rd Party CSS + Image Paths

We're on a mission to refactor all the old `<script>` and `<link>` tags *out* of
our templates. For the base layout, we're half way done! There is only *one*
script tag, which points to the `app` entry. That's *perfect*.

Back on top, we *do* still have multiple link tags, including Bootstrap from a
CDN, FontAwesome, which I apparently just committed into my `public/css` directory,
and some custom CSS in `styles.css`.

First, eliminate Bootstrap! In the *same* way that we can properly install JavaScript
libraries with yarn, we can *also* install CSS libraries! Woo!

In `app.js`, we're already importing a single `app.css` file. We *could* add another
import *right* here for the Bootstrap CSS. Instead, I *prefer* to import just *one*
CSS file per entry. Then, from *within* that CSS file, we can use the standard
`@import` CSS syntax to import other CSS files. To Webpack, these two approaches
are identical.

Now, you *might* be thinking:

> Don't we need to install the bootstrap CSS library?

And... yes! Well, I mean, no! I mean, we already did it! In `node_modules/`, look
for `bootstrap/`. This directory contains JavaScript but it *also* contains the
Bootstrap CSS.

## Importing CSS from node_modules

But... hmm... In JavaScript, we can say `import` then simply the name of the package
and... it just works! But we *can't* repeat that same trick for CSS.

Instead, we'll point directly to the path we want, which, in this case is probably
`dist/css/bootstrap.css`. Here's how: `@import`, `~bootstrap` and the
path: `/dist/css/bootstrap.css`.

The `~` part is special to CSS and Webpack. When you want to reference the
`node_modules/` directory from within a CSS file, you need to start with `~`.
That's *different* than JavaScript where any path that *doesn't* start with `.`
is assumed to live in `node_modules/`. After the `~`, it's just a normal, boring
path.

But yea... that's all we need! Move over and refresh. This looks exactly the same!

## Referencing *just* the Package Name

And... remember how I said that we *can't* simply import CSS by referencing *only*
the package name? That was... kind of a lie. Shorten this to just `~bootstrap`.

Go try it! Refresh and... the same!

*This* works thanks to a little extra feature we added to Encore... which may become
a more standard feature in the future. We already know that when we import a package
by its name in JavaScript, Webpack looks in `package.json`, finds the `main`
key.... there it is and uses *this* to know that it should *finally* import the
`dist/js/bootstrap.js` file.

*Some* libraries *also* include these `style` or `sass` keys. And when they *do*,
you only need to `@import` `~` and the package name. Because we're doing this
from inside a CSS file, it knows to look inside `package.json` for a `style`
key.

This is *just* a shortcut to do the exact same thing we had before.

## Installing & Importing Font Awesome

Bootstrap, check! Let's keep going: the next link tag is for FontAwesome. Get rid
of that and celebrate by deleting the `public/css/font-awesome.css` file *and*
this entire `fonts/` directory. This feels great! We're deleting things that I
never should have committed in the first place.

Next, download FontAwesome with:

```terminal
yarn add font-awesome --dev
```

When it finishes, go back to `node_modules/` and search for `font-awesome/`.
Got it! Nice! It has directories for `css/`, `less/`, `scss/` whatever
format we want. And fortunately, if you look inside `package.json`, it *also*
has a `style` key.

Easy peasy! In `app.css`, add `@import '~font-awesome'`.

Done. Find your browser and refresh. Let's see... down here, yes! *This* is a
FontAwesome icon. It still works!

## Image & Font Handling

But this is *way* cooler than it seems! Internally, the FontAwesome CSS file references
some *font* files that the user's browser needs to download: these files here.
But... these files aren't in our `public` directory... so shouldn't the paths to
these be broken?

Close up `node_modules` and check out the `public/build/` directory. Whoa! Where
did this `fonts/` directory come from? When Webpack sees that a CSS file
*refers* to a font file, it *copies* those fonts into this `fonts/` directory
and *rewrites* the code in the final `app.css` file so that the font paths point
*here*. Yes, it just *handles* it.

It also automatically adds a hash to the filename that's based on the file's
*contents*. So if we ever update the font file, that hash would automatically change
and the CSS would automatically point to it. That's *free* browser cache busting.

## Moving our CSS into Encore

Ok *one* more link tag to go. Remove it! Then, open `css/styles.css`, copy *all*
of this, delete that file, and, in `app.css`, highlight the blue background and paste!

That's a simple step so... it should work, right? Nope! Check out the build failure:

> Module not found: Can't resolve `../images/space-nav.jpg` in our
> `assets/css` directory.

It doesn't show the exact file, but we only have one. Ah, *here's* the problem:
PhpStorm is super angry about it too! This background image references
`../images`, which was *perfect* when the code lived in the `public/css` directory.
But when we moved it, we broke that path!

This is awesome! Instead of us silently not realizing we did this, we get a
*build* error. Amazing! We can't break paths without Webpack *screaming*.

To fix this, let's "cut" the entire `images/` directory and move it into the
`assets/` folder. Yep, it's gone. But Encore doesn't know to re-compile... so
make a small change and save. Build successful!

Go check it out. Refresh! It works! And even *better*, look at the `build/`
folder. We have an `images/` directory with `space-nav.jpg` inside. *Just* like
with fonts, Webpack sees our path, *realizes* that `space-nav.jpg` needs to be
public, and so moves it into the `build/images/` directory and rewrites the
`background-image` code in the final CSS to point here.

The moral is this: all *we* need to do is worry about writing our code correctly:
using the proper relative paths from source CSS file to source image file.
Webpack handles the ugly details.

Now, this *did* break a few `<img>` tags on our site that are referencing some
of these files. Now that they're not in the `public/` directory... they don't
work. We'll handle that soon.

But next, let's get more from our CSS by using Sass.
