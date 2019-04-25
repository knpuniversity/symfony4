# browserslist: What Browsers do you need to Support?

PostCSS is running! Let's see what it does! Go back to your browser. We haven't
reloaded the page yet. I'll search for `app.css` and click to open that. Search
for one of the vendor prefixes: `-webkit`. Ok, so *before* adding PostCSS, we have
77 occurrences - coming from our code and Bootstrap.

In *theory*, if we told PostCSS that we need to support *really* old browsers, this
number should get *way* higher! How can we do that? Some config in `postcss.config.js`?
Actually, no. It's *way* cooler than that.

## Hello browserslist

In the JavaScript world, there is a *wonderful* library called `browserslist`. It's
a pretty simple idea: `browserslist` allows you to *describe* which browsers your
site needs to support, in a *bunch* of useful ways. Then, *any* tool that needs
this information can read it from a central spot.

Check it out: open up your `package.json` file. Yes, *this* is where we'll configure
what browsers we need to support. Add a new key: `browserslist` set to an array.
You can do a *ton* of things in here - like say that you want to support the last
"2" versions of every browser *or* any browser that is used by more than 1% of the
web *or* some *specific* browser that you know is used a lot on your site. Yea,
`browserslist` uses *real-world* usage data to figure out *which* browsers you
should support!

Let's use a simple example: `> .05%`.

This is actually a pretty *unrealistic* setting. This says: I want to support all
browsers that have *at least* .05% of the global browser usage. So this will include
some *really* old browsers that, maybe only .06% of the world uses!

Stop and restart Webpack to force a rebuild and make sure PostCSS reads the new
setting:

```terminal-silent
yarn watch
```

Now, go back, refresh `app.css`, search again for `-webkit` and woh! 992 results!
That's amazing! By the way, there is *also* a tool called
[BrowserList-GA](https://github.com/browserslist/browserslist-ga) that reads from
your Google Analytics account and *dumps* a data file with *your* real-world usage
data. You can then use that in your `browserslist` config, by saying something like:
`> 0.5% in my stats`, which literally means: support any browsers that is responsible
for more than .5% of traffic from *my* site's real-world data. Cool.

## Configuring Babel

So what about our JavaScript? Does Babel read this same `browserslist` config?
Totally! Search for `.js` and click to open the compiled `admin_article_form.js`
file. Inside, search for `$autocomplete`. Yep! We saw earlier that Babel is outputting
`var $autoComplete`, even though this was *originally* `const $autoComplete`.
That makes sense: we said that we want to support *really* old browsers.

So... what if we change the `browserslist` config to `> 5%`? That's probably still
a *bit* unrealistic: this will only support the *most* popular browsers and versions:
pretty much *no* old stuff. Stop and re-run Encore:

```terminal-silent
yarn watch
```

Then move back over to `admin_article_form.js` and refresh. I'll do a force
refresh to be sure... then search for `$autoComplete`. And... huh? It's *still*
var? Hmm, that *might* be right... but `const` was added in 2015 - it *should*
be fully supported by all modern browsers by now.

It turns out... it *is*, and we're not seeing the changes due to a small bug in Babel.
Behind the scenes, Babel uses some smart caching so that it doesn't need to reparse
and recompile *every* JavaScript file *every* time Webpack builds. But, at the time
of recording, Babel's cache *isn't* smart enough to know that it needs invalidate
itself when the `browserslist` config changes.

Once you know this, it's no big deal: *anytime* you change the `browserslist`
config, you need to manually clear Babel's cache. In my terminal, I'll run:

```terminal
rm -rf node_modules/.cache/babel-loader/
```

*Now* restart Encore:

```terminal-silent
yarn watch
```

Let's check it out! Refresh and search for `$autoComplete`. There it is:
`const $autoComplete`. Look also for `class ReferenceList`. Now that we're only
supporting new browsers, that code doesn't need to be rewritten either.

Oh, but there *is* one type of thing that Babel can't simply rewrite into code
that's compatible with olders browsers. When you use a totally new *feature* of
JavaScript - like the `fetch()` function for AJAX calls, you need to include a
*polyfill* library so that old browsers have this. But... even for this, Babel
has a trick up its sleeve. That's next.
