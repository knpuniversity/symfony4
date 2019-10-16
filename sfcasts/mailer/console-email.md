# Console Email

Coming soon...

So we're in some of our new custom console command. We found all the authors that
want to receive a weekly update about the articles that they wrote during the week.
So we're iterating over the authors and they were finding all of the articles that
that author published each week. Sit down here. If they have published at least one
article, that's when we want to send them a nice little email. So if you download the
course code, you should have a tutorial directory with an inky email and one more
file inside called author weekly report on each two months quick. I'm going to copy
that file name and then put that into our templates /email directory. Now you check
this file out. This is already written in the inky markup, the markup that a inky
will translate into nice table layouts that will work in any email client. But mostly
this is just a static, uh, file. We do have a URL to the homepage down here. We are
printing out the name of the user. Um, but that's about it. In fact, we still need to
do some more work down here to actually make the articles themselves dynamic.

[inaudible]

in fact, to actually get that uh, inky stuff applied, we actually need to add the
filter. So I'm going to go into welcome the AIDS to a twig and I'm gonna steal the
apply from there. And then inside here I will paste that. So that's going to
transform all this markup, an inky and in line both of those CSS files, so down all
in the bottom. Then I'll say and apply and I'll just indent everything in so it looks
nice.

[inaudible]

okay, so we're ready to send this email. Let's go back into our command and we
already know how to start. It's just email = new and because we're going to use a
template for this

email = new templated email and they'll just start setting the data on that. And
actually for the from, I'm going to cheat here and go back into our source controller
security controller and find my register method. I'm going to use the same from as
before. Our alien mailer at example, that com and the name is the space bar. This is
actually the first bit of duplication that we're going to have between these two
emails. Probably all of your emails are going to be from the same address and you're
not really going to want to be duplicating this everywhere across your site on all
emails. So that's something that we are going to work on in a little bit. I'm going
to read type the S on named address and hit tab a so that it as the use statement on
topless file for me. Now for the two, once again I could just put an email address
here but instead I'm gonna say new named address cause that allows me to put an email
and a name and we'll say author->get email. And then for the second argument the name
will say author, hero get first name and then it's just the normal email. So normal
information subject, your weekly report on the us

space bar.

Now for the age, for the actual content, we'll use the HTML template trick and we'll
say email /author dash weekly dash report. That HTML, that quick and honest thing is
we need to pass it as any variables we need to pass in. Now, right now the only
variable we're using is the email variable, um, which is uh, which, which the email
system gives us for free. But we are going to need to pass on that at least one of
the variables. So I'm going to say context, pass that in array. And we know that
we're going to need to pass in. Let's pass on the author object just in case we need
it. And the big thing we needed is we need to pass any articles so we can print
information about those articles. And that's it. That was a beautiful uh, email
object. How do we send that email? Well that's nothing new and we need the mailer. So
we need to auto wire the mailer service. So I'm gonna the top of the class here and
I'll say male or interface mailer, add that as an argument. Put initialize fields to
create that property and set it. And finally down here we will say this->mailer->send
email. Love that.

Yeah.

Okay. So let's just see if this works. And actually we'll see. We have some data
fixtures in here that actually load some user information

[inaudible]

and you can see about 75% of the time he's in faker a, they're going to subscribe to
the newsletter. So just by chance we will hopefully have a couple of users in our
database that will already be subscribed to the, uh, to receive this. So let's try it
then. Console app author weekly report, Colin sun and okay.

Okay.

It looks like that sent eight emails pretty quick. Let's go over to here and they're
not there. Oh, and you know why this is actually, we should, we can do this a little
bit better here.

That'll be,

it's actually a little, add a little IO

note.

Oh, I mean, no, if it's actually just skipping cause it has no articles. So let's run
that again and yup. In fact

[inaudible]. Yeah.

So let's move over and I'm actually going to make sure my fixtures are nice and
fresh. I'll say doctrine and colon fixtures. Call load out. We'll make sure that all
of our, we have some articles that are fresh in the database because our fixtures
will make, always make sure that there are some articles that are recent and then
let's run our app author weekly reports, send again and it looks like six authors
were sent emails. Now we might not get six emails because it only means there are six
authors that subscribe to the newsletter. They might not all have articles, so some
of them might have exited, but hopefully we got at least one email sent and you did
exactly one email. So if you didn't see any emails, try reloading your fixtures again
until you get one that it actually shows up. And there it is. Now you'll notice that
there is actually a couple of problems here. One of them is that our image is missing
and the other one is that this link down here is broken. You can look in the bottom
left. It just says local host. We're going to talk about that in a second, but there
is actually a path problem happening and you can see it in the age to most source if
you scan for a while past all the ugly inline styles if you searched for

[inaudible].

So yeah, the link at the bottom is the right, we're going to talk about that a
second.

The other problem is that this kind of is missing the footer in the head or we had
before. If we look at one of our previous emails, so this really nice like header
with an image and this foot are down here and that's probably stuff that we're going
to want repeated, uh, in every single email. Um, and the reason it's missing is very
simple. If you look at the welcome template, it had the header up here with the logo
down here on the bottom. It had that nice footer down here and in the author report
and weekly, it didn't have any of that. It just jumps straight to the content of the
email. And we did that on purpose. Um, because I don't want to duplicate all that
stuff to fix that. We couldn't just copy a the header and footer into author weekly
report, but of course we know that's probably not a good idea cause it's all going to
be duplicated. So the answer is to do something we've done in tweak templates since
the beginning of time. Create a base layout. So in the email directory, under brand
new file called, how about email based at age 10 will that twig?

