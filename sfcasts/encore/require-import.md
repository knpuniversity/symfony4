# Modules: require & import

Encore is now outputting `app.css` and `app.js` thanks to the `app` entry. And we successfully added the `<link>` tag for `app.css` and, down here, the `<script>`
here for `app.js`.

## The Twig Helper Functions

But when you use Encore with Symfony, you *won't* render script and link tags by
hand. It's *too* much work. I'm kidding. 

Now in reality, when you use Encore and Symfony,
you're not usually going to create a normal `<link>` tag. Instead, you can use a Twig
helper that comes from the bundle install called `{{ encore_entry_link_tags() }}`. And here you
put `app` because this is the name of your entry and then down to the bottom and you
can replace the script tag with basically the same thing `{{ encore_entry_script_tags('app') }}`.
Now if we move over and refresh, we're not gonna see any difference because that did
the exact same thing. You can see we have a `<link>` tag up here and down at the bottom,
if I search for scripts there, we have the exact same `<script>` tag.

Now the, there's two interesting things about this. First, how does our Symfony APP
know what file to load for `app`? Well, it might seem obvious `app` would be `app.js`.
Right? But actually there's a little bit more to it. You notice in the bill director,
there's an entry points that JSON File. This was something that is built
automatically by Encore and it keeps track of each entrypoint. We only have one
right now, but we will have more later and the JavaScript and CSS files that go along
with each of them. So the interesting thing is that right now each entrypoint makes
one CSS file and one JavaScript file. But for performance purposes, that's not always
going to be the case. We'll talk more about that later. But the point is you can run
around and just say, I want to render all of my, every, all the script has a need for
my `app` entrypoint. And the bundle takes care of it for you by reading this
`entrypoints.json`.

Yeah.

Now of course, every time that you want to run Encore, every time you make a change,
right now you're gonna have to rerun the Encore command

```terminal-silent
./node_modules/.bin/encore dev
```

and that's lame. So of course there is a way that you can do this with a watch.
So instead of your normal command, we use the normal hand with the eight `--watch`
on the end of it

```terminal-silent
./node_modules/.bin/encore dev --watch
```

that boots up Encore and now it just sits there and waits for changes. So let's try
this out with the `app.js`. I'll add some exclamation points, save. And if you
move over and can you see it actually automatically rebuilt. I go over here
and opened my console and refresh. Now you can see it and if you don't see it,
show up. Make sure you can do a force refresh to force it. But even this is
a bit too more much work. So I'm gonna hit `control + c` and instead I'm just going to run

```terminal
yarn watch
```

Yeah,

I didn't notice a kind of prints out here that this is the same thing.
`encore dev --watch` it start Encore corn the exact same way. And that is not magic. If
you look inside of your `package.json` File, there's actually a section here
called scripts. This is something that we got for free when we installed the recipe.
And there's just some built in things that you can say to make life easier. You can
say `yarn dev` or `yarn watch` or later we'll use `yarn build` to build our production
build. So these are just nice short guns that you can take advantage of. All right,
so let's go back to talking about um, the real power of encore, which is the ability
to import or require external CSS files. So let's pretend like building this string
here is actually a lot of work and it's something we want to isolate into its own
file or maybe we want to reuse that somewhere. So just like we would do and PHP guy
who would normally create a new file to hold this. So I'm going to do the same thing
here and `assets/js/`. I'm going to create a new file called `get_nice_message.js`.

The key thing is when you create multiple files, um, is that every file is going to
export a value. It's a little bit different than PHP. So you can literally make a
file. Export is string and array an object or a function. And the way you do that is
by saying `module.exports =` and then the thing you want to export. So here I'm
actually gonna create a `function()` with one argument that called `exclamationCount`

inside of there. I'm going to go steal this string.

Okay.

I'm going to say `return` that string just to make a little fancy. I'll say
`+ '!'.repeat(exclamationCount)`. Yes. Because "!" is x
strings are actually objects. So you can do that. Cool. Now that we have this, we can
go back into `app.js` and at the top doesn't matter where, but usually you all your
imports aren't top. I'm gonna say recall, I'm gonna say
`const getNiceMessage = require('./get_nice_message');`

No, it's the `.js` here is optional. You can add it. It doesn't matter. Um, Webpack
automatically knows that you mean that js. The other thing is this. `./` is
important. Do you need to start with a dot. Just like we do with CSS? Uh, whenever
you're pointing to a file relative to yourself, we'll talk about that later again,
later one, when you start importing the third party bundles, uh, packages. Now we
have to do this. `getNiceMessage`, which we know is a function. We call it down here
and pass it five for five points because we're running the watch script in the
background. When we refresh, that works instantly. Now, when we left at the Webpack
homepage docs originally,

MMM,

we didn't see `require` being used, something called `import` being used. It turns out
this required are two ways to import external functions. It's your sin taxes that are
effectively identical. There's required and there's important. So let's refactor this
to use import. So instead of `module.export`, you're going to say `export default`.
And that's going to have the same effect. This word `default` here is important. You
can actually with this syntax, export multiple things from a file. When you `export
default`, it's kind of the way of the standard way you export in one thing. We're not
going to talk about how you export multiple values, but I just want you to know that
it's possible. And then over here, we're not going to say
`import getNiceMessage from './get_nice_message'`. And it works exactly the same way.
Now it turns out this import/export in text, and this is actually the standard,
it's syntax that you should use, um, with Webpack. The require thing comes from node.
It works, but it's not the recommended way of doing it. So you as important export. But I
wanted you to see what the require and the other one looked like. You can even do it
also for CSS, it's just `import`. There's no from, because we're not, we're just kind
of including it without any value. Just try that, refresh. It works.