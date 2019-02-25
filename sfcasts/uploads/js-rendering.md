# Js Rendering

Coming soon...

Here's the plan. Since we're using drops on to upload things via Ajax, we're going to
make this entire section absolutely awesome. It's going to be a kick butt JavaScript
widget. One piece that's missing right now is that when we upload a new file here,
like rocket dot Jpeg, it uploads successfully, but you don't actually see it here
until we refresh. It's not, that's not really raped. So what we're going to do is we,
to do that, we're going to render this entire list client side. We're going to turn
this whole thing into a true JavaScript widget and in order to be able to render this
client side and we're going to create a new API endpoint that's going to return to us
all of the references for a specific article. So we're going to start inside of our
article reference admin controller. Let's see here. Let's create a new public
function called get article. References have the APP above this with admin /r /id /or
references. So the idea here is I'm sort of creating a, you were out here that is a,
this is normally the URL to get information about single article and this is going to
get their related references off of them. You were ELLs are not really that
important, but you're not trying to create clean stuff. We'll make this method's =
get notice. You can use the curly brace when you use that or if you just have one
method, you can leave them off, doesn't matter. And we'll say name = admin article,
list preferences.

Awesome.

So here I'm the type Pentheus article and of course we need to remember to do our
security. So when you use the APP is granted inside of here, since we have the
article arguments and say manage and we can pass the article. So that's exactly the
same thing that we've been doing in our other controllers.

Okay.

And inside we're going to do the very difficult job of return. This arrow, JSON
Article->get article references. How nice is it that, all right, so try this out.
Let's take off and a year and we'll go to references. And it explodes. Um, semantical
Eric could not find constant article. Ah, this is a Ryan air.

Okay.

Because I need to have subject = article. That's just improper annotations. There we
go. Here's the air I was expecting. A circular reference has been detected. This is
the exact same thing we saw a second ago when we tried to serialize a single article
reference. Um, it got into a circular reference cause then it's serialized the
article, the article started serialize the article preference and we fixed that by
doing only the group's main or I need to do the exact same thing here, past 200 as a
status code.

Okay.

No custom headers, but we do any of this custom groups main thing. So that one to
refresh. Perfect. Look at that. Beautiful all the information that we need to render
this client side.

So what we're gonna do is I'm not, we're, um, we're not using vue js or not using
react in this project. Those are both wonderful options. I'm going to try to keep
things somewhat simple so it's understandable for everyone. So what we're gonna do is
we're going to create a sort of a react like class inside of our JavaScript that's
going to handle rendering this area. So first an edit that .html.twig, but to find
her UL, and I'm actually going to delete all of this. We're no longer, we're no
longer going to render this on the service side. In a second you'll see a, we're
going to render all of this in JavaScript and you'll see that what I was going to
completely delete that. I'm going to add a new jazz class. So we can target this jazz
stash reference Dash List. And I'm also going to add a little data dash you well
property here and I'm gonna use path and I'm going to use that same are not linked to
this end point here. So I'll copy the new route name that we had and I'll put that
there and we'll say id article, the ID. So this is going to be useful cause I'll be
able to read this in JavaScript so I can know what the URL is to that end point.

All right, perfect. Next in admin article form above and initialize drops. Some
doesn't really matter. I'm actually going to paste in a class. So this uses the new
ess six class syntax. You knows, I have a note here. It says use Webpack encore. Um,
the class in tax is not compatible with all browsers. Basically it doesn't work on
Internet explorer. Um, if you use Webpack encore, that's not a problem because all of
this code is rewritten for you. I'm just pointing out that we're sending you some new
[inaudible] features here and those work best when you use Webpack encore.

Okay.

Before we talk about this class, um, to use it up in, uh, our document out ready,

