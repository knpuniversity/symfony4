# Flysystem Imagine Bundle

Coming soon...

We're using fly system, it's working great, but there's sort of a hidden problem. The
cool thing about fly system is that in theory we could go into our not FlashSystem
diameter and we could change this to some cloud storage like s three and everything
would work right? Well we're cheating a little bit. Specifically the leap imagined
bundle is cheating, so check it out. Let's go to our templates article, homepage dot
h. Dot. Twig and you see what we do here is we call uploaded asset. We pass that
article image path and that's actually what we piped into the filter. So or what the
value of that's going into the filters actually uploads /article_image /the path of
the file name.

The

imagine bundle by default reads things from the filesystem always. So if we
refactored this to s three it would still be looking for that path on the filesystem.
You can actually see this if you run bin Console, debug and fig leaf imagine.

Okay,

you're the bottom. It has something called eight resolver. The resolver actually,
sorry. You should be looking at loader.

Yeah,

the loaders responsibility is given a path. It knows how to find that image, the
contents of that image. And by default it uses a filesystem,

uh, to do that. And it knows to look at the public directory. So when we pass it
upload /article image /the final name, it uploads the file in life is good. So we
need to change this to use fly system and said, so that in case our assets are stored
on s three, it will know how to load them from s three. So then I can do the
transformations on it. So actually let's go to back to the leap imagined bundled
documentation. I'll find their get hub page and then I'll click down here on the
download. The bundle has an easy way to get into their documentation. Then I'll go
back to their main documentation page. So down here near the bottom it talks about
different, uh, data loaders. So that's the thing we were just talking about. The
filesystem load is by default. Fly System is what we want to use. We've already
installed the bundles, so we're good there.

Yeah.

And we're gonna do is actually copy this loaders section. We already have our
filesystem's all set up so we're good there. Then we're going to go and leave imagine
and it doesn't matter where, but I'm going to do it up here. I'm going to paste this.
So this creates a loader called profile photos. That can be anything you want. So I'm
gonna call it fly system loader. Key thing here is the key fly system that says to
use flash system nine I need to do is point this at the actual service id of the
filesystem that you want to use. So if we go back into our config services.yaml
remember this long name here is actually the idea of our service idea of our
filesystem. So I'm going to copy that. Go to leap, imagine and we can paste that
there.

So that creates a loader call. They filesystem loader, FlashSystem loader. You can
technically have multiple loaders. I usually, I only need one and so down here you
can say data loader and you pass it the name of we gave it, which is fly system load.
Her up here. And this is going to say default loader to use for all filter sets. You
can actually specify in a filter set by filters that basis to a load from different
loaders. If you need that complexity. I don't need that complexity. I just want to
say in all cases, I want you to load from the filesystem. All right, so let's try
that. To try that, we need to go into the um,

okay.

Public Directory, let me find it. And we need to delete all of our existing
thumbnails. I'm gonna Delete the entire media cache directory. We need to do that so
that when we refresh the page, it will actually try to use the data loader to go and
get the contents of the file so that it can actually put them into the media
directory. So let's go back to how about the homepage and it doesn't work. It's
inspect element on that. So you can see here it's got the media cache resolve. That's
that dynamic. You were l. And then this last part here, uploads article, image
lightspeed. This is actually the path that we are passing into. Uh, uh, leap imagined
bundle. This is actually the value that we're getting. If we go back to our homepage
template, this is actually the value that we're getting from the uploaded asset call.
So the problem now is, and it's actually really cool, is that now that we've told
leap, imagine to use our filesystem while our filesystem it's root is the uploads
directory. So if you want to read or write a file from our filesystem, you do not
need to include the uploads part of it. You just need to give it the key
article_image /and then the file name.

So in other words here, we don't need the uploaded asset anymore. We're just going to
say article image, that image path, which will give us the article_image /the file
name and pipe that directly into the imagine filter. This is really cool. This makes
it super simple to do. Thumbnailing

okay,

you will still need to use the upload. An asset function is still cool because that's
what you would use if you didn't want to use any filters. If you just wanted to have
the absolute path to the original file name, but if you aren't doing thumbnailing,
then you're just going to pass the raw path. I actually really like this improvement.
It makes my code simpler. So now in refresh, it still doesn't work. It still doesn't
work. It's actually do a copy image address here, put that into our browser to see
what it says. Okay. It says source image for path article, image lightspeed could not
be found thanks to the fly system. This should be looking right here. Oh, of course.
Because we actually deleted all of our article images. Duh. Okay. If we're going to
actually down to the one, one of the ones that actually still exist, it does actually
work.

Okay.

Yeah, I think we can all right. Restart in there and it's still doesn't work. That's
actually because if you remember, we um, we actually deleted all of our uploaded
images, um, from the fixtures and actually just re uploaded a couple of them. So if
you scroll down a, here we go. Here's actually the earth image that we uploaded. So
it actually is now working correctly.

Okay.

It's actually run our fixtures load here so we can get a fresh set of uploaded assets
to really make sure this is working your first that much better. And then we just
need to make the same change in the two other places where we're uploading or using
thumbnail images. So in the templates directory articles show, we'll remove uploaded
acid there, refresh Alex, good. And then we'll go back into our admin /article
section will need to log back in with password engage because we reload the database.
Then when we're editing an image, we also needed to do it there. So that is an
article admin_form that h Montsweag take off the uploaded asset.

Got It.

All right, so the loaders, the thing that's responsible for finding the raw file,
there's another concept in leap. Imagine bundle, there's the loaders and there's
these things called resolvers saw. Click down on the fly system resolver. The
resolver is the thing that's actually responsible for writing the thumbnail. So it
reads it from the loader, it makes the transformation and then it wants to write it
somewhere once again. By default leave. Imagine been just writes things directly to
the filesystem. So even if we moved fly assessment to s three, it would still
ultimately be writing all of our files into, onto our server, not on, into this media
directory, not onto s three. So we're going to change that as well. So we'll go over
here and I'm going to copy this resolvers section. Go back into our leave. Imagine
Bundle, well pace that we'll do the same thing as before. I'll call it fly system
resolver and we're gonna use the same spy system service.

Okay.

And I'll get rid of visibility. We'll talk about that later. cache Prefix is
basically the directory within, uh, the filesystem where the files are going to be
stored. And then for the route you were out, this is actually the URL where that all
paths should be prefixed with. So actually what we want here is /uploads. So
basically if it,

okay,

because we know that things that are stored in this filesystem going into the public
uploads directory. So if you want to get a URL to them, you have to put in at
/uploads. We're gonna talk more about this. You were a later when he moved to a s
three, so time to check this out, I'm going to delete the media directory entirely.

Moved back over, refresh

and it works. And check it out. Look where it's stored. It's not storing in

Oh,

oh. And before we try it to make sure that this resolver is used by default, a lot of
key down here called the cache

and then

fly system resolver. Yup. That's actually put an e on that flag system resolver. It's
a little weird. They use the word resolvers and then word cache a bit
interchangeably. But this says to use that resolver all the time, it's always where
we want to write the, um, the thumbnails. All right, so let's go over refresh and it
works in checkout where it's stored. Those, there's no media director anymore. The
fly system, a filesystem is pointing at the uploads directory. So now it's actually a
media cache directory within the, um, within the uploads director were the filesystem
goes and thanks to our /uploads here, it knows that when it actually renders the path
to that thing, it knows it needs to put a /uploads in front. And then the path that
had actually wrote the file too. So this is awesome. It seemed like a small step
because it was technically already working a second ago, but this is the preparation
that you need to do if you actually are going to change fly system to store your
files somewhere other than your filesystem.