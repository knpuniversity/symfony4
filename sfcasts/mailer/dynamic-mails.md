# Dynamic Mails

Coming soon...

When you send the HTML version in mailer, it automatically creates a text version for
you and it's actually pretty good. It's not perfect yet and we're going to talk about
how to improve this later. Uh, but if you ultimately, if you really need to control
the TechStop put in security controller and your controller, you can get a call, the
text method or there's actually a new text templates. You can actually PO pointed to
a different template where you can completely control that, but most of the time we
use HTML template and then the texts will be generated from it. Now in both cases
with a text template or an HTML template, we're probably going to want to pass some
data to that town, but so we can make it dynamic and the way we do this is not via a
second argument to HTML template. Nope, we are going to pass context and pass that
and array. Let's pass a user variable set to the user that was just registered. As
soon as you do this and welcomed that HTML twig, we can replace this percent name
percent holder there with user dot first name user is a instance of our user entity
here and it has a first get named method on it, so that's how that's working.

[inaudible].

All right, let's try that. I'll hit back up the email type of password, hit enter and
then there it is. Perfect. Nice to meet you Fox. Just like that. We have dynamic
templates now in addition to whatever variables you pass and via the context there
are exactly two other variables that you have access to inside of an HTML inside of a
mail template. The first one is the app variable. This is nothing new. Every tweet
template in Symfony has access to an app, a variable. It helps you load things from
the session, the request you can get the currently authenticated user so that's
available to you like normal. The other one is called email and email is an instance
of rapt templated email. I'm actually going to hit shift shift and look for a
template email. Look for this under classes

and this is a beautiful class because it has tons of information on it. Um, it gives
you access to many things like the to name of the who this is being sent to. We'll
talk about that in a second. Um, the subject, the return path, and it actually allows
you to set a bunch of things which is typically a little bit weird and Twain, but
we're actually going to be able to, uh, for example, embed images directly from in
the email. So we're not going to talk about all these methods on here, but pretty
much any information about the email itself you can get from here. And you can
actually also set a few things directly from twig by using this male object.

Okay.

So one of the things, the first method here, actually that's one of the things that
we saw in here was actually in the subject. So if you're looking to walk them aged
about twig all the way at the top, we actually do have a title tag here, which is
just welcome to space bar. Let's change that to be email that subject. Now the title
a, a tag in an email is usually not that important. This usually doesn't show up
somewhere. Some email clients send it. So this is not something that's that
important. Just showing that you can use email that subject to a to set, uh, to, uh,
to get the subject of your email inside of here. Another one you'll see in rap temp
on an email all the way on top. Are they useful is to name and have you checked this
out.

What it does, it actually gets the first two address because you can technically send
an email to many people. And then if it's an instance of named address, it says to
arrow, get name, otherwise it returns an empty string. Now let's check this out.
Insecurity controller. When we said to we just pass it an email address, that's the
simplest thing you can do. But this method actually accepts, um, something called a
named address or even an array. There's two methods actually smarter than us. You
can, you can pass it and array of email addresses or you can pass it one or more
named address objects. So check this out. I can say to new named address and this
takes two arguments which is the address that we're sending to user->get email and
the second argument is the name that you want to identify this person as.

So I'll say user->get first name. We can do the same thing with the from, I'll copy
of the email address from her from but we can say new name to address and then we
will send it from alien mailer@example.com we'll put a name on that as the space bar.
Now this, these names addresses are cool in two ways. The first way is that now
instead of locking to age too much twig, we can use the email objects to get the name
of who we're sending this to instead of using the user variable. So just to prove it,
I'm going to comment out by user context so that's no longer available. And here I'll
say email that to name and specifically what I'm calling here is this to named method
here and that should print off the user's first name. The other way this name
addresses are cool is this is actually going to show up in my inbox.

So let's check all of this out. So we'll go back to my browser, hit back, changed
email again, we'll be doing this a lot type of password submit and there's a new
email address. Awesome. So you can see it still works. Nice to meet you Fox. It got
that off of the center of the email. And up here you can see it's from this space bar
and there's the email that's from and it's two fucks and there's the email that it's
to, you can say it's different from before when we just had the email addresses up
there. So now I have fully customized email. Um, and it's a really cool, now one of
the really cool things about milk catcher is you have this check HTML spot. And this
is not a perfect tool, but this is going to give you some hints on some of the things
that you're doing in your HTML and which, um, which male clients that's not going to
work on.

Okay.

Because one of the trickiest things about emails, and we'll talk about this, is that
you can't [inaudible] is that all the male clients, lots of male clients, render them
in very different ways, lots of things, lots of styles and CSS aren't actually
supported. In fact, you can see right here, see this style attribute online seven. So
let's talk about this. Uh, that's actually not allowed in a lot of male clients,
meaning it won't show up. All of this will be ignored and we'll have a completely
unstylish email. The only safe way to reduce CSS inside of, um, an email that will
work everywhere is not to use CSS at all, but to embed a style attributes on every
single element, which is going to make you hate your life. So next, we're gonna talk
about a way that, um, that mailer just takes care of that for us. It's called the CSS
in liner and you are going to love it. Well, let's talk about embedding images versus
linking to them and when you should use one or the other.