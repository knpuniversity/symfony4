# Flysystem

Coming soon...

Right now our entire upload system is very filesystem based. Like if we want it to
move to the, to some sort of cloud storage, it's not really set up to do that
particularly a file error move. That's something that moves things physically on your
filesystem. So one of my favorite tools and I think it's under utilized in our
community, is a library called fly system.

Okay.

We actually have a, I'm the creator of this Frank de young who helped us without
reacting tutorial. He actually spoke at Symfony con last year and we have his
presentation up on Symfony. Cas Fly system is just an eps filesystem abstraction for
PHP. So it gives you like a nice service object you can use and you can just write
files or read files. And then behind the scenes you can swap out whether you want to
use a local filesystem or s three or some other way. So it gives you an easy way to
uh, work with the filesystem when they filesystem could actually be local cloud or
who knows what else.

Okay.

So it's a great way to set up your upload system so that you can actually make that
change for you easily. So in the Symfony community, the way we installed this,
usually it's via the excellent one up fly system bundle. So I'm going to go into
that. Let's actually click into their documentation and we will copy the bundle name,
move over and run composer require the bundle name.

Okay.

And the way the library works, and in fact the way the bundle in general works is
that you need to do two things. First you're going to set up something called an
adapter. It's kind of a low level object. You give it a nickname, like my adapter.
And then this key here is important. This is going to be basic and adaptive that
stores things on the local filesystem. So if you use s three this would be s three
and you can see all those different things. When you click in there. See this nominee
adapter is AWS three v three so you set up an adaptive says, I'm going to start
things locally and here's where I want to store them. And then the real star is the
filesystems. This great summit called my filesystem and basically says this
filesystem uses this adapter. We'll talk about visibility private later, but
filesystem is what we're going to use directly to kind of read and write files.

Yeah.

Cool. If we move over, awesome. If finished, and the recipe for this bundle did
actually install a config packages one, apply a system with that basic setup that we
just saw and their documentation.

Yeah.

So here's, we're going to do, I'm going to change default adapter. We're going to
create an adapter and a filesystem for our uploads. So we're going to change this too
called about public uploads adapter. I'm saying public uploads because this is going
to go into the public, um, directories. This is gonna be publicly available uploads
in a little while. We're going to talk about uploads that actually need to be private
because you need to do some sort of security check. We'll change this to a kernel
that projector present and then we'll say public /uploads. That is the root of the
filesystem and they'll store everything local team relative to that. Down here I'll
call this the public uploads filesystem.

Okay,

the adapter, we'll use the public uploads adapter. Now for this alias, I want to show
you what that does first. So by creating a filesystem for your on Ben Console debug
container fly system

by creating this filesystem here, public uploads fastest. And what it means is we now
have a service called one on filesystem, public uploads filesystem filesystem. So
here's kind of the name of [inaudible] here. So this is the service that we're going
to use. It also creates an alias League fly system filesystem, which is actually an
alias to our service and that makes it, that makes it auto wire bubble. I'm actually
going to remove that and the reason is, and just, and you'll see how we're going to
handle that in a second. Um, you can keep that up to make sense to keep that on your
main filesystem. But if you have many filesystems, then there's a better way to
handle it. I'll explain that better later.

Okay.

All right, so now I'd upload or helper, which is what handles all of our uploaded
logic. Instead of passing the uploads path here, which we would use to figure out how
to store stuff, we're now going to change this to be filesystem interface. The one
from the fly system library filesystem and we'll use this down here. I'll rename the
property to filesystem. Perfect. And basically down here instead of file era move, we
can say this->filesystem Arrow, right.

Okay.

And there are a number of different methods in the filesystem for creating files or
editing files. And here we're going to say self corn article image, that /new file
name. And then the second argument is the contents will say file get contents and
we'll say file Arrow, get past name. This file object has tons of different methods
on it for like getting the file name, the path, name, the path. Honestly I get to
look confused, which one is which path name is the absolute file on the filesystem.

[inaudible]

and then up here and we can now get rid of this destination variable that's not being
used anymore. So because our filesystem, it's root is public health /uploads. The
only thing that we need to pass it is just the a path within there. So article, image
/and then the new file name. So that works really well. All right, so let's clear.
Let's try this out and I'm going to clear out our uploads directory again.

Okay.

And now that we have our fixtures work, it's really easy to test this. We just go
over here and run doctrine fixtures, load and oh, it does not work. Unused binding
uploads path in service. Unique, unique user validator. This is actually a bad error
message. The first path is actually um, a useful.

So a second ago we, our argument here is called uploads path. And if you go into your
config services.yaml we actually have that as a bind. So basically I would do that
earlier. So we can use this argument. When you do something as bind, it needs to be
used somewhere in the system. If it's not used anywhere in the system, you get this
air and it's just a way Symfonys way of saying, hey, you're binding this variable
name but then you literally are never using that somewhere. That could be a bug in
your system. So I'm actually going to remove that and then get a different air, which
is that we cannot auto wire service uploader help her argument filesystem of that
construct references filesystem interface. But no such service exists. Um, so this
could be fixed in one of two different ways. One, we could rehab that alias, uh, here
and that would fix that. But I want to do it a different way. I'm actually, once
again, I'm gonna use the bind functionality and I'm planning ahead cause I'm going to
have multiple filesystems in the future so I don't want to make one of them the main
one. So I do this, I'm actually going to rename the argument here to public upload
filesystem

and then I'm going to bind,

okay

public upload filesystem to the actual service ID that I want. And you can actually
see it in this air here. It kind of suggests that there are a couple of different
service ids. This is the one we want. This is the one that we saw in our debug auto
wiring a second ago. So here we can say at, and then that service id, so now anytime
we use that argument in an auto wiring spot, it's going to pass this, that filesystem
in. Then you locked. Now it should load cool no errors. Check our public uploads
directory, boom, we have files in one to refresh the homepage. And we are good. So a
small tweak here and now, um, there's a few other things that we need to worry about
with fly system, but we are now more agnostics and easier for us to change to the
cloud.