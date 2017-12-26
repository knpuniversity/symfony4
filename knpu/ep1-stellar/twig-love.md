# Twig <3

We know that Symphony's controller system is simple you just need to return a
symphony response object. Symphony does not care how you create that response
object. You could render templates make API requests do anything that you want.
Now these days. Some applications are built as pure API backhands that talk to
a javascript front and in some applications still render their own. Each team
all from the right to do a mixture of both so you can see how they work. But
for now I'm going to make these pages actually return each team out and I'm not
going to put the email directly in my controller. That would be terrible. We
are going to use a templating engine. Called twitt. Add this text before.
Symphonie is. Most of learning Symphonie is about learning. To install into use
optional tools inside of your controller to make your job easier.

One of those is a templating engine and we are going to use an awesome one
called TWIC. First. Make sure you commit all your changes. I already have. This
is going to make. Watching what the recipes do. Much more fun. Not run composer
required require tweak.

As you can see this installs the tweak bundle and a few other libraries and
configures are recipe. Ah. So let's see what that did. Run. Get status. I made
a few changes the first config slash bundles that page. This is not really
important. This is the place where all. Bundles. Are a plugin for symphony.

And when you install a bundle the recipe automatically takes care of
registering it with your application so it just works instantly. So this is
basically a detail you don't need to worry about. The recipe did. Three. The
recipe the two other recipe did a few other things I want to talk about two of
them. First it created a templates directory so you don't have to guess where
templates go. If you're using templates it creates a template directory. It
also created a base layout file that will use in a few minutes. The Bunna also
needs some configuration so it automatically added a config packages tweak that
Yemenite. And even though this file was added by the recipe it's yours to
modify. You can make what ever changes you want. This is really cool. Why do
your templates live in a temples directory. Because the configuration says that
exact thing. Don't worry about these percent. Syntaxes yet we're going to talk
about that in a future episode. But you can already see that preset kernel that
project her.

Is just some sort of variable to point to the root of her project.

So we're looking into the details of what the recipe did but the lesson is
always the same when you install a library. It takes care of the rest.

All right. Open article controller. We're going to render a template inside of
our show page as soon as you want to render a template. You need to extend.

A base class called abstract controller. Notice we hadn't extended anything yet
because simply doesn't care what your controller looks like. But by extending
abstract controller it's going to give us some shortcuts. The best shortcut is
return this error or render and you'll give us the name.

File name to your template. Let's say article slash showed that 2 last week so
that were consistent with our Controller name. The second argument is an array
of variables that you want to pass into your tweak template. Now eventually
we're going to start loading articles from the database but we don't have a
database yet. So let's just. Pass a title variable and let's set it to U.S.
words. And then SDL replace. Dasch with space. SLOC. In other words we're going
to take our slug and make it look like a title. And. For now that's it. The
temple locations are super easy. Inside your templates directory. Create a new
article directory. And then a new file called showed that each with that tweak
inside.

Twig it is really really easy. It only has 3 syntaxes the first syntax. Which
is curly Curly. Is the. Say something syntax. This is the print syntax so is
always Curly Curly and then a variable or curly Curly and then a string or
curly Curly and then a function it prints something.

It's that simple.

The second syntax is curly brace percent and that's called the do something
takk. It's used for things like an if statement or a for loop. I'll show you an
example of a second and I'll show you a full list of do something tax.

And the third type is not really a real type it's just comments curly brace
percent and then end with preset curly brace.

At the bottom of this page.

I'll paste some extra hard coded content for now. All right so check it out. Go
back to your page and refresh boom.

We have conta now notice if you view your page source it's just that content.
We do not have a base layout yet we don't have an HMO structure. Fix that in a
few minutes. But go back to your controller first.

And want a pass and a second variable so I'm going to paste in three fake
comments for this article. And then add a second variable called comments that
will pass into our template. So this time it's not as simple as just printing
those we need to loop over that. So let's add at the bottom and H2 that says
comments and keep things simple I'll make a UL and we'll use Crilley race
percent for. Loop. And will say for comment in the comments. And then most so
do something tags have a corresponding end tag so and for that inside the loop.
Comment is just your individual comment so we can go back toward Curly Curly
and say comment.

Perfect reference. And we've got Sugo to twig that Symphonie dot com.

And click on their documentation and then scroll down a little bit until you
see a couple of columns. This is awesome. You see these attacks on the left.
These are the entire list of do something tags so will always be curly brace.
Percent if Crilley race percent for curly brace percent block in. Honestly
you're only going to use about 5 of those so if you want to know more about how
a for loop works just click for the rest of this is pretty easy Twig has
functions which I assume you know how to use functions. It also has something
called tests which are a little more interesting but not difficult and a really
really cool thing called filters filters are like functions but have a
different syntax. Let me show you. There's one filter called length. To go back
to our tweaked template.

Message you want to print out the number of comments that are on this article
so I'll do a little parentheses and then we can say comments pipe length. That
is the filter the variable on the left passes to the right. Almost like a Unix
pipe and then the length just counts those and then we print the result.

We your. We've got. So let's talk about one more thing with Weygand that is
twigs. Amazing awesome killer. Lay out inheritance system.
