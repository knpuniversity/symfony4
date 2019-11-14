# Styling Emails with Encore & Sass Part 1

Our app actually uses Webpack Encore to build its assets. It's not something we
worried about because, if you download the course code from this page, it already
included the final `build/` directory... so that we didn't need to worry about all
the Encore stuff just to get the site working.

But if you *are* using Encore, I want to make a few improvements to how we're
styling our emails. For example, we took two shortcuts. First, the
`assets/css/foundation-emails.css` file is something we downloaded directly from
the Foundation website. That's not how we would *normally* do thing with Encore.
If we need to use a third-party library, we typically install it with `yarn` instead
of committing it directly.

The other shortcut was with this `emails.css` file. I'd *rather* use Sass... but
to do that, I need to process it thorugh Encore.

## Installing Foundation Emails via Yarn

Let's get to work! Over in the terminal, let's first install all of our current
dependencies with:

```terminal
yarn install
```

When that finishes, install Foundation for Emails with:

```terminal
yarn add foundation-emails --dev
```

The *end* result is that we now have a giant `node_modules/` directory and...
somewhere *way* down in this giant directory... we'll find a
`foundation-emails` directory with a `foundation-emails.css` file inside. They
also have a Sass file if you want to import that and control things further...
but the CSS file is good enough for us.

Before we make any real changes, let's make sure Encore can build:

```terminal
yarn dev --watch
```

And... excellent! Everything is working.

## Using Sass & Importing Foundation Emails

Now that we've installed Foundation for Emails properly, let's delete the committed
file: I'll right click and go to "Refactor -> Delete". Next, because I want to
be able to use Sass for our custom email styling, right click on `email.css`, go
to "Refactor -> Rename" and call it `email.scss`.

Because this file will be processed through Encore, we can import the Foundation
email file right here with `@import`, a `~` - that tells Webpack Encore to look
in the `node_modules/` - then `foundation-emails/dist/foundation-emails.css`

This is feeling good! I'll close up `node_modules/`... cause it's giant.

## Creating the Email Entry

Now, open up the email layout file: `temp.aes/emailBase.html.twig`. When we used
`inline_css()`, we pointed it at the `foundation-emails.css` file and the
`email.css` file. But now... we only really need to point it at `email.scss`...
because, in theory, it will include the styles from *both* files.

The problem is that this is now a *Sass* file... and `inline_css` only works
with CSS files: we can't point this at a Sass file and expect it to work. And
even if it *were* a CSS file, the `import()` won't work unless we process this
through Encore.

So here's the plan: we're going to pretend that `email.scss` is just an ordinary
CSS file that we want to include on some page on our site. Open up
`webpack.config.js`. Whenever we have some page-specific CSS or JS, we add a
new *entry* for it. In this case, because we don't need any JavaScript, we can
add a "style" entry. Say `.addStyleEntry()` - call the entry, how about, `email`,
and point it at the file: `./assets/css/email.scss`.

Because we changed the webpack config, in the terminal, press Ctrl+C to stop
Encore and restart it:

```terminal-silent
yarn dev --watch
```

And... it builds! Interesting: the `email` entrypoint dumped *two* CSS files.
Let's look at the `public/build` directory. Yep: `email.css` and also this
`vendors~email.css`.

This is thanks to an optimization that Wepback Encore makes when you call
`splitEntryChunks()`... which you can learn *all* about in our Encore tutorial.
But the basic point is that if we want *all* of the CSS from the built `email.scss`
file, we need to include *both* the `email.css` and `vendor~email.css` files.

Ok, easy, right? In the template, we could load the source of `vendor~email.css`
and `email.css`. The *problem* is that Webpack splits the files in a very dynamic
fashion: if it finds a more efficient way to split the files tomorrow - maybe
into *three* files - it will! Plus, when we do our production build, the files
will include a dynamic *hash* in their filename - like `email.123abc.css`.

So... we need to do a bit more work to reliably load this stuff through
`inline_css()`. Let's do that next with a custom Twig function.
