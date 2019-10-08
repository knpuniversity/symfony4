# Transport Config & Mailcatcher

We've already learned quite a bit about how to customize a specific email... with
a *lot* more coming. But how do we customize how an email is *sent*. In Symfony,
the way that your messages are delivered is called a *transport*. Go back to
your terminal and run:

```terminal
git status
```

## The Mailer dsn

When we installed the Mailer component, its *recipe* did a couple of interesting
things. First, it created a new file called `config/packages/mailer.yaml`. Let's
open up that file. Wow, as you can see: the mailer system doesn't really *have*
a lot of config. The only thing here is the `dsn`: a URL that tells Mailer what
server or service to use for delivery. This references an environment variable
called `MAILER_DSN`. Hey! That's the error we just saw:

> Environment variable not found: "MAILER_DSN".

The recipe also modify the `.env` file. If you run

```terminal
git diff .env
```

Yep! You'll see that it added a section with an example `MAILER_DSN`.

## Configuring MAILER_DSN

Open up `.env`. And, at the bottom, uncomment that `MAILER_DSN` line. By default,
this tries to send to a local SMTP server... and I definitely do *not* have one
of those running. But let's try it anyways. Refresh to resubmit the registration
form and... boom!

> Connection could not be established with host "tcp://localhost:25"

So how *are* we going to send emails? Because... they are a *lot* of different
options. You could run your own SMTP server... which is not something I recommend...
or you register with a cloud email sender like SendGrid and use your connection
details from them inside Mailer. Mailer supports a *bunch* of the most famous
cloud providers... and *any* cloud provider that implements SMTP. We're going
to show how to use SendGrid a bit later.

Why are we not going to use SendGrid immediately? Because... when you're developing
and debugging your emails, there's a *better* option. Instead of sending *real*
emails to a real email server, you can send them to a "fake" mailbox.

One of the most famous tools to do this is called MailCatcher. Basically, you download
this, start it on your machine, and it creates a temporary SMTP server that you
can send to. But instead of *delivering* the messages, you can view them all
in a fake inbox in your browser. MailCatcher is written in Ruby and a similar tool -
MailHog - is written in Go. Those are both *great* options.

## Hello MailTrap

But... to save me the headache of getting those running, I'm going to use a *third*
option called MailTrap. Head to [mailtrap.io](https://mailtrap.io/). This is
basically a "hosted" version of those tools: it gives us a fake SMTP server and
fake inbox, but we don't need to install anything. *And* it has an excellent free
plan.

After you register, you'll end up in a spot like this: with a "Demo inbox". Click
into your Demo inbox. On the right, you'll see a bunch of information about
how to connect to this. At the time of recording, they *do* have specific instructions
for Symfony 4... but these are for using MailTrap with *SwiftMailer*, not Symfony
Mailer.

No worries, setup is dead simple. The DSN follows a standard structure: the
`username:password@server:port`. Copy of the username from MailTrap, paste,
add a colon, copy and paste the password, then `@` the server - `smtp.mailtrap.io` -
one more colon, and the port. We could use any of these. Try `2525`.

And... done! If we haven't messed anything up, our email *should* be delivered
to our MailTrap inbox. Let's try it! Refresh the form submit and... ah! Validation
error. The last time we tried this, the email failed but the user *was* created.
Make the email unique by adding a "2". Then click the terms, enter any password
and... register!

Ok, no errors! Go check MailTrap! There it is! It's got the subject, *text*
content, but not HTML content because we haven't set that yet. There are also
a couple of other cool debugging features in MailTrap - we'll talk about some of
these later.

Now that we've got some success, it's time to attack the obvious shortcoming
of this email: it's just text! It's not 1995 anymore people, we need send *HTML*
emails. And Mailer gives us a *great* way to do this:  via native integration with
Twig. That's next.
