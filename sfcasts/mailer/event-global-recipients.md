# Event Global Recipients

Coming soon...

I want to propose two possible cool things that we could do to every email that we
sent. The first one is that while we're developing, if we decide to use a Mailtrap,
great, then all of our emails are going to go to Mailtrap. But if we decide that we
want to actually use SendGrid and send real emails in the development environment,
the tricky thing is that it means that whenever you register, you need to use a real
email address that you own so that you get the email in your inbox. What would be
cool is if we could override on a global level the two of every single uh, email
message so that even if we sent it to `coolguy@example.com`, it would end up in our
inbox. The second thing that we, that I want to do, and this is something that we
definitely need to do, is make sure that the forum is set globally. Right now we are
setting the from manually on every single email address and we really want to make
sure that we're sending from exactly the same user on all emails. So the way to
accomplish both of these is to leverage an event. So whenever you send a message in
mail or it dispatches one event called `MessageEvent`,

internally mailer itself has two listeners to this event. The first one is called
`MessageListener`, um, which does some internal stuff and is not that important. The
other one is called `EnvelopeListener`. I'm at shift shift and look for `EnvelopeListener`
to open that up, see what it looks like. So as you can see, `EnvelopeListener`, 
if we go down here to `getSubscribedEvents()`, is listening to the `MessageEvent`
this message event. And what it does is if you use it, you can pass it a
custom sender and a custom array of recipients and then will actually override the
sender and the recipients on every single email that's sent in the system. So this
class lives inside of Symfony, but by default Symfony doesn't use it. So right now
when we send an email, this method is not actually called in Symfony 4.4. Some
configuration has been added so that you can activate this listener and override the
sender and recipients on a global level without doing anything other than touching a
yaml file. But it's simply 4.3 you're going to need to activate it manually.

If you want to override the recipients globally, you need to activate this manually.
So that's what we're going to do. And we're also going to learn a tricky thing about
the sender here. Uh, maybe I won't mention that.

so to start, what I want to do is in the development environment, in the development
environment only, I want all the emails to actually be sent to my `ryan@symfonycasts.com`,
address regardless of who the actual email is sent to. So in `.env`
I'm actually going to create a brand new environment variable here. I'm
just making this up called `DEV_MAIL_RECIPIENT`

And I'm going to set this to `someone@example.com` and I'm going to set it. So the
default value won't be a real value here. And then I'm gonna copy that and I'm
immediately going to override it for my local machine and set this to
`ryan@symfonycasts.com` now this `EnvelopeListener`, we're going to want to activate
this, but I only want to activate this when we are in the dev environment. I
obviously don't want to change the recipients when we're on production. So to do that
I'm going to go into my `config/` directory and create a new file called `services_dev.yaml`
Because of that `_dev`, it's only going to be loaded. This file will only be
loaded in the dev environment and at the top of this I'm going to start with more or
less the same default code that we have inside of our services file, which is that
I'm going to have the `services:` key here.

I'm going to put `_default:` and then I'm a set `autowire: true` and `autoconfigure: true`
That's kind of standard thing I have at the top of all of my service files and
it says that by default all services defined in this file should have autowire `true`
and autoconfigure `true`. Now I'm the register this `EnvelopeListener` as a service.
So I'll actually copy its namespace down here and then I will say and and then I will
go copy its class name and `EnvelopeListener` and paste that. And then for arguments, this
class has two arguments and as a `$sender` and an `array $recipients` in a few minutes
we actually are going to globally set who the sender is but more than that had been
in a second. For now I don't want to override this. So I'm going to set this as `null`.

And as you see down here, if the `$sender` is set to `null`, then it doesn't override it.
It's more for the second argument, we are gonna pass an array with my one email
address in there. So for arguments I'm going to say dash `null` and then dash again for
the second argument in here, I will pass an array inside of yaml. Never the value,
I'm going to reference that environment variable. We do that with `%env()%`
And in the middle of the value here, we will
use dev. We will go copy my new environment, variable `DEV_MAIL_RECIPIENT` and paste it
in there. That should be it. That should register this as a listener because we have
autoconfigure, it will automatically it as an Event Listener and
ultimately we should have just overrode at the recipients for every single email.

Alright, so let's try that spin back over here and I'm going to go back to the
registration page. Oh, and I have a typo.

There you go. Yeah, we you just doing all

because, and my services as you can see my service with that yam, I meant to say
`_defaults`, not `_default`. Alright, try that again. Reload the
registration page. Then we'll go back to using a fake email address. 
`thetruthisoutthere13@example.com.` Any password? I agree to the terms and register. Now
remember, because we're configured to use SendGrid that actually sent to SendGrid. If
you check over in our inbox, we have a new email here. This is the original one from
a second ago, and you can see this is actually delivered to our inbox. Now, one
really important thing here is that you may or may not have noticed that in envelope
listener, these things are called recipients. But when we send the actual email
messages, we're setting a two. Those are actually, it turns out those are actually
two separate ideas

and it can be a little bit confusing. if you spin back over to the email
address, you can click the little.dot dot over here and say show original. And if you
look in here, you can see the two is `thetruthisoutthere13@example.com` and
if I search for `ryan@symfonycasts`, it actually says delivered to `ryan@symfonycasts`
and it has a couple other details down below there about it. So here's the
idea, just like in the real world, every email is a message and then that message is
put into an envelope. The To is actually what's written on top of the message. So
when we're looking at our email instead of our inbox, we're basically looking at the
message, but the envelope is what's actually used to deliver the message. And the
envelope is what can have recipients.

So by, by setting the recipients here, what we did is we changed who the end, we
changed the address on the envelope, which meant that it was delivered to our inbox.
But the message inside is still to, `thetruthisoutthere13@example.com`
So it's not something that's usually very important to understand, but there are
two separate concepts of the two versus the recipient by default. And the mailer,
when we set the, if we set the two but never set the recipient, then the recipients
are then mailer makes the recipients on the envelope equal the two on the message,
which is what you want most of the time. Anyways. Next, let's extend this idea to
globally set the from on every single email so that we don't have to repeat it every
single time that we send an email to do this, we won't be able to leverage the
envelope listener. Yeah.