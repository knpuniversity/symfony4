# Email Object

Coming soon...

Ah, yes, here we are, two decades,
Into the two thousands and emails are still a thing. They're still the center of our
life and the center of our online accounts, which means that we as developers, we
still have the job of sending emails, they're great for marketing, uh, et cetera, et
cetera. But sending emails is always kind of been a pain. It's never been my favorite
thing to do. They're hard to preview, are multiple ways to deliver it. Do you need an
SMTP server? Then there's the HTML version of the email and you're supposed to have a
text version. A don't even get me started about CSS and in learning styles. So then
all the different mail clients see it. And if I had to do attach files, I don't even
know how I'd start with that. So with all of this in mind and mailing, sending emails
still being such a central thing that we need to do as developers believe developer
Symfony Fabi Ponce dossier basically said, I'm going to fix this. I'm going to write
a modern, totally modern library centered around making great emails and loving the
process. And that's what we're going to talk about here and I am so excited cause it
really is just fun to use and you're going to be able to create super high quality
emails. As always,

you should,
unless you feel like just mailing it in, you should totally download the course code
and code along with me. You can download the course code from this page. When you
unzip it you'll find a that's not right. When you end Zipit you'll find a start
directory that looks like the code that you see here. You can open up the `README.md`
file for all the setup instructions. The last step will be to open a terminal moving
to the project and use the Symfony. Um, binary to start a web server with 

```terminal
symfony serve
```

Battlestar up a web server, um, `localhost:8000` we can spit over, go to
`localhost:8000` and welcome to this space bar. A project you'd probably
recognize cause we have a lot of our Symfony 4 tutorials built on top of it. This
mill, the Miller. This tutorial will be, you will be using Miller 4.3 but there are a
few cool features coming in. Symfony 4.4 so don't worry, we're going to talk about
those as we go. All right, so like most things in the Symfony, we don't have a mailer
library in the middle of component is not installed by the faults. So many to go over
open a new terminal tab and run a 

```terminal
composer require symfony/mailer 
```

Now one important thing is that, um, at the time of this recording, if you did 
`composer require mailer`, that actually wouldn't give you the Symfony mailer. It would give
you a Swift mailer bundle. So until Symfony 4.3 Swift mailer was the library. That
Symfony used to send emails. And even when you're Googling documentation Symfony
email, be careful because you might actually end up on these Swift Miller dock. So
this is actually the documentation for talking about how to use it. Swift mailer
instead of Symfony. And it might be the second link, sending emails with mailer that
actually talks about what we're interested in. So watch out for that.

So after the install finishes as usual, it gives us some nice, um, details here. Um,
and we're going to talk about all of this. All right, so what's the first email we
should send? Well, one of the most obvious is at registration email after user
registers. We should probably send them an email. So this controlling for this lives
in `src/Controller/SecurityController`. If you look down here, we have a `register()`
actions. Very traditional, has a Symfony form, saves a `User` object to the database,
uh, saves it to database, and then ultimately redirects the user down here. So when
you want to send an email right here, right after the user safe, but before it's
redirected. So the way you do that is beautiful. We're gonna create an email opted
with `$email = (new Email())`. The one from the Mime components because actually the mailer
is actually two different components.

The a mailer, the mailer components, and also another component called mime, which is
not really that important of a detail, but the mind component is all about
structuring messages. So a lot of times you'll see mine as well. Now I've put the
email object and parentheses here because that allows us to immediately chain off of
it and start sending some data. And all of these methods on it here are going to be,
are going to be very, very familiar to you, to you. So we're gonna need to set up
`->from()` address, let's say `alienmailer@example.com` and of course he went on `->to()` send
that to which we'll send to the user that just registered. So `$user->getEmail()`,
then we'll need a `->subject()`. How about 

> Welcome to the Space Bar! 

And finally we actually need a content to ours. And um, if you've done emails before, you
know that there's HTML content and text content. We're going to talk about that
later. For now, let's just set the `->text()` content of the email and I'll put this in
double quotes and say nice to meet you. And then use the fancy syntax to print out
and `$user->getFirstName()` and then a little heart emoticon.

Oops, that's what I term.

No, I use the fancy syntax, `$user->getFirstName()` and then a little heart in
emoticon.

Perfect. And there are other methods on this object. We have like CC, um, BCC, um,
even a couple of other things. So it's a very simple object but you can totally look
inside of there and figure out other things that you can do, um, replied to. And then
we're gonna talk about some of these like attaching things, um, and betting things
and those and those types of things. So that's it. Like what does an email, it's
these things. We have a nice, beautiful object that we can use to grit that email. So
how do we send the email? What would we installed the messenger component? What that
really gave us was one of the things that gave us was a new mailers service and we
can type into it with `MailerInterface`. So whenever you need it, let's actually go up
to the top of our controller. I'll add it as the first argument `MailerInterface $mailer`
down here. What methods do you use that object have on it? How about 
`$mailer->` Only one `send()`, it's just that simple `$email` [inaudible]

right when you do, how do you,

so that's it. That's enough to send an email right there. We haven't configured how
it's going to be sent yet, but let's try it anyways. So let's flip back over and will
register as, doesn't matter. The truth is out there at example that come, that's
actually my name.

Okay.

My name, my name is Fox email. The truth is out there. An example, that com, any
password agree to the terms register and Oh, 

> Environment variable not found: MAILER_DSN 

probably should have expected that because we haven't configured a, how
the mailer should be, uh, sent yet. So next, let's talk about how we configure
sending emails, um, the different options for that and a really great tool that we
can use for debugging emails while we're developing.