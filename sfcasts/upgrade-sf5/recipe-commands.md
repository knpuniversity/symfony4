# Upgrading Recipes: New Commands!

Coming soon...

Fun fact. If you start a brand new Symfony four or Symfony five project behind the
scenes, what you're doing is you're actually cloning from this repository. Symfony.
Slash. Skeleton. Yes, your project literally starts as a single composer.json file,
but by the time you see it, there's actually a bunch of other files.

Hello,

and the reason is that everything you see in your directory when you start a new
project is actually added by a recipe. So even like the most core files, like for
example, public /index dot PHP, this is the file that our web browser hits. We never
have to look in here. That bootstraps a framework. This was, it was added by a
recipe, one of the recipes for one from one of the packages inside of this composer.
Today's on file. Another example is config bootstrap dot PHP. This is a super low
level boring file that handles bootstrapping the environment variables. It's super
important that everyone has the exact same copy of this file to make sure that the
behavior is consistent everywhere and then configuration files. All of these
configuration files are originally added by different recipes. For example, cache
dot. Yammel wasn't uh, is installed from the recipe from Symfony slash.

cache. Now the interesting thing is that over time, a lot of times these recipes
update like the cache dot. If we installed the Symfony /cache component today, it
might give us a slightly different cache.yaml file. Now, there are three reasons that
a recipe, my update first and we might re someone might update a recipe just because
they want to add more examples or maybe tweak the documentation to make it more clear
how to use some file. That's not really that important for us to update into our
application. The second reason is that they might add new keys to a configuration
file that activates a new feature that's available that is not critical to update
into our, it's that critical if we update that into our applications, but that is a
little bit more important in the third reason that RSP, my update is because it might
actually change a file to fix something important. Like for example, this has
happened historically, historically, they've ever been small tweaks to the bootstrap
dot PHP file to make sure that the environment variables have just the right behavior
and all the situations, those types of updates we do want in our application, we want
our bootstrap dot BHB to look exactly like it should so that we get the best
behavior.

Now, a moment ago when we did all the composer upgrading, one of the packages that we
upgraded was actually Symfony flex itself. We upgraded it to 1.6 0.0 well. Guess
what? Starting in Symfony 1.6 0.0 there are some brand new fancy, amazing, incredible
commands added to composer to help us upgrade our recipes. It still takes a little
bit of work and a little bit of care, but it's now very possible. A big thanks to
community member and my friend max Helios who really helped get this done. All right,
so let's check this out. Move over to your terminal and run composer recipes. Ooh,
this gives you a print out of all of the recipes that have been installed into our
system, including whether or not there is an update available. Now, because my
project was originally created in Symfony 4.0 it's fairly old and a lot of these
things have recipes, have updates available,

and also because the, the recipe system is relatively new. There's just been a lot of
updates over the past two years, so there's a little bit more work to do if we want
to update all of these recipes. Now let's look at one of these specifically compose
the twig extensions. This is not a particularly important library, but we can run a
composer recipes two weeks /extension to get details about that specific one. Now
what you can see here, as you can see, it actually has a link to the installed
version of the recipe so I can go back over to the browser and put that in and you'll
see that this is what the recipe look like. The moment that we installed it. And if
you look over here, the latest recipe, we can also look at the latest recipe and it
looks like this.

Now, if we looked at the history of this, you can see that's the one that we have
installed is the, has the nine, eight, six. It's actually this commit right here. So
this recipe is out of date, but the only change that's been made to it is this one
commit. And if you search down here for tweaks /extensions to see all the changes
that were made, this is a completely superficial change. We changed till date to know
because they mean the same thing, but no is more obvious. So that is not at all an
important, uh, an important thing for us to update. So I'm just going to skip that
one for now. And actually more importantly, I want to focus on, because we're
updating Symfony, I want to focus on upgrading the recipes for everything that starts
with Symfony. Slash. So next, let's start actually upgrading some of these recipes. A
few of these like Symfony console, a Symfony framework bundle are critically
important to make sure that you have consistent behavior in your application.
