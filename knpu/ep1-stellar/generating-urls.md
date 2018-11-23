# Generating URLs

Most of these links don't go anywhere yet. Whatever! No problem! We're going to
fill them in as we continue. Besides, most of our users will be in hypersleep for
at least a few more decades.

But we *can* hook up *some* of these - like the "Space Bar" logo text - that
should go to the homepage.

Open `templates/base.html.twig` and search for "The Space Bar":

[[[ code('27f2d01739') ]]]

Ok - let's point this link to the homepage. And yep, we *could* just say `href="/"`.

But... there's a *better* way. Instead, we're going to *generate* a URL *to* the route.
Yep, we're going to ask Symfony to give us the URL to the route that's above our
homepage action:

[[[ code('460d7431e9') ]]]

Why? Because if we ever decided to *change* this route's URL - like to `/news` -
if we *generate* the URL instead of hardcoding it, *all* the links will automatically
update. Magic!

## The Famous debug:router

So how can we do this? First, find your terminal and run:

```terminal
./bin/console debug:router
```

This is an awesome little tool that shows you a list of *all* of the routes in your
app. You can see *our* two routes *and* a bunch of routes that help the profiler and
web debug toolbar.

There's *one* thing about routes that we haven't really talked about yet: each route
has an internal name. This is never shown to the user, it only exists so that we
can *refer* to that route in our code. For annotation routes, by default, that name
is created for us.

## Generating URLs with path()

This means, to generate a URL to the homepage, copy the route name, go back to
`base.html.twig`, add `{{ path() }}` and paste the route name:

[[[ code('73c504620b') ]]]

That's it!

Refresh! Click it! Yes! We're back on the homepage.

But... actually I *don't* like to rely on auto-created route names because they
could *change* if we renamed certain parts of our code. Instead, as soon as I want
to generate a URL to a route, I add a name option: `name="app_homepage"`:

[[[ code('90101ad865') ]]]

Run `debug:router` again:

```terminal-silent
./bin/console debug:router
```

The *only* thing that changed is the *name* of the route. Now go back to `base.html.twig`
and use the new route name here:

[[[ code('096760bd65') ]]]

It still works *exactly* like before, but we're in complete control of the route name.

## Making the Homepage Pretty

We now have a link to our homepage... but I don't know why you'd want to go here:
it's *super* ugly! So let's render a template. In `ArticleController`, instead of
returning a `Response`, return `$this->render()` with `article/homepage.html.twig`:

[[[ code('7d7a22a74f') ]]]

For now, don't pass *any* variables to the template.

This template does *not* exist yet. But if you look again in the `tutorial/` directory
from the code download, I've created a homepage template for us. Sweet! Copy that
and paste it into `templates/article`:

[[[ code('d5ce83eaa1') ]]]

It's nothing special: just a bunch of hardcoded information and fascinating space
articles. It *does* make for a pretty cool-looking homepage. And yea, we'll make this
all dynamic once we have a database.

## Generating a URL with a {wildcard}

One of the hardcoded articles is the one we've been playing with: Why Asteroids
Taste like Bacon! The link doesn't go anywhere yet, so let's fix that by generating
a URL to our article show page!

Step 1: now that we want to link to this route, give it a name: `article_show`:

[[[ code('9629fe3a0c') ]]]

Step 2: inside `homepage.html.twig`, find the article... and... for the `href`,
use `{{ path('article_show') }}`:

[[[ code('6b9d58a24a') ]]]

That should work... right? Refresh! No! It's a huge, horrible, error!

> Some mandatory parameters are missing - `{slug}` - to generate a URL for `article_show`.

That *totally* makes sense! This route has a wildcard... so we can't just generate
a URL to it. Nope, we need to *also* tell Symfony what *value* it should use for the
`{slug}` part.

How? Add a second argument to `path()`: `{}`. That's the syntax for an associative
array when you're inside Twig - it's similar to JavaScript. Give this a `slug` key set
to `why-asteroids-taste-like-bacon`:

[[[ code('fd28f8b776') ]]]

Try it - refresh! Error gone! And check this out: the link goes to our show page.

Next, let's add some JavaScript and an API endpoint to bring this little heart icon
to life!
