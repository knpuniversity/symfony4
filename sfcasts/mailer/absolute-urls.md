# Absolute URLs to Routes & Assets

Coming soon...

And it's just that
simple. Before we try this, let's make a couple of things dynamic inside of
`welcome.html.twig`. A first you can see that we have at links inside of here. What's right
now is just `#homepage`

That's not actually what we want. Now if we were, if this was a normal Symfony
template, we would use the `{{ path('') }}` function and then in our app, if you look at uh,
`ArticleController`, you can see that the homepage, the name of the rock for the
homemade is `app_homepage`. So I'd normally put path `app_homepage`. The problem
with you than any of the path functions that this will generate. Uh, this will
generate links that are not absolute. It will just be / we need the domain name to be
included in that. So change `{{ path() }}` to you, `{{ url() }}` that's the only thing that you need to
change. There's a couple other spots where we link to stuff down here. There's a spot
to create a new article or they replaced that with URL and the name of that. If you
looked in the application, the name of that is `admin_article_new`.

And then there's one more down here for the home page. So we'll say
`{{ url('app_homepage') }}`. Now the other thing that you've seen here that's important is we do
have one link to an image file. So the same thing here. This actually needs to be um,
uh, an absolute URL. So first of all, forgetting about emails. Um, this project uses
Webpack Encore for its assets. So I have an `assets/` director here. I have an `images/`
directory here, `email/logo.png`. um, but when you, uh, run Webpack, the end
result is that this actually copies that into a `public/build/images/` directory

and then there's an email directory and it gets copied here. You don't need to worry
about running Encore. I've, uh, if you download the source code, I've actually
included the final, built a directory here. But the point is, regardless of whether
using the Encore, not the path we actually want to link to, is this
`build/images/email/logo.png` here. Now the way we do that in Symfony is we use the
`{{ asset() }}` function. So in this case we just do the path to this is actually
`build/images/email/logo.png`. uh, because I'm using Encore, I don't need to include this
version hash inside of there. The asset function going to automatically add that for
me. If that doesn't make it, if you're not using Encore and that doesn't make sense
to you, that's fine. You just want to use the `{{ asset() }}` function like you normally would
to a link to whatever the final path is. Now, but we have the same problem as we have
with the uh, links. Though we don't want this to just renders
`/build/images/email/logo.png`. we want this to include the domain name in front of it. So to
get that, we're going to wrap this in `{{ absolute_url() }}` around the `asset()` function
and that should do it. All right, ready to try this?

Let's move over. I'll go back, change the email address again, type a new password,
hit enter, no errors which is always good and there it is. The emails already there
waiting for us and we got it. Check this out. It looks much better. We actually have
our image showing up here. If I hover over the URL, as you can see this is actually
one of the `localhost:8000` they get writing down here is showing `localhost:8000`
this is a little more obvious in the HTML source. Can you say everything has
those full URLs that are pointing to the image and the URL. Also as a bonus, we still
have a text part. All we sent inside of our controller was each time on template.
We're no longer sending the text thing but one of the things you get out of the box
is that if you don't send set a text part specifically, then Symfony is going to
automatically strip the slashes out of your HTML and include that as the text type.

Now you can see the top is not perfect because it has a bunch of styles in it and
things that we don't want. Um, we're gonna fix that later, but the bottom is actually
pretty awesome. It looks pretty good, especially for not putting any effort into
that. All right, next let's talk about, um, of course the one problem. This is great.
Of course, the problem is that this is all still hard-coded. We still, we need to
actually make this name thing dynamic. So now let's learn a little bit more how we
can pass variables into our template and also what other information is available
inside of here for us to customize things.
