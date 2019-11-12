# SendGrid & All About Transports

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

And... you're 1000% percent correct. This doesn't contain any username, or API
key. Heck, we haven't event created a SendGrid account yet! All true, all true.
But let's... try it anyways: Symfony is going to guide us through the process.

## Let Symfony Guide You to Configure the Transport

Head over to the browser and refresh. Woh! An immediate error:

> Unable to send emails via Sendgrid as the bridge is not installed.

This is *another* example of a Symfony making it easy to do something...
but *without* bloating our project with stuff we don't need. Now that we *do*
want to use Sendgrid, it helps us install a required library. Copy that
`composer require` line, spin over to your terminal and paste:

```terminal-silent
composer require symfony/sendgrid-mailer
```

Ooh, this package came with a recipe! Let's see what it did:

```terminal
git status
```

In addition to the *normal* files, this *also* changed our `.env` file. Let's
see how:

```terminal
git diff .env
```

Cool! This added a new section at the bottom. Back in our editor, let's see what's
going on in `.env`. Yea... this makes sense. We know that mailer is configured
via a `MAILER_DSN` environment variable... and so when we installed the SendGrid
mailer package, its recipe added a *suggestion* of how that variable should look
to work with SendGrid.

## SendGrid Symfony 4.4 Config Format

Two important notes about this. First, when you install this package in Symfony
4.4, the config added by the recipe will look a bit different: it will add just
one line:

```
MAILER_DSN=sendgrid://KEY@default
```

Like we just talked about, this is because Symfony 4.4 changed the config format:
the "transport type" is now at the beginning. The `KEY` is a placeholder that
we'll replace with a *real* API key in a few minutes - I'll show you that - and
the `@default` part just tells the SendGrid transport to send the message to
whatever the *actual* SendGrid hostname is. We don't need to look that up or
configure it - the transport already knows.

## A Note about Environment Variables inside Environment Variables

If you look at the config that Symfony 4.3 uses, you'll notice the second important
thing: this defines *two* environment variables: `SENDGRID_KEY` and *then*
`MAILER_DSN`. This is just a config trick. See how the `MAILER_DSN` value
contains `$SENDGRID_KEY`? That's *using* that variable: it's environment variables
inside environment variables! With this setup, you could commit this `MAILER_DSN`
value to `.env` and then *only* need to override `SENDGRID_KEY` in `.env.local`.

This idea - the idea of using environment variables *inside* environment variables
*totally* works in Symfony 4.4. But, just to keep the config a bit simpler, in
Symfony 4.4 - you won't see this in the recipe. Instead, we'll configure the
*entire* `MAILER_DSN` - it's simple enough.

Next... let's actually *do* that configuration! It's time to create a SendGrid
account and start using it.
