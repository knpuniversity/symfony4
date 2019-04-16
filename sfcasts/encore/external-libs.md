# Importing External Libraries & Global Variables

We already added the `app` entry files to our base layout: the `<script>` tag and
the `<link>` tag both live here. This means that *any* time we have some CSS or
JavaScript that should be included on every page, we can put it in `app.js`.

Look down at the bottom. Ah... we have a few script tags for external files *and*
some inline JavaScript. Shame on me! Let's refactor *all* of this into our new
Encore-powered system.

The first thing we include is jQuery... which makes sense because we're using it
below. Great! Get rid of it. Not surprisingly... this gives us a nice, big error:

> $ is not defined

## Installing a Library (jQuery)

No worries! One of the most *wondrous* things about modern JavaScript is that
we can install third-party libraries properly. I mean, with a package manager.
Find your terminal and run:

```terminal
yarn add jquery --dev
```

The `--dev` part isn't important. *Technically* we only need these files during
the "build" process... they don't need to be included on production... which
is why the `--dev` makes sense. But in 99% of the cases, it doesn't matter. We'll
talk about production builds and deploying at the end of the tutorial.

And... that was *painless*! We now have jQuery in our app.

## Importing a Third-Party Library

We already know how to import a file that lives in a directory next to us. To
import a *third* party library, we can say `import $ from`, and then the name of
the package: `jquery`.

The critical thing is that there is no `.` or `./` at the start. If the path starts
with a `.`, Webpack knows to look for that file relative to this one. If there is
*no* `.`, it knows to look for it inside the `node_modules/` directory.

Check it out: open `node_modules/` and ... there's it is! A `jquery` directory!
But how does it know exactly *which* file in here to import? I'm *so* glad you asked!
Open jQuery's `package.json` file. Every JavaScript package you install... unless
it's *seriously* ancient, will have a `main` key that tells Webpack *exactly* which
file it should import. *We* just say `import 'jquery'`, but it *really* imports
this specific file.

## Global Variables inside Webpack

Cool! We've imported jQuery in `app.js` and set it to a `$` variable. And because
that `<script>` tag is included *above* our inline code in `base.html.twig`, the
`$` variable should be available down here, right?

Nope! `$` is *still* not defined! Wait, the *second* error is more clear. Yep,
`$` is not defined, coming from our code in `base.html.twig`.

This uncovers a *super* important detail. When you import a file from a 3rd party
library, that file behaves *differently* than if you add a `<script>` tag on
your page that points to the *exact* same file! Yea!

That's because a well-written library will contain code that detects *how* it's
being used and then changes its behavior.

Check it out: hold Command or Ctrl and click to open `jquery.js`. It's not *super*
easy to read, but look at this: if `typeof module.exports === "object"`. That's *key*.
*This* is jQuery detecting if it's being used from within an environment like Webpack.
If it *is*, it *exports* the jQuery object in the same way that we're exporting
a function from the `get_nice_message.js` file.

But if we are *not* in a module-friendly environment like Webpack... specifically,
if jQuery is being loaded via a script tag in our browser, it's not too obvious,
but this code is creating a *global* variable.

So, *if* jQuery is in a script tag, we get a global `$` variable. But if you
*import* it like we're doing here, it does *not* create a global variable. It
*returns* the jQuery object, which is then set on this *local* variable. Also,
all modules... or "files", in Webpack live in "isolation": if you set a variable
in one file, it *won't* be available in any other file, regardless of what
order they're loaded.

That is probably the *biggest* thing to re-learn in Webpack. Global variables are
dead. That's *awesome*. But it *also* changes *everything*.

## Forcing a Global jQuery Variable

The *ultimate* solution is to refactor all of your code from your templates and
un-Webpack-ified JavaScript files *into* Encore. But... if you're upgrading an
*existing* site, phew! You probably have a *ton* of JavaScript that expects there
to be global `$` or `jQuery` variables. Moving *all* of that into Encore *all*
at once... it's, uh... not very realistic.

So, if you *really* want a global variable, you can add one with `global.$ = $`.

That `global` keyword is special to Webpack. Try it now: refresh! It works!

But... don't do this unless you *have* to. I'll remove it and add some comments
to explain that this is useful for legacy code.

Let's *properly* finish this next by refactoring all our code into `app.js`, which
will include installing *two* more libraries and our first jQuery plugin... It
turns out that jQuery plugins are a special beast.
