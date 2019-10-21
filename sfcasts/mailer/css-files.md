# Inlining CSS Files

Now that the styles are being inlined, we can go a step further. I don't *love*
having all my email styles inside a `style` tag. It works... but will be
a problem once our app sends *multiple* emails: we don't want to duplicate this
in every template.

Nope, in the real world, we put CSS into CSS *files*. Let's do that. Copy
*all* of the styles and delete them. Inside the  `assets/css` directory, let's
create a new `email.css` file. Paste!

So far, we've seen that the `inline_css` filter is smart enough to *notice*
any `style` tags in the template and use that CSS to style the HTML tags. But
you can *also* point the filter to an *external* CSS file.

Go back to `config/packages/twig.yaml`. To point to the CSS file, we need to add
another Twig path: let's set the `assets/css` directory to `styles`. So, `@styles`
will point here.

Back in `welcome.html.twig`, we can pass an argument to `inline_css()`: a
*string* of styles that it should use. To get that, use the `source()`
function, `@styles/` and then the name of our file `email.css`.

The `source()` function is a standard Twig function... that you don't see very
often. It tells Twig to go find the file - which could be a CSS file or another
Twig template - and return its *contents*. It's basically a `file_get_contents()`
for Twig. That's perfect, because `inline_css()` doesn't want the *path* to a CSS
file, it wants the *string* styles it should use.

Let's try this! Hit back once again in your browser, bump the email, type a password,
submit and... it looks good! And *this* time in the HTML source, the `style` tag
is *not* there... but the inline *styles* are. That's another benefit of
the CSS *file*: it got rid of the extra `style` tag, which makes our email
a little bit smaller.

## Using Sass or Encore for Email CSS?

By the way, if you prefer to use Sass or LESS for your CSS and are using Webpack
Encore to compile all of that into your final CSS file, then... you have a problem.
You *must* pass *CSS* to `inline_css` - you can't pass it Sass and expect it to
know how to process that. Instead, you need to point `inline_css` at the final,
*built* version of your CSS - the file that lives in `public/build/`.

Doing that *seems* easy enough: you could add another Twig path - maybe called
`encore` - that refers to the `public/build` directory. Except... if you're
using versioned filenames... then how do you know exactly what the built filename
will be? And if you're using `splitEntryChunks()`, your *one* CSS file may be
split into multiple!

This is a *long* way of saying that pointing to a CSS file with `inline_css` is
easy... but pointing to a Sass file is... trickier. Later, we'll walk you through
how to do it.

But first! The two rules of making an email look good in every email client are,
one, use a table-based layout instead of floats or flex-box. And two, inline
your styles. We've done the second, *now* its time to do the first. Does this mean
we need to rewrite our HTML to use ugly, annoying tables? Actually... no!
