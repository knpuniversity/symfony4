# Global From

Coming soon...

[inaudible].

The other thing I really like to do is I don't like to have this from on every single
email that I create because probably we want this from to be the same for every
single email and now that we know that mail or dispatches an event, every time it
sends a message, we could probably hook into that event to globally set the from on
every single message. But wait a second, a moment ago, we configure the envelope
listener as a service and we use it in the depth in the dev environment to
colloquially set the recipients. And as you can see here, we can also pass a sender
as the first argument. And if a center is set that overrides the sender on the
message. So is this setting the from just as easy as actually passing a value to the
first argument of envelope listener? The answer is no. [inaudible] remember a second
ago how I explained that an email is a message and then an envelope around that
message when you're

[inaudible]

the two on an email is the, is what goes on the message and the sender is actually
what goes and the recipients is actually what goes on the envelope and impacts, uh,
where it's actually delivered. The same is true when it comes to the from versus the
sender. And this one is a little bit more subtle. The center once again ends up
basically being written on the envelope and it's who it says that the message is
actually being delivered,

uh,

on, on the envelope. But it's the, from that is actually written on the message. And
so it's the from that is actually going to impact what you see inside of your email
itself.

Okay.

So actually it's kind of silly as it sounds.

Okay.

If we overrode, if we set the sender only,

okay.

Today the center is actually not enough. If we [inaudible] right now if we set the

no, let's do it that way.

So if you sell only the from Miller automatically takes the from and makes that the
sender on the envelope. But if we removed this line here and only set the sender, we
would actually get an air because we would now have a message with the Audi from on
it. So basically what we want to set is not the sender but the from, which is a long
way of saying that this stuff is confusing and silly. But the point is we can't use
envelope listener because what we really need to override is the from not the center.
So no problem, we're just going to create our own event listener. So in the source
directory, I'm going to create a new directory called event listener. And then inside
there and you PHP class called Seth from listener, make this implement event
subscriber interface, the interface for all subscribers. And then I'll go to the Code
-> Generate menu or Command + N on a Mac and hit "Implement Methods" to add the one,
get subscribed events here, returned an array. And the message we, the event we want
to listen to is called a message event. So a message event, ::class, and it's say
equal->and we'll say on, we'll say on message, this becomes the name of our, that
method that will be in the instead of here.

Ben

on top, I'll say public function on message and because we're listening to the
message event class, that's what we're going to get past here. So I'll say a
messaging event, dollar sign event. So cool. So what's inside of this event anyways?
Well, the most important thing that's out of here is the email that you can say email
= you vent->gift message. Now if you look at that, I'm going to hold command and
click that get message. You see that this returns something called a raw message,
which you know at first you're sort of like, what is this raw message thing? So far
we've been working with email objects or template of email objects. Well if you do a
little bit of digging, if you open template the email and you'll see Templeton email,
equal extends email, email extends, message and message extends raw message.
Basically what you send. Typically what we actually pass to mail or down here is an
instance of template of the email or email, but on a really, really low level, all
that mailer really needs here is a raw message, so when you're adding a listener it's
possible to it.

Yeah,

I'm not, I'll close a couple of classes. The point is when you have a listener here
and you say event get message, this is going to pass back whatever object was
actually sent to the send method, which in our case it's always going to be a
template of the email object, but just to be safe we'll say if not email instance of
email, so it's not at least an email instance. Make sure you get the one from the
mind component. We'll just return. This must be, this must be S this shouldn't
happen, but in theory if some third party bundle, we're doing something really low
level, you can do this. You can also throw an exception here. Then down here, now
that we know that this is an instance of email, we can say email,->set->from, and
let's go grab the from from our class here. I'll hit undo copy of that.

Is that from new name to address? Alien mailer@example.com the space bar. And I'll re
type the S on named address and hit tab to add that use statement on top. That's it.
So we've now globally set the from, I'll go back to my mailer and we'll delete it
from send walk a message and the second email as well. All right, so let's try it. Go
back over and I'll click back because we can register as any user because we know in
the development environment, regardless of the email here, all messages will be
delivered to Ryan at [inaudible] dot com type any password, hit register and let's go
check out her inbox. There it is.

[inaudible]

welcome to the space bar. And you can see here it's um, from alien mailer. Add
example, that com. So the global from still is sent in. The email looks great. Next,
let's talk about something different.