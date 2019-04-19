# Page-Specific JS: Multiple Entries

On the article show page, if you check the console... it's an error!

> $ is undefined

coming from `article_show.js`. This shouldn't be surprising. And not *just* because
I seem to make a lot of mistakes. Open that template and go to the bottom. Ah,
this brings in a `js/article_show.js` file. Go find that: in `public/`, I'll close
`build/` and... there it is.

This contains some traditional JavaScript from a previous tutorial. The problem
is that the global `$` variable doesn't exist anymore. If you look closely on
this page, you'll see that, at the bottom, we include the `app.js` file first
and *then* `article_show.js`. And, of course, the `app.js` file *does* import jQuery.
But as we learned, this does not create a *global* variable and *local* variables
in Webpack don't "leak" beyond the file they're defined in.

So... this file is broken. And that's *fine* because I want to refactor it anyways
to go through Encore so that we can *properly* import the variable on top.

Before we do that, let's organize *one* tiny thing. In `assets/js`, create a
new `components/` directory. Move `get_nice_messages.js` into that... and because
that breaks our build... update the import statement in `app.js` to point here.

## Creating the Second Entry

Ok: I *originally* put this code into a separate file because it's only
needed on the article show page. We *could* copy all of this, put it into
`app.js`... and that would work! But sometimes, instead of having one *big*
JavaScript file, you might want to split page-specific CSS and JavaScript
into their *own* files.

To do that, we'll create a second Webpack "entry". Move `article_show.js` into
`assets/js/`. Next, go into `webpack.config.js` and, up here, call `addEntry()`
*again*. Name it `article_show` and point it at `./assets/js/article_show.js`.

*Now* when we build Webpack, it will *still* load `app.js`, follow all the imports,
and create `app.js` and `app.css` files. But now it will *also* load `article_show.js`,
follow all of *its* imports and output new `article_show.js` and `article_show.css`
files.

Each "entry", or "entry point" is like a standalone application that contains
*everything* it needs.

And now that we have this new `article_show` entry, inside `show.html.twig`,
instead of our *manual* `<script>` tag, use `{{ encore_entry_script_tags() }}`
`article_show`. I don't have a `link` tag anywhere... nope - it's not hiding on
top either. That's ok, because, so far, `article_show.js` isn't importing *any*
CSS. And so, Webpack is smart enough to *not* output an empty `article_show.css`
file. But you *could* still plan ahead if you wanted: `encore_entry_link_tags()`
will print *nothing* if there's no CSS file. So, no harm.

Ok: because we made a change to our `webpack.config.js` file, stop and restart Encore:

```terminal-silent
yarn watch
```

And... cool! The `app` entry caused these three files to be created... thanks
to the split chunks stuff, and `article_show` just made `article_show.js`.

If you find your browser and refresh now... oh, same error... because we
*still* haven't imported that. Back in `article_show.js`, `import $ from 'jquery'`.

Refresh again and... boom! Error is gone. We can click the fancy JavaScript-powered
heart icon.

## Importing CSS

Because we *haven't* imported any CSS yet from `article_show.js`, we already saw
that Webpack was smart enough to not output a CSS file. But! Open up
`_articles.scss`. *Part* of this file is CSS for the article show page... which
doesn't *really* need to be included on *every* page.

Let's copy *all* of this code, remove it, and, at the root of the `css/` directory,
create a new file called `article_show.scss` and... paste!

Both `app.js` and `article_show.js` are *meant* to import *everything* that's
needed for the layout and for the article show page. `app.scss` and
`article_show.scss` are kinda the same thing: they should import all the *CSS*
that's needed for each spot.

At the top of `article_show.scss`, we don't *strictly* need to do this, but let's
`@import 'helper/variables` to drive home the point that this is a
standalone file that *imports* anything it needs.

*Finally*, back in `article_show.js` add `import '../css/article_show.scss'`.

Ok, check your terminal! Suddenly, gasp! Webpack is outputting an `article_show.css`
file! And wow! You can *really* see code splitting in action! That
`vendors~app~article_show.js` probably contains jQuery, because Webpack saw that
it's used by *both* entries and so isolated it into its own file so it could be
re-used.

Anyways, back in `show.html.twig` copy the javascripts block, paste, rename it
to `stylesheets` and then change to `{{ encore_entry_link_tags() }}`.

That should do it! Move over, refresh and... cool! The page still looks good
and the heart still works. If you inspect element on this page, in the `head`,
we have *two* CSS files: `app.css` to power the layout and `article_show.css`
to power this page.

At the bottom, we have *4* JavaScript files to power the two entrypoints. By the
way, WebpackEncoreBundle is smart enough to *not* duplicate the
`vendors~app~article_show.js` script tag just because *both* entries need it.
Smart!

Next: we are *close* to having our whole app in Encore. Let's refactor a *bunch*
more un-Webpack-ified code.
