# Sendgrid

Coming soon...

So far. If you look at that in by default, we have our mailer DSN is set to use the
Knoll transport as a reminder in Symfony 4.4 the syntax for the node transport will
no, I'm not going to say that and we're overriding that in that end. That local to
send to MailTrap. This is great for development a, but it's time to actually send to
a real email provider. Now Stephanie Miller supports sending to any SMTP server. You
can put the username password here and the SMTP server afterwards. Then the port just
like we're doing for MailTrap, but it also has built in special support for most of
the major cloud providers. For example, send a grid, so let's use SendGrid. I'm going
to comment out my mailer DSN in that aimed at local and replace it with SMTP. Colon.
Slash. Slash. SendGrid. Now in Symfony four point as I mentioned, the way that
smeller delivers emails is called a transport. A. One of the built in transports is
the SMTP transport. Another built in transport is the null transport, and then it has
transports specific to other, specific to other cloud providers like SendGrid and
other things. In Symfony 4.3 the way it figures out which transport to use is this.
SendGrid is the host name here in Symfony 4.4. This will change. It will be, it will
look like SendGrid, colon //default and Symfony 4.4. The transport you're choosing is
actually, um, the first part

you'll see the same thing with the Knoll transport in Symfony 4.4. This would look
like day I and = null colon, /slash, and uh, and then default to the where that
default is meaningless. So that's just a change to be aware of the transport in 4.4
will be this starting a string. But I'll talk about the, I'll point and I'll point
that out if there's any other changes along the way, but either way, whether you're
in 4.3 or 4.4, this is how you say, I want to use the SendGrid transport. Now,
obviously we haven't configured any keys. We didn't even have a SendGrid account yet,
but let's at least see what happens.

Yeah. [inaudible]

let's go back over. Let's go to the registration page and immediately we get an air
unable to send emails via SendGrid as the bridge is not installed. So this is another
example of a Symfony making it very easy to do something. But um, but in order to say
small, it's a not coming with this feature by default. So let's come copy that
composer require line there and we'll spend them or do a terminal and paste

[inaudible].

And you'll notice this actually configures a recipes. So I'm going to do get status
and you can see that in addition to the normal things, they made a change toward that
end file. So I'll do it that get diff, that M and E and cool. It actually changed a
section ever. That new section at the bottom. So let's go check that out. This is
just the, and this makes sense because we installed SendGrid, it added a little
example configuration here for how we might configure these SendGrid transport and
Symfony 4.4. This what you'll see in your changes to mailer DSN = SendGrid colon
//key at sun grid

[inaudible].

Notice also, it's actually taking advantage of the fact that you can, um, create an
environment variable called SendGrid and then refer to it as dollar sign send grid
below. Uh, we're not going to use that, but that's just a little example of some
fanciness that you can do with, uh, with environment variables. You'll see in a
second, we're going to configure this in a more straightforward way. All right, so we
don't even have a SendGrid account yet. So let's actually create one. I'm going to
send grid.com and let's create a new account. I'll call it Symfony casts, poppin
password, and I'll use Ryan S Symfony, cast.com. And I'm hopefully not a robot except
and create account, which it's not letting do. And I'll click to create the account.
Yeah, fill out some information here and then click get started.

Perfect.

No, actually over here and I might actually have my email open. So just to make sure
things work, I'll click over here and confirm my email address so that they are happy
and then I'll close that. All right. Awesome. So back in SendGrid, the first thing
we're gonna need to do is actually uh, um, on this, uh, guide page is hit start. Now
there's two main ways for us to set this up. There's a web API or there's the SMTP
relay. Now they say recommend as the web API, but we're going to choose the SMTP
relay. The reason that this is their recommended ways, this is if you were built, if
you were sending emails by hand, you're writing code to send emails by hand. It's
probably easier for you to use an API to send them. But because mailer in the mind
component are creating all of the email complexity for us, it's actually better for
us to use the SMTP relay, which is actually more powerful.

[inaudible]

all right. The next step is actually to create an API key. So I will say, um, give
your first

key and name, like create and it's going to create this long key down here, which is
going to be really important. Now notice the, they should tell you like which exact
server to send, which ports to use, which use the name and which password to use.
Which means if you look at our dot, invent local file, we could use just like the
normal SMTP, you can say S and D colon /lasts the user name, colon password and the
server, uh, buttons. Then we're going to use, well by using the SendGrid transport,
it's just going to basically make our life a little bit easier. So all we need to do
is take that key we had and say SMTP, colon //that long key at SendGrid. I'll get rid
of the line break there. So let's make sure that looks right. Yep. And ends and dash
O internally because reason to send good transport, a Symfony is going to, um, know
how to transform that and cause the actual SMTP server that needs to be used. Now as
reminder and Symfony. Um, 4.4, it will be SendGrid, colon, slash, slash, and then the
key, which would be our long key there at default and the default here is
meaningless.

All right, so let's try it. It says once you've set this up, um, we'll click, I've
updated my settings and then he says next, verify integration.

Okay,

so let's click that

[inaudible].

Perfect. And what I want you to do here is send an email from our code using that new
configuration. So that's easy enough. Let's go back here. I'll hit enter on the
registration page and this time because we're going to send a real email, let's, I'm
going to send it to my, my actual, uh, to some real inbox that I have. I use Ryan at
Symfony cast since I have that account open already, I agree to terms register and,
okay, no error. So let's go over to SendGrid here and we'll hit verify integration.
And this may take a minute or so. It's looking on its servers to see whether or not
it saw an email that was just sent from us. But while we're waiting for that, if you
look over here, it actually arrives. So this is my inbox and you can see the email
there. By the way, if you don't, so we can check it out and it looks beautiful. So
there we are sending through a real transport.

Okay.

If you don't see anything, uh, double check your spam, double check your spam because
actually our email looks very spammy right now cause you'll notice it says that we're
sending from alien mailer ad example.com. Well we don't own the example of that com
domain. So this should look a little bit spammy. Go back to SendGrid.

Um, and then I have [inaudible] here.

Oh but actually didn't see my emo but let's try it again cause it definitely sent,

could be

and that time it worked at my tech, a couple tries, I'm not sure why. Then you can
hit view email activity. Um, sometimes this is a little bit out of date. So you see
it says it doesn't see anything, uh, yet inside of here, even though we actually did
just send an email. Oh, actually we can see your email in there. Awesome. So next I
want to talk a little bit about why this is probably going to end up in spam in most
cases. And what we can do to avoid that. It talks about, uh, this is a, and some
configuration that we must set up.