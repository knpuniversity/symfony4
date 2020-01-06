# Fixing the First Deprecations

Okay. We are now on Symfony 4.4 and we've upgraded all of the Symfony recipes. Now
our path is fairly straightforward. Our job is to find and fix all of the deprecated
code that we're using. As soon as we've done that, it will be safe to upgrade the
Symfony 5.0 so how do we find all of the deprecated code paths we're using after off,
there's some function that's deprecated. How would we know that? There's two main
ways that you configure it out. The first one is down here on the web debug toolbar.
This literally tells us that this page load hit 49 deprecations. We're going to show
that in a second.

However, even if you fix all of the deprecations that you can find on your site,
there always might be some edge case page or edge case situation you didn't think of
trying locally and so, so you might not even, so you, there might always be some
cases that you didn't think of. So the second way to find applications is after you
fixed most of them, you can check the deprecations log on production. Check this out
in my config packages at prod monolog.yaml file. There's two handlers down here
called deprecation and deprecation filter. In production mode, you're going to have a
prod dot deprecations dot log file. So once you fixed all the deprecations, you know
of, you can go onto production and check this log file to make sure nothing new is
going into it. Once you're satisfied, all the deprecations are gone and your ready to
upgrade.

All right, so let's start talking about the deprecations. I'm going to refresh the
homepage and then in the new window, open up the uh, the profiler to the
deprecations. Fixing the deprecations is a, in some ways straightforward and, but in
other ways it's kind of a wild thing cause there's many different things that you
might need to do to fix a deprecation. The first one here is pretty straight forward.
It says the web server bundle is deprecated since Symfony 4.4 if you're ready.
Remember when we started her web server over here we use the Symfony serve command.
This comes from the nicest Symfony binary, which is a actually a binary built in. Go.
Or before this existed, we used to use a bin console server, call and run command
that comes from the web server bundle that's now deprecated. This is a super easy
thing to fix. If you look in our composer dot JSON file, we have a Symfony /web
server bundle here. I'm going to copy that move over and run composer, remove Symfony
/web server bundle that's going to remove that bundle. And actually it's going to
upgrade a couple of my packages. A patch version 4.4 0.2 has come out since then. It
also unconfigured as the recipe. So it's a it on registers itself. It also makes a
change in that get ignore

which, uh, stops, um, ignoring a file that, that a bundle would sometimes create. So
one deprecation done. So let's go back and look at our list. These second one down
here is something a call about, uh, calling event dispatcher dispatch method with the
event name as the first argument is deprecated since Symfony 4.3. So one of the
trickiest things about fixing deprecations is that you need to find out where this is
actually coming from. And to make things worse, a lot of times it's not coming from
our code, it's coming from third party libraries that we rely on. So in this case, if
you hit show trace here, it gives you a little trace about where this is coming from.
It's not super obvious, uh, but if you kind of look up here, you can see something
about leap. Imagine if I hover over this, you'll see that this is coming from leap.

Imagine bundle. So leap, imagine bundle is calling some deprecated code. Now there's
only one way to fix that and that is to upgrade leap. Imagine bundle. The easiest way
to do this is to go over find that package. So leap /imagine bubble and then move
over and just run composer update, leap. /imagine bundle. What we're hoping is that a
minor upgrade, like you know, maybe 2.1 to 2.2 is going to take care of this upgrade.
And actually you can see it upgraded from 2.1 to 2.2 did that fix the deprecation? I
have no idea. I'm being lazy. I'm just upgrading. So let's go find out how close the
profiler. Refresh the homepage and cool. You can see the deprecations went from 48 to
29 that's a good sign. I'll open that in a new tab and awesome. It looks like that
stuff is gone. So next, let's keep going with this. I'm, we're going to focus on
these tree builder root things next, which are also going to involve upgrading
bundles, but those upgrades are going to be a little bit more complex.
