# Polyfills & Babel

Babel is pretty amazing. But, it's even doing something *else* behind the scenes
that we haven't realized yet! Back in `admin_article_form.js`, and it doesn't
matter where, but down in `ReferenceList`, I'm going to add
`var stuff = new WeakSet([]);`

`WeakSet` is an object that was introduced in to JavaScript, um, ECMAScript in 2015.
Because the Encore watch script is running, go over and refresh the built file.
Here it is: `var stuff = new WeakSet([]);`.

## New Features & Polyfills

That's not surprising, right? I mean, we're telling Babel that we *only* need to
support *really* new browsers, so there's no need to rewrite this to some old,
compatible code... right? Well, it's more complicated than that. `WeakSet` is not
a new *syntax* that Babel can simply rewrite to an old syntax: it's an entirely
new feature! There are a bunch of these and some are *really* important, like the
`Promise` object and the `fetch()` function for AJAX calls.

To suppose totally new features, you need something called a *polyfill*. A polyfill
is a normal JavaScript library that *adds* a feature if it's missing. For example,
there's a polyfill *just* for `WeakSet`, which you could import if you want to make
sure you can use it safely in *any* browser.

But, keeping track of whether or not you imported a polyfill... and whether or not
you even *need* a polyfill - mauybe the feature is already available in the browsers
you need to support - is a pain! So... Encore pre-configures Babel to do it for us.

So check it out. Go back to `package.json` and change this to support older browsers.
Then, just like before, go to your terminal and manually clear the Babel cache:

```terminal
rm -rf node_modules/.cache/babel-loader/
```

And then restart Encore:

```terminal-silent
yarn watch
```

Ok, let's go back to the browser, refresh the built JavaScript file and search for
`WeakSet` set. It *still* looks *exactly* like our original code. But *now*, just
search for "weak". Woh. These are hard to read, but it's importing something called
`core-js/modules/es.weak-set`.

This `core-js` package is a library *full* of polyfills. Babel *realized* that we're
trying to use `WeakSet` and so it *automatically* added an import statement for
the polyfill! This is *identical* to us *manually* going to the top of the file
and adding `import 'core-js/modules/es.weak-set'`. How cool is that?!

## A Polyfill from the Past!

And... this is *not* the first time Babel has automatically added a polyfill! Open
up `build/app.js`. Back in the editor, the `get_nice_message` module used a String
method called `repeat()`. Whelp, it turns out that `.repeat()` is a *fairly* new
feature!

Search for `repeat` in the built file. There it is: it's importing
`core-js/modules/es.string.repeat`. When I used this function, I wasn't even
*thinking* about whether or not that feature was new and if it was available in all
the browsers we need to support! But because Encore has our back, it wasn't a problem.
That's a powerful idea.

By the way, this is all configured in `webpack.config.js`:
it's this `.configureBabel()` method. Generally-speaking, this is how you can configure
Babel. The `useBuiltIns: 'usage'` and `corejs: 3` are the key parts. Together, these
say: please automatically import the polyfills when you see that I'm *using* then
and I've already installed version 3 of `corejs`. That package was pre-installed
in the original `package.json` we got from the recipe.

Next: let's demystify a feature that we disabled *way* back at the beginning of
this tutorial: the single runtime chunk.
