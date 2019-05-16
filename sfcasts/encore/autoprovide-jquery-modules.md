# Auto-Provide jQuery for Mischievous Packages

Everything *should* be working... but nope! We've got this

> jQuery is not defined

error... but it's not from *our* code! It's coming from inside of
`autocomplete.jquery.js` - that third party package we installed!

## Poorly-Behaved jQuery Packages

This is the *second* jQuery plugin that we've used. The first was bootstrap...
and that worked brilliantly! Look inside `app.js`:

[[[ code('143e20d752') ]]]

We imported bootstrap and, yea... that was it. Bootstrap is a well-written
jQuery plugin, which means that inside, it *imports* `jquery` - just like
we do - and then modifies it.

But this Algolia `autocomplete.js` plugin? Yea, it's *not* so well-written. Instead
of detecting that we're inside Webpack and *importing* `jQuery`, it just says...
`jQuery`! And expects it to be available as a global variable. *This* is why
jQuery plugins are a special monster: they've been around for *so* long, that they
don't always play nicely in the modern way of doing things.

So... are we stuck? I mean, this 3rd-party package is *literally* written incorrectly!
What can we do?

## autoProvidejQuery()

Well... it's Webpack to the rescue! Open up `webpack.config.js` and find some
commented-out code: `autoProvidejQuery()`. Uncomment that:

[[[ code('c0f424bc82') ]]]

Then, go restart Encore:

```terminal-silent
yarn watch
```

When it finishes, move back over and... refresh! No errors! And if I start typing
in the autocomplete box... it works! What black magic is this?!

The `.autoProvidejQuery()` method... yea... it sorta *is* black magic. Webpack is
already scanning all of our code. When you enable this feature, *each* time it finds
a `jQuery` or `$` variable- *anywhere* in *any* of the code that we use - that is
*uninitialized*, it replaces it with `require('jquery')`. It basically *rewrites*
the broken code to be correct.

## Including CSS from the Algolia JS

While we're here, there's an organizational improvement I want to make. Look inside
`admin_article_form.js`. Hmm, we include both the JavaScript file *and* the CSS
file for Algolia autocomplete:

[[[ code('3df42a9776') ]]]

But if you think about it, this CSS file is *meant* to support the
`algolia-autocomplete.js` file. To say it differently: the CSS file is
a *dependency* of `algolia-autocomplete.js`: if that file was ever used
*without* this CSS file, things wouldn't look right.

Take out the `import` and move it into `algolia-autocomplete.js`. Make sure to
update the path:

[[[ code('2fc00b09af') ]]]

That's nice! If we want to use this autocomplete logic somewhere else, we *only*
need to import the JavaScript file: *it* takes care of importing everything else.
The result is the same, but cleaner.

## Making algolia-autocomplete.js a Proper Module

Well, this file still isn't *as* clean as I want it. We're importing the
`algolia-autocomplete.js` file... but it's not *really* a "module". It doesn't
export some reusable function or class: it just *runs* code. I *really* want to
start thinking of *all* of our JavaScript files - except for the entry files
themselves - as *reusable* components.

Check it out: instead of just "doing" stuff, let's `export` a new function that
can initialize the autocomplete logic. Replace `$(document).ready()` with
`export default function()` with three arguments: the jQuery `$elements` that we
want to attach the autocomplete behavior to, the `dataKey`, which will be used
down here as a way of a defining *where* to get the data from on the Ajax
response, and `displayKey` - another config option used at the bottom, which is
the key on each result that should be displayed in the box:

[[[ code('dc4270e9ce') ]]]

Basically, we're taking out all the specific parts and replacing them with
generic variables.

Now we can say `$elements.each()`:

[[[ code('6ff0898170') ]]]

And for `dataKey`, we can put a bit of logic: `if (dataKey)`, then `data = data[dataKey]`,
and finally just `cb(data)`:

[[[ code('f242cf3677') ]]]

Some of this is specific to exactly how the Autocomplete library itself works - we set
that up in an earlier tutorial. Down at the bottom, set `displayKey` to `displayKey`:

[[[ code('749c903dcb') ]]]

Beautiful! Instead of *doing* something, this file returns a reusable function.
That should feel familiar if you come from the Symfony world: we organize code
by creating files that contain reusable *classes*, instead of files that contain
procedural code that instantly *does* something.

Ok! Back in `admin_article_form.js`, let's
`import autocomplete from './components/algolia-autocomplete'`:

[[[ code('ec5759e32a') ]]]

Oooo. And then, `const $autoComplete = $('.js-user-autocomplete')` - to find
the same element we were using before:

[[[ code('45316e2ab0') ]]]

Then, if *not* `$autoComplete.is(':disabled')`, call `autocomplete()` - because
that's the variable we imported - and pass it `$autoComplete`, `users` for
`dataKey` and `email` for `displayKey`:

[[[ code('603887bffa') ]]]

I love it! By the way, the reason I'm added this `:disabled` logic is that we
originally set up our forms so that the `author` field that we're adding this
autocomplete to is *disabled* on the edit form. So, there's no reason to try to
add the autocomplete stuff in that case.

Ok, refresh... then type `admi`... it works! Double-check that we didn't break
the edit page: go back to `/admin/article`, edit any article and, yea! Looks good!
The field is disabled, but nothing is breaking.

Hey! We have *no* more JavaScript files in our `public/` directory. Woo! *But*,
we *do* still have 2 CSS files. Let's handle those next.
