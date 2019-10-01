# Embedded Image

Coming soon...

So when we add, send an HTML email, as we've seen, a mailer automatically generates a
text version for us and other than this extra style stuff up here, which is we don't
really want it, does a pretty good job. And actually this style thing will go away in
a few minutes when we refactor our styles to another, um, to another file. But
there's actually a really simple way to make the text content even better without any
more work. Find a terminal and running composer require league /HTML to markdown.
This is a library that's good at changing HTML to markdown what it's actually gonna
in. As soon as you install it mailer, we use it internally to transform your HTML
into markdown emails, which actually really look really good in text. So check this
out. Let's go back to our site. Went back to page, bumped the email again,

submit

and there's our new email. The HTML looks the same, but check out the text. Now.
First of all, this was smart enough to get rid of the style tax. So that style of
things are gone, but it did some cool stuff and actually embedded the logo, which if
somewhere we're rendering this in markdown down, that would actually be helpful. The
most important thing here is actually just gives it some nice structure here. It
knows that bolds should we have star stars like markdown just reads like really nice
text. So this is a nice way to get better text emails for free. Um, and then you
don't really need to worry about them too much.

Okay,

now look back on the HTML thing. The image up here is actually a link directing to
our site, just normal image tag and it points directly to our, our real file on our
site. It turns out there are two ways to put images into your emails. The first one
is this one. It's a normal boring image tag that links to our website. The other one
is to embed the image in the email itself and there are pros and cons to both of
them. Um, for example, when you link directly to the site, what happens if we delete
this file later? Well then it's not going to show up in the emails correctly, which
maybe is a problem.

Okay.

Of course, the fact that it's linking to our site means that we could just change. If
we needed to make some changes in the logo on her site, we could and it would
automatically show up on the email because it's centralized.

Okay.

We're going to talk a little bit more about when you should link versus when you
should embed. But first I want to show you how we can embed this logo instead of
linking it. A, remember the source version of this lug was actually an images, email,
logo dot P and. G. so this is the physical file that we want to embed into our email.

Now to start this,

this actually going to do this

from instead of twig, where she in our image tag when I point it at this images,
email, logo. Dot PNG file. Now to do that,

we're going to set up something called a twig path. We're basically gonna teach twig,
um, to know that we're gonna teach twig. That's what I do for the twig path. Let me
show you open up config packages, tweak .yaml. Let me close mailer that yam on that
end instead of here. One of the keys you can put in here that's not very well known
to super handy is called paths. And here when I'm going to put is I'm going to put
assets /images. So I'm literally pointing to assets /images and I'm gonna set that to
the word images, which could be anything. Now the point of forgetting about mailer,
the point of the paths here is that if you create a, it's for you to actually be able
to define that different directories where your templates would live.

So for example, if for some reason we actually stored some templates in the assets
/images directory, we would actually be able to refer reference those now as at
images, /food at HTML, that twig. So I mean there's a food at a small twig tweak
template. The reason of course we don't have templates in this director, we have
images, but this little trick here is going to allow us to reference images inside
this image directory from inside of twig. So the fact that we called this images,
that's going to be the key thing that we use. So now I'm going to go over back to
walk them down each month, twig and remove all of this asset stuff.

And when gonna replace this with email dot image. And actually if I remember, I
remember the emails actually this wrapped template email. I'm actually calling this
image function here, which is a way for us to embed an image inside of this file. And
here we're going to pass it at images /email /logo dot P and G. so the images part
here is because we defined in images path inside of R a configuration file. It tell
us wait to look and assets /images for that assets, images. And then we put the path
after that which is email /lowered up P and G, email /lower dot P and G. so that
tells it to embed the image right there. All right, let's see what difference that
made. So I'll go back to our site, do our normal thing here.

I've re registering and back there is a new email and perfect in here at least it
looks exactly the same. But if you look at the HTML source, it's really interesting.
It turns out the image source is not at our site. Now it's not this CID colon thing
and then this long string. So what happened internally is if you go to raw here, you
can see the um, uh, here's the text version of our email. Below this we're going to
see the HTML version of our email. So text, /HTML, and eventually down here you're
actually going to see our logo. So you can see here it's given this content ID of CF,
all this long stuff at Symfony, and then it has the binary content there. So when we
use, instead of our, uh, message itself, it references this content ID and then the
mailer, your mail client knows to actually go and load that up.

So it's actually kind of like an attachment, but it still shows up in line. Now,
which one should we use? Linking or embedding? Well, this is a really tough thing
because every, because of the problem of every male client doing different things,
some male clients will, will render an embedded image, but we'll require the user to
click some link that says show images from sender before they show one that is a
linked version. Other male clients won't show the embedded version at all. They'll
show this actually as an attachment. So the general rule of thumb is that if you're
linking to a generic image, um, that's part of your emails layout. You should use the
link method, the one that actually links directly to your website. If what you are,
if the image you're showing is something that's specific to that email, like maybe
it's a picture, uh, it's uh, picture that one of a that was uploaded to your site and
you want to send an email showing somebody that it was there, then if it's specific
to that email, then it usually makes more sense to embed it because then it's part of
the email and you don't need to keep it hosted somewhere.

So, um, use both of those. Well, those are very easy to do in Symfony. And, uh,
that's the rule of thumb. Next, if you look at this check HTML tab, you'll see this
actually helps you validate what things you're using in your HTML that might not be
compatible with different male clients. Because the most hardest thing was sending
emails really is that different mail clients. Two different things. And one of the
things you'll notice is that these style attribute doesn't work in some really
important male clients. It turns out that if you want CSS to work inside of your
emails, you can't use an inline style tab like this, and you can't use a link tag to
an external CSS file. The only way to get it to dependently work is to add style
attributes to every single element that needs a style. Of course. That's horrible. So
next we're going to talk about something called CSS. Inlining or mailer is going to
handle this for you.