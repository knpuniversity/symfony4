# Preload Lint Container

Coming soon...

There are two last small but cool features I want to talk about that are new and
Symfony 4.4 and 5.0 first one you can find if you search for Symfony preload, your
funded blog posts about new and simply pour four by four preloading Symfony
applications and PHP 7.4 so BHP 7.4 they added a new feature where in your PHP to INI
file it, you can add an op cache that preload setting and pointed to a specific file
and basically in that file you list a bunch of PHP files that you want to preload
into op cache. This is a way of basically preparing your server with a pre read
source code so that when you actually run your application, your code has PHP has
less work to do.

Now there are a couple of caveats of this. First, you need to create that list of
files, which is what we're going to talk about in a second. Second to every time that
those files change, you need to restart your web server or the changes won't be seen.
And third until SIF, until PHP 7.4 0.2 this feature was a little bit buggy and it's
possible there are still a few bugs left, so might want to use this with caution, but
it should be stable starting in PHP 7.4 0.2 so basically we're Symfony fits into this
is Symfony has a lot of intelligence about your application and it knows which
classes your application needs to use. So it pre-build a preload file that you can
use, check it out of your terminal and run bin console, cache Kwon cleaner dash dash
N = prod.

Yeah, we'll rebuild the container in the prod environment. I'll spin over and let's
check out the cache directories of VAR cache prod. And what we're looking for here is
this app Colonel prod container dot preload dot PHP. So it's pretty simple file, but
it's including a bunch of classes that it knows that you're going to need to use. So
on. You need to do then is just include this in your PHP to INI file, restart the web
server every time and you're going to get some level of performance. How much
performance? Not sure it's an a feature. The blog post says 30 to 50%. I've seen
things closer to like 10 or 15% on other blog posts. Yeah. Then one other thing I
want to talk about is actually related to how smart Symfony and Symfonys container is

because Symfony has called a compiled container when simply loads up all of your, of
your services and all of the third party services for the bundles, it can immediately
see problems. Like for example, if you register a service with a wrong class name,
you don't even have to go to a page that uses that service. Symfony's container isn't
even going to be able to build. You won't be able to do anything until you fix that
air. That's really powerful to find out about bugs that maybe you wouldn't have
thought of otherwise because they're being only used on some, uh, less important
page. Another one is missing arguments. So for some reason you registered a service
and it was just completely missing an argument. Like for example, Symfony couldn't
figure out like an argument to pass to a better example was a,

yeah.

Let's see here. A better example is mailer. If it couldn't figure out what are going
to pass any of these, again, you're going to get an error when a container builds,
not when you actually use this class.

So there's one other type of problem that Symfony can now detect and simply 4.4 and
that is if the wrong type of argument is being passed somewhere. So for example, if
we have a type in in our mailer class for a mailer interface, but we somehow have
this service miss wired, so it's passing us a different class or a string or an
integer, we can now find out about that. How are running bin console lint container
and Oh, check this out. This is a perfect example. In valid definition for service
Nexi /client argument, one of Nexi Slack time excepts a PSR client interface. But
apparently the service container is configured so that it passes this other instance.
So when I first started trying this link container command on this product, I was
surprised by this. It turns out this is actually a legitimate problem in my
application and I wouldn't have realized it until I went to a page that actually used
the next day Slack client. It turns out the problem is actually in the next Slack
client bundle. There's a very small bug in that bundle that can sometimes cause the
wrong instance to be passed to the slot. The next the Slack client service.

Yeah.

Uh, basically if you're not a composer, why PHP dash HTTP /agent to be plug. I won't
bore you with the details of how I figured this out, but when I dug into this, I
realized that this library needed to be at version two and you can see, and right now
we have version one and you can see that there are three other libraries that depend
on this. So the fix is actually to go to my composer, that JSON file. And if you dug
into this, you'd find out that I need to change the Gogol six adapter to version two.
And then I need to run a composer updates with all three of these libraries, PHP HTP
ATB plug, quite common so that we can, it can upgrade to a new version that allows
version two and the guzzle six adapters so it can upgrade to a new version that
allows HTTP plug to

[inaudible].

Yep. Perfect. So you can see a couple of the major upgrades here and as soon as we do
this, why don't we rent linen container? We get no outlet because now we are good.
Now just to make sure this, just to fully get this working, if you did look in the
change log for these libraries, um, or actually tried to use the service, you'd also
find that, uh, in this new version we need to include one other package, which is
HTTP dash interrupt /HTTP dash factory dash guzzle. Though, I don't need to run this
to give the Lindt container to be happy, but, uh, if you actually want a functional
guzzle service, that's the last step that you need to do it. All right, friends,
that's it. We upgraded to Symfony five. We removed our deprecations. We've talked
about a few of my favorite features, so I hope you'll join us in our Symfony five
track as we start really diving into the power behind 75 and building some really
cool stuff. If you have upgrade problems, as always, we're available in the comments.
Let us know what's going on and we'll do our best to help, right friends. See you
next time.