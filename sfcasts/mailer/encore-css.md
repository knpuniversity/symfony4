# Encore Css

Coming soon...

This app actually uses Webpack Encore to build its assets and it's not something we
worried about because if you download the source, the course code, it actually
included the final built directory, so you didn't need to know about Encore run
Encore on the site works just because all of this stuff was prebuilt [inaudible], but
if you are using Encore then I want to talk a little bit more about the integration
of Oncor and the CSS. With mailer for example, there are two things, two sort of
shortcuts that we took a for example in assets, CSS foundation dash emails that CSS,
this is actually something we downloaded directly from foundation. It's a vendor, a
CSS file. Typically when you use an Encore, you are going to install vendor libraries
via yarn instead of committing them directly into your project. The other one was
this emails dot CSS file. If we wanted to use SAS instead, well then we need to know
how to process this, this process this through Encore, so let's do that first. I'm
gonna flip over and run yarn install to download all of our yard dependencies.

[inaudible].

Next, let's install foundation with yarn add foundation dash emails, dash dash dev.
If you looked at the package manager, you'd find that that's the name of the package
to get foundation perfect. The end result of this is that we have a giant node
modules directory here and somewhere way down in this giant directory. We can say
foundation for that for emails and you can see inside of here it actually has
foundation dash emails that CSS. It also has a sass file. If you wanted to import at
VSS, we'll keep it simple and import the CSS file. Now before we make actually any
changes, I'm going to go over here and run yarn dev dash dash watch just to make sure
that my Encore assets can build correctly right now.

Perfect.

All right, so step one is instead of relying on this committed foundation dash
emails, let's delete that so I'll right click, go to refactor,

delete.

The second thing is to make things more complicated. I'm going to rename email that
CSS refactor rename to email dot CSS. So this is now a SAS file.

And then one of the things that you can do when you using Encore is that from within
a sass file you can another file. So really if you think about what does the total
CSS that all of our emails need, we need our emails to load our custom CSS and we
also need to load the new foundations uh, file. So we can import that here by saying
at import. And then I'll say til day that tells 'em Webpack Encore to look in the
node modules, directory and foundation bash emails /disc /foundation batch, emails
dot CSS. Perfect.

Now as a reminder, I'll close up node modules. If you're looking templates, email,
email based at each month, week. When do you use inline SCSS? We are pointing it at
the foundation emails that CSS file and the email that CSS file. Really, we now just
will only only want to point it at email that SCSS because in theory it contains both
of those files. The problem is that this is now a sass file in inline. CSS only works
with CSS files. So you can't point this to a sass file and expect it to work. And
even if it were a CSS file, this import is not going to work unless we process this
through Webpack Encore first. So let's fine. We're going to do is we're going to
treat our email that SCSS like a normal CSS files it as if this Ray file that we
wanted to include on a page in our site. So I'm going to go to the Webpack dot config
dot JS file and I'm actually going to add another style entry add style entry called
email and we're going to point it at that /assets /CSS /email that SPSS

[inaudible].

Alright, let's go over here and because we changed our Webpack file, we'll run, we
will stop on-court and restart it. All right, perfect. Now check this out. Down here
you can already see entry point email was dumped and of course it dumped some. And
uh, you can see it actually dumped two CSS files for this. Let's go look in the
public build directory.

And yet there's email that CSS and also this vendor's till day email that CSS. So
this is one of the properties. This is one of the optimizations that Encore makes
when you do this split entry jumps thing without going into too much and you can
learn about it in our Encore tutorial. But basically the point is that if we want to
all the contents of email that STSS actually the final built content actually live in
email that CSS and vendor till they emails dot CSS and we actually need to include
both of those files. If we want our emails to look good, snap pretends to that is a
bit of a challenge, you know, because technically we could right here, point a use to
source files to point at vendor emails. Dot. CSS and emails that CSS. The problem is
that Webpack splits the files in a very dynamic fashion.

So based on whatever's most efficient, it might tomorrow start splitting these into
three files or only one file. Also in production, these file names would change and
they would start including a hash on them like emails. Dot. One, two, three, four,
five. Dot. CSS. That changes every time the content of the email changes. This is why
normally for example, in base studies to my twig, we just call it Encore entry link
tags that takes care of everything. It actually looks in the public bill directory
for an entry points that JSON file, and this actually tells it all of the files that
it needs to include for the entry point, sorry for the app CSS or for the app
JavaScript. So if we look down here for our email one, you can see that it's
advertising that we said the two files that we need. The problem is that we don't
want to just output link tags. We actually need to read the source code of those
files.

