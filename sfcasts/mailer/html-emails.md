# HTML Emails with Twig

Every email can contain content in *two* formats: a "text" part and an HTML part...
and an email can contain *just* the text part, just the HTML part or both. Of course,
these days, *most* email clients support HTML, so that's the format you *really*
need to focus on. But there *are* still some situations where having a text version
is useful - so we won't *completely* forget about it. You'll see what I mean.

The email we just sent did *not* contain the HTML "part" - only the text version.
How do we also include an HTML version of the content? Back in the controller,
you can almost *guess* how: copy the `->text(...)` line, delete the semicolon,
paste and change the method to `html()`. It's that simple! To make it fancier,
put an `<h1>` around this.

This email now has two "parts" to it: an text part and an HTML part. The user's
email client will choose which to show, usually HTML. Let's see what this looks
like in Mailtrap. Click back to get to the registration form again, change the
email address, add a password and... register! No errors! Go check out Mailtrap.

Yeah! This time we have an HTML version! One of the things I love about Mailtrap
is how easily we can see the original HTML source, the text or the rendered HTML.

## MIME: The "Multipart" Behind Emails

*Or*, you can check what the "Raw" message looks like. It turns out that what an
email looks like behind-the-scenes is almost *exactly* what an HTTP response looks
like what we return from our app: it has some headers on top, like `To`,
`From` and `Subject`. But, the *content* *is* a bit different. Normally, our
app returns an HTTP response whose *content* is probably HTML or JSON. But this
email's content contains *two* formats all at once: HTML and text.

Check out the `Content-Type` header: it's `multipart/alternative` and then has
this weird `boundary` string - `_=_symfony` then some random numbers and letters.
Below, we can see the content: the plain-text version of the email on top and
the `text/html` version below that. That weird `boundary` string is placed between
these two... and literally acts as a *separator*: it's how the email client knows
when the "text" content stops and the next "part" of the message - the HTML part -
begins. Isn't that cool? I mean, if this isn't a hot topic for your next dinner
party, I don't know what is.

*This* is what the Symfony's Mime component helps us build. I mean, sheesh, this
is ugly. But all *we* had to do was use the `text()` method to add text content
and the `html()` method to add HTML content.

## Using Twig

So... as simple as this Email was to build, we're not *really* going to put HTML
right inside of our controller like this. Normally, when we need to write some HTMl
in Symfony, we put that in a Twig template. When you need HTML for an email, we'll
do the *exact* same thing. Mailer's integration with Twig is *awesome*./

First, if you downloaded the the course code, you should have a `tutorial/`
directory with a `welcome.html.twig` template file inside. Open up the `templates/`
directory. To organize our email-related templates, let's create a new sub-directory
called `email/`. Then, paste the `welcome.html.twig` template inside.

Say hello to our fancy new `templates/email/welcome.html.twig` file. This is a
*full* HTML page with embedded styling via a `<style>` tag... and... nothing
else interesting: it's 100% static. This `%name%` thing I added here isn't a variable:
it's just a reminder of something that we need to make dynamic later.

But first, let's use this! As *soon* as your email needs to leverage a Twig template,
you need to change from the `Email` class to `TemplatedEmail`.

Hold Command or Ctrl and click that class to jump into it. Ah, this `TemplatedEmail`
class *extends* the normal `Email`: we're really still using the same class as
before, but now with a few extra methods related to templates. One of those. Now
we have this, we can use one of those methods. Remove *both* the `html()` and
`text()` calls - you'll see why in a minute - and replace them with
`->htmlTemplate()` and then the normal path to the template: `email/welcome.html.twig`.

And... that's it! Before we try this, let's make a few things in the template
dynamic, like the URLs and the image path. But, there's an important thing to
remember with emails: paths *must* be absolute. That's next.
