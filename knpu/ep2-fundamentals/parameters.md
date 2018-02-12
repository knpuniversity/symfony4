# Config Parameters

The *container* is a fancy, scary word for a simple concept: the object that holds
all of the *services* in our app. But actually, the container can *also* hold a *second*
type of thing: normal boring config values! These are called *parameters* and, it
turns out they're pretty handy!

Open `config/packages/framework.yaml`. We configured the cache system to use this
`cache.adapter.apcu` service:

[[[ code('d804b91e93') ]]]

And then, in the `dev` environment only, we're *overriding* that to use
`cache.adapter.filesystem`:

[[[ code('6c72d91a8d') ]]]

## Creating a Parameter

Simple enough! But parameters can make this *even* easier. Check this out: inside
*any* configuration file - because, remember, *all* of these files are loaded by
the same system - you can add a `parameters` key. And below that, you can invent
whatever keys you want. 

Let's invent one called `cache_adapter`. Set its value to `cache.adapter.apcu`:

[[[ code('0cb8c5a28c') ]]]

# Using a Parameter

This basically creates a variable. And now we can *reference* this variable in *any*
of these configuration files. How? Remove `cache.adapter.apcu` and, inside quotes,
replace it with `%cache_adapter%`:

[[[ code('f791432b27') ]]]

Yep, whenever you surround a string with percent signs, Symfony will *replace* this
with that parameter's value.

## Overriding a Parameter

So yea... parameters are basically config *variables*. And, what programmer doesn't
like variables!?

The cool thing is, now that we have a parameter called `cache_adapter`, inside of
the dev config, we can shorten things. Change the key to `parameters` and *override*
`cache_adapter`: `cache.adapter.filesystem`:

[[[ code('f5e228e510') ]]]

Oh, and you may have noticed that *sometimes* I use quotes in YAML and sometimes
I don't. Yay consistency! YAML is *super* friendly... and so most of the time, quotes
aren't needed. But sometimes, like when a value starts with `%` or contains `@`,
you *do* need them. Sheesh! Don't worry too much: if you're not sure, use quotes.
You'll get a clear error anyways when you *do* need them.

Ok, let's see if this works! Open `MarkdownHelper` and `dump($this->cache)`:

[[[ code('bf73062a00') ]]]

In your browser, wave hello to this astronaut. Then, refresh! Yes! It is *still* using
the filesystem adapter, since we're in the `dev` environment.

## Moving Parameters to services.yaml

Now that we know that *any* config file can define parameters... let's *stop* putting
them everywhere! I mean, usually, for organization, we like to *only* define parameters
in one-ish files: `services.yaml`. Let's remove the parameter from the main
`framework.yaml` and add it there:

[[[ code('67eb12277f') ]]]

But... we have a problem. When you refresh *now*, woh! We're suddenly using the
APCU adapter, even though we're in the dev environment! Whaaaat?

Remember the *order* that these files are loaded: files in `config/packages` are
loaded first, then anything in `config/packages/dev`, and *last*, `services.yaml`.
That means that the config in `services.yaml` is *overriding* our `dev` config file!

Boo! How can we fix that? Create a new config file called `services_dev.yaml`. This
is the built-in way to create an environment-specific *services* file. And you can
see that we actually started with one for the `test` environment. Inside, copy the
code from the `dev` `framework.yaml` and paste it here:

[[[ code('3b62f04188') ]]]

Oh, and delete the old `framework.yaml` file. Now, refresh!

Woo! It *works*!

And that's really it! In `framework.yaml`, we just reference the parameter...

[[[ code('367e0f695b') ]]]

Which can be set in *any* other file. Like in this case: we set it in `services.yaml`
and override it in `services_dev.yaml`:

[[[ code('0e3612b57f') ]]]

[[[ code('fc6b569f8a') ]]]

Actually, if you think about it, since `framework.yaml` is loaded *first*, the parameter
isn't even *defined* at this point. But that's ok: you can reference a parameter,
even if it's not set until later. Nice!

## Using a Parameter in a Service

But wait, there's more! We can *also* use parameters inside our *code* - like
in `MarkdownParser`. Suppose that we want to *completely* disable caching when we're
in the `dev` environment.

How can we do that? Add a new argument called `$isDebug`:

[[[ code('f5c8cf926b') ]]]

Yep, in addition to other *services*, if your service has any config - like `isDebug`
or an API key - those should *also* be passed as constructor arguments.

The idea is that we will configure Symfony to pass true or false based on our
environment. I'll press `Alt`+`Enter` and select "Initialize fields" so that PhpStorm
creates and sets that property for me:

[[[ code('da4740d8be') ]]]

Below, we can say: if `$this->isDebug`, then just return the uncached value:

[[[ code('83b2c56a82') ]]]

Notice: this is the *first* time that we've had a constructor argument that is
*not* a service. This is important: Symfony will *not* be able to autowire this value.
Sure, we gave it a `bool` type-hint, but that's not enough for Symfony to guess
what we want. Oh, and reverse my logic - I had it backwards!

[[[ code('4c0dddbe11') ]]]

To see that the argument cannot be autowired, refresh! Yep! A clear message:

> Cannot autowire service `MarkdownHelper`: argument `$isDebug` must have a type-hint
> or be given a value explicitly.

This is the *other* main situation when autowiring does *not* work. But... just
like before, it's no problem! If Symfony can't figure out what value to pass to
an argument, just tell it! In `services.yaml`, we *could* configure the *argument*
for *just* this one service. But that's no fun! Add another global `bind` instead:
`$isDebug` and just hardcode it to `true` for now:

[[[ code('128c0e9da3') ]]]

Ok, move over and... refresh! Yea! It works! And if you check out the caching section
of the profiler... yes! No calls!

## Built-in `kernel.*` Parameters

To set the `$isDebug` argument to the correct value, we *could* create a parameter,
set it to `false` in `services.yaml`, override it in `services_dev.yaml`, and use
it under `bind`.

But don't do it! Symfony *already* has a parameter we can use! In your terminal,
the `debug:container` command *normally* lists services. But if you pass `--parameters`,
well, you can guess what it prints:

```terminal-silent
php bin/console debug:container --parameters
```

Just like with services, most of these are internal values you don't care about.
But, there are several that *are* useful: they start with `kernel.`, like `kernel.debug`.
That parameter is `true` most of the time, but is false in the `prod` environment.

Oh, and `kernel.project_dir` is *also* really handy. Copy `kernel.debug`, move back
to `services.yaml`, and use `%kernel.debug%`:

[[[ code('302f42ce52') ]]]

Try it! Refresh! It still works!

Ok, it's time to talk a *little* bit more about *controllers*. It turns out, *they're*
services too!
