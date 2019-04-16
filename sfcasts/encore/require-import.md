# Modules: require & import

Let's get back to talking about the *real* power of Webpack: the ability to import
or require JavaScript files. Pretend that building this string is actually a lot
of work. Or maybe it's something we need to re-use from somewhere else in our code.
So, we want to isolate into its own file. If this were PHP, we would create a new
file to hold this logic. In JavaScript, we're going to do the same thing.

In `assets/js/`, create a new file called `get_nice_message.js`. But *unlike* PHP,
in JavaScript, each file that you want to use somewhere else needs to *export* something,
like a function, object, or even a string. Do that by saying `module.exports =`
and then the thing you want to export. Let's create a `function()` with one argument
`exclamationCount`. Inside, let's go steal our string... then return that string
and, to make it fancier, add `'!'.repeat(exclamationCount)`.

Yes. Because strings are *objects* in JavaScript, this works - it's kinda cool.
This looks great! By the way, when a JavaScript file exports a value like this,
it's known as a "module". That's not a big deal, but you'll hear this term a lot:
JavaScript *modules*. It just refers to what we're doing here.

*Now* go back to `app.js`. At the top, well... it doesn't need to be on top, but
usually we organize them there, add
`const getNiceMessage = require('./get_nice_message');`

Notice the `.js` extension is optional, you can add it or skip it - Webpack knows
what you mean. And because programmers are lazy, you usually don't see it.

Also, that `./` at the beginning is important. When you're pointing to a file
*relative* to this one, you need to start with `./` or `../`. If you *don't*,
Webpack will think you're trying to import a third-party package. More on that soon.

And now that we have our `getNiceMessage` function, let's call it! Pass it 5 for
*just* the right number of excited exclamation points. And because we're running
the "watch" command in the background, when we refresh, it works instantly!

## import Versus require

But! When we originally looked at the Webpack talks, they weren't using `require`
and `module.exports`! Nope, they were using `import` and `export`. It turns out,
there are *two* valid ways to export and import values from other values... and
they are *basically* identical.

To use the *other* way, remove `module.exports` and say `export default`.

That does the *same* thing. The `default` is important. With this syntax, a module,
so, a file, can export *more* than one thing. We're not going to talk about that
here, but most of the time you'll want to export just *one* thing, and this `default`
keyword is how you do that.

Next, back in `app.js`, the `require` changes to
`import getNiceMessage from './get_nice_message'`.

That's it! That is 100% the same as what we had before. So, which should you use?
Use *this* syntax. The `require` function comes from Node. But the `import` and
`export` syntax are the *official* way to do module loading in ECMAScript, which
is the true name for the JavaScript language.

You can, and *should* also use this for CSS. Just `import, then` the path. There's
no `from` in this case because we don't need it to return a value to us.

Make sure *all* this fanciness works: refresh! Yes! Hey peeps - *we* can organize
our JavaScript code! That is *no* small thing. Heck, we could stop the tutorial
right now, and you would *still* have this amazing new superpower.

But... we won't! Oh, there is still *so* much cool stuff to talk about. Like,
how we can now *super* easily install third-party libraries via Yarn and import
them on our code. Let's do it!
