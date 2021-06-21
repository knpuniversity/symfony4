# Bootstrap & the Curious Case of jQuery Plugins

The inline code in `base.html.twig` isn't working anymore because we've *eliminated*
the `$` global variable:

[[[ code('dc92df5c76') ]]]

Woo! To make it work, let's move *all* this code into `app.js`:

[[[ code('e5953d6bf1') ]]]

Instead of global variables, we're importing `$` and that's why it's called `$`
down here:

[[[ code('17aa71539c') ]]]

It's all just local variables.

Try it now. Ok, it *sorta* works. It logs... then explodes. The error has some
Webpack stuff on it, but it ultimately says:

> dropdown is not a function

Click the `app.js` link. Ah, it's having trouble with the `dropdown()` function.
*That* is one of the functions that *Bootstrap* adds to jQuery. And... it makes
sense why it's missing: we're running all of our code here, and *then* including
Bootstrap:

[[[ code('51f1ec22c0') ]]]

It's simply *not* adding the function in time! Well actually, it's a *bit* more
than that. *Even* if we moved this script tag up, it *still* wouldn't work. Why?
Because when you include Bootstrap via a script tag, it *expects* jQuery to be
a global variable... and that - wonderfully - doesn't exist anymore.

Let's do this properly.

## Installing Bootstrap

Oh, by the way, this `popper.js` thing is here because it's needed by Bootstrap:

[[[ code('3e07820e6f') ]]]

You'll see how this works in Webpack in a moment. Delete both of the script tags:

[[[ code('66f14f261d') ]]]

Then, find your terminal and run:

```terminal
yarn add bootstrap@^4.3.1 --dev
```

Oh, and how did I know that the package name was `bootstrap`? Just because I cheated
and searched for it before recording. Go to https://yarnpkg.com/ and search for "Bootstrap".
9.7 million downloads... in the last 30 days... that's probably the right one.

And... it's done! Oh, and there's a little notice:

> bootstrap has an unmet peer dependency popper.js

We'll come back to that in a minute.

## Importing jQuery Plugins

Back in `app.js` installing Bootstrap isn't enough. On top, add `import 'bootstrap'`:

[[[ code('26d36e898e') ]]]

Nope, we *don't* need to say `import $ from` or anything like that. Bootstrap
is a jQuery plugin and jQuery plugins are... super weird. They do *not* return
a value. Instead, they *modify* jQuery and add functions to it. I'll add a note
here because... it just *looks* strange: it's weird that adding *this* allows
me to use the `tooltip()` function, for example.

## How Bootstrap Finds jQuery

But wait a second. If Bootstrap *modifies* jQuery... internally, how does it *get*
the jQuery object in order to do that? I mean, jQuery is no longer global: if
*we* need it, we need to import it. Well... because Bootstrap is a well-written
library, it does the *exact* same thing. It *detects* that it's in a Webpack
environment and, instead of expecting there to be a *global* `jQuery` variable,
it *imports* `jquery`, *just* like we are.

And, fun fact, when two different files import the *same* module, they get back
the same, *one* instance of it - a lot like Symfony's container. *We* import jQuery
and assign it to `$`. Then, a microsecond later, Bootstrap imports that *same*
object and modifies it:

[[[ code('272a089a72') ]]]

By the time we get past line 12, the `$` variable has the new `tooltip()` function.

## Installing popper.js

But... you may have noticed that, while I was talking about how awesome this is
all going to work... my build was failing!

> This dependency was not found: `popper.js` in `bootstrap.js`

This is awesome! Bootstrap has *two* dependencies: jQuery but *also* another library
called `popper.js`. Internally, it tries to import *both* of them. But, because
this is not installed in our project, it fails. By the way, if you're wondering:

> Why doesn't Bootstrap just list this as a dependency in *its* `package.json`
> so that it's automatically downloaded for us?

Excellent question! And that's *exactly* how we would do it in the PHP world. Short
answer: Node dependencies are complicated, and so *sometimes* it will work like
this, but *sometimes* it's a better idea for a library to force *us* to install
its dependency manually. That's called a "peer" dependency.

Anyways, this is a great error, and it even suggests how to fix it:
`npm install --save popper.js`. Because we're using Yarn, we'll do our version
of that command. Back in your open terminal tab, run:

```terminal
yarn add popper.js --dev
```

When that finishes... ah. Because we haven't modified any files, Webpack doesn't
know it should re-build. Let's go over here and just add a space. That triggers
a rebuild which is... successful!

Try it out - refresh! No errors.

Next! I have a surprise! Webpack has *already* started to silently optimize our
build through a process called code splitting. Let's see what that means and learn
how it works.
