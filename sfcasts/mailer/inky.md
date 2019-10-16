# Inky

Coming soon...

Our email template is literally a HTML, very traditional HTML. This is the type of
HTML that you would see inside of a website, um, and over in mail trout. If we look
at one of those, it looks good over here, but one of the trickiest things about
emails is that a lot of email clients don't have support for the HTML and CSS that
you're used to. For example, if you try to float elements next to each other or use
Flexbox, there's a good chance that stuff's not going to work and even though the
reality is if you want to write an email that's going to look consistently good in
every email client, the best practice is actually to use a table layout, which if you
have been around for a long time like me, you know what that is. It's literally where
instead of proper dibs, you use tables and table cells for your entire layout, which
no one wants to code like that. Fortunately, there's a really cool library and email
tool that can help us get the output, can help us write good clean code like this,
but get output that is actually a table output. Let me show you what I mean first.
We're going to stall neutral, so go to your terminal and composer require twig /inky
dash extension.

Oh, that's downloading. I'll go over and Google for it. Inky framework to find
something called Zerbe inky [inaudible] inc. as you can see here, this is actually a
CSS framework for responsive HTML emails that works on any device, even outlook. I'm
gonna go to the docs on this and I'm basically gonna give you that. The idea here and
this deserve is basically two pieces. First, it's a CSS file, just like bootstrap CSS
that helps you style your emails. But more interesting thing if you click use inky
down here is that it allows you to, I'm going to click switch to inky and allows you
to write HTML using these fake container row column elements and then when it
actually delivers the email, it transforms that into all these crazy table things you
need to get that table out so you get a table out, but you can write really nice
HTML. All right, so let's move back over. Perfect. That finished installing. Now if
you download with the course code, then you should have a tutorial directory and
inside there is the welcome to each

tunnel HTML. That toy template that we originally used. There's also an inky
directory with an updated welcome to age two month twig. This is basically the same
template but written in the markup that inky wants. You can see container row
columns, that kind of thing. So I'm going to copy this walking by age two, not twig.
And then just to be clear, I'll close that and then let's go to templates. Email.
Welcome to aides to my twig and I am going to paste this. And this is exactly the
same as before, other than the markup. It's exactly the same as before. It still has
all of the uh, dynamic. You were owls, um, and saying, you know, has the emails, uh,
the email name on it, it's just got different markup. Now I noticed one thing as a
second ago, we had added some input for an email that CSS right now that's gone.
We're going to put that back in a second, but don't forget about it. Don't worry
about CSS right now. Now, if we send this email right now, it would literally send
with this Mark up to transform this into the table based markup or we're going to do
is we're an astronomer. This whole thing

with apply, inky inky is the name of a filter. So we're applying the inky filter to
this entire file. We'll all the way to the bottom down here and I'll say and

apply it

and just to make it style, just to make it nice. I'll actually indent everything. All
right, so let's try that. I'm going to move back over. Let's go to our registration
form. The truth is out there at 11, at example.com.

Okay,

in the password, agree to the terms register and okay, let's go check this out. Go
over to MailTrap. There's a new email and okay, it looks terrible because we don't
have any CSS, but check out the HTML source. Yes, it is actually transformed this
into table elements. So just like that we have something that is, um, much more, it's
going to work a lot more email clients. Now to actually get that to look good, we
need to include the foundation CSS. So I'm gonna go back to, uh, the documentation.
Click on CSS version, and then you can click download foundation for emails. Now when
you unzip that, you're going to inside, you're going to find a CSS. And if you unzip
that inside, you're going to find

[inaudible]

feelings about that

[inaudible].

And if you unzip that, you're going to find just a CSS directory. And what we want to
copy there is this foundation dash emails dot CSS. So I'm gonna copy that back over
and we'll just put this in our assets CSS directory where we already have an emailed
that CSS with some custom CSS for our particular emails. We'll paste in our
foundation dash emails that CSS. And if you remember from before the, when we get
this is we use a a, a filter called inline CSS. So now we want to apply both the inky
filter and the inline CSS filter to this entire email. To do that, you can actually
pipe the inky filter into in line_CSS. And then here we're gonna pass source. And
then we're gonna pass at Stiles /foundation bash, emails dot CSS. Now as a reminder,
if you look in your config packages, tweak .yaml file, we set up an alias that said
anytime we say ass styles, that's actually pointing at the assets CSS directory. So
that's why we'd say at styles and then /foundation dash emails dot CSS. And actually
the other three, you still wanna include our email that CSS. So I'm actually on a
copy of this and we can pass a second argument that to inline CSS and we're just
going to point this at our

email that CSS

okay.

And that should do it. Oh one last change before we do this is in our original email
that CSS, if you go back to our tutorial directory here and there, inky directory, I
also included an email that CSS, what happened here is in our original CSS, now that
we're using a uh, uh, a third party CSS system, we don't need to define some of these
things are actually redundant, like the button and the tech center. So if you can see
this is basically a simpler version with those things removed. Some, a copy of the
inky, the one from the tutorial directory, paste it over. This just makes that file a
little bit shorter cause we don't need them to reinvent all the, all those wheels.

All right, so let's try this and see the whole thing in action. So I'll spend back
over, go back to the registration page, up to the email type password, hit enter and
go check it out in male trap. There it is. It looks awesome. In fact, it looks
exactly like it looked before. The big takeaway though is if you look in the HTML
source, we are now dealing with entirely a table layout. So if we want to know more
about foundation, you can look at its documentation, but it's basically just a CSS
framework and the biggest differences that you're going to use these container row
and columns a to basically make the exact email layout that you want. It's going to
take care of all the ugly details of making sure that that is using really old markup
that works everywhere. So it's pretty awesome.