No.

Now [inaudible] by using another Encore function called Encore entry CSS files and
some serious twig magic, we can actually do this, but it's kind of so crazy and so
magic that instead I'm going to create a new twig function whose job is to load all
of the source CSS for specific entry. So I'll actually show you what it's going to
look like. First, I'm going to make a new function where I can say on-court entreat
CSS source and then pass it email. That's gonna be smart enough to find all the CSS
files that are needed for the email entry point, load their contents and return them
as one big giant string. To do this, to add that custom function, our application
already has a custom twig function called app extension. So inside of here

[inaudible]

I'm just going to add a new tweak function called on-court entry.

Okay.

CSS source and the method that we'll call in this method book called get on court and
Sheree CSS source. So I'll copy that name then down here.

Okay.

Call public honker. Get Encore entry, says a source that's going to take a string
with the entry name and it's also gonna just return a string of the CSS source. Now
in order to, um, Symfony fortunately already has a built in service that's smart
enough of smart enough to look in the entry points, JSON and returned the files that
you need for specific entry the way get that services to type hint, a entry point
collection and entry point look up interface.

Now for reasons I don't want to get into in this tutorial, instead of using proper
constructor injection, we're using something down here called a service locator and
there's a performance reason for that and you can read about it in this tutorial. The
point is regardless of whether you're using the kind of facet fancy locator injection
or whether you want to use kind of the normal um, a constructor injection, we need
the entry point look up interface service. So because in this case, because I'm using
this service locator thing, I'm going to go down to [inaudible] to get subscribed for
services and certain entry point look up interface ::class and that will suck it into
this method. Then up and get Encore entry CSS source. We can start with files =
this->container->get. And your point look up interface ::class. So when you're using
the service locator pattern, that's how you would get that service out. Otherwise, if
you're doing it to the constructor, it's just this->entry point look up. And then
this has a handy thing on it called get CSS files and we pass it the entry name. So
this should return to us in array with something like these two paths, uh, built in
there.

[inaudible]

so we will for each over files as file and above, that's all credit in new source
variable set to an empty string. Now all we needed to do was take these pads and
actually go look for that path inside of the public directory and open and open that
file up. I could hard code the path to the public directory right here. Instead I'm
going to set up a new parameter and inject it. So open up your config services .yaml
file. And one of the things we talked about in previous tutorial is this global bind
functionality that's under defaults. This is a way for us to set, um, scalar
arguments that we want to be auto wire arable into our system. So I'm not going to do
one here called string dollar sign. Public Durer set too present. Colonel got project
Durer. That's a built in parameter that is the full path through our project /public
now is saying string public dear here. What that literally means is the string part
is actually optional.

[inaudible]

now putting public dirt here, that

literally means that we can go to any service

and up in the constructor we can have, I'll add string public dirt and Symfonys to
get to know what value to pass to the public during this wouldn't normally be auto
wired well because it's not a service bypassing a string public there, the string
parts actually optional. That's a new feature and 4.2 and actually means that you
have to type it this with string in order for that, a auto wiring to work. So it's a
little bit more responsible. We didn't use that up here on these other ones. Uh, but
we could have, so we're gonna have AB extensions during public there. I'll hit alt
enter and go to initialize fields to create that property and set it you okay?
Finally we can go down here and we can say source dot = file, get contents, this
arrow, public [inaudible] that file. And those files should have an opening /on them.
So we shouldn't need a /in the middle and the bottom or return source. Whew. Okay,
let's try this. We're already running Encore, so it's already dumped our email, CSS
and vendors, email CSS. So all we need to do is actually just go and try to send an
email. So I'll hit back.

Okay.

Bumped an email type any password, hit register and wow. Okay, great. No errors
didn't mean to sound so surprised. Go up. I'll refresh. Mailtrap okay. Now I remember
because we've refactored to use, um, a messenger that email's not going to be sent
until we consume messenger. So I'm actually gonna open up a new tab or a bin console,
messenger, colon consumed dash BV. There it is. You can see the messages found. The
messages got sent, spin over and there it is. And the styling looks great. All the
styles are in line. The styles are actually coming from CSS and SAS. So a little bit
of setup with Encore. Um, but you can absolutely get it working and it's a great way.
All right guys, I hope you absolutely loved this tutorial. I hope you want to mail
things. I like you. Okay. Bye. Bye.