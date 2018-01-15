# Services

It's time to talk about the most important part of Symphony services.

Symphony is really just a group of useful objects that work together to form a
framework. For example there's a router object which helps the routing system.
There's a twig object which is what's called Behind the scenes when we render a
template. And there's a logger object which symphony is already using.

Internally. To store things in a Dabdoub log file. Everything in Symphonie is
done by one of these useful objects and these useful objects are given a name
service.

A service. But don't get all excited when I say service. Just think an object
that does work for me. Simply is. Simply comes with. A huge number of services
which I want you to think of as your tools.

For example if I gave you the logger service then you would be able to use that
to log messages. So the entire second half of Symphonie is all about learning.
Where to find these services and how to use them.

And actually the logger is a really good example. If you go to a terminal and
run tailbacks.

Var logs dev dash log. I'll clear the screen. And then when we refresh. And
move back. There are some walk messages. This proves that there is a logger
somewhere in the system. So could we log messages as well. Yup we just need to
get access to the logger service. How.

From inside of a controller. You can add an additional argument. And give it a
logger interface type hint. I tab to autocomplete back and then say laager.
Remember when I autocomplete you statements and Pietri storm. They add a U
statement on top.

As soon as I do that. Now I get to logger arrow info. Article. Is. Being.
Guarded.

Before we talk about that. Let's try it. Go back. Let's try it. Switch over.
Click the heart. And.

You back. Yes there it is at the bottom of our tail. Article is being hard it.
By the way. A nicer way. I control see to exit that was actually a nicer way to
see the logs is to find the profiler for that request saw what the two are
going to be profiling for the AJAX request and then go to the logs tab. Yep
there is our article is being hardened.

But how the heck did this work.

When Symphonie rendered the page it actually looked at the hype. Hint laager
interface and knew that we wanted the laager service object and so it
automatically passed it to us. This is a really powerful thing. If you need a
service you just need to know what type hint to use to get it. So how the heck
did I know to use laager interface. Well of course the symphony documentation
if you look at the laager symphony backwardation it's gonna tell you what type
to use but there's a cooler way. Go to a terminal and run in console debug auto
wiring. Boom. This is a full list of all of the type points that you can use in
the system to get a service. You'll notice they all say alias to something.
That's not that. Don't worry about that too much. That's just an internal name
for the object that we'll learn more about later.

The point is this all of these blue text here are valid type pins that you can
use which is how I need to use laager interface. Then actually check this out.
If you want to use twig you can use either of these to type hits. And remember
I just said that everything in Symphonie is actually done by a service. So for
example when you call this error render. The logic for that. That actually just
goes and fetches a service and calls a method on it. What I mean is let's
pretend that that renders shortcut method doesn't exist. How could we render
templates. While all we need to do is get the Twiggs service. Slots at a second
argument called environment because that's the name of the. Type hint.

I'll call this twig environments. And then I'll change the return to each team
of equals. Twig environment. Arrow render the method name just happens to be
the same. Then at the bottom as I return new response. Import the age T.M.
inside of their.

The Render function when you call a render function on the controller. All it
really does is call render on the twig service and then wrap it inside of the
response object for you. What I mean is. If you go back and refresh. This works
exactly like before. Of course this is way too much work. So I'm going to
change it back to just return. This error or render. And I wanted to show the
other way. To show you how things actually work behind the scenes. This is
really important because some day you might be coding somewhere that is not a
controller and you need to know how to render a tablet. SUNAO thinks of the
Deba auto Wareing command. You know you can get a list of what all of your
tools are in the application and it's hype into use to get them.

But. When you install new packages you're going to get new tools. In fact
that's the main job. The main reason to install a new package is to get more
tools. Let me show you an example. Run. Composer require serializer. Serializer
is a symphony Lyor that helps turn things into. And helps serialize things into
Jaison or X amount. It's really useful for creating API is. As soon as it
finishes. Run then console debug auto wiring again and check this out. We have
new tools. This entire section there are now a whole bunch of different. Types
that you type hints that you can use. To get the serializer. Now I don't expect
you to look in this list and automatically know which to use. In the real world
you would read the serializer documentation.

And it would tell you. What to use. What you really need to understand how this
system works. So let's actually use this one serializer interface. We use down
here on our. Ajax and point. Will say serializer interface. Serializer. And
then instead of returning a decent response. Directly. I'll say Jaison equals.

Serializer Aeros. And the cool thing is because of the typing. We get auto
completion. And I'll pass on the data and I'll pass and Jaison. Now we still
need to return a Jaison. A response not a string. So return Jaison response.
From Jaison string and pass Jaison. From Jayson's string a fancy. Is just.

An alternate way to use the Jason response. It won't be encode the Jaison
string but it will still set the content type header for us. Without having any
configuration.

It still works. We've taken advantage of a new tool the serializer. Now the
serializer is actually overkill in this case it works best when you have
objects that you want to serialize.

But I wanted to prove that it worked. Now that we have it. Let's remove the
serializer.

And then we all go back to using our simpler code. Turn new Jaison response or
pass. That.

Code. And at the top.

Remove a couple of use statements. We don't need Ikebe response in case we need
it. Serializer uninstaller itself. We have one less tool now but our site still
works.