And what I'm going to do inside of here is close a few files. I'm going to go over to
my email, such welcomed at, I'm a twig, I'm going to copy that entire template paisan
to email base and then we're going to keep the footer stuff but the entire middle of
this. So basically what we're going to have here is we're going to have the
container, the class header and the class footer and the class bottom. And in the
middle here I'm going to say block, body block, content

and block.

So this forms our, our layout. Now thanks to this and welcome to H. dot. Twig. We can
delete almost everything.

Nothing that I have

so does that welcome. We can delete all the key stuff here actually want to start
with is our normal extends email /email base. That HTML twig and it's just a really
simple template. I don't need to have the apply and key filter anymore because this
content is going to be instead replace that with LOC content and then end of Glock.

We don't need that film, that filtered stuff anymore because all the contents out of
here is going to be put into the block content which is inside of that same filter.
Now of course we can also delete most of the content here. All we really need is the
row welcome and then down the bottom we can get rid of the class foot or cop stuff
and I'm also going to unindexed this so it looks a little bit better. Perfect. Oh
man, I've got an extra email in my temple up there. We can do the same thing over and
author weekly report. We will extend email base.

Okay,

got AIDS, Duma, twig, block content and all that in the bottom. We'll do end block
and then we can simplify things. So I'll take off that container. This guys, the rest
of it already is actually the content we want of the email. So I'll also uninvent
that one as well.

[inaudible]

all right, so let's try this. Easiest way to try it is to send my author weekly
report, move back over. There it is and yes you can see it ascending with that nice
layout

[inaudible]

so let's actually make this stuff dynamic here. Now as a reminder, we're already
sending in an articles variable, so this is actually now going to be very, very easy.
I'm just gonna get rid of this T R here, say for article and articles and for OTR
here we'll say, how about loop that index? That's a nice old secret there. It's ITR,
not for the first TD. We'll say loop that index and those just give us a nice little
number and then article that title and finally we can do a little article comments,
pipe link to below preview there. All right, so I'll spin back over. It would have
been console to run that report and cool. There it is. 11 comments and we're good to
go. If you want to get extra fancy, we get to actually turn this into a link, but
actually we already have a link and the link is broken, so if you look inside of
here, we do have a link down here to the homepage using URL app on just your
homepage. But if I click on that link, it just goes to local host

[inaudible].

Now we know that the,

we know that the URL function here tells Symfony to generate this as an absolute URL.
But if you look in the H, if you look actually do inspect element on that link here,
you're going to see that in this actually if = local host not local host colon 8,000.
And the same would happen if this were on production would say local host instead of
you know, your real domain.com the issue is that when you run it, the way that
Symfony figures out what your apps, what the domain name is of your site is normally
like if we fill out the registration form, when, when we submit the registration
form, Symfony looks at what the URL is that's being submitted from and uses that as
the domain name. When you run a console, command Symfony actually has idea what
domain name the site is. And so it just guesses local host, which of course is wrong.
So that's the trick with sending, um, and you're sending

[inaudible],

uh, emails with a console command is you need to worry about that. So the way to fix
this is a little bit of configuration now first in the dot and file, one of the
things that we already have configured on our site.

Okay.

And this is for a totally different reason. Uh, we already have this site base URL
environment variable that we've defined for our actual application. I'll show you if
you go to config /services.yaml this is actually something that we use for our upload
functionality of our site. It tells us kind of like where they uploads actually
exists. So it has nothing to do with Symfony. This is just something that we decided
to have. Now one of the things that you can do is to fix this problem by uploading is
you can actually tell Symfony what your domain specifically is. Now to do that,
you're going to say it router, that you have to set up a special parameter called
router dot request_context that scheme. And you're gonna set that to something like
HTTPS. And then there's another one called router that requests_context.host, sorry,
that host.

And you'll set that to, in our case, something like the local host colon 8,000 or
whatever your domain name is. Now obviously we don't want to hard code those in here.
And really we already have an environment variable that has that information kind of
mixed up into it. So here's what we can do. We can create two new environment
variables. We don't call it site underscore, base_host_scheme. And we'll start that.
The HTTPS make another one called site underscore, base_host equal to local host
colon 8,000 now the cool thing about these is that we can use these inside of
services .yaml for those values. So instead of hard footing H to BS here you can say
percent in or parentheses site-based scheme, closed parentheses percent. And then the
same thing down here for below we can say for the host we can say percent and open
parentheses percent. I gonna say site underscore, base_host.

Cool.

Now the only problem with this setup is that we do have some duplication. Now like
segues URL is duplicated with these types of things. So to fix that, we can actually
leverage a nice little trick with environment variables, which is actually, um, we
can reference environment variables in here. So we replace HDP HTTPS with dollar sign
site_based base_scheme that is legal and colon //and then dollar sign, site
underscore, base_URL, a host. So basically broken or site base, you are on the
smaller pieces and use that to set these to kind of magic, very important variables
that need parameters need to be set. And this is actually what Symfony uses when it
generates absolute you where else? So now when we spin back over and run our console
command, let's move back over and go to MailTrap and this time asks local host colon
8,000 at this pointing to our correct URL. Got next. Let's do some attachments.