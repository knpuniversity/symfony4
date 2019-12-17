# Upgrading symfony/console Recipe: bootstrap.php

Coming soon...

Now, the first recipe that I want to update is `symfony/console`. So I want to
get more information about that by saying 

```terminal
composer recipes symfony/console
```

and just like before it gives me a couple links here. One of them is that it gives me
a link to the current version of what the recipe looked like when I installed it. I'm
gonna go over here and paste that in there. Then it also gives me a version of a,
what the recipe looks like at this moment. I'll go back and just take a look at that
as well. Now, one critical thing is that because of the way that the recipes are
organized, if you want to see like what has changed between two versions, it's not
always as easy as just looking at the history. So this is what the recipe looks like
today and I feel look at the history of this. You might think, okay, I'll just go
back and see what commits had been made to this recipe in case I'm really interested
in like why things have changed. But if you actually look at the current thing of my
recipe, it's living in a `symfony/console/3.3/` directory and the new one lives in a
`symfony/console/4.4/` directory. So that's part of the way the recipe system works.
I'm actually going to go back to the master branch here. If we just,

I'm actually in clip back `symfony/recipes`. So we can um, look specifically at the
`symfony/console/` directory. Every, every recipe is allowed to have multiple versions.
And what this means is that if you installed this recipe and uh, and, and you had
`symfony/console: 3.3`, you'd get the `3.3` recipe. If you installed `symfony/console: 3.4`,
you would get the `3.3` recipe. If you eventually installed `symfony/console: 4.2`, you
would then get the `4.2` recipe.

So it's kind of a strange versioning, uh, uh, mechanism. So in our case, as you can
see, we're on the `3.3` and we're going to go all the way to the `4.4` version. So if you
wanted to see the full history of that, you kind of need to look at the history of
what commits have been made to the `4.4` branch and also maybe look at the history of
what's been done to the `4.2` branch and also maybe look at what history has been done
on the `3.3` branch. Now don't feel overwhelmed. I just wanted to give you kind of the
bad news up front. Most of the time the changes are going to be obvious and when
they're not, I'll show you how to find some more information. This is when I talk
about the recipe system upgrade system. It's very powerful, but still a little bit
complex. Okay, so how do we actually upgrade a recipe to the new system? Well, you
can see down here it gives you a command 
`composer recipes install symfony/console --force -v`. what that does is it basically, 
it's not really an update, a smart update system. What that basically is going to tell 
flex to do is completely re-install the `symfony/console` recipe as it would look today.

So I'll run that command 

```terminal-silent
composer recipes install symfony/console --force -v
```

and that means it's actually going to run over any changes,
override any changes. We have swollen need to apply these changes carefully. No one,
I've installed that things to that `-v` it's actually going to tell us, uh, what
operations it's making known as. This has created a `bin/console` and 
`config/bootstrap.php` and in those cases it actually modified those. It didn't create them.

And you can see that when we run get status. No. Where we need to do here is be very
careful and make sure that we understand what changes work we're committing. So I'm
going to do 

```terminal
git add -p
```

which is going to allow me to check things out little by
little and we're going to walk through some of those changes. The first one you see
here is there's actually a used in that changed from this `\Debug` to `\ErrorHandler`.
That's because Symfony has a new ErrorHandler component. So that is a change we want
to use because we want to use that new ErrorHandler handler thing. This exchange down
here, I don't really know what this is. We could go and look into it more, but
clearly it looks like it's some sort of edge case to make sure that we are maybe not
trying to use the `bin/console` file, uh, outside of the uh, uh, the command line. So
I'm going to say yes to commit those changes. The next thing, notice there's a big
block here where there's an erase spot up here that change. And also a big spot down
here where a bondage of things went away. No, there's a couple of things going on
here. If we wanted to know why this changes, let's say we want to understand like why
this change is being made. It's clearly some low level environment variable thing.
But let's say we don't want to trust it.

So what we can do is we can actually go back to `symfony/recipes`, console. We're gonna
go back to `4.4` and the firewall we're working here with inside of here is actually
`config/bootstrap.php`. So actually I just went into the wrong directory. You can
see the `config/` directory here. It's actually has a little arrow. This is actually a
SIM link, which means that the `4.4/config/` directory is actually the same as `4.2` so
actually fun to look at the config director. We need to go to `4.2/` then I can go to
`bootstrap.php` here,

but even this is actually once again a similar to another part of the system. The
`framework-bundle`, the bootstrap is one of the most complex and shared files across
recipes. So you're kind of seeing one of the ugliest cases. First, let's go over to
`symfony/framework-bundle/4.2/config/bootstrap.php` and now you can see the full
file in here or we can do is we can do a blame. Try to figure out what happened. So
you can see here there is a couple of commits here related to those two lines. So if
you wanted to, we could actually take a look at this one. A lot allow correct
environment to be loaded when `.env.local.php` exists and then you could
even click to see the pull requests to see. This is the discussion on why that's
happening. Now. The long story of uh, this `bootstrap.php` file is that we are
going to want to accept any of the changes that are inside of this file. This is a
really low level file that's meant to bootstrap the framework and the environment
variables. And unless you're doing something super custom, you are going to want to
accept these files. So I'm going to say yes to both of these. If you went and dug
down here, you'd actually find out that I'm,

and an early version of this recipe, if a `loadEnv` variable, a method was, uh,
existed on the `Dotenv` class, it called that `loadEnv()`. Elsa had a whole bunch of code
back here for older versions of `Dotenv` to imitate that while the new version of one.
It just assumes that you have that `loadEnv()`. So it shortens the whole thing to just
this short string here. So that is a good change. The other thing you're gonna see
here is a `symfony.lock` file. We don't even need to look at that. We should
always accept changes to `symfony.lock`. So that may have seemed like a small step
in one where it was actually a little bit confusing. If you really wanted to
understand the reasons for these changes, well, we just updated two very low level
files to make sure that we are applications working exactly like um, Symfony should
expect so we can commit these changes and confidently know that we are in good shape.
All right, next, let's keep going down the list. This is one of the hardest ones and
get the, all of our Symfony recipes updated.
