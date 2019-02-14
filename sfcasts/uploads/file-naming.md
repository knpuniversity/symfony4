# File Naming

Coming soon...

We're still having this form that's just submitting to this test end point. But the
point is that we're going to be uploading an article image. So the first time we need
to do is actually move this uploaded file, this file from the temporary, uh, spot on
the filesystem to its final location. So where do our article images need to be
stored? Well, the question to ask is do they need to be public or do they need to be
behind some security? And in this case article, I'm just to be published, but be
something that is always open to everyone on the Internet, which means that they need
to be moved somewhere into our document root. So somewhere inside the public
directory later we're going to talk about cloud storage, uploading the s three. But
right now we're going to assume that we're uploading directly to our server. So we're
gonna need to upload to the public directory. So how about we'll create a new
directory called public /uploads. And inside there I'm going to create a empty get
ignore file. Now, the reason I'm doing this is going to be a little bit weird. Um,
but what this allows us to do

is it allows us to, for example, if you go open over to your terminal end, open a new
terminal tab, we can now do you get ad public uploads, get ignore,

but really anything that's uploaded into the public uploads directory, I want to be
ignored from get. So what I'm gonna do is open the real good ignore file for the
project. And at the bottom we're going to ignore public /uploads. So this is a little
bit weird, but we're basically doing is we're where we want to ignore all of the
public oppose directory. But it would be nice if when I cloned the project, if that
directory at least existed, unfortunately in get, you can't add a directory to get,
you have to upload a file to get. So this getting our file here is actually just a
dummy file by adding to get ignore file and then ignoring public /upwards directory.
It's going to mean that to the public session uploads directory will always exist on
this project. But every file in it except for get ignored is going to be ignored. For
example, I just go in there and put a new file called Fu.

Okay,

we go over here and do get status. You're going to see, oh, we have this new file.
Public apples get ignore, but you don't see that new file inside of here. So that's
awesome and I'll delete that. All right, so let's get to work inside of our
controller to move file. So first thing let's do is let's actually set this to an
uploaded file variable. And we know that this get method on this, uh, unfortunately
the get method on files doesn't actually have a correct type hint. So we're gonna
help her edit out here by saying that this is an uploaded a file on that. Be careful
here. There's one from guzzle and there's one from http foundations, Symfony. You
want the one from Symfony? Remember we know it's an important file because we just
tested it in. When you upload a file, you always get this cool uploaded file object.
So one of the great things about this upload a file is it actually has this object.
It's actually has a method called the move and you just need to give it the
destination directory. So what I'll do first is I'll say destination = and we need to
get the path to our oppose directory. So we can use a little trick to do that. We can
say get parameter and get a cramp parameter called Colonel Dobb project

dirt. So that will give us the pat absolute path on our filesystem to the uh, the
root of our project, which happens to be called uploads. Then we can say dot /public
/uploads and file it down here. We'll say upload, uploaded file aero move and pass it
the destination. And actually if you hold command and look into this,

this lecture return you a file object representing the new file. So let's actually
see what that looks like on a dd this thing just so we can see what actually happens.
All right, cool. So if we go back and repost that, it's, I think work. You can say
this change to dumping a file object and it's telling us that actually moved it to
our public opposite directory. So come back over here and check it out. It did. Well
at least I think it did a cause that is a terrible filename do an ls /la on the
public uploads. Um, yeah, it looks correct. It's got about 1.8 megabytes. That's
probably it. But obviously one of the first things we need to think about is actually
giving these files a decent file name. The easiest way to do this.

Okay.

Fortunately there's a second argument to the uh, the move method, which is the name
that you want to give it. So the easiest name to give it is to call upload a file
Arrow, get client original name. This is going to be the file name that was sent with
the requests is the file. That is the, the name of the file on our computer, which
means you have to understand that the user is in full 100% control of what that file
name is.

Yeah. Okay.

So if you go over now and resubmit that yes, this time is called astronauts, that
jpeg perfect. But there are a few problems with this. Number one is security. And
we're going to talk about um, first, right now our upload form has no form of
validation. So even though we're thinking this is an image, they can update, upload
any file to our server and then it's going to be publicly accessible. So somebody
could use our site basically as a private file storage even for files with the virus.
So we're going to talk about validation also. You have no control over this, um, this
client original name that could even have the wrong file extension. So you could
validate that something looks like an image when it being uploaded, but they could
upload it with a dot. Exe extension. So that's not necessarily cool either. And the
last thing is that the file name is not going to be unique.

So if somebody else happens to upload an astronaut dot Jpeg, it's going to override
that one. So these are a number of issues that we need to work on. So first thing is,
let's talk about the uh, the uniqueness of the file name. And there are a number of
different ways to handle this, but the easiest one is to just use some sort of unique
ideas, part of the file name. So I'll use unique id and then I'll do dash and then
I'll say upload a file, get client original file name down here, and we'll just use
the new file name. Now, one thing I want to note about this, get Klein original name.
It's an edge case, but this could technically be blank. You could technically have a
situation where a user's browser sends you the file. It doesn't send you the original
file name. So if you're worried about that, you might want to have some logic to
handle fall back there. But if refresh now, okay. Better. So kind of an ugly hash on
the front of this, but we now have some uniqueness with our file names and later when
we actually started attaching this to our article, instead of just a random unique
id, we could actually use the ID of our article and they'll talk about that later as
well.

The second thing, next thing I'm going to handle is a this client or his name as I
mentioned, it could have the, it could be an image because they have the wrong file
extension like that exe. So we can do instead is actually create a new variable
called original file name and I'm gonna use PGS path Info function past this, upload
a file error, get client original name and then I'm gonna pass it the argument path
info

underscore file name. Well that's going to do is it since you're going to get the
original file name of the file being sent at, which is Ashen, that dot jpeg but
without the file extension. So this will literally just be the string astronauts.
Then down here for the file name, we can actually do something kind of cool. We can
say original file name, which will be the extension and then let's do add a little
dash year and then the unique id and then because we do want the extension, we can
change this to a dot and then we can say uploaded file, Arrow, guess extension. Now
notice there are two methods they're called guests, client extension and guests
extension. The difference between those two is that guests extension is actually
going to look at the contents of the file that were uploaded and try to guess what
type of file it is and then give you the correct extension guests. Client extension
is going to trust your user, your when your user uploads the file, your user says,
Hey, this is a jpeg of this is a p and g and then this and then it will get the
extension from that. So you don't want to use guests client extension because again
that can be something that is a spoofed by the user.

So not validating that this is an image file yet, but no matter what file they
upload, we should get the correct extension.

So for dry that now. Yup. We got Dot j peg at the end of it, which is now coming from
um, uh, that. Um, then the last thing you can do, and this is totally optional if you
want to, but if I go back, I can actually choose a different file. I noticed I have
one of my files has uppercases and spaces inside of it and if we upload that, that
doesn't call it a problem. I mean there's no problem with storing spaces on a
filesystem. You can see the uploads just fine, but if you want clean or file names
for some reason and then of course you can clean those up in. The way you can do that
is I'm actually going to use a, you were alizer class. Now notice this comes from the
GED most slugable library. So a, if you want to use this, you realize where you
actually need to install the doctrine extensions library or find a URL or somewhere
else. You realize there has a nice method. Monica, you are eyes and we can wrap our
original file name in that and it's going to make it just be a little bit cleaner.

So it's up to you. There are a lot of different things you can do with the file name,
but there are a couple of things you need to think about. Uh, like, um, making sure
the file extensions correct and making sure that it's unique, which you could also do
by putting it into a different directories. All right, next it's time to actually put
this into our form and properly start attaching this to our article object.