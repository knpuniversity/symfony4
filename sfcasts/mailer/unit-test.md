# Unit Test

Coming soon...

Of course other than code organization. One of the benefits of having things in a
service is we can run a unit test for it. So this stuff doesn't really have a lot to
do with mailer, but it's a great practice to get into the habit of doing some unit
testing so you know your code doesn't explode on you. We're also in the future in the
next chapter is going to show an integration test and a functional test. We're
talking about functional testing. There is some cool stuff we can do with mailer. So
let's spin over to our terminal and we'll use maker bundle to run the make Ben
council make colon unit dash test. This is a really simple utility. We can say mailer
test and it generates us a really simple unit test. Um, file down here in our test
directory string C tests, mailer test. The idea is that this is going to be testing
our mailer class, which lives up here in the service directory. So right now the make
unit test always puts things in the root of test. I'm going to create a new service
directory and move my mailer test inside of there. Because typically your unit test
directory structure is gonna match the directory structure of whatever class it's
testing. So back in Miller tests, let's also remember to add these service namespace
since this now lives in a service directory.

All right? So right now we have one test that's asserting that true is true. Let's
make sure that that passes. So we'll spin over. And the way that you run tests and
Symfony is by running PHP bin /PHP unit. That's a little rapper around PHP unit.
First time you run it a we'll install PHP unit in the background and you'll see that
in a second

and it passes. One thing you'll notice on here is this deprecation notice. It's not
something we're going to talk about right now, but one of the side benefits of the,
uh, PHP units, um, integration is that you're gonna see your deprecation notices, any
deprecated code you're using at the bottom. All right? So let's get to work for real.
So if you think about what can we test on mailer, you know what? I'm basically, if I
were to write a unit test for this, I basically would probably want to make sure that
the mail is actually sent and it might actually want to assert a few things about the
male email itself that's created. Let's start by just making sure that the mail is
actually sent. So back in Miller tasks. Let's rename this to

test send. Welcome message. Very simply. First thing we'll do here is say mailer =
new mailer. We're going to instantiate an instance of our mailer. Now of course, to
do that we need, we have uh, four dependencies, uh, the mailer twig, PDF and the
entry point lookup interface. So to get those, we are gonna need to do some mocking.
So above, let's say Symfony mailer = this->create muck. So in the mail or the first
argument here in our constructor is a mailer interface. So we're going to mock that
mailer interface. So here I will say mailer interface, ::class. Now we'll create me a
mock of that. Now in this mock we're going to want to do is assert that the send
method is called exactly one time to do that. We'll say Symfony mailer,->expects
this->once that the method send is called and it's that simple. You can even go
further and assert that it's called with the email instance, but that's kind of going
to be built in since the type it

[inaudible].

Alright, let's get our other dependencies. P F = this->create mock PDF ::class. And
let's see the last two that we need here or actually in the tweak environment and the
entry point look up in her face. So I'll say twig = this arrow, create mock
environment and entry point look up = this arrow. Create mock entry point.

Look up. There we go. Entry point. Look up interface. Now these three methods
actually aren't even used. Those three services aren't even used by our method, which
is maybe a good reason why we could have this. I might with split this one mailer
service into two. But the point is for our test, we don't need to, um, uh, we don't
need to add any behavior to these three, the message, they just need to be there so
we can instantiate the service. So let's not pass Symfony mailer and let's see second
arguments and twig, then PDF, then the entry point, look up, enter point, look up.
Perfect. Now we're just gonna call it mailer arrow. Send a welcome message. To do
that, we need a user object. So let's go up here and say user = new user. And let's
see here. The only information that's we use after the users, the email, and the
first name. So we'll make sure that we have an email on a first name set on there so
that it's fed into this. And in a second, what we're actually going to assert that
the correct email and correct first name are ultimately used on that email object. So
like user->set, first name.

And let's use the name of my brave co author for this tutorial, Victor and
[inaudible]. Email to victor@Symfonycast.com. By the way, if you're enjoying the
tutorial, you can email Victor directly and bother him.

No. Have you used the object? We will pass it down there so we don't have any asserts
directly down here yet, but we do have an assert base of the up here. We're going to,
starting with the method should be called exactly once. All right, so let's try that
spin back over and I will once again, I'll clear my screen and run PHP bin /Jace unit
and it passes. So we are asserting that it was sent successfully. Now if we want you
to make this, uh, you know, the kind of tricky thing is the bulk majority of this
method is actually creating the email and you may or may not dependence on how
complex your logic is actually assert some things about the email itself. Of course,
we can't really do that very easily right now. We could maybe do a trick with the
mockup here to make sure that it gets an argument with the correct date on it. But an
easier way is actually down here. Where I'm doing is I'm just gonna return email
object from the method. I'll advertise it. This returns a templated email. I'll do
the same thing down here, returning an email

and advertise this return returns a template, an email. You don't have to do this,
but it's definitely gonna make our unit test a lot, a lot simpler and more useful. So
now we can say email = mailer arrow, send a welcome email message. Welcome message.
And this is just now a nice simple unit tests. So let's just assert a few things.
We'll cert same that the subject is walking into the space bar and that should be
equal to email.->get subject.

Okay.

And then we'll say a certain count that one should be equal to email->get to so that
this was sent to exactly one person. Now the amount of stuff that you want to start
down here, it just, it's totally subjective and up to you, I don't typically do a lot
of assertions on simple methods like this because they don't scare me that much. But
you can assert as little or as much as you want to. Let's go a little bit further
here and I'll say named addresses = email get to. So we actually get who we're
sending this to and we can do a couple of assertions to make sure it's actually being
sent over here to Victor about this to help my editor. I'll advertise that this is
going to be an array of named addresses. In fact to be totally sure of that, we'll do
this->assert instance of named address colon, colon and class. Um, that named
addresses zero index is going to be an instance of that. So we should have at least
we should have exactly one of these. And that one is an instance of named address

down here we can say assert same. I'll actually make sure we're actually setting the
right date on it. So Victor's should be equal to [inaudible] named address. Left
square bracket zero->get first get name.

Okay.

And this assert same that Victor is Symfony casts.

Okay.

Should be equal to name, dress zero index arrow, get a dress. So that's about

considering that this is a fairly simple email that's about all that we can, uh,
[inaudible]

assert on it. All right, so let's try this

move over and it passes. So just a really nice, by organizing it, we get a really
nice unit test. Now what we're going to do next is we're actually going to unit test
the author weekly report message. Except you know, this method is actually, if you
just look at the email part of this, this method is pretty simple. A more useful way
to test this method. Actually an integration test, a real test that actually tests
and make sure that the tweet that the twig template is really rendered. And that's,
um, our PDF utility is really generating the PDF on the filesystem. So instead of a
unit test, we are next going to, um, test this with an integration test.