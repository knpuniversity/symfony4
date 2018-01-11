# Routes, Controllers, Pages, oh my!

Let's create our *first* page! Actually, this is the *main* job of a framework:
to give you a *route* and *controller* system. A route is configuration that defines
the URL for a page and a controller is a function that *we* write that *actually*
builds the content for that page.

And right now... our app is *really* small! Instead of weighing down your project
with *every* possible feature you could ever need - after all, we're *not* in
zero-gravity yet - a Symfony app is basically just a small route-controller system.
Later, we'll install more features when we need them, like a warp drive! Those always
come in handy. Adding more features is *actually* going to be pretty awesome. More
on that later.

## First Route & Controller

Open your app's main routing file: `config/routes.yaml`:

[[[ code('ca7e7b48e4') ]]]

Hey! We already have an example! Uncomment that. Ignore the `index` key for now:
that's the internal *name* of the route, but it's not important yet.

This says that when someone goes to the homepage - `/` - Symfony should execute
an `index()` method in a `DefaultController` class. Change this to `ArticleController`
and the method to `homepage`:

[[[ code('f5c6ae0ed2') ]]]

And... yea! That's a route! Hi route! It defines the URL and tells Symfony what
controller function to execute.

The controller class doesn't exist yet, so let's create it! Right-click on the
`Controller` directory and go to "New" or press `Cmd`+`N` on a Mac. Choose "PHP Class".
And, yes! Remember that Composer setup we did in Preferences? Thanks to that, PhpStorm
correctly guesses the namespace! The force is strong with this one... The namespace
for every class in `src/` should be `App` plus whatever sub-directory it's in.

Name this `ArticleController`:

[[[ code('b80d9887fc') ]]]

And inside, add `public function homepage()`:

[[[ code('87255115fa') ]]]

*This* function is the controller... and it's *our* place to build the page. To be
more confusing, it's also called an "action", or "ghob" to its Klingon friends.

Anyways, we can do *whatever* we want here: make database queries, API calls, take
soil samples looking for organic materials or render a template. There's just *one*
rule: a controller must return a Symfony `Response` object.

So let's say: `return new Response()`: we want the one from `HttpFoundation`. Give
it a calm message: `OMG! My first page already! WOOO!`:

[[[ code('9b6091d1b8') ]]]

Ahem. Oh, and check this out: when I let PhpStorm auto-complete the `Response` class
it added this `use` statement to the top of the file automatically:

[[[ code('8c7baf2d54') ]]]

You'll see me do that a lot. Good job Storm!

Let's try the page! Find your browser. Oh, this "Welcome" page only shows if you
don't have *any* routes configured. Refresh! Yes! This is *our* page. Our first of
*many*.

## Annotation Routes

That was *pretty* easy, but it can be easier! Instead of creating our routes in
YAML, let's use a cool feature called *annotations*. This is an extra feature, so
we need to install it. Find your open terminal and run:

```terminal
composer require annotations
```

Interesting... this `annotations` package *actually* installed `sensio/framework-extra-bundle`.
We're going to talk about how that works *very* soon.

Now, about these annotation routes. Comment-out the YAML route:

[[[ code('aa861906ab') ]]]

Then, in `ArticleController`, above the controller method, add `/**`, hit enter,
clear this out, and say `@Route()`. You can use either class - but make sure PhpStorm
adds the `use` statement on top. Then add `"/"`:

[[[ code('e9f8d1dc4a') ]]]

That's it! The route is defined *right* above the controller, which is why I *love*
annotation routes: everything is in one place. But don't trust me, find your browser
and refresh. It's a traaaap! I mean, it works!

***TIP
What *exactly* are annotations? They're PHP comments that are read as configuration.
***

## Fancy Wildcard Routes

So what else can we do with routes? Create another public function called `show()`.
I want this page to eventually display a full article. Give it a route:
`@Route("/news/why-asteroids-taste-like-bacon")`:

[[[ code('cae709ac9c') ]]]

Eventually, this is how we want our URLs to look. This is called a "slug", it's
a URL version of the title. As usual, return a
`new Response('Future page to show one space article!')`:

[[[ code('7ba5de7f42') ]]]

Perfect! Copy that URL and try it in your browser. It works... but this sucks!
I don't want to build a route and controller for *every* single article that lives
in the database. Nope, we need a route that can match `/news/` *anything*. How?
Use `{slug}`:

[[[ code('d572f8cdbc') ]]]

This route *now* matches `/news/` anything: that `{slug}` is a *wildcard*. Oh, and
the name `slug` could be anything. But whatever you choose now becomes available
as an *argument* to your "ghob", I mean your action.

So let's refactor our success message to say:

> Future page to show the article

And then that slug:

[[[ code('f97fda18bb') ]]]

Try it! Refresh the same URL. Yes! It matches the route *and* the slug prints!
Change it to something else: `/why-asteroids-taste-like-tacos`. So delicious!
Go back to bacon... because... ya know... everyone knows that's what asteroids
*really* taste like.

And... yes! We're 3 chapters in and you *now* know the first *half* of Symfony:
the route & controller system. Sure, you can do fancier things with routes, like
match regular expressions, HTTP methods or host names - but that will all be pretty
easy for you now.

It's time to move on to something *really* important: it's time to learn about Symfony
Flex and the *recipe* system. Yum!
