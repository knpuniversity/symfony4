# Async Emails

Coming soon...

Sending an email like after we complete the registration form that obviously involves
talking to a talking to or making requests to another network. So sending emails as
inherently something that's heavy and whenever you do something heavy that's going to
slow down your user experience. So the question is in, when do we register? Instead
of sending the emails immediately, could we send them a synchronously? Can we send
them later so that when we hit register, the user gets back a super fast response
instead of waiting for the email who sent? The answer is of course thanks to Symfonys
and messenger components, which has a really, really good integration with mailer.
First thing over in our editor, I'm going to open my bat in that local and for
simplicity I'm going to change back to using a mail trapper. Next, find a terminal
and let's run composer require messenger to download these Symfony /messenger
components. We have an entire tutorial about messenger. It's super fun to use. Um,

[inaudible].

So go check it out. I'll show you in just a few minutes how you can get set up with
sending asynchronous emails.

[inaudible]

this package. His recipe did a couple of things, created a new configuration file we
use and also updated that in file. So let's go look at dot N. perfect. Down here. So
to configure where to use messenger, you need to send to all of your, you need to S
uh, con configure a queue. The easiest one to use is the doctrine, which basically
means that asynchronous messages are gonna be stored in a database using doctrines.
I'll uncomment that messenger transport DSN. Next up in config packages, messenger,
Oh, here's a new file. I'm going to uncommit a new transport called async. So as soon
as you install messenger, whenever you send an email with mailer internally, mailers,
dispatch Miller, dis Miller sends it by dispatching a new message through messenger.
I'll have shift shift to open up a class called send email message.

Perfect. So specifically a mailer creates this object, puts our email message inside
and dispatches this through messenger. Now if the only thing we did was install
messenger, the fact that this is being dispatched through the message bus makes no
difference. It's still sent synchronously. If we registered right now, you would see
no difference at all. However, we can now route in this message two are async
transport. And by doing that, instead of sending the message, now it's going to queue
it through in our transport, which means it's going to save it into a a, it's going
to save it to a database table. So let's try that down here. I'm actually going to
copy the namespace of the, uh, send email message class and down here under routing,
I'll clear out the comments and we'll say Symfony /component /[inaudible] /messenger.
/then I'll copy, send email message and paste that.

And we're going to send this to the async transport. Perfect. Okay, let's try this.
I'm going to move over. Refresh the registration page. Everything's good. It
registered as Fox. The truth is out there. 15 example.com. Any password agree to the
terms and registered and you may not have noticed it there, but if we actually
profiled the registration before and after that change, that registration was way
faster because it didn't actually deliver the email. Check it out in Mailtrap.
There's no new messages here. I can refresh nothing. It is not being delivered. Where
did the message go? Well, because we're using the doctrine transport. It's an a new
database table. We can actually see it by running bin console doctrine query SQL,
select star from messenger_messages, which is the default name of the table that was
just created and there it is. There's one row inside of here and it has our message
inside. If you look closely enough you would actually see our

content inside of there

and the template name that should be used when it sends the email. How do we actually
send this? Well that's specific to messenger. The way you do it by running Ben,
consult messenger, colon consume dash V a and then we'll pass gas VV to get some more
debugging tools. This is something you'd have running that's called a worker. You'd
have a running on production at all times. This is constantly checking that database
table, finding messages on them and then processing them. So received it and it
handle the message, which means it actually sent that email. And in fact when we go
over here, boom, there it is. Welcome to the space bar, the full correct email
message sitting here in all its glory. I needed to double check, but I think in order
for that to work, you needed to have the route context stuff set, which we do. Okay.
So let's check out our other email from the console command bet should work just the
same. So I'm gonna hit control C to exit out of the worker and just to make sure my
data's fresh, I'm gonna reload the fixtures and then run bin console app.

Hold on.

Author weekly report sent and Whoa, it explodes. Incorrect string value. Wow. Okay.
So I wanted to show you this because in the real world you might hit this. One of the
limitations of the doctrine transport is that you can't send it binary data. And
because our console command creates,

okay, should've done that.

And because our console commands sends the author of your prequel send author weekly
report message, it creates a PDF and attach that PDF messenger tries to put that
binary and PDF into the queue. The talk can transfer, doesn't support that. And this
may be something that's fixed in Symfony 4.4 automatically, or you may be able to opt
into fixing it with a configuration option. I'm not sure yet. So there are two
options to fix this. The first is instead of doctrine, you can use another transport
like a MQP. The second thing, if you absolutely need to use doctrine and you
absolutely need to send a PDF attachments, there's another option here. Instead of
saying attached, you can say attach from path and here you can pass it the path to a
PDF file. Ultimately what gets stored in the, um, in the queue is just that string
and then the file is loaded when you actually send the message. The only caveat is
that that file needs to exist. You need to put that file on the filesystem and then
it needs to exist when the worker actually, um, uh, uh, works on it. Um, but the
attachment path is a way to get around that.

There's one other thing that I want to show with a messenger. Oh, I want, you're
using messenger. Run your test PSB, bin /PHP unit.

Awesome.

There are a whole bunch of deprecation notices, but they pats, but check this out.
Rerun our doctrine query SQL. It depends on definitely how you have your database
credentials set up and that invoice and that local. But there's a pretty good chance
that when you run select star for messages, you're going to see a new message in
there. This is actually the message that was sent during our functional test test
controller security test. Inside of here, we made a test that actually went to
register and registered and of course that sent an email. Well, if your dev and test
environments share the same database, then you're actually gonna end up with a new
entry inside of there from the test. Which is kind of a bummer because it means that
if we

tried to do messenger consume, that would actually send that message from the test
environment, which technically isn't a problem because it's just going to go to
Mailtrap. But it is a little bit weird. So because of that in the test environment
only instead of using the doctrine transport, I like to use something called the
in-memory transport. So I'm gonna copy messenger,_transport_DSN, then opened up the
that in that test environment, paste this here and replace doctrine with in dash
member investment memory. So index memory, colon, slash, /and that's it. The number
of transport is basically a fake transport. It says if something is sent there, don't
actually do anything with it. It just kind of holds onto it. And then at the end of
the test it just goes away forever. So with this, it just cleans things up. Cause if
we run our tests after we run our tests, the database table is empty. So we're good
there. Next, let's talk about Encore.