# The Magic of Split Chunks

View the HTML source and search for `app.js`. Surprise! We have *multiple* script
tags! Actually, let me go to the inspect - it's a bit prettier. Black magic! We
have *two* script tags - one for `app.js` but *also* one for `vendors~app.js`.
What the heck? Go look at the `public/build/` directory. Yeah, there *is* a `vendors~app.js` file.

I *love* this feature. Check out `webpack.config.js`. One of the optional features
that came pre-enabled is called `splitEntryChunks()`:

[[[ code('e8f128041c') ]]]

Here's how it works. We tell Webpack to read `app.js`, follow *all* the imports,
then eventually create one `app.js` file and one `app.css` file. But internally,
Webpack uses an algorithm that, in this case, determines that it's more efficient if the one
`app.js` file is split into *two*: `app.js` and `vendors~app.js`. And then, yea,
we need *two* script tags on the page.

## The Logic of Splitting

That may sound... odd at first... I mean, isn't part of the point of Webpack to
combine all our JavaScript into a single file so that users can avoid making a ton
of web requests?

Yes... but not always. The `vendors~app.js` file has some Webpack-specific code
on top, but *most* of this file contains the *vendor* libraries that we imported.
Stuff like `bootstrap` & `jquery`.

When Webpack is trying to figure out how to split the `app.js` file, it looks for
code that satisfies several conditions. For example, *if* it can find code from the
`node_modules/` directory *and* that code is bigger than 30kb *and* splitting it
into a new file would result in 3 or fewer final JavaScript files for this entry,
it will split it. That's *exactly* what's happening here. Webpack especially
likes splitting "vendor" code - that's the stuff in `node_modules/` - into its own
file because vendor code tends to *change* less often. That means your user's
browser can cache the `vendors~app.js` file for a longer time... which is cool,
because those files tend to be pretty big. Then, the `app.js` file - which contains
*our* code that probably changes *more* often, is smaller.

The algorithm *also* looks for code re-use. Right now, we only have *one* entry.
But in a little while, we're going to create *multiple* entries to support page-specific
CSS and JavaScript. When we do that, Webpack will automatically start analyzing
which modules are *shared* between those entries and isolate them into their own
files. For example, suppose our `get_nice_message.js` file is imported from *two*
different entries: `app` and `admin`. Without code splitting, that code would be
*duplicated* inside the final built `app.js` *and* `admin.js`. *With* code splitting,
that code *may* be split into its own file. I say "may" because Webpack is smart:
if the code is *tiny*, splitting it into its own file would be *worse* for performance.

## SplitChunksPlugin

*All* of this craziness happens without us even knowing or caring. This feature
comes from a part of Webpack called the SplitChunksPlugin. On top, it explains
the logic it uses to split. But you can configure *all* of this.

Oh, see this big example config? *This* is a small piece of what Webpack's config
*normally* looks like without Encore: your `webpack.config.js` would be a big
config object like this. So, if we wanted to apply some of these changes, how
could we do that in Encore?

The answer lives at the bottom of `webpack.config.js`. At the end, we call
`Encore.getWebpackConfig()`, which *generates* standard Webpack config:

[[[ code('7f2207ee5b') ]]]

If you need to, you can always set this to a variable, *modify* some keys, then
export the final value when you're finished:

```javascript
// webpack.config.js

// ...

const config = Encore.getWebpackConfig();
config.optimization.splitChunks.minSize = 20000;

module.exports = config;
```

But for most things, there's an easier way. In this case, you can say
`.configureSplitChunks()` and pass it a callback function. Encore will pass you
the *default* split chunks configuration and then you can tweak it:

```javascript
// webpack.config.js

// ...

Encore.
    // ...
    .splitEntryChunks()
    .configureSplitChunks(function(splitChunks) {
        splitChunks.minSize = 20000;
    })
    // ...
;

module.exports = Encore.getWebpackConfig();
```

This is a common way to extend things in Encore.

But... Webpack does a pretty great job of splitting things out-of-the-box.
And... if you look at the `entrypoints.json` file, Encore makes sure that this
file stays up-to-date with exactly *which* script and link tags each entry requires.
The Twig helpers are already reading this file and taking care of everything:

[[[ code('bb4daf0c6a') ]]]

Basically, code splitting is free performance.

Oh, and *all* of this applies to CSS too. In a few minutes, after we've made our
CSS a bit fancier, you'll notice that we'll suddenly have multiple `link` tags.

Next, let's do that! Let's take our CSS up a level by removing the extra link
tags from our base layout and putting everything into Encore. To do this, we'll
start importing CSS files from third-party libraries in `node_modules/`.
