# Transport Config & Mailtrap

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
open up that up. Wow... as you can see: the mailer system doesn't really *have*
a lot of config. The only thing here is the `dsn`: a URL that tells Mailer what
server or cloud service to use for delivery. This references an environment variable
called `MAILER_DSN`. Hey! That's the error we just saw:

> Environment variable not found: "MAILER_DSN".

The recipe also modified the `.env` file. If you run

```terminal
git diff .env
```

Yep! You'll see that it added a section with an example `MAILER_DSN`.

## Configuring MAILER_DSN

Open up `.env`. And, at the bottom, uncomment that `MAILER_DSN` line. By default,
this tries to send to a local SMTP server... and I definitely do *not* have one
of those running. But... let's try it anyways. Refresh to resubmit the registration
form and... boom!

> Connection could not be established with host "tcp://localhost:25"

So how *are* we going to send emails? Because... there are a *lot* of different
options. You could run your own SMTP server... which is not something I recommend...
or register with a cloud email sender - like SendGrid - and use your connection
details from *them* for Mailer. Mailer supports a *bunch* of the most famous
cloud providers... as well as *any* cloud provider that implements SMTP... which
is like... all of them. We're going to show how to use SendGrid a bit later.

Why are we not going to use SendGrid right now? Because... when you're developing
and debugging your emails, there's a *better* option. Instead of sending *real*
emails to a real email server, you can send them to a "fake" mailbox.

One of the most famous tools to do this is called MailCatcher. Basically, you download
MailCatcher, start it on your machine, and it creates a temporary SMTP server that
you can send to. But instead of *delivering* the messages, it holds onto them and
you can view them all in a fake inbox in your browser. MailCatcher is written in
Ruby and a similar tool - MailHog - is written in Go. Those are both *great* options.

## Hello Mailtrap

But... to save me the headache of getting those running, I'm going to use a *third*
option called Mailtrap. Head to [mailtrap.io](https://mailtrap.io/blog/send-emails-in-symfony/). This is
basically a "hosted" version of those tools: it gives us a fake SMTP server and
fake inbox, but we don't need to install anything. *And* it has an excellent free
plan.

After you register, you'll end up in a spot like this: with a "Demo inbox". Click
into that Demo inbox. On the right, you'll see a bunch of information about
how to connect to this. At the time of recording, they *do* have specific instructions
for Symfony 4... but these are for using Mailtrap with *SwiftMailer*, not Symfony
Mailer.

No worries, setup is dead simple. The DSN follows a standard structure:
`username:password@server:port`. Copy the username from Mailtrap, paste,
add a colon, copy and paste the password, then `@` the server - `smtp.mailtrap.io` -
one more colon, and the port. We could use any of these. Try `2525`.

Done! If we haven't messed anything up, our email *should* be delivered
to our Mailtrap inbox. Let's try it! Refresh the form submit and... ah! Validation
error. The last time we tried this, the email failed to send but the user *was*
saved to the database. Make the email unique by adding a "2". Then click the terms,
enter any password and... register!

Ok, no errors! Go check Mailtrap! There it is! It's got the subject, *text*
content, but no HTML content because we haven't set that yet. There are also
a couple of other cool debugging features in Mailtrap - we'll talk about some of
these soon.

Now that we've got some success, it's time to attack the obvious shortcoming
of this email... it's just text! It's not 1995 anymore people, we need to send *HTML*
emails. And Mailer gives us a *great* way to do this: native integration with
Twig. That's next.
