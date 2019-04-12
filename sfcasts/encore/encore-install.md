# Hello Webpack Encore

Yo friends! It's Webpack Encore! Yeeeeees! Well, maybe not *super* "yeeeeees!"
if you're responsible for *configuring* Webpack... cause, woh, it can be tough!
But, pffff - that's what Webpack *Encore* is for! More about that in a few
minutes.

## Why all the Webpack Buzz?

But first, I want you to know why you should *care* about Webpack... like *really*
care. Sure, *technically*, Webpack is just a tool to build & compile your JavaScript
and CSS files. But it will *revolutionize* the way you write JavaScript.

The reason is *right* on their homepage! In PHP, we organize our code into small
files that work together. But traditionally, in JavaScript, we tend to *smash*
them together into one file... or split them up, but then need to worry about
having multiple script tags on the page... which are in *just* the right order.
Mess anything up and... kaboom!

To start, Webpack gives us one new superpower. Suppose we have an `index.js` file
but we want to organize a function in different, `bar.js` file. Thanks to Webpack,
you can "export" that function as a value from `bar.js` then *import* it use it
in `index.js`. Yes, we can *organize* our code into small pieces! Webpack's job
is to read `index.js`, parse through *all* of its `import` statements, and output
*one* JavaScript file that has everything inside of it. Woh.

So let's get to it! To import or... Web*pack* the *maximum* amount of knowledge
from this tutorial, download the course code from this page and code along with.
After you unzip the download, you'll find a `start/` directory that has the same
code I have here: a Symfony 4 app. Open up the `README.md` file for all the setup
details.

The last step will be to open a terminal, move into the project start a web server.
I'm going to use the Symfony local web server, which you can get from
https://symfony.com/download. Just run:

```terminal
symfony serve
```

Then, swing back over to your browser and go to http://localhost:8000 to see...
The Space Bar, an app we've been working on throughout our Symfony series. And,
we *did* write some JavaScript and CSS in that series... but we kept it *super*
traditional: the JavaScript is pretty boring, and there are multiple files and
each has its own `script` tag in my templates.

This is *not* the way I really code. So, let's do this correctly.

## Installing WebpackEncoreBundle + Recipe

So even though both Webpack and Encore are *node* libraries, if you're using Symfony,
you'll install it via composer... well... sort of. Open a new terminal tab and run:

```terminal
composer require encore
```

This downloads a small bundle called WebpackEncoreBundle. Actually, Encore *itself*
can work with any framework or any language! But, it works *super* will with Symfony,
and this thin bundle helps with that.

This bundle *also* has a Flex *recipe*, which gives us some files to get started!
If you wanted to use Webpack from *outside* of Symfony, you would just need these
files in your app.

Back in the editor, check out `package.json`. *This* is the `composer.json` file
of the Node world, and it requires Encore plus two optional packages we'll use.

## Installing Encore via Yarn

To *actually* download these, go back to your terminal and run:

```terminal
yarn
```

Or... `yarn install` if you're less lazy than me - it's the same thing. Node has
*two* package managers - `yarn` and `node` - and you can install and use whichever
you want. Anyways, this is downloaded our 3 libraries and their dependencies into
Node's version of the `vendor/` directory: `node_modules/`.

And... done! Congrats! You now have a gigantic `node_modules` directory... because
JavaScript has tons of dependencies. Oh, the recipe *also* updated our `.gitigore`
file to *ignore* `node_modules`. Just like with Composer, there is *no* reason to
commit this stuff. This *also* ignores `public/build`, which is where Encore will
put our final, built files.

## Hello webpack.config.js

In fact, I'll show you why. At the root of your app, the recipe added another,
*super* important file: `webpack.config.js`. *This* is the configuration file that
Encore reads. Actually, if you use Webpack by itself, you would have this *exact*
same file! Encore is basically a configuration generator: you tell it how you want
Webpack to behave and then, *all* the way at the bottom, say: please give me the
standard Webpack config that will give me that behavior.

Most of the stuff in this file configures optional features that we'll talk about
along the way - so ignore it all for now. The three *super* important things that
*we* need to talk about are output path, public path and this `addEntry()` thing.
Let's do that next, *build* our first Webpack'ed files and include them on the page.
