# Cached S3 Filesystem

Coming soon...

All of our images and files of an S3. Right?

MMM,

that's no problem. Um, but check this out. I'm actually going to turn my Wifi. No,
what's going to happen when I do this? I mean, when I refresh the page, obviously
like all of these links to S3 are not going to work anymore, but actually
building the page should work. So it might be surprising that one refresh it fails in
exception has been thrown air executing list objects in SF cast space where s3
Amazon,

blah, blah, blah.

What's happening here, and it has some details about the thumbnails is it may not
have been obvious, but on every single request, our site is currently making an API
request to S3 in fact, maybe multiple S3 requests. And the reason for this
is the imagined bundle. So you remember this, this thing called the resolver and this
thing called loader. There's actually three things that happen behind the scenes.
First, every single time, um, that, for example, we use the `|imagine_filter()` filter.

Okay?

The resolver takes in that path and it has to ask, has this already been filmed?
Nailed, yes or no?

Okay.

Now if you think about it, the only way for the resolver to know that is actually to
make an API request to S3 to say, Hey, do you already, does this thumbnail file
already exist? Yes or no? Because if it does exist,

okay

then it won't do anything else. And a little just return that you were out to S3
what should get it? If it doesn't exist, then it needs to use the loader to download
the file and then the resolve or we'll actually cache it. So right now there is one
request per thumbnail image just to see if it lives in the cache and that's super
wasteful. Having those uh, network requests every time. So we're going to do is cache
that. So going back to the OneupFlysystemBundle and I'm gonna go back to their
kind of main part of the documentation, I should probably also turn my wife, I back
up.

Okay. There we go. And

that's actually a bad way to do it. Let me go back to their home page. There we go
back to their home page. And if you searched for cache on their homepage, you'll find
a link eventually called caching your filesystems. This is a really cool feature of
fly system where you can actually have some filesystems where you say, hey, when you
read something, uh, cache it, I don't want you to read it again. And this is
basically what we want to do for our, for Liip. We want Liip to check one time to see
whether or not that thumbnail exists, but once it exists, it's not going away. It
doesn't need to do that check every single time. So I'm gonna copy the composer
require line. This is going to give us a new cached adapter from fly system

and I would run that. 

```terminal-silent
composer require league/flysystem-cached-adapter
```

Then while we're waiting, let's go look at the documentation.
So there's a couple things going on here, but basically what you do is you can take
an existing filesystem, you can register a cache called my cache, and then basically
tell your filesystem to use that cache. If that doesn't make sense, that's fine. It
actually has lots of different cache options. We're going to use the one called PSR6
 You may or may not realize that Symfony has a wonderful cast system built into
it. So anytime you need cache to anything, you can just reuse Symfonys cache system.
That's exactly what we're going to do here. So start by going to `config/packages/cache.yaml`
So this is the configuration for a Symfonys cache system. We talked about
it in our Symfony series. This app cacheier is basically represents a service that
you can use a for everything, but you can also optionally create additional pools.
There are common, like almost a little like namespaces. So check this out. We can
create a new pool. Call `cache.flysystem.psr6:` the name of this is not
important at all. This is just kind of the, the name I'm giving this filesystem. And
then I'm going to say adapter `cache.app`, this basically says `cache.app` is
actually the main caching service in Symfony.

MMM.

And this key here is how you configure out how it actually caches, where we're doing
some fancy stuff with the cache adapters. So we can have cache on and off in the Dev
and prod environment. We've talked about that in a previous episode, but what the end
result of this is actually creates a new service, um, that uses cache gap behind the
filesystem, but it has its own namespace. So own from flipped. So check this out. We
can run 

```terminal
php bin/console debug:container psr6
```

And there you go. There's our new
`cache.flysystem.psr6`. That's it. Now we can use that next in one up
flights. It's dumb. It doesn't matter where, but I'll put it on top. We can not
create a `cache:` key where we start red, uh, registering these cache adapters so long
to create a new one called `psr6_app_cache:`

that he doesn't matter at all. By the way, psr6 is the standard for
cacheing interfaces. Then we'll say `psr6:`. This key is important. That's what
tells, um, the bundle to basically use that new, um, cache adaptive that we just
installed. And here just gonna tell it the ID

of our service. So service `cache.flysystem.psr6`. So just by doing this,
we've created a kind of a a cache filesystem, but nobody's using it. Yes to actually
use it. I'm going to duplicate our `upload_filesystem:` and create a second call
`cached_uploads_filesystem`. It's gonna use the same adapter. We're still gonna upload S3
but this time we can add an additional `cache:` onto it and then go grab our `psr6_app_cache`
from up here. You paste it down there. So now it's still going to read and write
from the same spot, but it's going to catch anything that it gets and you can control
the lifetime through here. We're going to keep the lifetime permanent cause we never
want it to be done. So the, so thanks to this, we now have a new service in our
container.

We can see it if we search for `cached_uploads`. There we go. The 
`oneup_flysystem.cached_uploads_filesystem_filesystem`. And so finally in 
LiipImagineBundle for the loader. We still want to use the original filesystem. 
We don't want to do any caching. It doesn't really matter. This does the writing 
of the file, but for the reading of the

right,

I shouldn't say it for the first one. That one doesn't happen very often. There's no
reason to cache it. That's all we want. But for this one down here, we do want to use
the cache system, sort of going to put any little `cached_` at the
beginning of that. This, the resolver is also responsible for writing, but there's
never any cache and that happens on writing.

Yeah,

and that's it. So just a lot of little layers that you need to hook up together. I'm
to refresh the page now and everything seems to work just fine and check us out. It's
actually turn off our Wifi. Refresh the page.

Wow.

Everything's still working. Do a forest refresh to make sure there we go and look at
loads just fine. Of course, all of the CSS and JavaScript and images are missing, but
it proves that our page is not making those background requests.