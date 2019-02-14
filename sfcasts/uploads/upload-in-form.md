# Upload In Form

Coming soon...

We now know what it looks like to actually physically upload a file to Symfony. We
can work with the upload a file object and we know how to move the file around. Uh,
but we need to actually start talking about how to use this in a real form we want is
this to be a field on this form. And then we need to actually save the file name of
the uploaded image to our article cause ultimately on the homepage of our site we're
rendering all the article images next to each article. So first thing, go into the
source directory and let's look at the article entity itself. So I've already set
things up to be able to handle an image file names. So you can see here there is an
image of file name field and it is a string. So the first thing to know is that your
foe, your uploaded files going to be stored somewhere on your server, on the cloud
somewhere it doesn't matter. But in the database, the only thing you're going to
store is the actual, just a file name string of, yeah,

that file.

So this form here is actually handled by source form, a article form type and an
article admin controller. If you scroll up a little bit. So here's our edit action
and you can see it using article form type. And right now this is a pretty
traditional form. It handles the request, um, it saves the article to the database,
so nothing to a incredible here. So we're going to do, here is actually in article
form type. We're going to add a new field. So we're gonna say ad we know that we have
a file called image file name. And to make an I file type, we're going to use file
type from the form system, ::class. Now there is a problem with this and may some of
you may already see it. So if we move over now and refresh, we get a huge error. The
forms that view data is expected to be an instance of class. Ah Ha Foundation file
file. But it is a string. It's not super obvious where this is coming from, but we
know that we just made this change here. So that's probably the, the the issue. So
the problem is that we now know that when he files uploaded, what you get is a
uploaded file object, not a string, but the image file name field here on article
that's a string. So there's a bit of a disconnect here.

Ah, but yes we do, we need to ultimately set this image file name string, but first
something needs to handle that uploaded file object, move the file somewhere and get
the new final image file name.

So I want you to change this to just be called image file. Now that does not exist on
our entity and a lot of times you'll see people actually create a property here and
not persistent to the database, but they'll create a property here just so that
they're allowed to have an image file, a field in their form. I don't like doing
that. Instead, I think using a trip that we talked a lot about in our forums
tutorial, which is set this to Matt false, if you're not too familiar with that,
we'll talk about it in a second. But for now, I'm going to have a new image file
field. And so I'm going to go into my edit that age Dima twig. And let's actually
remove our hardcoded form. Now we're finished with that and the form itself looks
lives in underscore, uh, form that h to twig. Sit down here. Let's do form, row,
article form that image file. So we're just going to render that row like normal.

Finally we know that this form is going to submit to back to article admin controller
edit. So instead of form is valid. When you have an unmapped field, the way that you
get at its data, it won't be on your article object, but you can say form last square
bracket and then you can say image file cause that's the name of the field in our
form. And then he could say Arrow, get data. So try that out. We can move back over
here. I'll actually hit enter here so we can refresh the form itself. There's our
image file name and let's select our astronaut again.

Okay,

hit open. Now I know. So one thing is you didn't see any changes. It doesn't look
like it actually selected it. This is actually a display bug when you use a Symfonys
bootstrap themes, so we'll talk about it later. It is attached so we can hit updates
and hello, it is our beloved uploaded file object. We know how to work with that.
That is awesome. One of the thing I want to point out real quick, if you go back and
do an inspect element,

okay,

on your form itself, check this out. The form has the enc type eagles multipart /form
data. That's nice feature of the form system because we use this form start function
to run the form tag. As soon as there is even one file upload field, it automatically
adds this attribute for you, so that's awesome. All right, so let's go back and let's
Grab Earth Dot Jpeg and upload that. All right, so now our job and the controller is
we have two jobs and our controller, first we need to move this file to the final
location and second we need to take the new file name that we started as in store
that on our image file name field. So inside article, I'm a controller. I'm going to
scroll down here and steal all of our code from our temporary upload action and then
I'll delete it.

Yeah,

up top. I'll take off my dd and we'll set this to uploaded files. Upload a file
equals, I'll do the same thing as before. I'll put that little, uh, inline
documentation and then I'm going to paste all my existing code. So we have the same
destination and we're going to get the file name of it and then move it into our
public uploads directory. I'll remove the DD and that's it. That's enough code right
there to actually move the file to its final location. So the last thing we need to
do is we actually need to store the a file name. So I'm going to do that with
article, Arrow, set image, file name.

Okay.

Set image, file name, new file name. And then of course it will save like normal. Now
one thing to realize here is this new file name. It does not contain the, this is
just the file name. It doesn't came, didn't contain any directory, doesn't contain
the word uploads or anything like that. It's just the direct, it's just the actual
file name. In fact, I'm actually going to uh, add a little article image to the end
of My uh, upload directory here so that we can, you know, eventually we will be
uploading multiple things so we can put them in multiple different directories. So
actually delete all this old stuff and let's try this.

Okay.

Speak over, refresh and it probably worked too, can't really tell, but if you go over
and look on public abstract Apple's directory, there is our earth and now I can spend
over, I'm gonna run Bin Console Doctrine, colon query, colon SQL and let's say select
star from article where id equals, let's see, the idea of this article is one and
yes, image file name has that image file name that is a really Nice functional upload
field.

Now there is one small problem and that is that. Let's say we just want to upload the
title of our article and we don't actually want to change the file. The file is fine.
We don't want to upload a new file so we go hit update and it says please select a
file right now. This contains some html five validation that says this is a required
field. If you remember from our na forms tutorial, this is just a attribute that
automatically gets put on every single field, so you have to kind of watch out for
it. The fix is to go down here and add it required

false.

Now we go back and refresh the page. If we try to leave that field, Mike, again hit
enter it works but now it says call to a member function. Get client original name.
Ah, of course for not uploading a file. Then there is no uploaded file. This is
probably no, so let's cool. That just means that we don't, if they didn't upload a
file, we don't need to move the file. We don't need to change the image. File name,
whatever file is attached currently is the one we want. So this is very simple. We
just want to wrap this all an if statement. So if there is an upload a file, then we
do all of that. Otherwise we do not have it now in refresh. Got It. All right, next
let's do something. I'm not sure what it is.