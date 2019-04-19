# Single Runtime Chunk

Coming soon...

Hi back to the homepage and just click any of these articles here. One of the bits of
JavaScript that we uh, added an earlier tutorial and artery factor does this little
heart icon, which makes an Ajax call and increments the heart thing. So a bit of fake
code, but behind the scenes is making an Ajax call man, a little bootstrap tool tips.
When I hover over this it says click to like, so we know that this page template,
this page is `article/show.html.twig`. And I'll remind you that this
page has its own, uh, entrypoint its own `article_show.js`. So let's go
on to `assets/js/article_show.js` and what we can do here is let's find the
anchor tag here. It is a `href` Papa's on the multiple lines, so it's a little bit
readable and had a `title="Сlick to Дike"`, so all we need to do is copy this 
`js-like-article` class here and inside the `article_show.js` right on top.

I'll say `$('.js-like-article').tooltip()`, which is a jQuery plugin that bootstrap
ads easy, right? Well let's try it. Refresh and it doesn't work. 

> ...tooltip is not a function 

This may be surprised you, maybe it didn't surprise you. So if you think
about it at the bottom of our page, the uh, `app.js` `<script>` tags are loaded first.
And if you'll remember instead of `app.js` we import jQuery and then we import,
bootstrap and bootstrap. This adds all of those functions like `tooltip()`, they add
them to the `$` variable. So you might think that because then our articles
show that js has loaded. You might think that an `article_show.js` when you
important jquery, you're getting the jQuery object that has already been modified,
pay, bootstrap. And for the most part that's true. When two different files import
the same module, they get the exact same object and memory.

However, by default Webpack treats different. Entrypoints is two totally separate
applications. So if we import `$` from here or if we import `$` from
`get_nice_message.js` for example, which is it, which is important by this, they'll
get the same jquery variable object. But if when we important `$` from
`article_show.js` since that's a different entrypoint, it gets a completely
isolated environment and for the most part this is a good thing. We want our entry
points to behave like completely isolated environments. It doesn't mean that jquery
is downloaded two times, it just means that they are, we are given two instances of
them. So the fix is simple to `import 'bootstrap'`, however, refresh and yet this time
it works. So the reason I'm showing you this, uh, there's one other reason I'm
showing you this and that is to talk about a feature that we looked at very briefly.
The very beginning of our tutorial, which is there we go. `.disableSingleRuntimeChunk()`
I want you to change is to `.enableSingleRuntimeChunk()` cause we just modified
Webpack come back over and `control + c` and run it. 

```terminal
yarn watch
```

again Yeah.

If you look closely, you're going to immediately see a difference. Every single entry
point is now pointing to eighth new file called `runtime.js`, which basically means
it's a new file that needs to be a new `<script>` tag that needs to be included before
every single file, uh, before every single entrypoint. Of course, that's not
something that we need to worry about because I want to refresh the page and view
source our Twig functions. Take care of rendering this for us. There's two important
things I want you to know about this `runtime.js`. The first thing is it contains
some of WebPacks runtime code. By enabling the single runtime chunk. What you're
doing is you're actually saying, hey bootstrap, instead of adding all of this code at
the beginning of `app.js` and in the beginning of `article_show.js` just include
it once in `runtime.js`

which means you have a, everything's a slightly smaller. The other thing is that
this `runtime.js` which is pretty small, contains a couple of like internal ids
that point to our, our files, which might change more rapidly. So by isolating
things, by moving though a runtime code into `runtime.js`, it actually means that
our other JavaScript files might change less often. And anytime your JavaScript files
change, less often, it means they're better for caching. So the `runtime.js` will
change a little bit more often, but it is smaller. So that's less of a big deal. But
there's one, there's side effect of this, and I'm not sure if it's a good side effect
or a bad side effect. Go back to `article_show.js` and comment out the 
`import 'bootstrap'`. If you go back and refresh the page.

Okay.

It works. So the side effect is that when you have that single runtime chunk, it
means that all the modules are shared across, um, all of your entry points. They work
a little bit more like a single application. It's not necessarily a bad thing. You
just need to be aware of that. In fact, it might be exactly what you want in your
application.