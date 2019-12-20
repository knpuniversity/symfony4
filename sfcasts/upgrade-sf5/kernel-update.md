# FrameworkBundle Recipe Part 2: The Kernel Class

We're *right* in the middle

Now as you can see, we're not using it as common and not in both cases, so I'm
actually just going to ignore this change and say no. The important thing is we were
able to dig in and find out what was causing that and it actually changes the
`.gitignore` file in ignores a new `config/secrets/prod/prod.decrypt.private.php`
file. We don't have that file yet. It's part of Symfony, the new secrets management
system. We're going to talk about it later, but let's say yes because once we use it,
we're going to want to have that into our get ignore file. The next change is a
`cache.yaml`, a configuration file and this just updates some comments. Great. We don't
need that, but while say yes to get those and then exchange down here, it looks like
they changed some of the example pools. They changed the example, some of the uh,
example configuration, uh, but we've already overwritten this configuration with our
own custom stuff. We so do, we do not want this. I'm going to say no to that change.
The next file is `framework.yaml`. Now I'm not gonna, I'm not going to dig and find
what these changes are manually,

but the default locale goes away because in new versus a Symfony that's, that's only set
in a `translation.yaml` file by default. Now down here, this cookie secure pin cookie,
same site. What this is doing is in new Symfony projects, it is activating a more
secure cookie system called same site.

This is something you probably want for your application, but it's possible that it
could break something. You can read more about this, a feature on Symfony's blog, so
if you're not sure about it, you can reject the change for now, but this is something
that you're probably going to want in your site. So I recommend using it. So I'm
going to say yes to both these changes. Now the next file is just see is removing a
bunch of stuff and it's services.yaml. You could see because it's overriding my file
entirely, it's deleting all of my custom parameters and it's deleting all of my
custom services on the bottom. Now if you kind of look closely, the real change this
is making is it apparently this `public: false` was removed along with all of the
comments describing it. That's because in Symfony four actually services are public
false by default. So basically this line is not needed anymore. Now I can't say yes
to this because this will also kill all my custom code. So I'm actually going to type
Q right here to quit out of the `get add -p`

And instead I'm going to move over and just make that change manually. So, so
`config/services.yaml` and no, actually before I do this, but I'm going to want to do is do a

```terminal
get checkout config/services.yaml
```

that was going to undo all of those changes. Get our custom code back.

Okay.

Nope. There it is. All of our customer goes back and then we will manually take out
the `public: false`. All right, so let's go back in and do I

```terminal
git add -p
```

It's gonna
once again, ask us about a couple of files we already said no to, so I'll say no to
that no,

and this time we can say yes because this is `services.yaml` and this is the one
change that we want. The next file is `public/index.php`. This is once again
changing a use statement to use the `ErrorHandler`. This is good. If we didn't do
this, you would actually have seen a deprecation warnings, so the recipe updates
actually saving us. It's actually upgrading some of our deprecated code. Then the
last major thing that this recipe updates is the `Kernel.php` file, which is
probably not something that you ever look at or, but it's possible that you have
custom code inside of there, so you need to be careful. I'm going to highlight a
couple of the changes in here. Most of them are really simple. For example now
because Symfony PHP 7.11 allows you to have private constants. This makes it called
this makes the constant private, not a big deal, get cache and get logged or were
removed because these are now implemented in the parent class and have the exact same
values so they're just not necessary and also add an `iterable` to register bundles.

Again, if we hadn't done that you would've seen a deprecation warning, so that's just
saving you for some from some deprecations someone say yes to all those changes. I'll
clear my screen next. It added a `getProjectDir()` directory. If you did some digging, we
decided to add this to `composer.json` does removes the current directory of the
project. It was more dependable than the previous version which tried to guess the
project directory based off a `composer.json` file the `configureContainer()` line now
returns a `void` return types that which is super not important and down here there's a
couple of changes, just some parameters specifically there's a parameter called
container auto wiring, strict mode which is now removed and this is because this is a
value.

This is because your project, you do want to use strict mode, but starting even a
Symfony, 4.0 strict mode is the default value. So we don't need this stuff in there
anymore. In line factories is a performance thing. In the down here there's some
slight change to how it loads a directories. It doesn't load them as recursively as
it did before, but that was a feature that no one was using anyways. And then down
here at the routes return `void` and it changes some of the recursive stuff. So I'll
say yes to that and that's it. The next file is `symfony.lock`. So I'll say yes
to that. And if we do it, get status is the only thing is there's also a new
`config/routes/dev/framework.yaml` file. So let's look at that.
`config/routes/dev/framework.yaml` Now, you may or may not know, but one of the
features of Symfony is that it gives you this `/_error` you were out here, which you
can use to preview what your air pages look like.

You're a 4040 error urges, you're 500 error pages, etc. Previously, if you
look open the `twig.yaml` file, this feature came from Twig bundle. Now it lives inside
a Framework bundle. That's why the recipe adds this here cause it. Now when it comes to
framework bundle now temporarily, you're going to realize that we now have a toy that
yam on a frame or Diane while they're both trying to load routes onto the exact same
route prefix. That's true. Next we're going to update the twig bundle recipe, which
is actually going to remove `twig.yaml` because it no longer has this feature, but
we'll leave it for right now. So I'm going to manually say

```terminal
get add config/routes/dev/framework.yaml
```

down here. And then the only two changes that are left are the changes
that we didn't want somebody to get rid of those by saying

```terminal
get checkout .
```

perfect and now we can commit this with something like upgrading symfony/framework-bundle
recipe and phew, we just made it through the hardest, most important recipe to keep
our system UpToDate and focused. Next, let's upgrade. Took bundle and start really
making progress down that list.
