# Flysystem Defensive

Coming soon...

There's a few minor problems with our new fly system integration that I want to clean
up. The first one is that file get contents eats memory. It actually reads all of
these contents into your PHP memory. So it's probably not that big of a deal for a
two megabyte file or four megabyte file. But if you eventually start uploading he
quite big files, this could actually cause your phd process to run out of memory.

Yeah.

So for that reason in general, when do you use fly system instead of using methods
like right or update, which is the edit we're going to use right stream.

It's pretty similar insistence that have pacifier, we can't, that's your pass a file
stream, which we can create with stream = f open file, Arrow, get path name and then
this case we just need to read it. So we do the our flag, then we pass it the stream
down here. That's it. Just about as simple and now if I'm now it's not going to read
that in a memory. And then just in case here we're going to close the resource. So if
there, if that stream is still a resource we're going to f close it and annoying
thing you need to worry about with the file streams and you need to do is resource
here because depending on the adapter, the resource may or may not have been closed
already.

Okay.

The next thing, it's not really related to fly system but let's go back to /admin.
Slash. Article. Actually let's test this first. We'll need to log back in and let's
find an article image. Perfect eyebrows for our astronauts update and perfect it
works. So the thing is is that we actually need to, when we do it, I just did and we
just updated that file. What we really need to do is make sure that we delete the old
file.

We don't want that old file sticking around it anymore. So to do that on upload
article image, I'm going to add a second argument hint here, which is going to be a
noble string called existing file name is the idea here is you can pass it, they new
file object. And if there's an existing file name, you'll pass it right here. And
this function can take care of bleeding. It needs to be nullable because sometimes
there is not a um, existing file name. Then at the bottom it's pretty simple. We can
say if the existing file name was passed, then this->filesystem error delete. And we
need to pass it the path to that. So it'd be self arthel image existing file name.
And that's it. So right now you can see our astronaut, that jpeg right there. Let's
go over here and this time let's grab it. Rocket Update. You can say or ask them not
to a jpeg right there.

Okay,

we'll go over, I'm going to go over and we will do article admin controller and we're
gonna need to pass that argument. We need to pass a both places. So this is edit
here. So an edit and we're going to pass it article, Arrow, get image file name. And
you don't technically need to do it in new, we could actually just pass null cause
there's no way there's going to be one. But I'll just pass article. I'll get image
file name. Um, just for consistency there. And then another place and we need to do
is our data fixtures. So here, down here we're actually gonna pass and null. In this
case we're never going to, there's not going to ever be an existing one, so we're not
going to update it.

Okay.

All right, so let's try that again. We have our astronaut here. So let's go back
here. We'll upload rocket this time

update

and there's a rocket and yes, our astronaut is gone. All right, so this created also
created one small edge case problem, um, which is in theory in a perfect system. This
file, the existing file is always going to be there. And so there's always going to
be a file to delete. But when you're developing locally or maybe something weird
happens on production, let's pretend that the file, the existing file is not there
for some reason, so I'm actually going to empty my uploads directory entirely. When I
refresh the form. Actually the uh, this image still shows because the thumbnail is
still there, but the original image is actually gone. Okay, no problem. Let's
actually grab earth that jpeg it update and it fails and you'd see it fails on that.
This->filesystem air would delete line. Now this may be the behavior you want, you
want, you may want to say, hey, if something so weird happens that the old file names
not there for some reason I want the whole process to explode when they update the
new one. But I just want to do something slightly different. I think if the old file
does not exist for some reason, it shouldn't make the entire process explode.

So you can see the air that we get here is a file not found exception from league fly
system. So we can do instead of uploader helper is, I'm going to wrap this in a try.
Catch. We're gonna catch that file, not found exception. The one from the flies, a
flash system.

Okay.

And that's going to fix that. However, I do not like doing this. Anytime you have a
try and nothing in the catch, Ooh, that's not a good situation. One of the benefits
of throwing exceptions is that on our sites, we always configure the exceptions to
lock to a slack channel. So we can see when things are going wrong. If we swallow
this air, we might not be a good thing. I kind of want to know that something is not
right with our system. So to fix that, I basically want to do what I call soft
failure. I don't want the exception to be thrown, but I want to be notified about it.
So at the top here, I'm going to auto wire the logger interface. I'll hit option,
enter, enter, go to initialize fields to create that property and set it. And then
down here we'll say this->logger->alert, which is one of the highest levels I usually
a log alert and higher to my slack channel. It will say old uploaded file percent s
was missing one. Trying to delete, I'll pass the existing file name. That way we know
that there's some sort of a problem you want to look into. Files are missing for some
reason, but it's not going to kill the whole process.

Yeah,

snack no over here. I'll repost that. Perfect. It works. New files there and um, and
we actually got a log message. Again, if you configure it out, production to a log to
slack, then we get something in our slack channel. A really cool thing with the
Symfony web server, you can actually see all the logs being streamed right here. So
if you scroll up here, you can actually see alert, old upload a file rocket was
messing and trying to delete so you can actually see that uploaded. They're written,
they're all right. The last thing is about um, coding defensively. One of the ways
that one of the things that you need to know about fly system if you hold command or
control and hit right stream here is that if something fails, it,

sometimes an exception is thrown, sometimes not. So in this case, this will throw an
exception. If the resource we path is not, uh, is not a valid handle or if the file
already exists then it will throw an exception. But for anything else, anything that
goes wrong like a network error, it may or may not throw an exception. You can see
what it actually does. Is a boule true on success? False on failure and exception may
be thrown. Like for example if you're using, if you're running two s three, the s
three adapter itself might throw an exception but it might not. So basically what I'm
saying is that if right, if any of these methods fail, um, and exception not be
thrown, it might just return false. For that reason, I recommend coding a little
defensively, which means sending this to our result variable and saying if results is
exactly equal to false, then we're going to throw a new exception that says could not
write uploaded file percent s we'll pass out the new file name. I'm going to copy
that cause I'm gonna do the same thing down here on the delete. You know, if the file
is not there, you'll get this exception of if something else went wrong, we might
want to know about that.

So resulting cause false will throw an exception that says,

okay,

did not delete old uploaded file percent ass. And then we'll do the existing file
name right there. Now again, if you don't want to, you might not want to throw the
exception here. You might want to actually log another alert so that this doesn't
fail catastrophically. This would be a pretty unexpected air. Um, but you know, the
point is we want to make sure that we're, uh, at least notified about it versus it
just failing silently. So with all that in place, just to make sure we didn't break
anything, let's upload. Not Stars because that's too big. Earth from the moon update.
Perfect. Got It.