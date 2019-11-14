# Events & Overriding "Recipients"

I want to propose two cool ideas.

First, while we're developing, if we decide to use Mailtrap, great: all of our
emails will go there. But if we decide that we want to use SendGrid to send
*real* emails while developing... it's a little trickier. For example, whenever
you register, you would need to use a *real* email address. Otherwise, the email
would never make it to your inbox.

So here's idea number 1: what if, in the `dev` environment only, we globally
*override* the "to" of every email and send to ourselves. So even if we
registered as `space_cadet@example.com` - the email would *actually* be delivered
to our real address: `ryan@symfonycasts.com` for me. That would be cool!

My *second* idea is similar: instead of *manually* setting the `from()`
on *every* email object... what if we hook into mailer and set this *globally*.
That's less duplication and more consistency.

## Hooking into Mailer: MessageEvent

The way to accomplish *both* of these is by leveraging an *event*. Whenever an
email is sent through Mailer, internally, it dispatches *one* event called
`MessageEvent`. Mailer itself comes with a *couple* of classes that can
"listen" to this event. The most interesting one is called `EnvelopeListener`.

## Built-in Listener: EnvelopeListener

I'll hit Shift+Shift and look for `EnvelopeListener` so we can see inside. Start
by looking for `getSubscribedEvents()`. Yep! This is listening on
`MessageEvent`. Here's the idea: *if* you used this class, you could instantiate
it and pass a custom sender or a custom array of recipients. Then, whenever an
email is sent, the `onMessage()` method would be called and it would *override* that
stuff on the email.

I love it! Even though this class lives inside Mailer, Symfony doesn't *activate*
it by default: it's not currently being used. In Symfony 4.4, some new config
options were been added so you can activate & configure it easily:

```yml
# config/packages/mailer.yaml
# or config/packages/dev/mailer.yaml for only the dev environment
framework:
  mailer:
    envelope:
      sender: 'sender@example.org'
      recipients: ['redirected@example.org']
```

But in Symfony 4.3, if we want to use this class, we need to activate it manually...
which is kinda fun anyways.

So here's the plan: to start, in the development environment only, I want *all*
emails to *actually* be sent to `ryan@symfonycasts.com`, *regardless*
of the `to()` address on the email.

## Setting up the Dev Email

To do this, in `.env`, let's create a *brand* new, shiny environment variable:
`DEV_MAIL_RECIPIENT` set to, how about, `someone@example.com`. That's not a real
email, because each developer should need to copy this variable, open
their own `.env.local` file, and customize it to whatever *they* want.

## Registering EnvelopeListener in dev Only

Next, we need to register `EnvelopeListener` as a service... but *only* in the
`dev` environment: I don't want to change the recipients on production. To do
that, in the `config/` directory, create a new file called `services_dev.yaml`.
Thanks to that `_dev` part, this will only be loaded in the `dev` environment. At
the top, start with the same `_defaults` code that we have on top of our main
services file: `services:`, then the magic `_defaults:` to set up some *default*
options that we want to apply to *every* service registered in this file. The
default config we want is `autowire: true` and `autoconfigure: true`.

Now, let's register `EnvelopeListener` as a service. Copy its namespace, paste,
add a `\` then go copy the class name and put that here too. For arguments, the
class has two: `$sender` and an array of `$recipients`. We'll focus on setting
the "sender" globally in a few minutes... but for right now, I *don't* want to
use that feature... so we can set the argument to `null`. Under arguments, use
`- null` for sender and, for recipients, `- []` with
one email inside. To reference the environment variable we created, say `%env()%`,
then copy the variable name - `DEV_MAIL_RECIPIENT` - and paste it in the middle.

That should be it! This will register the service and, thanks to `autoconfigure`,
Symfony will configure it as an event subscriber.

Testing time! Move over, refresh and... ah! I have a typo! The key should be
`_defaults` with an "s". Try it again. This time register with a fake email:
`thetruthisoutthere13@example.com`, any password, agree to the terms and register!

Because our app is configured to use SendGrid... that *should* have sent a
*real* email. Check the inbox - we have a new one! That's the original
email from a minute ago on top... and here's the new one.

## Recipients Versus To

But! This is even cooler. If you were watching *really* closely, you
may have noticed that, in `EnvelopeListener`, what we're *setting* is something
called "recipients". But when we create an email... we use a method call `->to()`.
It turns out, those are two different concepts. Gasp!

Back over in gmail, I'll click to view the "original" message. Check this out:
this email is *to* `thetruthisoutthere13@example.com`. Search for
`ryan@symfonycasts`. Hmm, it says `Delivered-To: ryan@symfonycasts.com`.

## Envelope Versus Message

Here's what's going on. *Just* like how, in the real world, you put a "message"
into an "envelope" and then send it through the *real-world* mail, an email is
*also* these same two parts: the message itself and an *envelope* that goes around
it. The `To` of an email is what's written on top of the "message". But the
*envelope* around that message could have a totally *different* address on it.
*That* is known as the "recipient". The envelope is how the email is *delivered*.
And the message is basically what you're looking at inside your inbox.

So by setting the recipients, we changed the address on the envelope, which caused
the email to be *delivered* to `ryan@symfonycasts.com`. But the `To` on the
message inside is still `thetruthisoutthere13@example.com`.

This... for the most part... is just fun mail trivia. *Most* of the time, the
"To" and the "recipients" will be the same. And... that's exactly what happens
if you set the `To` but *don't* set the recipients: mailer sets the
recipients *for* you... to match the `To`.

This idea becomes even *more* important when we talk about setting the `from`
address globally so we don't need to set it on every email. Because... yep,
`from` is different than "sender". That's next.
