# Inline Css

Coming soon...

our email. It looks good in MailTrap, but will it look good and Jean, Gmail or
outlook? Well, generally speaking, when mail plans render emails, they do
a bad job and there are a number of very unreasonable rules that you need to follow
if you want your HTML emails to look decent. The first one is that you probably need
to use a table layout instead of actual like float based layout. We're going to talk
about that later. Right now we're using more of a kind of traditional CSS layout. And
the second one is that you can't use external CSS files or even a `<style>` tag. If you
want styles on your elements, you literally need to add `style=""` two every single HTML
element, which is insane. That is no way to live. So we are not going to do that, but
we do need that to often only happen in our email. We're going to use a tool from
mailer that's gonna do a forest. So first it's actually an external tool. We're gonna
install, I'm going to say 

```terminal
composer require twig/cssinliner=extension
```

This is, this will give us a new twig tag, which takes advantage of a third party
library that's really, really good at inlining styles to use this. It's
awesome. We're just going to go all the way to the top of our template and we're
going to add a new tag called `{% filter inline_css %}` adds
that new filter. This is a way to, um, you want me to see filters as pipe inline CSS.
You can also do it in this long format because we want to filter this entire file. So
all the way at the bottom, I'm going to say `{% endfilter %}`. What that's gonna do is it's
actually going to read all of our styles that we have inside of here and then
automatically convert those into style tags on the individual HTML elements. Yeah,
it's crazy. So let's see this. So let's go back, bump the email again, type the
password, hit enter and go check out that email. So it looks the same inside of here,
but if you look at the HTML source, the style stuff is still there.

But now it's okay if it's ignored because look at, it's all been, the styles have
been applied to each element as inline `style` attributes. This is one of my absolute
favorite features of mailer. Um, at Symfony cast, we've, we've used that library and
done this manually before, but the fact that it just works out of the box is
beautiful. Now another thing is that you're, as cool as it says, you're probably not
going to want to have your styles in line like this. Especially if you start having
multiple emails. You're probably going to want each email to share a common CSS file.
Um, so instead I'm going to copy all of this CSS here, delete it, remove the `<style>`
tag, and instead we're going to the CSS `assets/css` directory and let's create a new
`email.css` file.

Okay.

And I will paste


So basically with this inline CSS filter, one of the things you can do is you can
say, I want to inline, I want to add inline styles to everything within this filter.
But you can tell it, use this as external file as these sources, CSS. So again,
pointed at the email, that CSS file. To do that, we're going to go back to 
`config/packages/twig.yaml` and we're gonna need to add a second path here so that we can
refer to the `assets/css` directory. So this will be very similar, similar, we'll say
`assets/css`. And then how about let's put `styles`. So now means that we can use
this `@styles` keyword to refer to files and the `assets/css` directory. Now I'm
`welcome.html.twig` we can add a predeceased `inline_css()`. Then we're
going to use a function called `source()`, which is, that's a standard twig function you
don't see very often and we're going to say `@styles/` and then the name of our file
`email.css`. So what the `source()` function does, it actually says go find this file
using twigs. Normally you rules and the literally read its source code. Basically
`file_get_contents()` and pass the final contents to `inline_css()`. So we're actually
passing through and ICSs here is actually just a big string of the styles that we
want to inline inside of this spot.
So lets go make sure this works. We'll go back.

Bumper the middle again, type of password, submit and it looks good. And this time if
you look at HTML source added benefit is there's no style tag here unnecessarily
anymore. But we do have all of our inline CSS. Now if you're using, one of the
questions you might have is what if I want to use SAS for my CSS file and I'm using
Webpack Encore to turn my SCSS file, uh, into a CSS file. Well the thing is you do
need to point this to a final CSS file, not a, a a sass file. So what you do there is
you'd use Encore to do your normal processing and then you'd end up with some file in
your `public/build` directory, which is a CSS file. So you basically just set up a path
that instead of looking at assets, last CSS is looking in your `public/build`
directory. Now, the only tricky part of that is if you're using, um, asset versioning
where in production there, each CSS file is actually going to have a random string.
You will need to do a little bit more work there. Yeah,

What you're gonna need to do is, um, probably write a custom twig function here

and uh,
that would, um, where you can pass it in the, you know, `email.css` path here and
it would actually go and use the Symfony funk normal Symfony functionality to look up
the correct version, um, path and um, and render and render it because, uh, load that
file a, file your contents and render it. So ultimately, one way or another, all you
need to have here is you need to pass this a string. If you need to create a custom
function to load that string from some custom place, you can absolutely do that. But,
uh, it's going to take care of the rest. Next, let's do something.