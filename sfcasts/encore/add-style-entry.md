# addStyleEntry(): CSS-Only Entrypoint

There are only two files left in the `public/` directory, and they're both
CSS files! Celebrate by crushing your `js/` directory.

We have *two* page-specific CSS files left. Open `account/index.html.twig`:

[[[ code('1760f9706b') ]]]

Yep, this has a link tag to the first... and in `security/login.html.twig`, here's
the other:

[[[ code('d96e22dcb2') ]]]

Oh, and we also include `login.css` from `register.html.twig`:

[[[ code('505b5b61ae') ]]]

This is kind of a tricky situation.... because what Webpack *wants* you to do is
*always* start with a *JavaScript* entry file. And of course, if you *happen*
to import some CSS, it'll nicely dump a CSS file. This comes from the single-page
application mindset: if *everything* in your app is built by JavaScript, then
of *course* you have a JavaScript file!

So... hmm. I mean, we *could* leave those files in `public/` - we don't *need* them
to go through Webpack. Though... I *would* like to use Sass. We could *also*
create `account.js` and `login.js` files... and then just import each CSS file from
inside. That would work... but then Webpack would output empty `account.js` and
`login.js` files... which isn't *horrible*, but not ideal... and kinda weird.

In the Encore world, just like with Webpack, we really *do* want you to *try* to
do it the "proper" way: create a JavaScript entry file and "import" any CSS that
it needs. But, we *also* recognize that this is a legitimate situation. So, Encore
has a little extra magic.

First, move both of the files up into our `assets/css/` directory. And just
because we can, make both of them `scss` files.

Next, in `webpack.config.js` add a special thing called `addStyleEntry()`. We'll
have one called `account` pointing to `./assets/css/account.scss` and another one
called `login` pointing to `login.scss`:

[[[ code('3d0b511abf') ]]]

Easy enough! Find your Encore build, press `Control` + `C`, and restart it:

```terminal
yarn watch
```

Awesome! We can see that the `account` and `login` entries both only dump
CSS files.

And *this* means that, back in `index.html.twig`, we can replace the link tag
with `{{ encore_entry_link_tags('account') }}`:

[[[ code('2e4a506a27') ]]]

Copy that and do the same thing in `login.html.twig` for the `login` entry:

[[[ code('f2e8b5dde1') ]]]

And then in `register.html.twig`, one more time for `login`:

[[[ code('096a6dd796') ]]]

Ok! Let's double-check that the site doesn't explode. Go to the `/account` profile
page. Everything looks fine.

So... yea, `addStyleEntry()` is available for this. But... to pull it off, Encore
does some hacking internally. Really, `addStyleEntry()` is the *same* as `addEntry()`,
which means that Webpack *does* try to output an empty JavaScript file. Encore
basically just deletes that file so that we don't have to look at it.

Next, oh, we get to talk about one of my *favorite* things about Webpack and Encore:
how to *automatically* convert your CSS - and JavaScript - so that it's understood
by older browsers. *And* how to control *exactly* which browsers your site needs
to support.
