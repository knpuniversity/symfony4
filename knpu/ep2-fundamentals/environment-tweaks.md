# Leveraging the prod Environment

Right now, our app is in the `dev` environment. How can we change it to `prod`?
Just open `.env` and set `APP_ENV` to `prod`!

```ini
# .env

# ...
APP_ENV=prod
# ...
```

Then... refresh!

This page may or may *not* work for you. One *big* difference between the `dev` and
`prod` environments is that in the `prod` environment, the internal Symfony cache
is *not* automatically rebuilt. That's because the `prod` environment is *wired*
for speed.

In practice, this means that whenever you want to switch to the `prod` environment...
like when deploying... you need to run a command:

```terminal
./bin/console cache:clear
```

The `bin/console` file *also* reads the `.env` file, so it knows we're in the `prod`
environment.

And *now* when we refresh, it should *definitely* work. And check it out! There's
no web debug toolbar. And if you go to a fake page, you get a very boring error page.
And yea, you can *totally* customize this: just Google for "Symfony error pages":
it's really easy. The point is, this is *not* a big development exception page anymore.

Click back into our article, and then go find its template: `show.html.twig`. Let's
change the `3 hours ago` to `4 hours ago`:

[[[ code('58b7c762bb') ]]]

Move back to your browser and refresh! Yep! The page did *not* update! That's
the behavior I was talking about.

To make it update, find your terminal and run:

```terminal
./bin/console cache:clear
```

## The dev and prod Cache Directories

Oh, and check out the `var/cache` directory. Each environment has its own cache
directory: `dev` and `prod`. When you run `cache:clear`, it *basically* just clears
the directory and recreates a few files. But there is *another* command:

```terminal
./bin/console cache:warmup
```

This goes a step further and creates *all* of the cache files that Symfony will
*ever* need. By running this command when you deploy, the first requests will be
much faster. Heck, you can even deploy to a read-only filesystem!

And *now* when you refresh... it works: 4 hours ago.

## Changing the Cache in the dev Environment

Change the environment back to `dev`:

```ini
# .env

# ...
APP_ENV=dev
# ...
```

Here's our next challenge. In `config/packages/framework.yaml`, we configured
the cache to use APCu:

[[[ code('8489bdc252') ]]]

What if we *did* want to use this for production, but in the `dev` environment, we
wanted to use the *filesystem* cache instead for simplicity. How could we do that?

We *already* know the answer! We just need to override this key inside the `dev`
environment. Create a new file in `config/packages/dev` called `framework.yaml`...
though technically, this could be called anything. We just need the same keys:
`framework`, `cache`, `app`. Add those, but now set `app` to `cache.adapter.filesystem`,
which was the *original* value:

[[[ code('466aefea88') ]]]

Let's see if it worked! Open `ArticleController` and dump the `$cache` object so
we can see what it looks like:

[[[ code('714a50e97b') ]]]

And, refresh! Yes! It's using the `FilesystemAdapter`! What about the `prod`
environment? In `.env`, change `APP_ENV` back to `prod`:

```ini
# .env

# ...
APP_ENV=prod
# ...
```

But don't forget to clear the cache:

```terminal
./bin/console cache:clear
```

The `warmup` part is optional. Refresh! Yea! In the `prod` environment, the cache
*still* uses APCu.

Change the environment back to `dev`:

```ini
# .env

# ...
APP_ENV=dev
# ...
```

In reality, you won't spend much time in the `prod` environment... it mostly exists
for when you deploy to production.

Let's also remove the `dump()`:

[[[ code('930bf35e8e') ]]]

Oh man, with environments behind us, we can jump into the *heart* of our tutorial..
the thing I have been *waiting* to do: create our *own* services.
