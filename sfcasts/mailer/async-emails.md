# Async Emails with Messenger

Sending an email - like after we complete registration - takes a little bit of time
because it involves making a network request to SendGrid. Yep, sending emails is
*always* going to be a "heavy" operation. And whenever you're doing something
heavy... it means your user is waiting for the response. That's... not the end
of the world... but it's not ideal.

So... when a user registers, instead of sending the email immediately, could we
send it... later and return the response faster? Of course! Thanks to Symfony's
Messenger component, which has first-class integration with Mailer.

## Installing & Configuring Messenger

First: in our editor, open `.env.local` and, for simplicity. let's change the
`MAILER_DSN` back to use Mailtrap. To install Messenger... you can kinda guess
the command. In your terminal, run:

```terminal
composer require messenger
```

Messenger is *super* cool and we have
[an entire tutorial](https://symfonycasts.com/screencast/messenger) about it.
But, it's also simple to get set up and running. Let's see how.

The recipe for Messenger just did a few things: it created a new `messenger.yaml`
configuration file and also added a section in `.env`. Let's go find that.

Here's the 30 second description of how to get Messenger set up. In order to do
some work "later" - like sending an email - you need to configure a "queueing"
system where details about that work - called "messages" - will be sent. Messenger
calls these transports. Because we're already using Doctrine, the easiest "queueing"
system is a database table. Uncomment that `MESSENGER_TRANSPORT_DSN` to use it.

Next, open `config/packages/messenger.yaml` - that's the new config file - and
uncomment the transport called `async`.

## Making Emails Async

Great. As *soon* as you install Messenger, when Mailer sends an email, internally,
it will automatically start doing that by *dispatching* a message through Messenger.
Hit Shift + Shift to open a class called `SendEmailMessage`.

*Specifically*, Mailer will create *this* object, put our `Email` message inside,
and dispatch it through Messenger.

Now, if we *only* installed messenger, the fact that this is being dispatched
through the message bus would make... absolutely no difference. The emails would
*still* be handled immediately - or *synchronously*.

But *now* we can tell Messenger to "send" instances of `SendEmailMessage` to
our `async` transport *instead* of "handling" them - meaning *delivering* the
email - right now. We do that via the `routing` section. Go copy the namespace of
the `SendEmailMessage` class and, under `routing`, I'll clear out the comments and
say `Symfony\Component\Mailer\Messenger\`, copy the class name, and paste:
`SendEmailMessage`. Set this to `async`.

Hey! We just made *all* emails async! Woo! Let's try it: find the registration
page.... register as "Fox", email `thetruthisoutthere15@example.com`, any password,
agree to the terms and register!

You may not have noticed, but if you compared the response times of submitting
the form before and after that change... this was way, *way* faster.

## Checking out the Queue

Over in Mailtrap... there are no new messages. I can refresh and... nothing. The
email was *not* delivered. Yay! Where is it? Sitting & waiting inside our queue...
which is a database table. You can see it by running:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM messenger_messages'
```

That table was automatically created when we sent our first message. It has one
row with our *one* Email inside. If you look closely... you can see the details:
the subject, and the email *template* that will be rendered when it's delivered.

## Running the Worker

How do we *actually* send the email? In Messenger, you process any waiting
messages in the queue by running:

```terminal
php bin/console messenger:consume -vv
```

The `-vv` adds extra debugging info... it's more fun. This process is called a
"worker" - and you'll have at least one of these commands running at all times
on production. Check out our Messenger tutorial for details about that.

Cool! The message was "received" from the queue and "handled"... which is a fancy
way in *this* case to say that the email *was* actually delivered! Go check out
Mailtrap! Ah! There it is! The full correct email... in all its glory.

By the way, in order for your emails to be rendered correctly when being sent
via Messenger, you need to make sure that you have the
[route context parameters](https://symfonycasts.com/screencast/mailer/route-context)
set up correctly. That's a topic we covered earlier in this tutorial.

So... congrats on your new shiny async emails! Next, let's make sure that the
"author weekly report" email still works... because... honestly... there's going
to be a gotcha. Also, how does sending to a transport affect our functional tests?
