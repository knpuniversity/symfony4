# Creating, Configuring & Sending the Email Object

Time to send... an email! After a user registers for a new account, we should
probably send them a welcome email. The controller for this page lives at
`src/Controller/SecurityController.php`... find the `register()` method.

This is a very traditional controller: it creates a Symfony form, processes it,
saves a new `User` object to the database and ultimately redirects when it finishes.

Let's send an email right here: right *after* the user is saved, but *before*
the redirect. How? It's *gorgeous*. Start with `$email = (new Email())` - the
one from the `Mime` namespace.

## Mime & Mailer Components

Actually, this is a good moment to mention that when we talk about the Mailer
component in Symfony, we're actually talking about *two* components: Mailer and
Mime. The Mime component is all about creating & configuring the email itself and
Mailer is all about *sending* that email. But mostly... that's not too important:
just don't be surprised when you're using objects from this `Mime` namespace.

## Configuring the Email

I've put the new `Email` object in parentheses on purpose: it allows us to
immediately chain off of this to configure the message. Pretty much all the methods
on the `Email` class are... delightfully boring & familiar. Let's set the `->from()`
address to, how about, `alienmailer@example.com`, the `->to()` to the address of
the user that just registered - so `$user->getEmail()` - and this email needs
a snazzy subject!

> Welcome to the Space Bar!

Pure poetry. Finally, our email needs content! If you've sent emails before,
then you might know that an email can have text content, HTML content *or* both.
We'll talk about HTML content soon. But for now, let's set the `->text()` content
of the email to:

> Nice to meet you

And then open curly close curly, `$user->getFirstName()`, and, of course, a ❤️ emoji.

There are a bunch more methods on this class, like `cc()`, `addCc()`, `bcc()`
and more... but most of these are dead-easy to understand. And because it's such
a simple class, you can look inside to see what else is possible, like `replyTo()`.
We'll talk about many of these - like attaching files - later.

So... that's it! That's what it looks like to create an email. I hope this "wow'ed"
you... and disappointed you in its simplicity... all at the same time.

## Sending the Email

Ok... so now... how do we *send* this email? As soon as we installed the Mailer
component, Symfony configured a new mailer *service* for us that we can autowire
by using - surprise! - the `MailerInterface` type-hint.

Let's add that as one of the arguments to our controller method:
`MailerInterface $mailer`.

And... what methods does this object have on it? Oh, just one:
`$mailer->send()` and pass this `$email`.

I *love* how this looks. But... will it work? We haven't actually configured *how*
emails should be sent but... ah, let's just see what happens. Move over and register:
first name `Fox` (last name, Mulder, in case you're wondering), email:
`thetruthisoutthere@example.com`, any password, agree to the terms that we definitely
read and, register!

Ah! Error!

> Environment variable not found: MAILER_DSN

Ok, *fine*! To actually *deliver* emails, we need to add some configuration via
this environment variable. Let's talk about that next... including some awesome
options for debugging emails while you're developing.
