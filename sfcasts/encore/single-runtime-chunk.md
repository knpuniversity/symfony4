# The Single Runtime Chunk

Head back to the homepage and click any of the articles. In an earlier tutorial,
we added this heart icon that, when you click it, makes an AJAX request and increases
the counter. Well, part of this is faked on the backend, but you get the idea.

To make this more clear, let's add a Bootstrap tooltip: when the user hovers over
the heart, we can say something like "Click to like". No problem: open up the
template: `article/show.html.twig`. And I'll remind you that this page has its own
entry: `article_show.js`. Go open that: `assets/js/article_show.js`.

Ok, let's find the anchor tag in the template... there it is... and use multiple
lines for sanity. Now add `title="Click to Like"`.

To make this work, all *we* need to do is copy the `js-like-article` class, go
back to `article_show.js` and add `$('.js-like-article').tooltip()`, which is
a function added by Bootstrap.

Coolio! Let's try it. Refresh and... of course. It doesn't work:

> ...tooltip is not a function

This may or may *not* surprise you. Think about it: at the bottom of the page,
the `app.js` `<script>` tags are loaded first. And, if you remember, inside of
`app.js`, we import `jquery` and then `bootstrap`, which *adds* the `tooltip()`
function to jQuery.

## Are Modules Shared across Entries?

So, it's *reasonable* to think that, inside `article_show.js`, when we import
`jquery`, we will get the *same* jQuery object that's already been modified
by `bootstrap`. And... that's *almost* true. When two different files import
the same module, they *do* get the exact same object in memory.

However, by default, Webpack treats different entrypoints like totally separate
applications. So if we import `jquery` from `app.js` and also from
`get_nice_message.js`, which is part of the same entry, they *will* get the *same*
jQuery object. But when we import `jquery` from `article_show.js`, we get a
*different* object in memory. Each entrypoint has an isolated environment.
It doesn't mean that jQuery is downloaded twice, it just means that we are
given two different instances.

So the fix is simple: `import 'bootstrap'`.

Refresh and... this time, it works.

## enableSingleRuntimeChunk()

Understanding that modules are *not* shared across entries is good to know.
But this *also* relates to a feature I want to talk about: the runtime chunk.

In `webpack.config.js`, at the *very* beginning of the tutorial, we commented out
`enableSingleRuntimeChunk()` and replaced it with `disableSingleRuntimeChunk()`.
*Now*, let's reverse that.

Because we just modified the Webpack config, come back over, press `control + c`
and restart it:

```terminal
yarn watch
```

If you watch closely, you'll see an immediate difference. Every single entry
*now* includes a new file called `runtime.js`, which means that it's a *new* file
that needs to be included as the *first* script tag before *any* entry. Of course,
that's not a detail that *we* need to worry about because, when we refresh and view
the page source, our Twig functions took care of rendering everything.

Ok, so... why? What did this change and why did we care? There are two things.

## Single Runtime Chunk & Caching

First, `runtime.js` contains Webpack's "runtime" code: stuff it needs
to get its job done. By enabling the single runtime chunk you're saying:

> Hey Webpack! Instead of adding this code at the beginning of `app.js` and at
> the beginning of `article_show.js` and all my other entry files, only add
> it once to `runtime.js`

The user *now* has to download an extra file, but all the entry files are a bit
smaller. But, there's *more* to it than that. The `runtime.js` file contains
something called the "manifest", which is a fancy name that Webpack gives to code
that contains some internal ids that Webpack uses to identify different parts of
your code. The *key* this is that those ids often *change* between builds. So,
by isolating that code into `runtime.js`, it means that our *other* JavaScript
files - the ones that contain our big code - will change less often: when
those internal ids change, it will *not* affect their content.

The tl;dr is that the smaller `runtime.js` will change more often, but our bigger
JavaScript files will change less often. That's great for caching.

## Shared Runtime/Modules

The *other* thing that `enableSingleRuntimeChunk()` changes may or may not be a
good thing. Go back to `article_show.js` and comment out  `import 'bootstrap'`.
Now, move over and refresh.

Yea, it *works*! When you enable the single runtime chunk, it has a *side effect*:
modules are shared *across* your entry points: they all work a bit more like one,
single application. That's not necessarily a good or bad thing: just something
to be aware of. I still *do* recommend treating each entry file like its own
independent environment, even if there *is* some sharing.

Next: it's time to talk about async imports! Have some code that's only used in
certain situations? Make your built files smaller by loading it... effectively,
via AJAX.