I'm gonna say Var Reference List = new reference list. And we're going to do is we're
going to pass it, uh, the, that js Dash Reference Dash Lists. So that's the element
that we just created a class. We just create an honor UL service is going to find the
UL via j query. We're going to pass that to our reference list and then a reference
list. Really it takes care of the rest. So let's walk through the class really
quickly so you can see what it's doing. It has a constructor. So we passed that
jquery element and we just store it on this, that element. It also keeps track of all
the references data that it has. And so it starts out empty and then a calls. This
error render and the job of this error render is actually to fill in all of the html
that's going to go instead of is UL element.

So what it does is it uses this.references dot map that's kind of a fancy way to loop
over the references. Of course, in the very beginning of they are empty, but they
won't be empty forever. It's going to look over the references and uh, it's going to
create a new array full of the html needed for each reference. So each reference has
the Lli has the same classes we saw before. We're using, um, a new feature of ESX
called string interpolation, or we can reference a render variable names inside of
there. So you see as referencing reference dot original file name and referenced.id
down here. Ultimately these references are going to come from this end points that
we're going to have access to all of the data that you see here.

Okay.

And there was also that I am hard code in the URL to this end point. Um, it's not
really that big of a deal there. You can use fos jazz routing bundle if you want to
dynamically

okay

render ami routes. But

as long as you are aware that you've hard coded some of your URLs in JavaScript, then
you'll know if you change it, you URL all that, you're gonna need to update it in
JavaScript. That was my down here. We basically grab all of that html and we stick it
into the element. So again, references empty at first, but we immediately make an
Ajax call. We read the data, a Dashi URL attributes off of our element. We
immediately make a uh, Ajax request of that when it finishes. We said this, that
references equal to data. So this is going to be set to all of this data here. And
then we call this error render again. So all rerender itself so it will be empty for
just a moment. And then once the Ajax request finishes, it's going to render itself.

So with any luck, let's actually go back. Let's refresh this and yes, you saw it, it
was empty and then it filled in. That is perfect and this is really great because now
we can very easily add any new rows on up on finish of Ajax. The way we do this is
down inside of our drop zone. We're going to add instead of a knit, we're going to do
another event listener on success. This is called after a file is successfully
uploaded. This is going to take that same file and data arguments and actually I'm
just going to console dot log and data so we can see what that looks like. So let's
refresh to get that new JavaScript select any file here and I'm looking for is inside
of our console dot log. Yes, there you go. You see it actually returns the, because
our end point returns the serialized reference, we get that data right here. That's
perfect because that's what we need to set onto hour class. So basically if we can
take that data and add it to the references and then rerender it's going to rerender
with that new row.

So to allow that, let's add a new function here called add reference. We'll take in
that reference data down here. We'll say this, not references dot. Yeah.

Push

for people that use react. I am Ma

and I'm going to say this. Dot render to rerender those. So it's going to rerender
those all from scratch. I do want to highlight that this is a poor approximation of
react. Js. Uh, every time we add, make any changes to references, we need to rerender
everything, uh, whereas react as smarter. It's smart enough to know that we only
added one new reference, so only kind of rerun a part of itself. So if you're really
serious about doing some big nice front in which it's like this, use vjs, use react
at jazz. Um, this is nice for small stuff, but it's not as good as those. All right,
so now down here, inside of our initialized drops on, we're going to force a
reference list object to be passed to us. Maybe even going to document that with a
little APP. Harambe here, how you do it in JavaScript, you can say, this is going to
be an instance of that reference list class and all the way up on top, I will
actually pass it in. So we instantiate the reference list, we said its reference list
op variable, and I'll pass that object into our initialized drops on.

Okay,

so that's awesome because now I can take this reference list here and instead of
console dot log, we'll say reference list dot add reference. We'll pass it data.

Yeah.

All right, let's give that a try. Refresh the page.

Okay.

And let's see here. Astronaut that Jpeg is our last one. So let's upload Earth for
Moon Dot Jpeg. It uploads and boom so fast and we can even instantly downloaded. That
is awesome.