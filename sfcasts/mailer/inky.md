# Ink: Automatic CSS Email Framework

Our email template is HTML... very *traditional* HTML. What I mean is, this is
the type of HTML and CSS you would see on a normal website. And, at least inside
Mailtrap... it looks good! But a *big* lesson of sending emails is that their HTML
is often *not* rendered like a normal browser would render it. Some email clients
don't support float... or flexbox... so if you're using *those* to establish an
email layout then... oof, it's going to look *bad* for some people... like people
using gmail.

If you want to write an email that's going to look consistently good in every
email client, the best practice is actually to use *tables* for your layout. If
you have *no* idea what a table layout is... oh, you are *so*, *so* lucky. Back
in the dark ages of the Internet, back before CSS float and flexbox existed,
every webpage's layout consisted of tables, rows and cells. It was tables, inside
of tables, inside of tables. It was... a nightmare.

So... um... am I saying that the nightmare of table-based layouts is still a
reality for emails? Yes... and no. Mailer has another trick up its sleeve.

## Hello Ink / Foundation for Emails

Google for "Inky Framework" to find something called "Ink" by "Zurb". Let me
define... a few things. Zurb is the name of a company. That company created
"Foundation" a CSS framework that's probably the second most famous in the
world behind Bootstrap. "Ink" is the name of a CSS framework that's designed
*specifically* for emails. And actually, they've renamed "Ink" to just
"Foundation for Emails".

So, Ink, or Foundation for Emails is a CSS framework for responsive HTML emails
that works on any device, even Outlook. Click on the docs.

Foundation for emails is basically two parts. First, it's a CSS file that defines
useful CSS classes and a grid structure for designing emails. Again... it's just
like Bootstrap CSS for emails.

## The Inky Templating Language

That CSS file is super handy. But the *second* part of foundation for emails is
even *more* interesting. Click the "Inky" link on the left. The *second* part
of this library is centered around a custom templating language called "Inky".
It's a simple, but *fascinating* tool. Click the "Switch to Inky" link.

Here's the idea: *we* write HTML using some custom Inky HTML tags, like
`<container>`, `<row>` and `<columns>`... as well as a few others like
`<button>` and `<menu>`. Then, Inky will *transform* this pretty HTML into the
crazy, ugly table-layout required for it to render in an email! Yea, it lets us
have table-based emails... without needing to use tables! Yeehaw!

## Using the

Now if you downloaded the course code, you should have a `tutorial/` directory,
which olds the original `welcome.html.twig` *and* an `inky/` directory with an
*updated* `welcome.html.twig`.

This is basically the same template but written with that special "Inky" markup:
containers, rows, columns, etc. Copy the contents... and let's close a few things.
Now open up `templates/email/welcome.html.twig` and *completely* replace this file
with the updated version.

It's *really* the same email as before: it still has the dynamic URLs and is printing
the recipient's name... it's just different markup. Oh, and notice that the
`inline_css()` stuff we added a few minutes ago is *gone*! Gasp! Don't worry: we'll
put that back in a minute. But until then, forget about CSS.

If we sent this email right now, it would *literally* send with this markup. To
*transform* this into the table-based markup we want, we'll use another special
filter on the *entire* template. On top, add `{% apply inky_to_html %}`... and
*all* the way at the bottom, put `{% endapply %}`. I'll indent it to make it
look nice.

Let's try it! Find your browser and make sure you're on the registration page.
Let's register as `thetruthisoutthere11@example.com`, any password, check the
terms, register and... error!

Ah, but we know this error! Well, not this *exact* error, but almost! This is
Twig telling us that we're trying to use a filter that requires an extra library.
Cool! Copy the composer require line, move back over to your terminal, and paste:

```terminal
composer require twig/inky-extra
```

When that finishes... move back to your browser, go *back* to the registration
form, tweak that email and... deep breath... register! I think it worked!
Let's go check it out.

There's the new email. Oof, it looks *terrible*... but that's only because we haven't
added any CSS yet. But check out the HTML source. Yes! It *transformed* our clean
markup into table elements! We just took a *huge* step towards making our emails
look good in every email client... without needing to write bad markup.

## Inlining the foundation-emails CSS

Tpo get this to look good, we need to include some CSS from Foundation for Emails.
Go back to the documentation, click on the "CSS Version" link and click download.
When you unzip this, you'll find a `foundation-emails.css` file inside. Copy that...
and paste it into, how about, the `assets/css` directory.

How do we include this in our email template? We already know how: the `inline_css`
filter. But instead of adding *another* apply tag around the entire template, we
can piggyback off of inky! Add `|inline_css` and pass this `source()` and the path
to the CSS file: `@styles/foundation-emails.css`.

Remember: if you look in `config/packages/twig.yaml`, file, we set up a path that
allows us to say `@styles` to refer to the `assets/css` directory. That's how this
path works.

And... I still *do* want to include my custom `email.css` code. Copy the `source()`
stuff, add a *second* argument to `inline_css` - you can pass this as *many* arguments
of CSS as you want - and point this at `email.css`.

That should do it! Oh, but before we try this, back in the `tutorial/`, that `inky/`
directory *also* holds an `email.css` file. Now that we're using a CSS framework
for our emails, some of the code in our original `email.css`... just isn't needed
anymore! This new `email.css` is basically the same as the original one... but with
some extra stuff removed. Copy the code from thew file, and paste it over the
one in `assets/css`.

Ok, time to see the final product! Go back to the registration page, update the
email, add a password, enter and... go find check out Mailtrap. There it is and
it looks awesome. Well, it looks *exactly* like it did before, but in the HTML
source, now that we have a table-based layout, we know this will display more
consistently across all email clients.

So that's "Foundation for Emails": it's, one, a CSS framework for emails... a lot
like Bootstrap for emails... and two, a tool to transform the pretty markup known
as Inky into the ugly table-based HTML that the CSS framework needs.

Next, let's bootstrap a console command that will send some emails! It turns out
that sending emails in a console command requires an extra trick.
