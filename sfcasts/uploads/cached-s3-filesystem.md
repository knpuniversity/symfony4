# Cached S3 Filesystem For Thumbnails

Check this out: I'm going to turn off my Wifi! Gasp! What do you think will happen?
I mean, other than I'm gonna miss all my Tweets and Instagrams! What will happen
when I refresh? The page will load, but all the images will be broken, right?

In the name of science, I command us to try it!

Woh! An error!?

> Error executing ListObjects on https://sf-casts-spacebar ... Could not
> contact DNS servers.

What? Why is our Symfony app trying to connect to S3?

Here's the deal: on *every* request... for *every* thumbnail image that will be
rendered, our Symfony app makes an API request to S3 to figure out if the image
has already been thumbnailed or if it still needs to be. Specifically, LiipImagineBundle
is doing this.

This bundle has two key concepts: the resolver and the loader. But there are actually
*three* things that happen behind the scenes. First, every single time that we use
`|imagine_filter()`, the resolver takes in that path and has to ask:


> Has this image already been thumbnailed?

And if you think about it, the *only* way for the resolver to figure this out is
by making an API request to S3 to ask:

> Yo S3! Does this thumbnail file already exist?

If it *does* exist, LiipImagineBundle renders a URL that points directly to that
image on S3. If not, it renders a URL to the Symfony route and controller that will
use the loader to download the file and the resolver to save it back to S3.

Phew! The point is: on page load, our app is making one request to S3 *per* thumbnail
file that the page renders. Those network requests are *super* wasteful!

## The Cached Filesystem

What's the solution? Cache it! Go back to OneupFlysystemBundle and find the main
page of their docs. Oh! Apparently I need Wifi for that! There we go. Go back
to their docs homepage and search for "cache". You'll eventually find a link about
"Caching your filesystem".

This is a *super* neat feature of Flysystem where you can say:

> Hey Flysystem! When you check some file metadata, like whether or not a file
> exists, cache that so that we don't need to ask S3 every time!

Actually, it's even more interesting & useful. LiipImagineBundle calls the `exists()`
method on the `Filesystem` object to see if the thumbnail file already exists. If
that returns *false*, the cached filesystem does *not* cache that. But if it returns
true, it *does* cache it. The result is this: the first time LiipImagineBundle asks
if a thumbnail image exists, Flysystem will return false, and Liip will know to
generate it. The *second* time it asks, because the "false" value wasn't cached,
Flysystem *will* still talk to S3, which will *now* say:

> Yea! That file *does* exist.

And because the cached adapter *does* cache this, the *third* time LiipImagineBundle
calls `exists`, Flysystem will immediately return `true` without talking to S3.

To get this rocking, copy the composer require line, find your terminal and
paste to download this "cached" Flysystem adapter.

```terminal-silent
composer require league/flysystem-cached-adapter
```

While we're waiting, go check out the docs. Here's the "gist" of how this works,
it's 3 parts. First, you have some existing filesystem - like `my_filesystem`.
*Second*, via this `cache` key, you register a *new* "cached" adapter and tell
it how you want things to be cached. And third, you tell your existing filesystem
to process its logic through that cached adapter. If that doesn't totally make
sense yet, no worries.

For *how* you want the cached adapter to cache things, there are a *bunch* of
options. We're going to use the one called PSR6. You may or may not already know
that Symfony has a *wonderful* cache system built right into it. Anytime you need
to cache *anything*, you can just use *it*!

## Configuring Symfony's Cache Pool

Start by going to `config/packages/cache.yaml`. *This* is where you can configure
anything related to Symfony's cache system, and we talked a bit about it in our
Symfony Fundamentals course. The `app` key determines how the `cache.app` service
caches things, which is a general-purpose cache service you can use for anything,
including this! *Or*, to be fancier - I like being fancy - you can create a cache
"pool" *based* on this.

Check it out. Uncomment `pools` and create a new cache pool below this called
`cache.flysystem.psr6`. The name can be anything. Below, set `adapter` to `cache.app`.

That's it! This creates a *new* cache service called `cache.flysystem.psr6` that,
really... just uses `cache.app` behind the scenes to cache everything. The *advantage*
is that this new service will automatically use a cache "namespace" so that its
keys won't collide with other keys from other parts of your app that *also* use
`cache.app`.

In your terminal, run:

```terminal
php bin/console debug:container psr6
```

There it is! A new fancy `cache.flysystem.psr6` service.

Back in `oneup_flysystem.yaml`, let's use this! On top... though it doesn't matter
where, add `cache:` and put one new cached adapter below it: `psr6_app_cache`.
The name here *also* doesn't matter - but we'll reference it in a minute.

And below *that* add `psr6:`. That exact *key* is the important part: it tells
the bundle that we're going to pass it a PSR6-style caching object that the adapter
should use internally. Finally, set `service` to what we created in `cache.yaml`:
`cache.flysystem.psr6`.

At this point, we have a new Flysystem *cache* adapter... but nobody is using it.
To fix that, duplicate `uploads_filesystem` and create a second one called
`cached_uploads_filesystem`. Make it use the same adapter as before, but with an
extra key: `cache:` set to the adapter name we used above: `psr6_app_cache`.

Thanks to this, all Filesystem calls will *first* go through the cached adapter.
If something is cached, it will return it immediately. Everything else will get
forwarded to the S3 adapter and work like normal. This is *classic* object
decoration.

After all of this work, we should have one new service in the container. Run:

```terminal
php bin/console debug:container cached_uploads
```

There it is: `oneup_flysystem.cached_uploads_filesystem_filesystem`. *Finally*,
go back to `liip_imagine.yaml`. For the loader, we don't really need caching:
this downloads the source file, which should only happen one time anyways. Let's
leave it.

But for the resolver, we *do* want to cache this. Add the `cached_` to the service
id. The resolver is responsible for checking if the thumbnail file exists - something
we *do* want to cache - *and* for *saving* the cached file. But, "save" operations
are never cached - so it won't affect that.

Let's try this! Refresh the page. Ok, everything seems to work fine. Now, check
your tweets, like some Instagram photos, then turn off your Wifi again. Moment of
truth: do a force refresh to *fully* make sure we're reloading. Awesome! Yea, the
page looks *terrible* - a bunch of things fail. But our server did *not* fail:
we are *no* longer talking to S3 on every request. *Big* win.

Next, let's use a *super* cool feature of S3 - *signed* URLs - to see an alternate
way of allowing users to download private files, which, for large stuff, is more
performant.
