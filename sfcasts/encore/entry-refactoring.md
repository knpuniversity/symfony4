# Entry Refactoring

Here's our mission: to get rid of *all* the JavaScript and CSS stuff from our
`public/` directory. Our next target is `admin_article_form.js`. This probably
won't come as a *huge* shock, but this is used in the admin section. Go to
`/admin/article`. If you need to log in, use `admin1@spacebar.com`, password
`engage`. Then click to edit any of the articles.

This page JavaScript to handle the Dropzone upload and a few other things. Open
the template: `templates/article_admin/edit.html.twig` and scroll down. Ok: we
have a traditional `<script>` tag for `admin_article_form.js` as well as two
external JavaScript files that we'll handle in a minute.

## The Repeatable Process of Refactoring to an Entry

This is *super* similar to what we just did. First, move `admin_article_form.js`
into `assets/js`. This will be our *third* entry. So, in `webpack.config.js`
copy `addEntry()`, call this one `admin_article_form` and point it to
`admin_article_form.js`. Finally, inside `edit.html.twig`, change this to
use `{{ encore_entry_script_tags('admin_article_form') }}`.

Now, stop and restart Encore:

```terminal
yarn watch
```

Perfect! 3 entries and a lot of good code splitting. But we shouldn't be *too*
surprised that when we refresh, we get our *favorite* JavaScript error:

> $ is not defined

Let's implement phase 2 of refactoring. In `admin_article_form.js`,
`import $ from 'jquery'` and... we're good to go!

## Refactoring the External script Tags

In addition to moving things out of `public/`, I *also* want to remove all of
these external script tags. Actually, there's nothing wrong with including external
scripts - and you can *definitely* argue that including some things - like
jQuery - could be good for performance. If you *do* want to keep a few script
tags for external stuff, check out Webpack's "externals" feature to make it work
nicely.

The reason I don't like them is that, in the *new* way of writing JavaScript,
you never want undefined variables. If we need a `$` variable, we need to import
`$`! But check it out: we're referencing `Dropzone`. Where the heck does that come
from? Answer: it's a global variable created by this Dropzone script tag! The
same is true for `Sortable` further down. I *don't* want to rely on global variables
anymore.

Trash both of these script tags. Then, find your terminal, go to your open
tab and run:

```terminal
yarn add dropzone sortablejs --dev
```

I already looked up those exact package names to make sure they're right.
Next, inside `admin_article_form.js`, these variables will truly be undefined now.
Try it: refresh. A most *excellent* error!

> Dropzone is undefined

It sure is! Fix that with `import Dropzone from 'dropzone'` and also
`import Sortable from 'sortablejs'`.

*Now* it works.

## Importing the CSS

But there's *one* more thing hiding in our edit template: we have a CDN link to
the Dropzone CSS! We don't need that either. Instead, in `admin_article_form.js`,
we can import the CSS from the dropzone package directly. Hold command or
control and click to open dropzone. I'll double-click the `dropzone` directory
to take us there.

Inside `dist`... there it is: `dropzone.css`. *That's* the path we want to import.
How? With `import 'dropzone/dist/dropzone.css'`.

Most of the time, we're lazy and we say `import` then the package name. But it's
totally legal to import the package name */* a specific file path.

As *soon* as we do that, go check out the Encore watch tab. Wow! The code splitting
is getting crazy! Hiding inside there is *one* CSS file:
`vendors~admin_article_form.css`.

Flip back to the edit template and add
`{{ encore_entry_link_tags('admin_article_form') }}`.

Try it! Find your browser and refresh! Ok, it looks like the Dropzone CSS is
still working. I think we're good!

## Including script & link on the New Page

This *same* JavaScript & CSS code is needed on one other page. Go back to
`/admin/article` and click create. Oof, we still have some problems here. I'll
close up `node_modules/` and open `templates/article_admin/new.html.twig`.

Ah, cool. Replace the `admin_article_form.js` script with our helper Twig function.

Under stylesheets, the new page doesn't use Dropzone, so it didn't have that
same link tag here. Add `{{ encore_entry_link_tags('admin_article_form') }}` anyways
so that this page has *all* the JS and CSS it needs.

But this *does* highlight one... let's say... "not ideal" thing. Some of the JavaScript
on the edit page - like the Dropzone & Sortable stuff - isn't needed here... but
it's part of `admin_article_form.js` anyways. And actually, the reverse is true!
That autocomplete stuff? That's needed on the "new" page, but not the edit page.
At the end of the tutorial, we'll talk about async imports, which is one really
nice way to help avoid packaging code all the time that is only needed *some*
of the time.

Anyways, if we refresh now... the page is still *totally* broken! Apparently
this "autocomplete" library we're importing is trying to reference jQuery.
Let's fix that next... which will involve a... sort of "magical" feature of Webpack
and Encore.
