# Mime Type Validation

Coming soon...

Unless the authors that can upload these files are super, super trusted. We need to
have some validation on there because right now they can literally upload any file
type they want to the system.

All right, awesome. No problem. Ah, let's go into our article, our controller. So
hmm. Now the problem is that we don't have a form before him. He had an article and
my controller, we just put the validation into the form. Form is valid, gave us all
the air's, everything rendered, everything was just fine. While we're not in a forum,
it means you're gonna need to validate directly which, which is totally fine. There's
a valley, it's very simple to do at another argument to our controller called the
validator interface validated. This is the service that the form uses internally to
do validation.

Okay.

Then before we do anything with that uploaded file, we're going to say it, violations
= and we're gonna use the validator. So to do that, you can say validator Errol
validate and we're going to do here is actually pass it the object that we want to
validate the uploaded file. And then we're going to pass it the constraint that we
want to validate against. Now remember there are two main constraints with file
uploads. There's the image constraint where, which we use before. And there's also
the file constraint, which is what we're gonna use here. So this time say new file,
it's the one from component validator.

And this has an up and the main option, one of the main options with this one has max
size. And just to get this working, let's get this tube one kilobyte so we can see
the air. So the idea is that if validation of this object against this constraint
fails, then it's going to return one or more violations. This violations is uh,
basically an array of errors, but it's not actually an array. It's a special object
that looks and acts like an a, right? So to figure out they're actually getting
violations. You can say if violations Aero count is greater than zero, then we have a
problem. Let's just Didi this violations thing so that we can see what it looks like.
All right, so let's go over, we'll choose our Symfony best practices. That's way more
than one kilobyte. And let's go over here. We'll choose our Symfony best practices,
hit upload and awesome. There it is. Constraint violation list. That's the object
that looks and acts like an array and it just holds basically an array of objects
called constraint violations. And there you go. It's got a message. The file is too
large allowed, maximum size is one kilo bytes and you can customize that message and
that message if you want by passing the Max size message there.

So in theory you can have multiple validation rules and you can have multiple errors,
uh, to keep things simple. I'm just going to show whatever the first air is to the
user. There's usually only one anyways, so let's do that. I'm going to say violation
= violations and just get the zero key off there that object implements or access so
you can treat it like an array and to help out my editor, I'm going to tell it this
is a constraint.

Wow.

Violation object because that's what we're seeing over here.

Then right now then how are we going to do this air to the user? Eventually we're
going to turn this into an sort of an Ajax or API end point that communicates and
JSON, but right now this is just a normal end point or a redirect to another page
afterwards. So really the best, easiest way to show the air as I said it as a flash
message, so I'll say this ad flash, we'll put it as an error type and they'll say
violation->get message and then we'll redirect right back to the admin article page
and saw a copy of the redirect route on the bottom and I'll put it up here. Now for
this ad flash thing, if you followed our tutorial before, if you go to templates
based at age two months twig, if you scroll down a bit, let's see.

Yeah they are.

You'll see that we're already rendering these success flash messages, but we don't
have anything rendering the air flask messages. So let's copy this and we'll loop
over the air flask messages and say alert dash danger. So those should come out now.
All right, so refresh this time redirects. Boom, there is our air. And like I said,
we can customize that text if we want to. All right, but what we really want to do
here is control the, the types of files that are uploaded. So I'm going to change
this back to change it to five megabytes. And the way that you control the mind types
is the file types is with eight mime types option. So these are gonna be sort of
documents that the user uploads. They could be images. So one of these you can do is
you can say image /star tell, I'll allow anything that starts with image. /this is
actually what the image constraint checks for internally. And then how about
application /pdf. It's also a lot PDFs. And you know it's a little tricky actually
because there are probably so many valid file types that we want to accept here. Uh,
if you want to cheat, there is a nice file. Um,

okay.

Your type shift shift and type mom type extension guesser

she can find a class, deepen the core. This is actually a pretty cool glass. It's,
it's a, it's something where you give it the mime type and gives you the file
extension and it's got a really nice source. Basically have lots and lots and lots
and lots of mime types in here. So if you're looking for a specific mime type, um,
it's really easy to search by extension. So for example, if I want to look for,
what's the mime? Type Four M. Dot. Docs, Microsoft word.docs. I can look in here for
quote dot quote. There you go. Application /ms word. So I'm going to close this file
and I'm just going to paste in a few more types here. So this is ms word. These,
we'll get like the doc doc x kind of things and actually I forgot one here can do.
There we go excel.

Wow.

So you might need to play with this to see, um, what works for you. But that's the
idea. So this time,

okay,

which is the pdf

cool. That works. You can't see it, but we don't see the validation air. And I also
have an earth, that zip file here. This is actually just these two photos zipped up,
but it is a zip file. So let's try that. And cool. The mime type of is invalid
allowed, mine types are blah blah blah blah. And so you can also customize this
message cause that's a little bit too technical. Then the last thing, if you just hit
enter to refresh the form, if you hit upload, you actually get a giant air because we
didn't select file. So everything kind of blows up inside of our uploader helper. So
that's actually very easy to fix. The second argument to validate is actually either
a single constraint object or an array. So I'm actually gonna pass an array here in
debt. The new file.

Okay.

And then I'll put new, not blank, and I'll even put a custom message there, which
says, please select a file to upload. Nice. All right, so refresh now. So that big
Auger air yet we get a nice air there. All right, cool. So next, let's talk about how
we actually allow the user to download these private files.