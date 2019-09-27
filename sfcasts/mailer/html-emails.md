# Html Emails

Coming soon...

Every email can have a text part and HTML part or you can send an email that has an
HTML version and also a text version. Now these days, most mail clients are going to
display the HTML version. So that's the one that you really are going to want to
focus on it and that we're going to focus on. But in some cases they might, you might
have a male client or some situation where it only shows the text email. I think it
also helps with accessibility. So you're going to want to make sure that your text,
uh, you do have a text part of your email and it's at least, um, something that's
readable and mailer is going to help make this very, very easy. So [inaudible] first
of all has to be sent right now. And this email doesn't have any HTML part, it only
has a text version.

So how do we send HTML? Well back in our controller, you can kind of guess and a copy
of this text line here and delete the semi colon paste. We'll change the nothing to
HTML and we'll put an H one around that. Just to make it extra obvious what's going
on. That's it. That's now an email that has a text part and an HTML part. And I want
you to see what that looks like. So I'm going to go back to my browser. A hit back to
go to the registration page, change the email address a little bit. Type in a
password.

Yeah,

hit enter. Perfect. It worked. And then go back and you can see the new email up
here. Yeah, this time we have an HTML version of it and one of the things I like
about mail trap is how you can very easily see the HTML source. Um, but you can see
the each demo version and you can, you can see the text version. Another really cool
thing, especially as we start to get more advanced, is be able to see what the RA
email looks like. And this is actually what mailers sent to this thing. You can see
as the things you expect like from two in subject, but then also has this kind of
content type boundaries stuff. So you can see it as a content type text plane where
it has the plain text email, then a content type text us HTML, which it has the HTML
version. These multiple parts is what Symfony's a mime type component and really
helps you build. So we don't have to worry about all these led tails, but this is
actually what an email looks like behind the scenes.

[inaudible].

All right, so clearly we're not going to just put HTML on right inside of our
controller like this. Normally, what do we want to regenerate HTML instead of
Symfony, we're going to use the [inaudible], we're gonna use twig, we're going to use
a template file for that. And mailer comes with really smooth integration with TWIC.
So first, if you've done a little the course code, you should have a tutorial
directory if they welcomed the HTML that twig a template inside. Let's open up the
templates directory. I'm gonna create a new sub directory called email, and then

I'm going to paste that inside of there. So we have a new

templates email, welcome .html.twig. And if you look at this right now and say full
HTML page here, um, we have some inline styles, which I'm going to talk a little bit
about later, like an actual style tag. And then right now this is a nice welcome
email, but it's 100% static cause you can see there's nothing actually filled in. I
even have this little percent name percent here. That's not a variable. That's just
something that we need to fill in later

to S to use this inside of a mailer. What you're going to do is change from email to
templated email and I'll hold command or control and click into that. And you can see
the temple to the email extends emails. So it's still the same email class. It just
adds a couple of methods on here to help with a templates. One of those methods. Now
we have this, we can use one of those methods. I'm going to delete the texts in HTML
and instead say HTML template and here we're just going to pass the normal path to
this template. So email /welcome that age to all that twig. And it's just that
simple. Before we try this, let's make a couple of things dynamic inside of welcome
to HTML twig. A first you can see that we have at links inside of here. What's right
now is just hashtag homepage.

That's not actually what we want. Now if we were, if this was a normal Symfony
template, we would use the path function and then in our app, if you look at uh,
article controller, you can see that the homepage, the name of the rock for the
homemade is app_homepage. So I'd normally put path app_score homepage. The problem
with you than any of the path functions that this will generate. Uh, this will
generate links that are not absolute. It will just be /we need the domain name to be
included in that. So change path to you, R L that's the only thing that you need to
change. There's a couple other spots where we link to stuff down here. There's a spot
to create a new article or they replaced that with URL and the name of that. If you
looked in the application, the name of that is admin article new.

And then there's one more down here for the home page. So we'll say you were out
app_homepage. Now the other thing that you've seen here that's important is we do
have one link to an image file. So the same thing here. This actually needs to be um,
uh, an absolute URL. So first of all, forgetting about emails. Um, this project uses
Webpack Encore for its assets. So I have an assets director here. I have an image
directory here, email, logo dot P and G. um, but when you, uh, run Webpack, the end
result is that this actually copies that into a public build images directory

[inaudible]

and then there's an email directory and it gets copied here. You don't need to worry
about running Encore. I've, uh, if you download the source code, I've actually
included the final, built a directory here. But the point is, regardless of whether
using the Encore, not the path we actually want to link to, is this build images, a
email logo that P and G here. Now the way we do that in Symfony is we use the asset
function. So in this case we just do the path to this is actually build /images
/email /logo dot P and G. uh, because I'm using Encore, I don't need to include this
version hash inside of there. The asset function going to automatically add that for
me. If that doesn't make it, if you're not using Encore and that doesn't make sense
to you, that's fine. You just want to use the asset function like you normally would
to a link to whatever the final path is. Now, but we have the same problem as we have
with the uh, links. Though we don't want this to just renders /build /images. /email
/Logan, that P and. G. we want this to include the domain name in front of it. So to
get that, we're going to wrap this in absolute URL around the asset function

and that should do it. All right, ready to try this?

Let's move over. I'll go back, change the email address again, type a new password,
hit enter, no errors which is always good and there it is. The emails already there
waiting for us and we got it. Check this out. It looks much better. We actually have
our image showing up here. If I hover over the URL, as you can see this is actually
one of the local salon 8,000 they get writing down here is showing low close Connie a
thousand this is a little more obvious in the HTML source. Can you say everything has
those full URLs that are pointing to the image and the URL. Also as a bonus, we still
have a text part. All we sent inside of our controller was each time on template.
We're no longer sending the text thing but one of the things you get out of the box
is that if you don't send set a text part specifically, then Symfony is going to
automatically strip the slashes out of your HTML and include that as the text type.

Now you can see the top is not perfect because it has a bunch of styles in it and
things that we don't want. Um, we're gonna fix that later, but the bottom is actually
pretty awesome. It looks pretty good, especially for not putting any effort into
that. All right, next let's talk about, um, of course the one problem. This is great.
Of course, the problem is that this is all still hard-coded. We still, we need to
actually make this name thing dynamic. So now let's learn a little bit more how we
can pass variables into our template and also what other information is available
inside of here for us to customize things.