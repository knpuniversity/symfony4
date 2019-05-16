# Async Imports

Head back to `/admin/article`. We have a... sort of... "performance" issue here.
When you create a new article, we have an author field that uses a bunch of
autocomplete JavaScript and CSS. The thing is, if you go back and edit an article,
this is purposely *not* used here.

So, what's the problem? Open `admin_article_form.js`. We import
`algolia-autocomplete`:

[[[ code('0aab5d825d') ]]]

And it imports a third-party library and some CSS:

[[[ code('26f1c66490') ]]]

So, it's not a *tiny* amount of code to get this working. The `admin_article_form.js`
entry file is included on both the new *and* edit pages. But really, a big chunk
of that file is *totally* unused on the edit page. What a waste!

## Conditionally Dependencies?

The problem is that you can't conditionally import things: you can't put an if
statement around the import, because Webpack needs to know, at build time, whether
or not it should include the content of that import into the final built
`admin_article_form.js` file.

But, this *is* a real-world problem! For example, suppose that when a user clicks
a specific link on your site, a dialog screen pops up that requires a lot of
JavaScript and CSS. Cool. But what if *most* users *don't* ever click that link?
Making *all* your users download the dialog box JavaScript and CSS when only a
*few* of them will ever need it is a waste! You're slowing down *everyone's* experience.

We need to be able to lazily load dependencies. And here's how.

## Hello Async/Dynamic import()

Copy the file path then delete the import:

[[[ code('50d4d3d80b') ]]]

All imports are *normally* at the top of the file. But now... down inside
the if statement, *this* is when we know that we need to use that library.
Use `import()` like a *function* and pass it the path that we want to import.

This works almost exactly like an AJAX call. It's not instant, so it returns a
*Promise*. Add `.then()` and, for the callback, Webpack will pass us the module
that we're importing: `autocomplete`:

[[[ code('b4ddc26e42') ]]]

Finish the arrow function, then move the old code inside:

[[[ code('ca3fcd86ba') ]]]

So, it will hit our `import` code, download the JavaScript - just like an AJAX
call - and when it finishes, call our function. *And*, because the "traditional"
import call is gone from the top of the file, the autocomplete stuff *won't* be
included in `admin_article_form.js`. That entry file just got smaller.
That's freakin' awesome!

By the way, if we were running the code, like, after a user *clicked*
something, there would be a small delay while the JavaScript was being downloaded.
To make the experience fluid, you could add a loading animation before the `import()`
call and stop it inside the callback.

Ok, let's try this! Go back to `/admin/article/new`. And... oh!

> autocomplete is not a function

## Using module_name.default

in `article_form.js`. So... this is a little bit of a gotcha. If your module uses
the newer, trendier, `export default` syntax:

[[[ code('752589da9b') ]]]

When you use "async" or "dynamic" imports, you need to say `autocomplete.default()`
in the callback.

Move back over and refresh again. No errors! And it works! But also, look at the
Network tab - filter for "scripts". It downloaded `1.js` and `0.js`. The `1.js`
file contains the *autocomplete* vendor library and `0.js` contains *our* JavaScript.
It loaded this lazily *and* it's even "code splitting" our lazy JavaScript into
two files... which is kinda crazy. The `0.js` *also* contains the CSS... well,
it *says* it does... but it's not really there. *Because*, in the CSS tab, it's
loaded via its own `0.css` file.

If you look at the DOM, you can even see how Webpack hacked the `script` and `link`
tags into the `head` of our page: these were *not* there on page-load.

So... dynamic imports... just work! And you can imagine how powerful this could be
in a single page application where you can asynchronously load the components for
a page when the user *goes* to that page... instead of having one *gigantic*
JavaScript file for your whole site.

By the way, the dynamic import syntax can be even simpler if you use the `await`
keyword and some fancy destructuring. You'll also need to install a library
called `regenerator-runtime`. Check out the code on this page for an example.

```javascript
// and run: yarn add regenerator-runtime --dev

async function initializeAutocomplete($autoComplete) {
    const { default: autocomplete } = await import('./components/algolia-autocomplete');

    autocomplete($autoComplete, 'users', 'email');
}

$(document).ready(function() {
    const $autoComplete = $('.js-user-autocomplete');
    if (!$autoComplete.is(':disabled')) {
        initializeAutocomplete($autoComplete);
    }

    // ...
}
```

Next: there's just one more thing to talk about: how to build our assets for production,
and some tips on deployment.
