# Refactoring Autocomplete JS & CSS

We still have work to do to get the `new.html.twig` template working: we have a
script tag for this external autocomplete library and one for our own
`public/js/algolia-autocomplete.js` file... which is our *last* JavaScript file
in the `public/` directory! Woo!

This holds code that adds auto-completion... on this author box... which, yes,
is *totally* broken.

## Installing the Autocomplete Library

To start, remove the CDN link to this autocomplete library and, at your terminal,
install it properly!

```terminal
yarn add autocomplete.js --dev
```

## Organizing our Autocomplete into a Component

Next, you know the drill, take the `algolia-autocomplete.js` file and move it into
the `assets/js/` directory. But I'm *not* going to make this a new entry point. We
*could* do that, but really, we *already* have an entry file that's included on
this page: `admin_article_form`. So really, `admin_article_form.js` should
*probably* just *use* the code from `algolia-autocomplete.js`.

So, move that file into the *components/* directory... which is kind of meant for
reusable modules. And... well, this isn't really *written* like a re-usable module
yet because it just executes code instead or returning something, like a function.
But, we'll work on that later.

Let's also take the `algolia-autocomplete.css` file and move that all the way up
here into `assets/css/`. And just because we can, I'll make it an SCSS file!

Okay! Back in `admin_article_form.js`, let's bring in this code:
`import './components/algolia-autocomplete'`. We don't need an `import from` yet...
because that file doesn't actually *export* anything. For the CSS:
`import '../css/algolia-autocomplete.scss'`.

Back in `new.html.twig`, the *great* thing is, we don't need to import this CSS
file anymore or any of these script files. This is *really* how we want our
templates to look: a single a call to `{{ encore_entry_script_tags() }}` and a
single call to `{{ encore_entry_link_tags() }}`.

So if we refresh right now, not surprisingly, it *still* won't work! And it's our
*favorite* error!

> $ is undefined

from `algolia-autocomplete.js`. Yes, this *is* the error I see when I close my
eyes at night.

## Using the autocomplete.js Library

Let's get to work. Of course, *we* are referencing `$`. So,
`import $ from 'jquery'`. We're *also* using the autocomplete library in here.
No problem: `import autocomplete from 'autocomplete.js'`.

Wait... that's not quite right. This `autocomplete.js` library is a standalone
JavaScript library that can be used with anything - jQuery, React, whatever.
But... our *existing* code isn't using the "standalone" version of the library.
It's using a jQuery plugin - this `.autocomplete()` function - that comes
with that package.

So, we *could* refactor our code down here to use the, kind of, *official* way of
using this library - independent of jQuery. But... that's the *easy* way out! Let's
see if we can get this to work as a jQuery plugin.

## Finding and Using the jQuery Plugin

I'll hold command or control and click into `autocomplete.js`. Then double-click
the directory to *zoom* us there. The "main" file is this `index.js` at the root
of the directory. But if you look in `dist/`, hey! `autocomplete.jquery.js`!
That's what we were including *before* via the `<script>` tag!

So instead of importing the main file, let's import
`autocomplete.js/dist/autocomplete.jquery`.

And remember, we don't use import *from* with jQuery plugins... because they don't
return anything: they *modify* the jQuery object.

Ok, I think we're *great* and I think we're ready. Move over, refresh and... huh:

> jQuery is not defined

Notice it doesn't say "$ is not defined": it says "jQuery is not defined"... and
it's coming from `autocomplete.jquery.js`! It's coming from the *third* party
package!

This... is tricky. Plain and simple, that file is written *incorrectly*. Yea,
it *only* works if jQuery is a *global* variable! And in Webpack... it's not!
Let's talk more about this *and* fix it with some black magic, next.
