# debug:container & Cache Config

I want to talk more about this key: `markdown.parser.light`:

[[[ code('fd29d95a47') ]]]

We got this from the documentation: it told us that there are five different valid
values that we can put for the `service` key.

But, this is more than just a random config key that the bundle author dreamt up.
Remember: all services live inside an object called the *container*. And each has
an internal name, or id.

It's not *really* important, but it turns out that `markdown.parser.light` is the
id of a service in the container! Yep, with this config, we're telling the bundle
that when we ask for the Markdown parser - like we are in the controller - it should
now pass us the service that has this id.

Go to your terminal and run:

```terminal
./bin/console debug:autowiring
```

And scroll to the top. Check this out! The `MarkdownInterface` is now an alias
to `markdown.parser.light`! *Before* the config change, this was `markdown.parser.max`.
Yep, this literally means that when we use `MarkdownInterface`, Symfony will pass
us a service whose id is `markdown.parser.light`.

Normally, you do *not* need to worry about all of this. I mean, if you just want
to use this bundle and configure a few things, follow its docs, make some config
tweaks, go on a space walk, and then keep going!

## The Many *other* Services in the Container

But we're on a quest to *really* understand how things work! Here's the truth, this
is *not* a full list of all of the services in the container. Nope, not even close.
This time, run:

```terminal
./bin/console debug:container --show-private
```

*This* is *actually* the *full* list of the *many* services in the container. The
service `id` is on the left, and the class for that object is on the right. Don't
worry about the `--show-private` flag: that just makes sure this lists *everything*.

But, in reality, *most* of these services are internal, boring objects that you'll
never use. The most important services show up in `debug:autowiring` and are really
easy to access.

But yea... you *can* also fetch and use *any* of these services, and sometimes you'll
need to. I'll show you how a bit later.

But here are the two big takeaways:

1. There are *many* services in the container and each has an id.
2. The services you'll use 99% of the time show up in `debug:autowiring` and are easy
to access.

## Configuring the Cache Object

Let's play with one more object. Instead of dumping `$markdown`, dump the `$cache`
object:

[[[ code('541c1aafd7') ]]]

Find your page and refresh! Interesting: it's something called a `TraceableAdapter`,
and, inside, a `FilesystemAdapter`!

So I guess our cache is being saved to the filesystem... and we can even see *where*
in `var/cache/dev/pools`.

So... how can we configure the cache service? Of course, the *easiest* answer is
just to Google its docs. But, we don't even need to do that! The cache service
is provided by the FrameworkBundle, which is the one bundle that came automatically
with our app.

## Debugging your Current Config

***TIP
In a recent change to the recipe, the cache config now lives in its own file `config/packages/cache.yaml`
***

Open `framework.yaml` and scroll down:

[[[ code('7b9ecdb12a') ]]]

Hey! This file even comes with documentation about how to configure the cache!
Of course, to get an even *bigger* example, we can run:

```terminal
./bin/console config:dump framework
```

Here's the `cache` section, with some docs about the different keys. Now, try
a slightly *different* command:

```terminal
./bin/console debug:config framework
```

Instead of dumping *example* config, this is our *current* config! Under `cache`,
there are 6 configured keys. But, you won't see all of these in `framework.yaml`:
these are the bundle's default values. And yea! You can see that this `app` key
is set to `cache.adapter.filesystem`.

## Changing to an APCu Cache

The docs in `framework.yaml` tell us that, yep, if we want to change the cache
system, `app` is the key we want. Let's uncomment the last one to set `app` to
use APCu: an in-memory cache that's not as awesome as Redis, but easier to install:

[[[ code('763badc2f7') ]]]

And just like with markdown, `cache.adapter.apcu` is a service that already exists
in the container.

Ok, go back and refresh! Yes! The cache is now using an `APCuAdapter` internally!

***TIP
Fun fact! Running `./bin/console cache:clear` clears Symfony's internal cache that
helps your app run. But, it purposely does *not* clear anything that *you* store
in cache. If you want to clear that, run `./bin/console cache:pool:clear cache.app`.
***

## Bundle Config: the Good & Bad

So the *great* thing about configuring bundles is that you can make powerful changes
with very simple config tweaks. You can also dump your config and Symfony will give
you a *great* error if you have any typos.

The *downside* about configuring bundles is that... you *really* need to rely on
the debug tools and documentation. I mean, there's no way we could sit here long
enough and eventually figure out that the cache system is configured under `framework`,
`cache`, `app`: the config structure is totally invented by the bundle.

Let's go back to our controller and remove that dump:

[[[ code('8590a2b0fe') ]]]

Make sure everything still works. Perfect! If you get an error, make sure to install
the APCu PHP extension.

Next, let's explore Symfony *environments* and *totally* demystify the purpose of
each file in `config/`.
