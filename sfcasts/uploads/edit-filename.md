# Edit Filename

Coming soon...

Okay. What else do we want to do with to making these references fancier? What about
updating that? Okay, well I'm not going to allow the user to update the actual
attached file. If you need to up attach different file, just delete astronaut Dot
Jpeg and upload a different one. But they could change this file name. Remember this
is the original file name. If they uploaded astronaut that Jpeg, they might wanna be
able to change that to something else. Um, no, let's make that possible.

Okay.

Again, thinking about our article reference in terms of an API, we have an end point
here to delete and article reference. This would be an API end point to edit an
article reference except that the only field they're probably going to be editing is
the original file name.

Yeah,

so copied the beginning of our delete end point. Paste it down here, close it up.
We'll call this update article reference and we'll keep the same URL. We'll change
the route name too. Adam, an article update reference. It shouldn't be references, it
should be reference. I'll fix that in both places. I don't think I'm referencing that
anywhere. And then instead of methods = delete, we'll say methods = put.

Cool. Now this case, when we, if we think about the way this end point is going to
work hard in our API is the client, our JavaScript is going to send a JSON Body that
contains the data that should be updated on the article reference. Of course in this
example, the data's only gonna have one field original file name. But in theory there
could be other fields that we want to allow the user to edit. So, so far we've been
using, uh, we've been using this error, JSON, to turn an object or multiple objects
into JSON that uses Symfony serializer behind the scenes. Now we're going to use the
Siri laser to do the opposite. We're going to take some JSON and turn that into an
article reference object that's actually pretty sweet. So first we need to another
argument here. Serializer interface serializer so we can get Symfony serializer and
also going to need the request object, the one from HD to foundation.

So we can read the raw, uh, JSON Body off of the request. Not, this is really cool.
We can say Siri, Lazur, Arrow,->de serialize. It just has these two methods. And to
this we're going to pass it the first argument, which is the actual data that will be
request->gets content. We're going to read the raw JSON body off of the request
second, and we're going to tell what type of object that should be serialized into.
It should be an article reference ::class in third grade to tell him what format,
what to expect. This is gonna be JSON. You can technically DC real from XML or other
formats. The last thing we do is pass it some options, some context. Um, by default,
what de serialize does is actually creates a new article reference object. We don't
want it to do that.

We actually want to update our existing reference, so we're going to pass it in
option called object to populate Or said that to reference and that will update that
existing object. The other thing I'm gonna do is when we've been serializing we've
been passing this group's option which tells it only to add, um, only to turn
whenever properties have this main group. Only to put that into JSON to skip other
properties that don't have that. We can do the same thing on input. We don't want it
to allow the user to update the internal file name or the ID or anything like that.
We only want, I want to allow them to update the original file name. So I'm actually
going to turn this into an array and give original file name, a second group called
input.

And then in our controller way back down here, we can add a group's option here
called input. So if any other fields or past, they're just going to be ignored only
the properties with the input group, which is only our original file name, are going
to be allowed to uh, update. And that's it. We're going to worry about, we do need to
worry about validation. We'll cover that in a second. Right now we can say entity
manager,->persist, um, reference. And we technically don't need to do that because
it's not a new object. But I'll do it anyways and see magic or flush and then we'll
say re more return. And then typically after you edit, uh, a resource and an API,
you'll return that resource again. So I'm actually going to scroll all the way up
here to our, this is our, um, up upload and point. And I'm going to steal this entire
JSON thing. If you wanted to, you could create some private functions on here so
you're not repeating this long JSON thing over and over again. And then we will say
reference and change this to 200, cause we're not creating that resource, we're just
updating it.

All right? So that end point should be good. Let's look over in our JavaScript.

Okay.

So the way I'm going to make this work is, um, I could make it fancier. I don't want
to get too much in JavaScript. We're just going to turn this printed string into an
input box and then on blur. So when I click off of it, it will make the Ajax request
and it'll save it. So I'm gonna copy this string here. I'm going to say input type =
text and we'll say value = and we'll print the original file name. Let me get a
couple of classes. We'll say form dash control from bootstrap and then js dash edit
dash file name. Ah, that's the classroom. And using a second to target this with some
behavior. And then also a little another styling thing. I'm just saying with auto on
that it's gonna make it just print a little bit better. Perfect. All right, let's
copy that js file name. We're going to do the same thing we did with our delete end
point, which is that up in our constructor, we're going to attach a behavior to that.
So this.element that on blur this time of js edit file name, then call our->function
and inside, and we'll call it new function called handle

