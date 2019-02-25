# Delete File

Coming soon...

All right. I think the next missing thing with our cool widget over here is the
ability to delete some of these items. So let's work on that. This is actually Kinda
cool cause what we're doing here is a little bit of file uploading but also we're
sort of creating an API for our article reference. We already have the ability to,
you know, get all references for specific article. We have the ability to download
them and now we're going to need the ability to delete one. Some are kind of new
public function at the bottom called the wheat article. Reference the at route
[inaudible].

Okay,

well mate, the URL /admin /article /or references /curly brace, um, id. So I'm kind
of keeping consistent with what we're using for everything else when you're talking
about a single reference. Uh, in this case I'm going to say name = admin article,
delete references. And a key thing here is I need methods = delete this time. We
don't want the to allow anyone to make a get request to see where all, because if
you're building an API, if you make a get request to see, well that should probably
return the JSON for that swan specific reference. Uh, inside, I'll type in the
argument article, reference a reference, and then we'll do our normal security thing
that we did before. Actually copy it from above. So give the article and then deny
access unless we have that. All right, cool. So how can we, let's talk about
uploading. How can we delete the file? What we've done this once before and upload
helper, go do the service and upload or helper. We have functions for uploading
different types of images. We have functions for getting the public path or reading a
stream. So it's going to create another great utility function, um, for deleting a
file. In fact, I'm going to copy of the reeds stream function up here, rename that to
delete file.

It's won't return anything cause it's just going to delete the file. We'll start the
same way. We'll grab whichever filesystem we need.

Okay.

And they'll say results = filesystem, air delete, pass of the path. And then to code
defensively we'll say if that result is false, then we're going to throw a new
exception with air deleting, present s and then the path. And we don't need to return
to anything on the BOP. That nice. So back on our controller and we're going to add
an upload or helper arguments and we're also going to need to delete this, uh, our
reference from the database. So let's go ahead and add the entity manager interface
argument as well. So first delete it from the database and city manager->persist, not
persist and to manager, Arrow, remove reference entity manager, aero flush and then

uploader Halpert Carol, delete file reference Arrow, get file path. And then we need
to pass false so that it uses the private filesystem. Now one thing we noticed here
is that, uh, in the real world we could, the removing of the reference could fail for
some reason, you know, even a database issue. But also this up, this delete file
could fail. For some reason, especially if we have our files stored on the cloud. So
you could end up with a situation where the file gets deleted, the rogue, it's
deleted, the file doesn't get deleted. If you change the order, you could end up with
a situation when the file gets deleted, but the road doesn't get deleted. So if
you're, if you're worried about this, you're gonna want to do is actually use a
doctrine transaction and wrap this entire section and the doctor transaction. Make
sure that the deleting the file was fully successful and then commit the transaction.
If there was an exception, you can roll back to transactions so that the file is not
deleted and the row is not deleted at the bottom. What are we gonna Return? Well, we
don't really need to return anything, so it's pretty common on delete end points to
return a new response. The one from HDD foundation with null as the content and a two
or four status code to a four literally means the operation was successful, but we
have no content to return.

There we go. That is a beautiful end point. So let's get into our JavaScript to
actually make this happen. So first down in the render function, we're going to add a
nother um, link over here, kind of a little trash icon to delete this.

MMM.

I'm going to make this a button because it's going to need to be a delete request. So
it's going to, it's not going to be just something that we can click. I'm going to
give it a class jazz dash reference that the wheat so we can find it.

Okay.

And then I'll give it a couple of classes like Btn, Btn Dash Link. Inside the button,
we'll use a font. Awesome. Again, we'll say FAA, FAA dash trash.

Perfect. Okay,

well copied this js reference delete because we're not using a front end framework
like react. We need to kind of bind our, our, um, listeners manually. So Up and
construct. I'll say this, that element

not on click. And then I'll pass

