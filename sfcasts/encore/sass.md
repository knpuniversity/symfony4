# Sass & Overriding Bootstrap Variables

What if I want to use Sass instead of normal CSS, or maybe Less or Stylus?
*Normally*, that takes some setup: you need to create a system that can
compile all of your Sass files into CSS. But with Encore, we get this for free!

Rename `app.css` to `app.scss`. Of course, when we do that, the build fails because
we need to update the `import` in `app.js`:

[[[ code('c33e5b35b9') ]]]

But the build *still* fails. Go check out the error. Woh! That's awesome!
It basically says:

> Hey! How are you? Great weather lately, right? Listen, it looks like you're trying
> to load a Sass file. That's super! To do that, enable the feature in Encore
> and install these libraries.

This is the philosophy of Encore: give you a really solid, but small-ish core, and
then offer a *ton* of optional features.

## Enabling Sass

Go back to `webpack.config.js`. The `enableSassLoader()` line is already here.
Uncomment it:

[[[ code('265a83ffdf') ]]]

Back at the terminal, copy the `yarn add` command, go to the open tab, and run it!

***TIP
Instead of `node-sass`, install `sass`. It's a pure-JavaScript implementation that
is easier to install and is now recommended.
***

```terminal-silent
yarn add sass-loader@^7.0.1 sass --dev
```

This *could* take a minute or two: `node-sass` is a C library and it may need to
*compile* itself depending on your system. Ding!

Thanks to the watch script, we *normally* don't need to worry about stopping
or restarting Encore. There is *one* notable exception: when you make a change to
`webpack.config.js`, you *must* stop and restart Encore. That's just a limitation
of Webpack itself: it can't re-read the fresh configuration until you restart.

Hit `Control`+`C` and then run `yarn watch` again.

```terminal-silent
yarn watch
```

And this time... yes! We just added Sass support in like... two minutes - how
awesome is that?

## Organizing into Partials

This next part is optional, but I want to get organized... instead of having
one big file, create a new directory called `layout/`. And for this top stuff, create
a file called `_header.scss`. Little-by-little, we're going to move *all* of this
code into different files. Grab the first section and put it into header:

[[[ code('e5fc2ef3e8') ]]]

We'll import the new files when we finish.

Next is the "advertisement" CSS. Create another folder called `components/`. And
inside, a new `_ad.scss` file. I'll delete the header... then move the code there:

[[[ code('786916a80f') ]]]

Let's keep going! For the article stuff, create `_articles.scss`, and move the code:

[[[ code('a0d8b9860e') ]]]

Then, `_profile.scss`, copy that code... and paste:

[[[ code('ea08cdf581') ]]]

For the "Create Article" and "Article Show" sections, let's copy *all* of that
and put it into `_article.scss`:

[[[ code('6e3c16cf65') ]]]

And for the footer, inside `layout/`, create one more file there called
`_footer.scss` and... move the footer code:

[[[ code('f38897f760') ]]]

And finally, copy the sortable code, create another components partial called
`_sortable.scss` and paste:

[[[ code('a8fda20c72') ]]]

*Now* we can import all of this with `@import './layout/header'` and
`@import './layout/footer'`:

[[[ code('64cb6371a4') ]]]

Notice: you don't need the `_` or the `.scss` parts:
that's a Sass thing. Let's add a few more imports for the components: `ad`,
`articles`, `profile` and `sortable`:

[[[ code('65f6535fff') ]]]

Phew! That took some work, but I like the result! But, *of course*, Encore is
here to ruin our party with a build failure:

> Cannot resolve `./images/space-nav.jpeg`

We know that error! In `_header.scss`... ah, there it is:

[[[ code('9135b9f4d3') ]]]

The path needs to go *up* one more level now:

[[[ code('1746772cd0') ]]]

And... it works.

Move over and make sure nothing looks weird. Brilliant!

## Adding Variables

To celebrate that we're processing through Sass, let's at *least* use *one* of its
features. Create a new directory called `helper/` and a new file called
`_variables.scss`.

At the top of `_header.scss`, we have a gray `background` color:

[[[ code('6563f6d411') ]]]

Just to prove we can do it, in `_variables`, create a new variable called
`$lightgray` set to `#efefee`:

[[[ code('9461f05a8a') ]]]

And back in headers, reference that: `$lightgray`:

[[[ code('d5fddacf29') ]]]

We even get auto-completion on that! As *soon* as we save, the build fails!

> Undefined variable: "$lightgray"

Perfect! Because... inside of `app.scss`, all the way on top, we still need to
`@import` the `helper/variables` file:

[[[ code('7d4478056e') ]]]

About a second later... ding! It builds and... the background is still there.

## Overriding Bootstrap Sass Variables

But wait, there's more! When we import `bootstrap`, Encore has some logic to find
the right CSS file in that package. But now that we're inside a *Sass* file, it's
smart enough to *instead* import the `bootstrap.scss` file! Woh!

Check it out. Hold Command or `Ctrl` and click `~bootstrap` to jump to that directory.
Then open up `package.json`. This has a `style` key, but it *also* has a `sass`
key! Because we're importing from inside a Sass file, Encore *first* looks for
the `sass` key and loads that file. If there isn't a `sass` key, it falls back
to using `style`.

Now look at the `font-awesome/` directory and find *its* `package.json` file. It
actually does *not* have a `sass` key! And so, it's *still* loading the
`font-awesome.css` file, which is fine. If you *did* want to load the Sass file,
you would just need to point at the file path directly.

Anyways, to *prove* that the Bootstrap Sass file is being loaded, we can override
some of its variables. See this search button? It's blue because it has the
`btn-info` class. It's color hash is... here: `#1782b8`.

Suppose you want to change the info color *globally* to be a bit darker. Bootstrap
lets you do that in Sass by overriding a variable called `$info`.

Try it: inside the variables file, set `$info:` to `darken()`, the hash, and `10%`:

[[[ code('836a8a8cd6') ]]]

Once the build finishes... watch closely. It got darker! How cool is that?

Next, let's fix our broken `img` tags thanks to one of my favorite new Encore features
called `copyFiles()`.
