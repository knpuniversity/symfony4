# Public Path

Coming soon...

Okay.

The hardest part of doing the uploading, you might not even be the uploading part.
It's rendering, rendering image tags, um, thumbnailing, which we'll talk about in a
second, doing a allowing downloading of assets, especially if they're private assets.
Um, how things change, how public paths change when he moved to the cloud. This is a
huge topic and we're going to create a really great system that's going to work both
in the cloud and if you store your files locally. So let's look back to the homepage
and you can see that almost all these images work except for this one. This is
actually the one that we've been working on. So if I inspect element on that, you can
see it's /images, slash, astronaut and there's the file name. So the way this worked
before it was in our fixtures, we were just a setting the image file name to a
specific file name. And then we had some hard coated acids and public images. So for
example, you can see the astro dot Jpg, um, is just images /astro dot jpeg. So we
were just basically faking uploads. These are not uploaded files. We were just faking
it. And the way this worked, if we look at the template for this, so templates,

article, homepage dot h to my toy. There we go. We were just using the asset
function. Oh that's the wrong spot. We were saying asset article, that image path and
image path was actually a function inside of here called get image path. What it is
it just prefix images /the image file name. So we had asteroid dot jpeg in the
database. This return images /astro dot jpeg. We wrapped it in the asset function and
boom, it worked perfectly. So now we don't want it to work for our hardcore to the
image is going to work at four are uploaded images. So the easiest way to fix that is
to go back into an article and just update the path. Here it is now uploads
/article_image /and then this that get image file name. That should get us, yep.
Public article image.

Okay,

it's now and refresh. Perfect. We don't care about these broken ones. That one works.

So that was the first step. And now there's a bunch of cleanup that we need to do to
make this really nice. So one problem that I have is classic problem is that in the
public path here we have article on a square image and an uploader help or where we
actually handle the upload. We have article_image that's the directory where all
article images are stored. And as we start uploading more files, you know like uh,
we're going to be storing more paths like this. So basically I don't like those
strings instead of we're going to move that into a constant. So in upload or helper,
how about let's create a constant call article image just literally equal to
article_image, that sub directory, we can use that down in the same class very
easily. Self article image and we can use that over in our article here, upload or
helper article, image. And when we refresh, that works fine. All right, so let's keep
going here.

Okay,

back in article, having this words up, we have to have the word uploads here cause
that's the public path to our asset. Now that's not a huge problem, but I actually
don't want that upload string to be here. And the reason is that as we're uploading
more files, every entity that has a file upload is going to have a method like this
and they're all going to have the word uploads in it. So if later we decided to
refactor it to the cloud, that's actually a bit more, we have more places in our code
to change. And actually it's worse than that. When we upload to the cloud, we're
going to probably, we might need to use a dynamic host name. Um, it's going to, it's
going to cause a problem. I'm explaining this poorly. So instead when I really want
this get image path to be is just

okay,

these sorts of file path to this file relative to where ever files are uploaded. So
we're in this case relative to the uploads directory. But if we are uploading to the
cloud, for example, this would be the path to the relative to the root of wherever
our files are being uploaded to in the cloud. Instead of calling get image path and
expecting it to be the full public path, we're actually going to go into upload
helper and a new public function here called gets public path. That's going to take
the string path. So something like article_image /astronaut dot jpeg and it will
return a string, which should be the absolute, which will be the public path. And
here we'll return uploads /dot path. And that seems a little bit silly, but now from
anywhere in our code base, we can call it get public path, has it and the uploaded
path. And it's going to give us the absolute, it's going to give us the public path
to that. And if we change to the cloud later, um, we only need to change. Add the
cloud. You were all right here and we actually will do that.

So it's cool. So we have a, a functioning call from anywhere. It gives us the public
path. The problem is how to be called this. We basically need to call this get public
path from our homepage template, right? Because right now if we refresh

it doesn't work. Of course because it's missing that uploads part. So to do that,
we're actually going to create a twig extension or really where we already have each
week extension from early inner Symfony series. So we're just going to add to this
and here's the idea. In homepage that's weight. Instead of using the asset function,
we're going to use a new function. We're going to create called uploaded asset. We're
going to pass it article, that image path. So that's something like uploads, sorry,
article, image, the last file name. And then this will ultimately call get public
path, which will return the final public path. So when APP extension, we created a
filter. We haven't created any custom functions yet. So actually I'm going to copy
this, get filters, change it to get functions returning to, and inside here we'll say
new twig function. We'll pass this uploaded asset and then this, and we'll call it
method and Eric called gets uploaded asset path. I'll copy. Cool. Now down here,

