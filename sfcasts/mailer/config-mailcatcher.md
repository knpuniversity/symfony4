# Config Mailcatcher

Coming soon...

We've already learned quite a bit about how to customize a specific email with a lot
more coming. But how do we customize how it's sent in Miller? The way that your
message, your message are delivered are called a is called a transport. Go back to
your terminal after we installed the mailer a second ago and its recipe actually. Um,
I actually did a couple of interesting things. First I created a new file called
config packages at mailer.yaml. Let's go to config packages, mailer that ammo. And
you can see the mailer doesn't actually have a lot of config and the only thing that
you need to configure for it is something called the DSN, a URL that shows where your
mail should be delivered. And this references an environment variable called the
mailer DSM. And that's the error we just saw. Environment variable in that found
mailer_DSN. The recipe also modify the.in file. I'll do D get diff that and, and you
can see they actually added a section in here where it gave us an example mailer DSN.
So let's go to the dot and file. And I'm at the bottom, well uncomment out that
mailer underscored DSN. Now how do we want to send emails?

Now if we just did that in order over here and refresh it again, you're going to get
a different, you're probably gonna get a different air. Could not, uh, establish a
connection could not be established with host local host colon 25 because you
probably don't have any local SMTP server running. So how are we going to send
emails? Cause they're all a lot of different options. Well, you could run your own
SMTP server, which is not something that I recommend. What you should probably do and
what we're going to show later in the tutorial is signing up with a cloud provider
like SendGrid, which is going to, they'll set up and then, and then using their
credentials here too, um, to point to them. And mailer has built in support for any
cloud service that, that gives you an SMTP server, but it also has special support
for a lot of different, um, uh, famous cloud providers out there.

So we're gonna talk about that later. And we could do that right now. We could go
sign up for a cloud provider and start using their credentials here and start sending
real emails. But when you're developing and debugging your emails, there's actually a
better way. There's a couple of really cool tools you can use to help. One of them is
called mail catcher. It's kind of famous and what milk catcher is is it's actually
something you can download the download on your machine. It runs a fake SMTP server
and when you send emails to it, instead of them actually getting sent somewhere, they
show up in this little web interface, you can debug them. There's another one called
the mail hog, which is maybe a little bit easier to install. Male catches run on
Ruby, and there's a third option, which is what we are going to use.

It's called male trap, but a MailChimp dot. Oh, this is going to give us that same
experience. It's going to set up kind of a fake a SMTP server for us. And when we
send emails to it, instead of those actually being delivered, it's going to, this
site is going to catch them and we're going to be able to see all of them. The
advantage over something like mail catcher or mail hog is that you don't need to
install anything to get this work. This is a service that you can pay for them, but
fortunately they also have a free plan. So after you register, you'll end up in a
spot like this with a demo inbox. I'm going to click in my demo inbox here and you
can say my inbox right now is empty. And over here it gives me a bunch of information
about my um, cert. And down here it kind of tells you how to integrate it. By the
way, at the time of this recording, they do have one for Symfony four, but this is
actually talking about how to set this up with these Swift mailer bundle. So don't
listen to that, but it's easy enough. All we need to do inside of our dot. En file is
configure the username host is take this information and put it into our dot N file.

So these are always follow kind of a specific standards. So I'm a copy of this
username here and the way this looks is you have a user name, colon and then the
password colon and then at and then the server, smt.mailtrap.io and then colon the
port. So we're going to use, could use any of these. We'll use 25 25 and it's that
simple. All right, so let's try this. I'm going to go back over here. I'll refresh
the page. And of course not, it fails, it fails validation because we're already
registered saving the database. So I'll just for the two there we agree type of
password and okay, looks like it worked. Football over to MailTrap. There it is.
Awesome.

I've got the email, I've got the subject, there's our text content. You can see we
don't have HTML content yet because we haven't set that. And there are several other
tools in here, um, that are really cool for debugging things, but we're going to get
all into more of that later. Next we do. We, we're probably not going to just send
text emails. We're probably going to send HTML emails and a mailing gives us a really
nice way to do this by using the tool that we already know and trust for creating
HTML, which is TWIC. Let's talk about that next.