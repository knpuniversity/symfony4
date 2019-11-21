# Setting "From" Globally

I don't like to have this `->from()` on every single email that I create.
This will probably *always* be the same, so let's set it globally.

We know that Mailer dispatches an event each time it sends an email. So, we
could probably create a *listener* for that event and set the `from` address
from there!

But wait. A minute ago, we configured `EnvelopeListener` as a service in the
`dev` environment and used it to globally override the recipients. This class
*also* allows us to pass a "sender" as the first argument. If we did, it would
override the sender on this "envelope" thing.

So, is setting the `from` globally as easy as passing a value to the first argument
of `EnvelopeListener`? Is this video about 10 seconds from being over?

## From Versus Sender

Sadly... no. Remember when I mentioned that an email is two parts: a message and
then an envelope around that message? When you set the `->to()` on an Email, that
goes into the message. The *recipients* is what goes on the *envelope*... which
*totally* impacts *where* the email is delivered, but does *not* impact who the
email *appears* to be addressed to when reading the email.

The same is true when it comes to `from()` versus "sender". But this... is even
more subtle. The "sender" is the address that's written on the *envelope* and
the `from` is what *actually* goes into the message - this is the part that
the user will see when reading the email. It's a weird distinction: it's like
if someone mailed a letter on your behalf: *they* would be the sender - with
*their* address on the envelope. But when you opened the envelope, the message
inside would be signed *from* you.

The *point* is, setting the "sender" is not enough. When we set the `from()`,
Mailer *does* automatically use that to set the "sender" on the envelope... unless
it was set explicitly. But it does *not* do it the other way around: if we removed
the `->from()` line and only set the sender, Mailer would give us a huge error
because the message would have *no* from.

So what does this all mean? It means `EnvelopeListener` can't help us: we need to
override the "from", not the "sender". No problem: let's create our own event
listener.

## Creating the Event Subscriber

In the `src/` directory, create a new directory called `EventListener`. And inside,
a new PHP class called `SetFromListener`. Make this implement
`EventSubscriberInterface`: the interface for all subscribers. I'll go to the
"Code -> Generate" menu - or Command + N on a Mac - and hit "Implement Methods"
to add the one method required by this interface: `getSubscribedEvents()`.

Inside, return an array: we want to listen to `MessageEvent`. So:
`MessageEvent::class => 'onMessage'`. When this event occurs, call the `onMessage`
method... which we need to create!

On top, add `public function onMessage()`. Because we're listening to
`MessageEvent`, *that* will be the first argument: `MessageEvent $event`.

So... what's inside of this event object anyways? Surprise! The original Email!
Ok, maybe that's not *too* surprising. Add `$email = $event->getMessage()`.

But... is that... *truly* our original Email object... or is it something else?
Hold Command or Ctrl and click the `getMessage()` method to jump inside. Hmm, this
returns something called a `RawMessage`. What's that?

*We* have been working with `Email` objects or `TemplatedEmail` objects. Open up
`TemplatedEmail` and... let's dig! `TemplatedEmail` extends `Email`... `Email`
extends `Message`... and `Message` extends... ah ha! `RawMessage`!

Oooook. *We* typically work with `TemplatedEmail` or `Email`, but on a really,
really low level, all Mailer *really* needs is an instance of `RawMessage`. Let's...
close a few files. The point is: when we call `$event->getMessage()`, this will
return whatever object was actually passed to the `send()` method... which in our
case is always going to be a `TemplatedEmail` object. But just to be safe, let's
add if `!$email instanceof Email` - make sure you get the one from the Mime
component - just return. This shouldn't happen... but could in theory if a
third-party bundle sends emails. If you want to be safe, you could also
throw an exception here so you *know* if this happens.

Anyways, now that we're sure this is an `Email` object, we can say `$email->from()`...
go steal the `from()` inside `Mailer`... and paste here. Re-type the "S" on
`NamedAddress` and hit tab to add its `use` statement on top.

That's it! We just *globally* set the from! Back in `Mailer`, delete it from
`sendWelcomeMessage()`... and also from the weekly report email.

Testing time! Register with *any* email - because we know that all emails are being
delivered to `ryan@symfonycasts.com` in the development environment - any password,
hit register and... run over to the inbox!

There it is! Welcome to The Space Bar *from* `alienmailer@example.com`.

Next, sending an email requires a network call... so it's a *heavy* operation.
We can speed up the user experience by sending emails asynchronously via Messenger.