reference edits. File name. We'll pass it the event.

Okay.

Oh, copy that. Go down. And we will create that function. Take an event object. And
actually I'll steal first two lines from handle referenced elite because we're going
to just start the exact same way. We're actually going to be making an Ajax end
point, a call to the same URL just with a put method instead of a delete method.

So we're also going to need the ID down here. Now the first one we need to know is,
um, we're ultimately to do is set an Ajax request up with that original file name
data. But I want you to kind of pretend for a second that we're allowing many fields
to be updated on our reference. So more abstractly, what we were really wanting to do
is find the one reference object inside of our references array that is currently
being updated. Change some data on it, changed the original filing date on it, and
then JSON and code and send that entire reference up to the end point. It doesn't
make total sense. You'll see, uh, what I'm doing here, I'm actually just doing things
a little bit more complex, but it's going to be a little bit more flexible. So first
I'm going to find the exact reference object that we're working with right now by
saying constant reference = this. Dot. References dot find and this will pass
a->function. Well, we passed a reference arguments and we'll say return reference.

Okay.

= = = id. So this basically will loop over all the references and it will return the
first one and finds that matches this idea, which should only be wrong. Then I'm
going to change some data on that, but change the original file name to me, a dollar
sign, open parentheses event, that current target, that will give me the input file
input field down there. And then finally we can just make an Ajax request. So I'm
going to

steel at the beginning of my Ajax call. From the method above, I'm actually going to
remove the dot then let me same, uh, you were l the method would be put and for the
data, just past reference, that's not actually, there's a small problem with this,
but the idea is we're just going to set up all the fields. Yes, this will set up more
fields than we need, but we've set up our end point to just ignore any extra fields.
So that's okay. All right, so let's go back and try this. Refresh the whole page. Oh,
I have a little extra less than sign. And there I have a feeling I messed something
up and my render, let's scroll down and find that. But there it is. That looks
better. And I'll just put a dash one one click over here and Ooh, it explodes. CanNot
set property original finally of undefined. So if we look back over here, this is not
working correctly.

Let's move over. And it's not finding this reference. Oh Duh. Return referenced.id =
= Id to find that one reference. All right, we'll try that again. Refresh what? Eight
dash one on here. Hit Tab and a 500 error this time. So I'm going going up in a hold
command or control the openness in the new tab and syntax error. And look, it's
coming from the JSON d code area and he'd say, look at serialized de serialize. Here
is the data is being passed. So it happened here. I do this all the time is that um,
when you make an Ajax call, if you just pass the date of reference, it's actually
going to send that up, not as JSON, but actually has this strange little string,
which is how forms submit. So we're gonna say JSON, that string of fi reference and
see if that helps things. That's actually what we want. So refresh this page again
dude. Dash one hit tab and no airs. If you go to the network tab. Yeah 200 and look,
you can see the response that we get back has the updated file name and just want to
show you if you look down in the headers for the request headers, the request to body
over here. Yeah, you can see it is actually sending JSON Up to that end point. So
that is awesome.

The last thing I want to do before I move on is we do need to have some file
validation on this because right now I could actually just make the file name blank
and that would be totally allowed. So what we're doing here is ultimately we're
editing the article references original file. So I'm just going to ask them
annotations of off here. Let's do not blank. And let's also do it at length. And you
know this is two 45 in the database, but let's say Max = a hundred. Then inside of
our, uh, endpoint, there's no form here, so that's fine, but that's fine. We're going
to add a validator interface validator

argument. And then right after we update the object with a serializer, we'll say
violations = validator->validate, we'll pass it just they reference object. And then
if violations Aero count is greater than zero, we're just going to return this Arrow,
JSON violations 400 and that actually going to handle that in JavaScript. I'm gonna
leave that to you, how you want to handle that. If you want to highlight the elements
in the red and print the air below there. Um, that's up to you. But what leads to
make sure that it works. So if I clear that out, hit tab. Yep. There you go. 400 air
and you get back the response that looks like this. So you can handle that, for
example, um, inside of your JavaScript, if you're using jquery by doing a.fail, uh,
on here, and you can actually get that, um,

uh,

data passed back from the server and you can do whatever you want with them. All
right. Next, let's talk about actually reordering.