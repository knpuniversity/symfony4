# Production Settings with SendGrid

In `.env`, we're using the `null` transport. In `.env.local`, we're overriding
that to send to Mailtrap. This is great for development, but it's time to make
sure our app is ready to send *real* emails through a *real* email server.

To do that, I recommend using some cloud-based email service and Symfony Mailer
supports sending to *any* service that supports the SMTP protocol... which is
all of them. We did this for Mailtrap using the
`{username}:{password}@{server}:{port}`
syntax.

But to make life *even* nicer, Mailer has *special* support for the most common
email services - like SendGrid, Postmark, Mailgun, Amazon SES and a few others.
Let's use SendGrid.

Before we even *create* an account on SendGrid, let's jump in and start configuring
it. In `.env.local`, comment out the Mailtrap `MAILER_DSN` and replace it with
`MAILER_DSN=smtp://sendgrid`. In Symfony 4.4, the syntax changed to
`sendgrid://default`.

## All About Transports

So far, we've seen two *transports* - two *ways* of sending emails: the `smtp`
transport and the `null` transport. Symfony *also* has a `sendgrid` transport.
In Symfony 4.3, you *choose* which transport you want by saying `smtp://` and
then the name of some available transport, like `null` or `sendgrid`. In
Symfony 4.4 and higher, this is different. The syntax is the *transport* name,
like `null` or `sendgrid` *then* `://` and whatever other options you need to
pass to that transport. The word `default` is a dummy placeholder when you don't
need to configure a server, like for the `null` transport or for `sendgrid`,
because that transport already knows internally what the address is to the SendGrid
servers.

Anyways, whether you're in Symfony 4.3 with the old syntax or Symfony 4.4 with
the new one, *this* is how you say: "I want to use the SendGrid transport".

Some of you might currently be *screaming*

> Wait! That can't *possibly* be *all* the config we need to

this is how you say, I want to use the SendGrid transport. Now,
obviously we haven't configured any keys. We didn't even have a SendGrid account yet,
but let's at least see what happens.

let's go back over. Let's go to the registration page and immediately we get an error:

> Unable to send emails via Sendgrid as the bridge is not installed.

So this is another example of a Symfony making it very easy to do something.
But um, but in order to say small, it's a not coming with this feature by default.
So let's come copy that `composer require` line there and we'll spend them or
do a terminal and paste

```terminal-silent
composer require symfony/sendgrid-mailer
```

And you'll notice this actually configures a recipes. So I'm going to do

```terminal
git status
```

and you can see that in addition to the normal things, they made a change toward that
end file. So I'll do it that

```terminal
git diff .env
```

and cool. It actually changed a
section ever. That new section at the bottom. So let's go check that out. This is
just the, and this makes sense because we installed SendGrid, it added a little
example configuration here for how we might configure these SendGrid transport and
Symfony 4.4. This what you'll see in your changes to `MAILER_DSN=sendgrid://key@sendgrid`

Notice also, it's actually taking advantage of the fact that you can, um, create an
environment variable called `SENDGRID_KEY` and then refer to it as `$SENDGRID_KEY`
below. Uh, we're not going to use that, but that's just a little example of some
fanciness that you can do with, uh, with environment variables. You'll see in a
second, we're going to configure this in a more straightforward way. All right, so we
don't even have a SendGrid account yet. So let's actually create one. I'm going to
`sendgrid.com` and let's create a new account. I'll call it `symfonycasts`, poppin
password, and I'll use `ryan@symfonycasts.com`. And I'm hopefully not a robot except
and create account, which it's not letting do. And I'll click to create the account.
Yeah, fill out some information here and then click get started.

No, actually over here and I might actually have my email open. So just to make sure
things work, I'll click over here and confirm my email address so that they are happy
and then I'll close that. All right. Awesome. So back in SendGrid, the first thing
we're gonna need to do is actually uh, um, on this, uh, guide page is hit start. Now
there's two main ways for us to set this up. There's a web API or there's the SMTP
relay. Now they say recommend as the web API, but we're going to choose the SMTP
relay. The reason that this is their recommended ways, this is if you were built, if
you were sending emails by hand, you're writing code to send emails by hand. It's
probably easier for you to use an API to send them. But because Mailer in the Mime
component are creating all of the email complexity for us, it's actually better for
us to use the SMTP relay, which is actually more powerful.

all right. The next step is actually to create an API key. So I will say, um, give
your first

key and name, like create and it's going to create this long key down here, which is
going to be really important. Now notice the, they should tell you like which exact
server to send, which ports to use, which use the name and which password to use.
Which means if you look at our dot, invent local file, we could use just like the
normal SMTP, you can say `smtp://username:password@server` and the
uh, buttons. Then we're going to use, well by using the SendGrid transport,
it's just going to basically make our life a little bit easier. So all we need to do
is take that key we had and say `smtp://` that long key `@sendgrid`. I'll get rid
of the line break there. So let's make sure that looks right. Yep. And ends and dash
O internally because reason to send good transport, a Symfony is going to, um, know
how to transform that and cause the actual SMTP server that needs to be used. Now as
reminder and Symfony. Um, 4.4, it will be `sendgrid://`, and then the
key, which would be our long key there `@default` and the `default` here is
meaningless.

All right, so let's try it. It says once you've set this up, um, we'll click, I've
updated my settings and then he says next, verify integration.so let's click that

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

If you don't see anything, uh, double check your spam, double check your spam because
actually our email looks very spammy right now cause you'll notice it says that we're
sending from `alienmailer@example.com`. Well we don't own the example.com
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
