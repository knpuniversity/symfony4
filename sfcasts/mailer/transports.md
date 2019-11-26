# SendGrid & All About Transports

In `.env`, we're using the `null` transport. In `.env.local`, we're overriding
that to send to Mailtrap. This is great for development, but it's time for our
app to grow up, get a job, and join the real world. It's time for our app to send
*real* emails through a *real* email server.

To do that, I recommend using a cloud-based email service... and Symfony Mailer
can send to *any* service that supports the SMTP protocol... which is all of
them. We did this for Mailtrap using the `{username}:{password}@{server}:{port}`
syntax.

But to make life *even* nicer, Mailer has *special* support for the most common
email services, like SendGrid, Postmark, Mailgun, Amazon SES and a few others.
Let's use SendGrid.

Before we even *create* an account on SendGrid, we can jump in and start configuring
it. In `.env.local`, comment-out the Mailtrap `MAILER_DSN` and replace it with
`MAILER_DSN=smtp://sendgrid`. In Symfony 4.4, the syntax changed to
`sendgrid://default`.

```
#MAILER_DSN=smtp://USERNAME:PASSWORD@smtp.mailtrap.io:2525
MAILER_DSN=smtp://sendgrid
# Symfony 4.4+ syntax
#MAILER_DSN=sendgrid://default
```

## All About Transports

So far, we've seen two *transports* - two *ways* of *delivering* emails: the `smtp`
transport and the `null` transport. Symfony *also* has a `sendgrid` transport,
as well as a `mailgun` transport `amazonses` transport and many others.

In Symfony 4.3, you choose *which* transport you want by saying `smtp://` and
then the name of one of those transports, like `null` or `sendgrid`. In
Symfony 4.4 and higher, this is different. The syntax is the *transport* name,
like `null` or `sendgrid` *then* `://` and whatever other options that transport
needs. The word `default` is a dummy placeholder that's used when you don't
need to configure a "server", like for the `null` transport or for `sendgrid`,
because that transport already knows internally what the address is to the SendGrid
servers.

Anyways, whether you're in Symfony 4.3 with the old syntax or Symfony 4.4 with
the new one, *this* is how you say: "I want to deliver emails via the
SendGrid transport".

At this point, some of you might be *screaming*

> Wait! That can't *possibly* be *all* the config we need to send emails!

And you're 1000% percent correct. This doesn't contain any SendGrid username, or
API key. Heck, we haven't even created a SendGrid account yet! All true, all true.
But let's... try it anyways. Because, Symfony is going to guide us through the process.
How nice!

## Let Symfony Guide You to Configure the Transport

Head over to the browser and refresh. Woh! An immediate error:

> Unable to send emails via Sendgrid as the bridge is not installed.

This is *another* example of Symfony making it easy to do something...
but *without* bloating our project with stuff we don't need. Now that we *do*
want to use Sendgrid, it helps us install the required library. Copy the
`composer require` line, spin over to your terminal and paste:

```terminal-silent
composer require symfony/sendgrid-mailer
```

Ooh, this package came with a recipe! Let's see what it did:

```terminal
git status
```

In addition to the normal stuff, this *also* modified our `.env` file. Let's
see how:

```terminal
git diff .env
```

Cool! The recipe added a new section to the bottom! Back in our editor, let's
see what's going on in `.env`:

[[[ code('3b4998982f') ]]]

Yea... this makes sense. We know that mailer is configured via a `MAILER_DSN` 
environment variable... and so when we installed the SendGrid mailer package, 
its recipe added a *suggestion* of how that variable should look in order 
to work with SendGrid.

## SendGrid Symfony 4.4 Config Format

Now, two important notes about this. First, when you install this package in Symfony
4.4, the config added by the recipe will look a bit different: it will add just
one line:

```
MAILER_DSN=sendgrid://KEY@default
```

Like we just talked about, this is because Symfony 4.4 changed the config format:
the "transport type" is now at the beginning. The `KEY` is a placeholder:
we'll replace with a *real* API key in a few minutes. And the `@default` part
just tells the SendGrid transport to send the message to whatever the
*actual* SendGrid hostname is.... we don't need to worry about configuring that.

## A Note about Environment Variables inside Environment Variables

Now, if you look at the config that Symfony 4.3 uses, you'll notice the
second important thing: this defines *two* environment variables. Gasp! It
defines `SENDGRID_KEY` and *then* `MAILER_DSN`. This... is just a config trick.
See how the `MAILER_DSN` value *contains* `$SENDGRID_KEY`? It's *using* that
variable: it's environment variables inside environment variables! With this
setup, you could commit this `MAILER_DSN` value to `.env` and then *only* need to
override `SENDGRID_KEY` in `.env.local`.

This idea - the idea of using environment variables *inside* environment variables
*totally* works in Symfony 4.4. But to keep the config a bit simpler, in Symfony
4.4 - you won't see this two-variable system in the recipe. Instead, we'll
configure the *entire* `MAILER_DSN` value. After all, it's a pretty short string.

Next... let's actually *do* that configuration! It's time to create a SendGrid
account and start using it.