dot js referenced delete. This is called a delegate selector. It's really handy
because it basically means that, um, even if elements are added to the html, after we
register this listener, those new elements, we'll still get triggered, this listener.

And then I'm actually

going to, I'll use a new yes six->function so that the, this variable inside of here
is actually this object. And I'll call a new function called this.handle reference.
Delete. And I'll pass it event. So then I'll copy that function name. We're going on
here and add a new handle referenced, delete it. We'll get into vet object. Um,
perfect. Surgeons out of here is two things. We need to actually make the Ajax
request to delete the item from the uh, the server. And we also need to remove the
reference from the references are right in rerender so it actually disappears in the
rope. So the first thing I'd do is say const l y and I'm actually going to use the
button to find the l I element and you'll see why in a second. So constant ally = the
our sign open parentheses, event dot current target that will return us the element
that is the button itself. And then I'll say dot closest and I'll hear, I'll say it,
list dash group dash items. So that's a way of saying, hey, go up and find my, go up
my parental tree until I find that list group item.

Okay,

next I want to actually get the ID of thus specific, um,

reference that was just deleted. So to do that, I'm actually going to add any new
data dash ID attribute on my l I Adam. This is going to be handy in general because
it means no matter what, whenever we clicked any button, we can find the Lli and then
we can get the reference id from that. So I use [inaudible] vocal concurrently close
curly. That's how you print things when you're in a string interpolation. And I'll
say reference that Id. So now this is why we got the ally up here. We can say const
ID = a dollar sign, an Loi, that data id. And then finally I'll say, I'll hear, I'll
say l I n. Dot. Add class disabled. Um, so that basically it looks like we're
deleting it while the Ajax calls. Finishing down here, we'll just make an Ajax call.
So I send the Ajax, the URL I'm going to, once again, I'm just hard for the URL
preferences. /admin /article says references /and plus Id. We're not the method here.
It needs to be delete. And then that, then I'll pass another inline function,
a->function, and inside of here, now that it's been deleted from the server, we're
going to actually remove it from um, the remove this reference. We need to remove
this reference from our references array. So we need to basically find which
referenced in there has these specific id that was just deleted and remove it. Really
Nice Way to do this is to say this.references = this.references dot filter and pass
this a

Arrow function that will receive a reference argument. And inside return reference
reference.id does not equal equal id. So what's going to happen here is, um,

this callback function will be called once for each item in the array. And if the
function returns true, that item will be put into the references. If it returns
false, it won't be. So basically it's going to make sure it's gonna make that it's
gonna make it so that this not references contains every single item in the array
except for the one whose id it was just delete it. I know a little bit complex. This
is one of the trickier things in JavaScript. And then down here, read, say this, not
render. It's not going to rerender without that reference. Inside of there. Again,
this is very similar to what you're doing. React, react as much more powerful than
this, but this should get, it was working. Phew. So let's try it. Refresh the page
and cool. There's our delete icon. It looks a little weird. We'll fix that in a
second. And let's see here. We have rocket dot jpeg.

So before we even tried downloading a deleting this, let's go to our Var directory.
Okay. So you can see there's our rocket that jpeg. Um, there. So let's delete that
disappeared. The delete requests. Yeah, two minutes, two or four status code that
looks good and asked the rocket file is gone. So that just worked really, really
nicely. All right to really make this a look nice and let's fix this alignment issue.
This is just kind of a styling thing, but you know what this to look good. So down
here inside the render function, I'm just going to add a couple of classes on my
first anchor tag on to turn this into a button link as well. That's most and MSA btn
dash s m them to add btn dash s m also to my other one that's going to fix most of
the alignment issues right there. There's still a tea. This one is still a touch off
and um, that's kind of subtle way to fix that is actually to add a style on the icon.
This says vertical align middle, right? And that whale should pop it. Justin place.
Yep. Just a little bit better than ice and now we can delete all kinds of stuff.

Awesome.

And Boom, they're just getting deleted over here. On the server suite. Next, let's
talk about allowing the file name to be edited.