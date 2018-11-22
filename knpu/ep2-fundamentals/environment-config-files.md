## Explore! Environments & Config Files

Not unlike our space-traveling users, we are *also* pretty adventurous. Sure, *they*
might be discovering intelligent life on other planets or exploring binary planets
inside the habitable zone. But *we*! We are going to explore the `config/` directory
and learn *all* of its secrets. Seriously, this is *cool* stuff!

## Environment?

We know that Symfony is really just a set of routes and a set of services. And we
*also* know that the files in `config/packages` *configure* those services. But,
who *loads* these files? And what is the importance - if *any* - of these
sub-directories?

Well, put on your exploring pants, because we're going on a journey!

The code that runs our app is like a machine: it shows articles and will eventually
allow people to login, comment and more. The machine always does the same work, but...
it needs some *configuration* in order to do its job. Like, where to write log files
or what the database name and password are.

And there's other config too, like whether to log *all* messages or just errors,
or whether to show a big beautiful exception page - which is great for development -
or something aimed at your end-users. Yep, the *behavior* of your app can change
based on its config.

Symfony has an *awesome* way of handling this called *environments*. It has two
environments out-of-the-box: `dev` and `prod`. In the `dev` environment, Symfony
uses a set of config that's... well... great for development: big errors, log everything
and show me the web debug toolbar. The `prod` environment uses a set of config that's
optimized for speed, only logs errors, and hides technical info on error pages.

## How Environments Work

Ok, I know what you're thinking: this makes sense from a high level... but how does
it *work*? Show me the code! 

Open the `public/` directory and then `index.php`:

[[[ code('cddd3f233a') ]]]

This is the front controller: a fancy word to mean that it is the *first*
file that's executed for *every* page. You don't normally worry about it,
but... it's kind of interesting.

It's looking for an environment variable called `APP_ENV`:

[[[ code('e91795adda') ]]]

***TIP
If you start a new project today, you won't see this `APP_ENV` logic. It's
been moved to a `config/bootstrap.php` file.
***

We're going to talk more about environment variables later, but they're just a way
to store config values. One confusing thing is that environment *variables* are
a *totally* different thing than what we're talking about right now: Symfony environments.

Forget *how* the `$env` variable is set for a moment, and go down to see how it's used:

[[[ code('10d86a2b1e') ]]]

Ah! It's passed into some `Kernel` class! The `APP_ENV` variable is set in
a `.env` file, and right now it's set to `dev`. Again, more on environment *variables*
later.

Anyways, the string `dev` - is being passed into a `Kernel` class. The *question*
is... what does that *do*?

## Debugging the Kernel Class

Well... good news! That `Kernel` class is *not* some core part of Symfony. Nope,
it lives right inside our app! Open `src/Kernel.php`:

[[[ code('211b01be9b') ]]]

After some configuration, there are three methods I want to look at. The first is
`registerBundles()`:

[[[ code('cd98a56794') ]]]

*This* is what loads the `config/bundles.php` file:

[[[ code('39b5c6cf5b') ]]]

And check this out: some of the bundles are only loaded in *specific* environments.
Like, the `WebServerBundle` is only loaded in the `dev` environment:

[[[ code('d185c748af') ]]]

And the DebugBundle is similar. Most are loaded in `all` environments.

The code in `Kernel` handles this: you can pretty easily guess that
`$this->environment` is set to the environment, so, `dev`!

[[[ code('fa9b2ace61') ]]]

The other two important methods are `configureContainer()`... which basically means
"configure services"... and `configureRoutes()`:

[[[ code('54ccd7ce8d') ]]]

Of course! Because - say it with me now:

> Symfony is just a set of services and routes.

Ok, I'll stop jamming that point down your throat.

## Package File Loading

Look at `configureContainer()` first:

[[[ code('e81c77d2e2') ]]]

When Symfony boots, it needs config: it needs to know where to log or how to connect
to the database. To get *all* of the config, it calls this *one* method. You can ignore
these first two lines: they're internal optimizations.

After, it's uses some sort of `$loader` to load configuration files:

[[[ code('5cae8cfc53') ]]]

This `CONFIG_EXTS` constant is just a fancy way to load any PHP, XML or YAML files:

[[[ code('52a383a25f') ]]]

First, it loads any files that live *directly* in `packages/`:

[[[ code('6ef0cc9506') ]]]

But then, it looks to see if there is an environment-specific sub-directory,
like `packages/dev`. And if there *is*, it loads all of *those* files:

[[[ code('3f92768b74') ]]]

Right now, in the `dev` environment, it will load 5 additional files. The *order*
of how this happens is the *key*: any overlapping config in the environment-specific
files *override* those from the main files in `packages/`.

For example, open the main `routing.yaml`. This is not very important, but it sets
some `strict_requirements` flag to `~`... which is `null` in YAML:

[[[ code('aa98bf8874') ]]]

But then in the `dev` environment, that's *overridden*: `strict_requirements` is set
to `true`:

[[[ code('50eaa7f689') ]]]

To prove it, find your terminal and run:

```terminal
./bin/console debug:config framework
```

Since we're in the `dev` environment right now... yep! The `strict_requirements`
value is `true`!

This *also* highlights something we talked about earlier: the *names* of the files
are *not* important... at *all*. This could be called `hal9000.yaml` and not change
a thing. The *important* part is the root key, which tells Symfony which bundle
is being configured.

*Usually*, the filename matches the root key... ya know for sanity. But, it doesn't
have to. The organization of these files is subjective: it's meant to make as much
sense as possible. The `routing.yaml` file *actually* configures something under
the `framework` key.

My *big* point is this: *all* of these files are really part of the same configuration
system and, technically, their contents could be copied into one giant file called
`my_big_old_config_file.yaml`.

Oh and I said earlier that Symfony comes with only two environments: `dev` and
`prod`. Well... I lied: there is also a `test` environment used for automated testing.
And... you can create more!

Go *back* to `Kernel.php`. The *last* file that's loaded is `services.yaml`:

[[[ code('6ead45d8a5') ]]]

More on that file later. It can also have an environment-specific version, like
`services_test.yaml`.

## Route Loading

And the `configureRoutes()` method is pretty much the same: it automatically loads
everything from the `config/routes` directory and then looks for an
environment-specific subdirectory:

[[[ code('4e637fec37') ]]]

So.. yea! All of the files inside `config/` either configure services or configure
routes. No biggie.

But *now*, with our new-found knowledge, let's tweak the cache service to behave
differently in the `dev` environment. And, let's learn how to *change* to the
`prod` environment.
