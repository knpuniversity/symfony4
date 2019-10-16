# The "email" Variable & Recipient "Name"

When you set the HTML part of an email in mailer, it helps us by creating
the "text" version for us! It's not perfect yet... and we'll fix that soon...
but... it's a nice start! But if you *did* want to control this manually, in
`SecurityController`, you could set this explicitly by calling either the
`text()` method `textTemplate()` to render a template that would only contain
text.

## Passing Variables (context)

In both cases - `htmlTemplate()` and `textTemplate()` - you're probably going to
want to pass some *data* into the template so that it can be dynamic. The way to
do this is *not* via a second argument to `htmlTemplate()`. Nope, go pass variables
into the templates, call `context()` and pass that and `array`. Let's pass a
`user` variable set to the `$user` that was just registered.

As *soon* as we do this, in `welcome.html.twig`, we can replace that weird `%name%`
placeholder with `{{ user.firstName }}`... because `user` is a instance of our
`User` entity... which has a `getFirstName()` method on it.

Let's try it! In your browser, go back on page, tweak the email, type a password,
hit enter and then... there it is! Nice to meet you "Fox".

## The Built-in "app" and "email" Variables

But wait, there's more! In addition to whatever variables you pass via `context()`,
you *also* have access to exactly two *other* variables for free. The first one...
we already know: it's the `app` variable... which *every* Twig template in Symfony
can access. It can be used to read info from the session, the request, get the
current user and a few other things.

The *other* variable that you magically get access to in all email templates is
more interesting. It's called... `emu`. Um, I mean, `email`... and is *not* a
large flightless bird from Australia, but instead an instance of
`WrappedTemplatedEmail`.

## Hello WrappedTemplatedEmail

I'll hit Shift+Shift and look `WrappedTemplatedEmail` under "classes".

This is a *super* powerful class... full of *tons* of information. It gives us
access to things like the name of the who email is being sent to - more about that
in a minute - the subject, return path... and it even allows you to *configure*
a few things on the email, like embedding in image right from Twig!

We're not going to talk about *all* of these methods... but basically, *any*
information about the email itself can be found here... and it even allows you
to *change* a few things about the email... right from inside Twig. In fact,
we already *used* the `image()` method earlier to *embed* the logo. Handy!

Go back to our `welcome.html.twig` email template. All the way at the top, we have
a `title` tag set to

> Welcome to the Space Bar!

Having a `<title>` tag in an email.... is usually not *that* important... but
it doesn't hurt to have it and make it match the email's subject. Now that we
know about the `email` variable, we can do this properly. Change the text to
`{{ email.subject }}`.

## NamedAddress and email.toName()

Back inside `WrappedTemplatedEmail`, all the way on top, one of my *favorite*
methods is `toName()`. When you're sending an email to just *one* person, this
is a *super* easy way to get that person's name. It's interesting... if the
"to" is an instance of `NamedAddress`, it says `$to->getName()`. Otherwise it
returns an empty `string`.

What is that `NamedAddress`? Go back to `SecurityController`. Hmm, for the `to()`
address... we passed an email *string*... and that's a *totally* legal thing
to do. But instead of a string, this method *also* accepts a `NamedAddress`
object... or even an *array* of `NamedAddress` objects.

Check this out: replace the email string with a `new NamedAddress()`. This takes
two arguments: the address that we're sending to - `$user->getEmail()` - *and*
the "name" that you want to identify this person as. Let's use
`$user->getFirstName()`.

We can do the same thing with from. I'll copy the from email address and replace
it with `new NamedAddress()`, `alienmailer@example.com` and or the name, we're
sending as `The Space Bar`.

This is actually even cooler than it looks... and helps us in *two* ways. First,
in `welcome.html.twig`, we can use the `email` object to get the name of the person
we're sending to instead needing the `user` variable.

To prove it, let's get crazy and comment-out the `user`  variable in context.
In the template, use `{{ email.toName }}`. This will call the `toName()` method...
which *should* give us the first name.

This is nice... but the *real* advantage of the `NamedAddress` can be seen in
the inbox.

Try the flow from the start: find your browser, go back, change the email again -
we'll be doing this a lot - type a password, submit and... go check the email.
There it is:

> Nice to meet you Fox.

It's *now* getting that from the `NamedAddress` on the to. The *real* beauty is
on top: from "The Space Bar", then the email and to "Fox" next to the actual
address. This is how pretty much *all* emails you receive appear to come from
a specific "name", not just an address.

## The "Check HTML"

By the way, one of the tabs in Mailtrap is "Check HTML"... which is kinda cool...
well... only "kind of". There is a *lot* of variability on how different email
clients *render* emails, like some apparently don't support using the
`background-color` style attribute. Crazy!

If you *really* want to test how your emails looks, this "Check HTML" tab probably
isn't going to help too much - there are other services Litmus that can help you.
But this *does* highlight one *huge* thing we're doing wrong. It says that some
`style` thing on line 7 isn't supported. That's referring to the `style` tag.
It turns out that Gmail doesn't support embedding CSS in your email - it doesn't
let you do it with a `style` tag *or* with a CSS file. Nope, to make things look
good in gmail, you *must* manually put all the styles you need as style *attributes*
on *every* single element. Gross. Fortunately, Mailer comes to the rescue. We'll
see *how* soon.

But first, let's *perfect* how our auto-generated text content looks like...
by running one command and high-fiving Mailer.
