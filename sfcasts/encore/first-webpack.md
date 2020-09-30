# Webpacking our First Assets

So, Webpack only needs to know three things. The first - `setOutputPath()` -
tells it *where* to put the final, built files and the second - `setPublicPath()` -
tells it the public path to this directory:

[[[ code('50c769779f') ]]]

## The Entry File

The *third* important piece, and where *everything* truly starts, is `addEntry()`:

***TIP
The Encore recipe *now* puts `app.js` in `assets/app.js`. But
the purpose of the file is exactly the same!
***

[[[ code('2446057d47') ]]]

Here's the idea: we point Webpack at just *one* JavaScript file - `assets/js/app.js`.
Then, it parses through *all* the import statements it finds, puts all the code
together, and outputs one file in `public/build` called `app.js`. The first argument -
`app` - is the entry's name, which can be anything, but it controls the final filename:
`app` becomes `public/build/app.js`.

And the recipe gave us a few files to start. Open up `assets/js/app.js`:

[[[ code('cd6be845b2') ]]]

*This* is the file that Webpack will start reading. There's not much here yet - a
`console.log()` and... woh! There *is* one cool thing: a `require()` call to a CSS file!
We'll talk more about this later, but in the same way that you can import other JavaScript
files, you can import CSS too! And, by the way, this `require()` function and the
`import` statement we saw earlier on Webpack's docs, do basically the same thing.
More on that soon.

To make the CSS a bit more obvious, open `app.css` and change the background
to `lightblue` and add an `!important` so it will override my normal background:

[[[ code('713ab641a3') ]]]

## `disableSingleRuntimeChunk()`

Before we execute Encore, back in `webpack.config.js`, we need to make one other
small tweak. Find the `enableSingleRuntimeChunk()` line, comment it out, and put
`disableSingleRuntimeChunk()` instead:

[[[ code('c72340aedf') ]]]

Don't worry about this yet - we'll see *exactly* what it does later.

## Running Encore

Ok! We've told Webpack *where* to put the built files and which *one* file to start
parsing. Let's do this! Find your terminal and run the Encore executable with:

```terminal
./node_modules/.bin/encore dev
```

***TIP
For Windows, your command may need to be `node_modules\bin\encore.cmd dev`
***

Because we want a development build. And... hey! A nice little notification that
it worked!

And... interesting - it built *two* files: `app.js` and `app.css`. You can see them
inside the `public/build` directory. The `app.js` file... well... basically just
contains the code from the `assets/js/app.js` file because... that file didn't
import any other JavaScript files. We'll change that soon. But our `app` entry
file *did* require a CSS file:

[[[ code('72c7555c1c') ]]]

And yea, Webpack understands this!

Here's the *full* flow. First, Webpack looks at `assets/js/app.js`. It then looks
for *all* the `import` and `require()` statements. Each time we import a JavaScript
file, it puts those contents into the final, built `app.js` file. And each time we
import a CSS file, it puts *those* contents into the final, built `app.css` file.

Oh, and the final filename - `app.css`? It's `app.css` because our *entry* is called
app. If we changed this to `appfoo.css`, renamed the file, then ran Encore again,
it would *still* build `app.js` and `app.css` files thanks to the first argument
to `addEntry()`:

[[[ code('bfcee5fdf0') ]]]

## Adding the Script & Links Tags

What this means is... we now have *one* JavaScript file that contains *all*
the code we need and one CSS file that contains all the CSS! All *we* need to do
is add them to our page!

Open up `templates/base.html.twig`. Let's keep the existing stylesheets for now
and add a new one: `<link rel="stylesheet" href="">` the `asset()` function
and the public path: `build/app.css`:

[[[ code('6a73e5bf73') ]]]

At the bottom, add the `script` tag with `src="{{ asset('build/app.js') }}"`. Oh,
make that `app.js`:

[[[ code('764da17402') ]]]

If you're not familiar with the `asset()` function, it's not doing anything important
for us. Because the `build/` directory is our document root, we're literally pointing
to the public path.

Let's try it! Move over, refresh and... hello, weird blue background. And in the
console... yes! There's the log!

We've only started to scratch the surface of the possibilities of Webpack. So if
you're still wondering: "why is going through this build process so useful?". Stay
tuned. Because next, we're going to talk about the `require()` and `import` statements
and start organizing our code.
