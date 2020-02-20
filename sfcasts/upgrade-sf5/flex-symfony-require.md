# Flex, Versioning & extra.symfony.require

Hi friends! Today we get to explore the, strange, mysterious, shiny world of
Symfony 5. Duh, duh, duh!

Well... first we'll cover how to *upgrade* to Symfony 5 - which is its
own fancy process - and *then* we'll chat about some of my favorite features.

## Symfony 5: So What's New?

As far as upgrading goes, Symfony 5 doesn't make any *huge* changes: there isn't
a totally new directory structure or some earth-shattering new paradigm like
Symfony Flex and the recipe system. That's really because Symfony is in a great
place right now.

So if you were looking forward to countless hours of work and *huge* changes to
your app in order to upgrade it. Well...  you're going to be disappointed.
But if you're hoping for a smooth update process - and to learn about what's
changed since Symfony 4.0 was released - welcome! After we're done, we should have
some time left over to go eat cake.

## The Release Cycle

But the fact that nothing *crazy* changed does *not* mean that nothing has been
happening. Really... phew! The last 2 years since Symfony 4 was released have
been *huge*... with the introduction of the Messenger component, Mailer and many,
*many* other things.

Symfony releases a new "minor" version every 6 months months: 4.0 in November
2017, 4.1 in May 2018, 4.2 in November 2018, 4.3 in May 2019 and 4.4 in
November 2019. It's the most *boring* release cycle ever... and I *love* it!
symfony.com even [has a roadmap page](https://symfony.com/releases) where you
can check the timing of any past or future versions. Each of these minor versions
comes packed with new features.

Will there be a Symfony 4.5? Nope! The `.4` - like `3.4` or `4.4` - is always the
last one. In fact, on the *same* day that a `.4` minor is released, Symfony *also*
releases the next *major* version. Yep, Symfony 4.4 and 5.0 were released on the
same day. The *reason* deals with how upgrades work in Symfony. But more on that
later.

## Project Setup

So let's get to work! To `composer update` your upgrading skills - sorry... I
couldn't help it - you should *definitely* download the course code from this
page and code along with me. When you unzip the file, you'll find a `start/`
directory that holds the same code you see here.

This is a Symfony 4.3 project... but the app *originally* started on 4.0. So it
has a decent amount of old... "stuff" that we'll need to upgrade. Open the
`README.md` file for all the setup instructions. The last step will be to find a
terminal, move into the project and use the
[Symfony binary](https://symfony.com/download) to start a local web server:

```terminal
symfony serve
```

That starts the server at `https://127.0.0.1:8000`. Find your browser and
go there. Say hello to an application that will be *very* familiar to many of you:
The SpaceBar: an alien news site that we've been working on since Symfony 4 was
released two years ago.

## What Does Upgrading Mean?

So, I have... kind of a silly question: what does it *mean* to upgrade Symfony?
Because Symfony isn't just one big library: it's a *huge* number of smaller components.

Go to the project and open `composer.json`. Our app has grown pretty big:
it has a lot of dependencies... and about half of these start with `symfony/`:

[[[ code('16699717a7') ]]]

When we talk about upgrading Symfony, we're *really* talking about upgrading
all of the libraries that start with `symfony/`. Well, not *all* of the libraries:
a few packages - like `symfony/webpack-encore-bundle` - are *not* part of the main
Symfony code and follow their own versioning strategy:

[[[ code('0f008395b2') ]]]

You can upgrade those whenever you want.

But the *vast* majority of the `symfony/` packages are part of the main Symfony
library and we *usually* upgrade them all at the same time. You don't *have* to,
but it keeps life simpler.

## Removing symfony/lts

Before we begin, if you started your project on Symfony 4.0, then inside of your
`composer.json` file, you *might* have a package called `symfony/lts`. If you do,
remove it with `composer remove symfony/lts`. I already removed it from this app.

`symfony/lts` was a, sort of "fake" package that helped you keep all of your many
`symfony` packages at the same version. But this package was deprecated in favor
of something different.

## extra.symfony.require

Look inside your `composer.json` file for a key called `extra` and make sure
it has a `symfony` key below it and another called `require`:

[[[ code('195bdd4757') ]]]

This is a special piece of config that's used by Symfony Flex. Remember, Flex
is the Composer plugin that give us the recipe system and a few other goodies.
Flex reads `extra.symfony.require` and does two things. First, behind the scenes,
it tells Composer that all the `symfony/` repositories should be locked at version
`4.3.*`.

Scroll back up to the `require` section. See how `symfony/form` is set to `^4.0`?

[[[ code('dc133c352a') ]]]

In Composer land, that format *effectively* means `4.*`. If we ran `composer update`,
it would upgrade it to the latest "4" version. So, 4.4.

But thanks to Symfony Flex and the `extra.symfony.require` `4.3.*` config,
`symfony/form` would *actually* only be updated to the latest 4.3 version.

The *second* thing this config does - and this is the *true* reason it exists -
is optimize performance. When you run `composer update` or `composer require`,
Flex filters out all versions of `symfony/` packages that don't match `4.3.*`.
That actually makes Composer *much* faster as it has less versions to think about.
If you've ever wondered why you *used* to run out of memory with Composer a few
years ago... but don't now... this is why.

Let's see this `4.3.*` require thing in action. Spin over to the terminal, open
up a new tab and run:

```terminal
composer update "symfony/*"
```

If we did *not* have Symfony Flex installed, we would expect that `symfony/form`
would be updated to 4.4. But... yea! It says:

> Restricting packages listed in `symfony/symfony` to 4.3.*

And... when we find `symfony/form`, it *did* upgrade it, but only to the latest
4.3 release - *not* 4.4. You can also see that it updated a few other libraries
that start with `symfony/*` but that aren't part of the main Symfony code. Flex
has no effect on these: they upgrade normally, and that's fine.

So upgrading the "patch" version of Symfony to get bug fixes and security releases
is *just* as simple as running `composer update "symfony/*"`. But to upgrade to the
next *minor* version, we need to change the `extra.symfony.require` key. Except...
there will be one other trick. Let's see what it is next.
