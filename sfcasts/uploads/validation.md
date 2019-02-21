# Validation

Coming soon...

The next thing that we really cannot ignore his validation right now there is no
validation on the image file field and our form. So you can literally upload
anything, any file type whenever you want. So this case we need to restrict to only
image type pings, jpegs, gifs, that kind of stuff. But normally we do this by going
into our entity class. So since ultimately we're updating an article object, we would
normally go into article and we would add some

uh,

uh, validation annotations in here. But of course the problem in this case is that
the image file is an unmapped field. So there is no image file property in article to
edit too, which is fine when you use unmapped fields, this is when you're going to
actually add the constraints right into the form via the constraints option. And
we're talking about file uploads. They are two really important constraints, one
called file and an even stronger one called image. So say new image, you get the one
from the validator constraints and that's it. That's enough right there to make sure
that the file type of uploaded is some sort of image. If you go over and look for
Symfony image and Google for Symfony image constraints,

okay.

You'll find the docs and talk all about this. So you can see the image constraint
works exactly like the file constraint file constraints, actually a parent type. So
basically you get everything that you get from this file constraint, which means you
can set in Max size, um, and configure different mime types.

Yeah.

But the image is really awesome because it is preconfigured to work with only image
mime types. So it's going to make sure the only images are uploaded, plus it adds a
whole bunch of other things and men with Matt Max width if we needed to make sure
that it was exactly a certain type of image. So basically this is just going to work
for, go over here. I'm gonna refresh just to make sure everything is fresh. Browse.
I've sneaked snuck the Symfony best practices pdf into this page here. Let's select
that update and boom, this file is not a valid image. So another thing you'll
probably we'd probably want to do is, um,

yeah,

I'm going to click down to back to the file type is if again this is the parent type
is configured the Max size cause we don't want allow any upload size. So I'm going to
pass an option image and let's say Max size and Fred and I'm actually gonna set this
to five kilobytes. That's way too small. But I want to be able to see the air Sophie
go back right now it's browse for any of our files. They're all bigger than that. It
update and perfect the file is too large.

So now I'll change this back to five megabytes. All right, but you remember last time
when we, in the last episode we tried to upload the stars and the stars is three
megabytes that is below our five megabytes maximum, but it is above our PHP to Ini
setting. So we actually, and we got a really nasty air when we saw that. One of the
cool things is as soon as you use the image or the file constraint, if you do try to
upload some events above your piece with the Ini setting, it's going to catch that
with a validation error. It's a little easier to debug. And now we know that we
should maybe change this, uh, that Max upload size setting, I'm a little bit higher.
So that's it. There's lots of other things you can configure in here. Um, you can get
in including the, um, and the air messages, but it's fairly straight forward. Number
one tricky thing is making this field required. So let's say that when you create an
article, you must upload an image file. So what you might think as we, you might
think, okay, we'll go into this constraints here and we will add a new, not, not
right now image file, this image file field's always going to be required and that
will not work. The problem with doing that is it would make this image file upload
field be required always. So even if I were editing an article and there were already
an image file, it would still make this image file required.

So another thing you might think of,

okay,

so this is a little bit tricky. We basically want the image file to be required, but
only if the article doesn't already have an image file names. So if the article has
no image file name, then the article image, the image file field is required. So
here's how we're going to do this. I'm actually going to break us on a multiple lines
here and set the constraints to a new construct. Image constraints, variable image
constraints equals, and then I'm going to copy the new image and then down for our
main constraints option, we'll just use constraints, image constraints. Oh and that's
actually spell that correctly.

Now we can conditionally Abby, not no constraint in here if we need to. So in our
forms episode, if we scroll up a little bit, you can see we actually use the data
option past to here to get the article object that this form is bound to. Now if it's
a new form, there might not be an article object. So this is going to be an article,
object or null. I also use that to create an is edit variable and figure out if we're
on the edit screen or not. And we used that a few places down here to do different
things and we can leverage that again by saying if this is not the edit page, so this
is the new form, then we basically want to add, and not in all, but I'm going to add
one more edge case. You're just in case I'm gonna say if it's not edit or not article
here, I'll get image file name.

Then we definitely need to say image constraints and we needed to add new, not null.
And let's even customize the message here to please upload an image. So just saying
if not his edit is probably enough because by adding that every single a article on
edit screen, we'll have an image file name. Um, but I'm just being extra safe here in
case somehow an article that created a, without an image, it will now be required.
So, yeah, that's it. So if we go back here and let's actually just refresh the entire
form, we'll leave it blank right now. We know behind the scenes that this article
already has an image hit update. That works just fine. But if we go back,

yeah.

And create a new article, fill on a few of the

okay.

Required fields, and create, boom. There we go. Please upload an image. All right,
next, let's talk about something else.