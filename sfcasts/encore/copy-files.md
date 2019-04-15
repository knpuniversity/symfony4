# Copy Files

Coming soon...

Okay.

Hey Forrest, refreshing the home page. Whoa. Okay. You can see we actually have a
couple of broken images. What's doing spect on that? And see what the problem is.
Okay, so this is an image tag 0.2 images /a meteor shower dot jpeg. In fact, get rid
of all that.

Okay,

let's go look at that template. This lives in templates, article home page dot
.html.twig and yeah, you can see just a normal boring asset tag to images. /meteor
shower dot jpeg fat's broken because you remember we moved our entire images
directory out of public and into the assets directory. This is a really nice property
of having a system like Webpack. You don't need to have most of your public images in
the public director anymore. You can build them all inside of assets and then Webpack
takes care of moving them all into the final directory. But unless you're building a
single page application, you still might have these cases where you just need a good
old fashioned the image tag that points to an image. So in this case, this image is
not being processed through Webpack and so it's not being copied into our final bill
directory. So it's kind of an annoying problem. And so because of that, we added
something to Webpack encore to help this. So open up the Webpack dot config dot js
file. And anywhere in here we're going to add a copy files method

and pass us and object. One of the things that I want to mention is that the
documentation inside of Webpack, it's encore itself is really good. So I'm gonna hold
command or control and click into this and it's going to take you into the index dot
js file of Webpack encore, which is almost entirely just methods and documentation
about those methods. So this is a great resource to figure out how something works.
So you can see the copy of files is pretty simple. It can be as simple as I want to
copy everything from assets /images into my built directory. So that's actually
pretty much exactly what we're going to do. So I'll copy that. Go on Webpacket,
config dot js and have an extra curly brace there. Perfect. So copy everything from
assets /images into the bill directory. Now remember, every time we make a change to
our Webpack dot config dot js file, we do need to actually go back over here and it
control c and restart Webpack and once this finishes, cool. So check it out.

Okay.

And the public build directory. Yeah, there we go. Meeting your showers, spice, a
spice, Naf, all the files we have here and our build directory. Um, but it is of lame
because it just dropped them all directly into build. Uh, just for my own sanity,
having them in the images directory would be much cooler. So to do that, going back
to the documentation, there's also a way here that you can tell where you want it to
go to. And notice this has a couple of wild cards in it, like path in name and
extension that you can control it. Actually, I'm gonna just went down to here because
this one has built in a versioning similar to what you've seen. The images directory
where it actually has different Hash is included. So you kind of get free cache
busting.

So back over here. I'll add that too. And then we're going to rebill encore. Now you
might think that I need to go over here and actually clean out all these old files so
that we can delete that. But one other optional feature that we're already using is
called clean output before build. This basically is going to completely clear out the
build directory before every time we build. So we don't actually need to clear out
the old stuff. It will just take care of it itself. So I control seat paste and let's
go check it out.

Nice. Everything is in the images directory and it has this Nice Hash, nobody. Only
problem now is because we have this hash and the file name. What should we put here
for the image tag? Should we put build images, meteor shower dot five c seven seven
blah blah. We don't want to do that because if we ever update meteor shower, it's not
going to be very obvious that all of our image tags just broke and we really want you
to dynamically, you read this file name that really are we talking about the entry
points dot JSON File, which is what Webpack uses, which is what our twig helpers use
inside of. For example based studies not twig to figure out which Lincoln script tax
to render. There's actually one other JSON file that encore automatically makes it a
little bit less important but it's called manifest at JSON.

This is literally just a map from the original file name to the final file name,
which for most, which right now you can see is mostly the same, but later when we,
when we enable versioning off cross everything, these pads are actually going to be
different and the really cool thing to hear is that you can see that for our images
we have the source image, build images, meet your shout out jpeg without the Hash and
it points to the fine on one with the half cache. Now what do we installed? Webpack
encore, the recipe bought in a, uh, I configure packages, assets.yaml file that we
didn't talk about and it had just this one line here called JSON Manifest path set to
manifest that. JSON, the significance of this line here is that anytime we use the
asset function inside of Twig, it's going to take this key and tried to look it up
inside of our manifest that JSON and if it fights it here, it's going to use this
path over there. If it doesn't find it, it will just use the, it won't make any
changes. So this means that if we want to point at images, meteor, shower dot jpeg,
all we need to do is point at build images, meteor shower dot jpeg. So I'll copy that
path going to homepage and I'm going to paste it here.

I also have a couple of other image tags in here. Search for image tag that was going
to the thumbnail systems, so that's okay.

Okay.

Yeah, that one's okay. That one's a dynamic image. Um, this one here will change to
build /images /alien desk profile, that jpeg and you're in the bottom build /images
/space ice. That P and g. All right, let's try it. Go back, refresh and it works.
Inspect element on that. There is, you can see the actual final hash found him. So if
you update that, it's just going to change the hash, but it'll automatically, they
were under the right thing. We have a couple of other image tags that I want to fix
before we keep going. They are an article showed us each month twig. So for image
tags, again, that's another dynamic we uploaded one build /another build /another
build /and finally one of the bottle. So I clicked now NC, anyone, any of these real
image files here and yet all of these little avatars here and coming from what we
just did, it's, there we go. So copy file sports, Super Handy. Built in versioning.