# Production

Coming soon...

There's one more thing that we need to talk about getting this code up to production.
So first, not going to a public bill director right now, if you open any of these
files, you're going to notice that they are not minified. And at the bottom there's
actually a bunch of sourcemap stuff which helps our browser, uh, debug that code to
build.

Okay.

To build for production, just run yarn build. This is actually a shortcut for yarn,
encore production. And you'll remember if you go back to the package of that JSON
File, we have a bunch of scripts that came with that project. So this is actually the
real command here on core production instead of encore Dev, which we've been running
until now.

Okay.

This makes a very, when this finishes, this makes a very different bill directory.
There's a number of things you can see. First of all, a lot of the file names are ops
UK did. So before you kind of saw things like App till they vendored, uh, which kind
of exposed some of our internal, uh, uh, structure of application. Now you're going
to see a lot of things. It's use numbers like zero, one, two, three, four. Also, if
you look in any of these files, now you're going to see they are totally minified and
they're certain, there's no source maps on the bottom. You just do silly. Do see
still these you deals do still see these licensed headers that can be disabled by
default for legal purposes. That's the only comments that are kept in any of these
files.

Okay,

so just like that, we have a bill that's optimized for production and we can try it
instantly. If we refresh over here, everything's still works. There's one other thing
you might notice and that is that every single file now has a hash in it that is
thanks to in our Webpack dot config. That js file if feature in here called enable
versioning and check this out. We've had this the whole time, but it's using this
function called encore.is production. So we've disabled versioning of the development
mode. We've enabled it in the production mode. So as soon as we ran from production,
boom, we had version file names. And the really awesome thing about this is that
every time the contents of this article, show dot CSS file changes, it's going to
automatically get a new hash. But we don't need to change anything in our code
because our twig helpers automatically always updates, uh, render the correct script
files, link tags or the correct jazz tabs. So basically we get absolutely free
versioning on all of our files. As soon as they change the hash will be updated and
your a user's browser will be busted. This also means that you really should take
advantage of something called longterm caching, which is where you can tell your web
server like engine x that every file that it serves from the /and build directory
should be cached for like one year or forever.

That means that when your user downloads any of these files, they will never ever ask
the server for that file ever again. They will cache it locally and use it locally.
If we ever update one of these files, the Hash will change. It won't be in the user's
browser cache and they'll ask for a new one. So add expiration caching. Cause it's
just free performance. So let's talk about deploying this production. So how do we
deploy this to production? But the answer is it depends. It depends on how
sophisticated your deployment is. A system is. So if you have a really simple
deployment system where you basically, you know, um, get Paul on production and then
clear the Symfony cache, you're probably going to need to actually install node on
your production server, run yarn, install, and then run our yarn, build up on
production. That's not ideal. But that is the easiest way to do it, to get those
files there. If you have a slightly more sophisticated system,

then you can do something way better. The key thing here about encore is that once
you've run yarn build, the only files that need to get to production are this build
directory. So you could literally run yarn D, build on some other server or even
locally, and then just make sure that this bill directory gets copied to production.
That's it. You don't need to have node installed on production. You don't need to run
yarn. You don't need to run anything on production as long as it's spelled
directories. There you are. Good. All right, so we haven't even touched all the
features of encore. We didn't, didn't even talk about the, um, type script support or
the react to the review support. It all works. Those are the things that you're
interested in. Go try them out. They're awesome. And if you have any questions at
always, uh, find us in the comments section. All right guys, we'll see you next time.