okay.

Public function, get uploaded. Asset path that will take in the string path and
return the absolute, the public path

in here. Where we need to do is we actually need to get the upload or help helper
service because we need to call get public path on it. Now normally we do this via
dependency injection, uh, but there are few places in Symfony where you actually were
for performance purposes. You want to get services in a lazy way. This is not
something I'm gonna talk about much right now because we already talked about it in
our, in our Symfony fundamentals. But this class actually uses something called a
service subscriber, which allows you to basically fetch services down here. Basically
choose which services you need down here and those are passed to this container
object. And then you can actually fetch them out by saying this air container get in
the class name. So very quick description of how that works. But the point is we're
going to say upload or helper down here, ::class. And that's gonna allow us up here
to say return this->Container Arrow, get upload or helper, ::class. Whoops. And get
public and pass it the path. All right, so now go back, refresh and it works again.

So if we click this to go to the show page. Ah, that's right. This one doesn't work
yet because we need to update everywhere in a code to use this new uploaded assets.
So I'll copy that. We'll go into showed at h. Dot. Twig. Here it is here uploaded
assets and reroof fresh and oh, it's still doesn't work. Inspect element on that
again. Ah, so it's the cat, the right path. The problem is that it doesn't have the
opening /on the front. And so now that we're on a kind of a sub directory, you were
out. It doesn't work. So if I actually added that /it would pop up. So how do we fix
that?

MMM,

well that's actually one of the jobs of the asset function. So if you wrap this
entire thing in asset, one of its jobs is to make sure that it always kind of prefix
it and refresh. Now it actually works,

but

it's kind of annoying to have to wrap everything in the asset function. So let's make
her, I uploaded the asset, do that automatically. Now the easiest way to do this,
would it be go to get public path and just add a /on the front because that would
render with that slashed in the front and everything would work just fine. However,
there is an edge case that this is not thinking about that the asset function usually
takes care of and that's if you have your site deployed under sub directory, like
we're not@thespacebar.com or@thespacewhereatacomslashwhereatgalaxy.org /the space
bar. And so every single URL has that in front of it. That's actually the root of our
domain right there. If you have a situation like that than hard coding, the /and the
front is not going to work. You really need to say slash

uh,

the space bar. Basically it needs to be printed out automatically and that's actually
what the asset function does behind the scenes. So we're just going to miss no
problem. We can actually mimic what the asset function does, um, inside of here. And
this is a little bit technical. We're going to work with a service that you don't see
very often in Symfony, but it's the service that's used internally by the asset
function to figure out how to do this prefix. So here's how it works.

Okay,

go to the top and we're going to add a type hint for a class called risk request.
Stack context. I'll hit enter. It's like initialize fields to create that property
in. Set it now don to get public path.

Yeah,

we can say return this->requests stack context Arrow, get the base path

and then

that /uploads. /path. So that gets pays path. Well that's going to be is if we're
under, you are like this, the base pass is going to be /news. Wait, that's not right.

Shoot.

Now if we did live under a sub directory, that would actually include the sub
directory plus some other URLs.

So we try this right now, we're actually going to get a huge air. This request, that
context thing is such a low level service that it's an action not actually exposed as
an auto wire bubble alias. So we can't actually, it exists but it doesn't, it's not
made available to us just for auto wiring, but this actually gives us a really good
thing. It says, Hey, you cannot auto wire the request, that context method and it
references a class called request kicks that context, but no service exists. Maybe we
should alias this class to the existing assets. That context service. This is a
little bit technical and we talk about this in our Symfony fundamentals episode. Um,
what it's actually telling us is to go into our config services dot Yam and anywhere
inside of here. Well the bottom, if you paste that instead of two, copy the service
ID.

Okay.

At assets dot context, so assets, that context is actually the internal service ID
that we want to use. This syntax here creates an alias. It basically creates a
service call request dot context, this long name and when you fetch it, it actually
just gives you this other service like a sim link. By adding this alias, it actually
makes this auto wire bowl. If you go over to your terminology and say debug auto
wiring.

Okay,

actually debug auto wiring request. You will now see this request at context here as
an auto wire we'll alias, but this was not there a second ago before I added that.
It's now we go over it and refresh. Yes, it works. And if I look at the path there,
Yep. You can see /upload /article image /astronaut that jpeg and a, if we lived in a
sub directory, it would work so small detail, that last part, but it makes her site
really nice. Importable.