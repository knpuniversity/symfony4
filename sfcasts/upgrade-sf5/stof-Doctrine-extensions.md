# Stof Doctrine Extensions

Coming soon...

Let's take a look at how things are deprecations are looking at this moment. So I'll
go refresh the home page and let's open up our profiler and okay, so we still have
the tree builder root thing coming from staff doctrine extension. So let's find the
stuff, doctrine extensions, bundle package name. I'll copy that. And again we'll hope
that just upgrading this from version 1.3 to 1.4 or 1.5 will be enough. So I'll say
composer update stop /doctrine /dash extensions bundle. But like with the campy page
knitted bundle, we don't get an update. So now we need to figure out what's going on
with this. So I'll Google for stuff, doctrine extensions bundle and have it over to
the get hub page. And the first thing I'm gonna look at is like what is the latest
release? And you can see that we're using version 1.3 0.0 and the latest version is
1.3 0.0 from almost from over two years ago. Adding support for Symfony four. So
unfortunately if you look into this bundle, which I really like, it is a bit
abandoned. Fortunately by digging in a little bit, you can see that the community has
done a wonderful job here of actually forking this library

[inaudible].

I'm actually going to copy that and let's copy that entire package name and I'm going
to Google for that.

[inaudible]

Oh I found it's packages page and let's actually click over to the get hub page. So
basically what happened here is this user here, um, forked stops library and he's at,
they've actually been doing a really wonderful job maintaining it. You can see it has
the same tag history version 1.3 0.0 and they've added a 1.4 added 75 just four and
1.4 0.1 and 1.4 0.2 so they're actually doing a really nice job of maintaining this
library. It's the exact same package. It's got all the same code as before. They've
just actually added a fixing bugs, added Symfony five support and it's under a new
name. So we're now going to switch to this library so we can get a version that's
compatible with simply five. So here's how this is going to work. I'll copy the stock
package name again. I'm going to say composer remove stuffed /doctrine extensions
bundle.

And when we do that, we're going to get a huge air. Um, don't worry cause it actually
did remove it, but our code is relying on some of that. So since that code has gone,
uh, it's temporarily going to be broken. Now I'm gonna move back over and click back
to the homepage here of this fork. Go down to the read meat and compose copy the
compose require line with their name instead of staff in the beginning. And we'll
paste that. This is going to reinstall the package at a newer version and it's
actually going to install a recipe, which again, this user has done a really good
job. I've actually created a recipe that's basically identical to the original one.
So really is everything. It looks and feels like the original one. So I'm gonna do
it, get status here, because this did just re-install the recipe.

So let's add compose that JSON, compose it out, lock Symfony, that lock, because we
know we need those files. And now I want to do get add dash P. now the first change
is in bundles. That PHP, you can see it didn't really remove this because if we say
yes here, it just added the same one down at the bottom. So that's actually really a
meaningless change. And then because it uninstalled the package and re-install the
recipe, it actually deleted my custom code and stopped doctrine extensions that
Yamhill. So I'm going to say no to that change.

Perfect. So I'll come. So I'll commit

that we're using a doctrine extension well on the fork and they'll say get
checkout.to get rid of those custom changes. So it was a bit of a weird one, but if
we close everything up and refresh, now our deprecations go from 25 to 16 that took a
bunch of the spots off. So next, let's work on something different.