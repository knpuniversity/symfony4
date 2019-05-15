# Support any Browser with PostCSS & Babel

Go back to `/admin/article` and click to edit one of the articles. View the
source and search for `.js`. Okay, we have several JavaScript files, because
Webpack is splitting them. Click to look at `build/admin_article_form.js`, which
will probably contain all the non-vendor code from that entry point.

The top of the file contains some Webpack boootstrap stuff, then our code is below,
still mixed in with some things that makes Webpack work.

Now, check this out: in the *original* `admin_article_form.js` file, we created
a class called `ReferenceList`:

[[[ code('6b2c2229f5') ]]]

And we also use the `const` keyword for `const $autoComplete`. Back in the compiled
file, search for `$autoComplete`. Woh! It's not `const $autoComplete`, it's
`var $autoComplete`! And if you search for `ReferenceList`... and get down to the
class... there's no class syntax! It's wrapped in some sort of a "pure" function thingy.

Surprise! Something is *rewriting* our code! But, who? And, why?

## Hello Babel

The *who* is Babel: an amazing library that has the superpower of reading your
JavaScript and *rewriting* it to *older* JavaScript that's compatible with older
browsers. And this is *seriously* important! Because if JavaScript comes out with
a new feature, we do *not* want to wait 10 years for all of the browsers to support
it! Babel solves this: you can use *brand* new language features and it compiles
it to boring, traditional code.

But... wait. How is Babel deciding which browsers our site needs to support?
Different sites need to support different browsers... and so, in theory, Babel
should be able to rewrite the code *differently* for different sites. For example,
if you need to support *super* old browsers, you probably need to rewrite `const`
to `var`. But if all of your users are *awesome*... like our SymfonyCasts users...
and all use *new* browsers, then you *don't* need to rewrite this. In general,
converting new code to *old* code makes your JavaScript *larger*, so avoiding
unnecessary changes is a good thing.

## Rewriting CSS for Older Browsers?

Let's answer the question of "how" we can control Babel by talking about something
*completely* different: CSS. Babel does *not* rewrite CSS. But, if you think about
it, it *would* sorta make sense.

For example, if you're using a `border-radius` and need to support older browsers,
you need to add some vendor prefixes, like `-webkit-border-radius`. You can see
one we added manually down here: we have `box-shadow`, but we *also*
have `-webkit-box-shadow` to make it work in some older browsers... which we might
not even need, depending on what browsers we decide we need to support:

[[[ code('b3ea9c347d') ]]]

*Anyways*, forgetting about Webpack and Babel for a minute, in the CSS world, you
do *not* need to add these vendor prefixes by hand. Nope! There's a wonderful
library that can do it for you called `autoprefixer`. *You* write code correctly -
like using `box-shadow` - tell it *which* browsers you need to support, and it
adds the vendor prefixes for you.

## Enabling PostCSS

Because that sounds *amazing*... let's add it! In `webpack.config.js`, anywhere,
but how about below `.enableSassLoader()`, add `.enablePostCssLoader()`:

[[[ code('f05adc0370') ]]]

PostCSS is a library that allows you to run things at the "end" of your CSS being
processed. And it's the easiest way to integrate `autoprefixer`.

Next, because we just changed our `webpack.config.js` file, go restart Encore:

```terminal-silent
yarn watch
```

Hey! This is familiar! Just like when we enabled Sass, this requires us to install
a few things. Copy the command, go to your open terminal and run that!

```terminal-silent
yarn add postcss-loader@^3.0.0 --dev
```

Ok, let's try Encore again:

```terminal-silent
yarn watch
```

Hmm, *another* error! This is kinda cool: to use PostCSS, you *need* to create a
`postcss.config.js` file. Encore walks you through that process and sets it up
to use `autoprefixer` to start. Copy that, go to the root of your project, create
the `postcss.config.js` file and paste:

[[[ code('6b41d99074') ]]]

Ok, hit `Control` + `C` and try that again:

```terminal-silent
yarn watch
```

Sheesh! One last error. PostCSS is probably *the* most involved thing to get running.
This error isn't as obvious:

> loading PostCSS plugin failed: Cannot find module autoprefixer

We know what that word "module" means! It's trying to find that library. We
told PostCSS to use `autoprefixer`, but that doesn't exist in our project yet.
Run:

```terminal
yarn add autoprefixer --dev
```

And *now* try Encore.

```terminal
yarn watch
```

No errors! So... it's *probably* working? Let's see it in action next *and* learn
how we can tell PostCSS *and* Babel *exactly* which browsers we need to support.
