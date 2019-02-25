# Reordering

Coming soon...

What else could we possibly do with this area over here? What about reordering it?
This is a question I got from a lot of you, so it's not really related to uploading,
but let's cover it. So first thing I want to do is go over and we're going to add a
new field and run bin Console to make density. We're an update our article reference
entity, and we're going to add a new field called position, which we'll use to
petition the fields. It, it'll be an integer not knowing the database. We'll make it
required and that's it. The thanks to that aren't no girlfriends now has a new
position field and I'll actually send that to equal zero by default. So unless we set
the position and they'll all be the same and we don't really care about the order
that's run bin Console and make migration.

Okay,

I'll go move over and look in the source migrations directory just to make sure that
it looks, it doesn't contain anything surprising. It looks perfect. Close that and
run bin Console doctrine migrations migrate.

Excellent. All right, so for sort of all, there's lots of libraries for um, for doing
reordering, sorting kind of stuff, I'm going to use one called sortable, sortable js.
Um, it's got a lot of support for a friend and frameworks. It's just really nice,
works really well. And that's got a ton of options. We're going to use just a few of
them. Again, if were using Webpack encore, I would actually install this via yarn and
require into my file. But since we're not, I'm going to Google for it's sortable js
CDN. We're just gonna include in our page, um, click this one from js deliver. Uh,
this is a different library called of, well of course there's always 10 libraries in
JavaScript, but the same name.

Yeah.

And we just need a JavaScript file in this case. So I'm going to click here. I'm
going to say copy html plus Sri. Then we move over to our edit template and we will
scroll down to our JavaScripts and we'll just paste that in there. Excellent. So we
now have sortable js, which gives us a global sortable object.

Yeah.

Do you use this in our admin article? A form. Let's scroll up to my constructor. So
the idea is that we're going to make each of the elements here, I sort of will, and
as we drag and drop them when he finished dragging and dropping them, we'll send an
Ajax request to record the new order. So over here we can say

this.sortable I'm going to store the instance of the sort of on a property because
I'll need it later. = sortable dot create, which is how you create this thing. And
then we need to pass it the kind of parent element that we want to attach this to. So
in our case, we actually need to attach this to the u l element that is around
everything. And this happens to be this, that element. So I can say this.element, and
this actually wants a dom node, not the jquery objects. So we'll use left square
bracket zero.

And when this refresh, and that should be enough, grab anywhere around here. And
Yeah, look, when you're ordering, it's not saving anywhere or anything like that, but
it is set up. So let's actually add a few options to make this little bit nicer. So
we're going to pass a second argument here, which is going to be an array of options.
And the first one to do is actually at a drag handle. So dot drag dash handle,
instead of being able to grab anywhere, we're going to make a little icon that you
can drag on. And then down in render, let's actually add that. So right, four inputs.
I'm going to add a span class = drag dash handle, and then also give an FAA and FAA
dash dash and reorder.

And also while I'm here, I'm gonna go back up to my options and I'm going to add an
animation one 50. This is going to make a little animation. You'll see things move
around. It's just a little bit smoother. All right? So there's a little drag thing
and yeah, you can see kind of moving around in there as we do it. Awesome. And one
last little thing to make this really nice is um, I'm going to go into our public
directory, public CSS. We have a styles dot CSS, which is a global styles for entire
site. Just to keep things simple. I'm going to add a couple of styles in here. I'll
add a little section here that says that this is for sortable and then I'll just add
some styles quickly here. Sort of a ghost is an element as a class that's given to
the element. As you're dragging it, you'll see that it makes it a little more obvious
what's going on. And then we'll give the drag handle a cursor of grab.

Cool.

It's now try and refresh

and

you could see the, well that's not actually working with them to do a force refresh.
There we go. Didn't show from Indiana force refresh there. You can see the kind of
blue background as we drag it around. That's excellent. All right, so now our goal is
we need to actually make this talk to the server. So first of all, make things a
little more interesting. I'm going to, this is wonderful. Check this out, I'm going
to upload all of these files. Oh, how nice is that? And of course one did fail down
here because it's a one valence, the wrong type and another fail cause it's too big
and we have really nice errors. So it's not cool. So right now we have a lot more
things to work with. So when we finished dragging, we're gonna want to send it an
Ajax request to, uh, make that happen. We can add an option called on end,

and we're going to pass this an->function. And here we're gonna say console dot log
this.sortable so reason, the instance of sortable that to array. This is Amir, really
cool function for us. So check this out, refresh the page. Straggle one of these and
go over and look at our console and won't look at this. See those, those are the
ideas of these article references. So you can try it again, sort of this one up. And
you're, so if you're on a wait, and this is the idea is in the correct order. If
you're wondering where's it getting the ids from? Um, because each, each element
that's being reordered is this Loi. And because we put a data dash id attribute onto
it, the sort of a library knows to use that as the ID. So that's actually what it's
using it. And it's the ID. And this is wonderful because we can send this up to the
server and very easily use that information to update the database with the story. So
that's what we're gonna do next is we just need to create an end point for that. So
in article references avenue controller,

