# Twig Helpers, entrypoints.json & yarn Scripts

Encore is outputting `app.css` and `app.js` thanks to the `app` entry:

[[[ code('6a1484af3d') ]]]

And we successfully added the `<link>` tag for `app.css` and, down here, the `<script>`
for `app.js`:

[[[ code('fb63ac58c0') ]]]

## The Twig Helper Functions

But when you use Encore with Symfony, you *won't* render script and link tags by
hand. No way! We're going to be *way* lazier, and use some helper functions
from WebpackEncoreBundle. For the stylesheets, use `{{ encore_entry_link_tags() }}`
and pass it `app`, because that's the name of the entry:

[[[ code('7842d57c4f') ]]]

At the bottom, replace the script tag with almost the same thing:
`{{ encore_entry_script_tags('app') }}`:

[[[ code('46af2af729') ]]]

Move over and refresh to try this. Wow! This made absolutely *no* difference!
The `<link>` tag on top looks *exactly* the same. And... if I search for
"script"... yep! That's *identical* to what we had before.

So... why? Or maybe better, *how*? Is it just taking the `app` and turning it
into `build/app.js`? Not quite... it's a *bit* more interesting than that.

In the `public/build/` directory, Encore generates a very special file called
`entrypoints.json`. This is a map from each entry name to the CSS and JS files
that are needed to make it run. If you were listening closely, I just said
*two* strange things. First, we only have one entry right now. But yes, we
*will* eventually have *multiple* entries to power page-specific CSS and JS.
Second, for performance, *eventually* Webpack *may* split a single entry into
*multiple* JavaScript and CSS files and we will need *multiple* script and link
tags. We'll talk more about that later.

The important thing right now is: we have these handy helpers that output the exact
link and script tags we need... even if we need multiple.

## Using --watch

Ok, back to Encore. Because it's a *build* tool, each time you make a change
to anything, you need to rebuild:

```terminal-silent
./node_modules/.bin/encore dev
```

That's lame. So, of course, Webpack *also* has a "watch" mode. Re-run the same
command but with `--watch` on the end:

```terminal-silent
./node_modules/.bin/encore dev --watch
```

Encore boots up, builds and... just chills out and waits for more changes. Let's
test this. In `app.js`, I think we need a few more exclamation points:

[[[ code('b8f66a4a47') ]]]

Save, then check out the terminal. Yea! It already rebuilt! In your browser,
refresh. Boom! Extra exclamation points. If that doesn't work for some reason,
do a force refresh.

## Shortcut "scripts"

But even *that* is too much work. Press `Ctrl`+`C` to stop Encore. Instead, just run:

```terminal
yarn watch
```

That's a shortcut to do the *same* thing. You can even see it in the output:
`encore dev --watch`. But there's *no* magic here. Open up `package.json`.
We got this file from the recipe when we installed the WebpackEncoreBundle via
Composer. See this `scripts` section?

[[[ code('f3816431e9') ]]]

This is a feature of Yarn and npm: you can add "shortcut" commands to make
your life easier. `yarn watch` maps to `encore dev --watch`. Later, we'll
use `yarn build` to generate our assets for *production*.

With all this done, let's get back to the *core* of why Webpack is awesome: being
able to import and require other JavaScript. That's next.
