# Webpacking our First Assets

We're talking about a lot of stuff in here. Most of it are
optional features. The two biggest things are these set output path, which is going
to say that I want my final files to be put into a public build directory. The public
path to that is /build. And then down here, this ad entry thing in entry is basically
a standalone JavaScript file that's going to keep that a standalone. It, uh, Java
JavaScript file. It's kind of

okay.

A standalone JavaScript file,

okay.

That you want your user to execute.

Okay.

And you'd see this first key or it key here. It can be anything. That's going to be
the name of the final, uh, built file. And we just pointed out a JavaScript file.

Okay,

so we actually start with a small structure in here. Assets, js, APP dot js.

Okay.

You look inside there, there's not much, we have a council that log inside of here,
but we also have require key. We're actually requiring this CSS file. We're gonna
talk a lot more about this later, but you can actually define your dependencies of
your CSS in your JavaScript.

Okay.

Webpack is going to be able to build all of your JavaScript and all of your CSS.

Okay.

In fact, to make this a little bit more obvious to see if this is working, I'm
actually going to go into APP dot CSS, change this to light blue. Important because
then when I really see once you put this on the page whether or not this is actually
working.

Okay.

Alright. And one last thing before we actually try this. Uh, there's a line inside of
your Webpack cafe called thought enabled single runtime check. Actually it changed
that too. Dot. Disabled single runtime check. We will talk about what that means
later, but that's going to be a simple worst set up to start. So yeah, we've told uh,
a wetback where to put our files. We've told them which one file to parse and then
it's going to take care of just parsing that file and outputting the JavaScript and
the CSS file.

Okay.

To run this, go back to your terminal and run dot /node modules dot been encore and
then Dev, because this will be our development build.

Okay.

And Nice to get a nice little uh, notification

and it builds an APP dot CSS and an APP dot js file. You can see those inside of our
public build directory. That's it. App Dot js contains the js code and because of
this requires statement, it actually followed it and found this app dot CSS and build
an APP dot CSS file. This could be called App Foo and this could be called the APP
Fu. Um, but they're ultimately going to be the apple file's going to call app dot CSS
because that's the name of our entry in Webpack dot config. That doesn't make total
sense yet. Don't worry. We're going to talk more about that later.

Okay,

so finally to give us on our page, I'm going to go into templates based studies from
a twig and I'm gonna keep all my old CSS for now, but we're going to add a link, rel
= style sheet h ref = and just,

okay.

Is that normal asset build /app dot CSS. And then same thing down at the bottom for
the script tag. I'll leave my old scripts for now, say script as her c equals.

Okay.

Use the asset function to point to build /APP dot CSS. And if you're not familiar
with the asset function, it's actually really not important. It's not giving us
anything, uh, special. We're just literally pointing since public is our build
directory to app dot CSS. Oh my gosh. Of course you'd be app dot js not CSS. Awesome.
So now I move over, refresh and hello, blue background. And then the log there is our
console.log(. So we've just started to scratch the service because you might be
thinking, what did this do for me? Really? A wetback really hasn't done a lot yet.
Yes, it is a parsing. It's, it hasn't done much it yet. That's the next one. Talking
about what Webpack actually does, what it actually gives us. That's awesome. And
really start leveraging it.