find the download article reference, uh, you may have not have noticed it, but um,
about half of them methods and this controller, um, have an id that has the article
reference and African and kind of keeping these at the bottom. And then half of them
actually have the, uh, are different end points for the ID is actually the article
you're doing an operation, the article, this is gonna be an operation on the article.
We're going to be, uh, sending a post request up, sending the article Id, sending the
ideas of all the references and then at work reordered them. So I'm actually going to
copy this entire endpoint that for getting the article references, we'll change the
name to reorder article references and we're going to say /reorder on the URL. Make
this a method = post. And for the name we'll say admin article, reorder references.
This is not a very restful endpoint. Having this reorder here is not very restful.
Sorry. Uh, you know, rewarding was kind of a weird end point and yeah, that's a good
start.

Okay,

next to read the, so here's the idea. We're going to send a uh, JSON by to this end
point, but that JSON Body is just going to hold an array of the, is, it's basically
going to be just this array. So we just need to decode it to get ideas out in the
correct order. So we're going to need the request object so we can get three that
data. And then we're also going to need the entity manager interface so that we can
ultimately save all the new values to the database.

Yeah.

And this time I don't keep it very simple. I'm not going to use the serializer. I'm
just going to say quartered ids.

Okay.

= JSON Decode and I'll pass that request.->get body gets content and then true, so
returns that as an associative array and that if order ID use = false. Exactly that
problem. It means that there was some serve an air. Sign this and return this->JSON.
Okay, and how about just detail invalid body and 400 I'm not going to be handling
that in any way and that means

right?

Did this cause it shouldn't actually happen in the wild. All right then to actually
update the things here, I'm going to say ordered ids = array flip ordered ids and
I'll put a little comment above that to explain it. This is going to take us from
kind of a position equal arrows id because you know this will have a zero or the in
the index is will be zero one, two, three, four pointing at the ID.

Okay,

I look over here. This should be the zero index, the one index, the two index, and
we're going to flip that to id, equal arrow. Then new position it should have. Okay.
Now we can say for each,

okay,

Article Arrow, get article references as reference. And here we can say
reference->set position. And here we say more good ideas, left square bracket and we
can use reference->get id to look up the new position. And yes, you could code a
little more defensive we hear, especially if this is going to be a public API, people
could send up, uh, like you know, um, bad ids or something like that. And you would
want to check for that and send back some sort of an error response. That's totally
up to you. But since this is ours, I'm just going to be a little bit lazy here and
for dominance and on to say on entity manager->flush. All right, so would that
blessed, let's hook up the JavaScript for this, right? So an article, an article
form,

okay,

go back up to the top in our on end. And here we're just going to send an Ajax
request. Dollar sign dot Ajax.

All right.

And for the URL we can actually use, you might remember that, um, the u l elements
actually has a data dash and you were out on it.

Okay.

Which is the path to admin article list references. I'll show you what that looks
like. So this is basically the, you were out to get all the references for a specific
article we use that you were l in our case and added /reorder onto it so we can hard
code that that entire you were out if you want to, that's fine. I'm actually going to
reuse part of this. I'm gonna say this, that element, that data that you were l and
I'm going to say plus /reorder. If you create kind of consistent, you were as these
are nice things that you can take. Advantage of. Method is going to be post.

Okay.

And data is going to be JSON that string a fi and it's going to be that same thing we
had before this. Dot. Sortable dot. To Array.

Yeah.

All right, let's try that out and makeover refresh.

Okay. Everything looks good. Astronaut Dash 41 let's move him down to position three
and okay, may 200 status go. That's a good science. Let's refresh and see if it
worked. Oh, it's right back on top. But actually I think this did work in the
database. The reason this is on top is remember this, look, this list loads by making
an Ajax request, uh, to an end point to get all of the references first for a
specific article. That end point. If you look in our controller, that's the right
here, that's called article references in the way it creates that JSON is his
article->get article references. The problem is that this method doesn't know that it
needs to order itself by the position field. So go open your article, Penh city and
we can go above article references in, we can say at or am ordered by clearly varies
curly brace position = a s c that's not going to change the order of the end point
and there you got to look at astronaut that's Jpeg is now first position and we'll
see that one. Refresh this page.

Okay,

boom. Astronaut, not national ones. Let's move him all the way down. Move them down a
little bit further. Well, I'll move best practices up. Astronauts now on the bottom.
Refresh. Same order. There you go. Reordering. Simple, beautiful works. Awesome.