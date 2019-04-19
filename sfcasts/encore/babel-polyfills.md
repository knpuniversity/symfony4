# Babel Polyfills

Coming soon...

I've just ended this `browserslist` key here to say that we want to support all
browsers that have at least 0.05% market share, which is not a very realistic number,
but that's a really great way to say, to kind of force to say that our project even
needs a support really, really old browsers. And we learned that the autoprefixer
library, which is, um, being applied by PostCSS, it reads this. And because of that,
it's now adding lots and lots of vendor prefixes to support older browsers. So it
turns out that we also talked about how the fact that our JavaScript is also being
rewritten and the tool that's doing that is called Babel.

Okay.

Babel is actually rewriting this `var $autoComplete` when originally it was actually
`const $autoComplete`. What turns out Babel also reads this browserslist config.
That's what makes that idea. So, so cool. All the tools, kind of our standardized and
know to look for this. So right now it's not surprising that the `const` is being
rewritten to `var` because we've said that we want to support really, really old
browsers. And `const`, uh, only a few years old. So to see how this, let's see if
we can get this to work. Let's actually change this to `> 5%` and this
actually means we only need to support the most absolute, most popular browser is
probably no old versions. So if we go back and let's rerun Encore

```terminal-silent
yarn watch
```

and then move back over here to our `admin_article_form.js` refresh. In fact, I'll
do a force refresh and search for `$autoComplete` and ha far is still there. So this is
a bug currently with Babel and browserslist. A babble for performance has an
internal cache and that cache doesn't know, doesn't know to update when we change the
browserslist config. So anytime you change this browserslist config, you need to
go over into your browser and manually delete a `node_modules/.cache/babel-loader/`
directory. Soon as we do this, let's rerun encore, 

```terminal-silent
yarn watch
```

now refresh and it
will search for that `$autoComplete`. And there it is `const $autoComplete` and we can
look for the `class ReferenceList`. Now that we're only using supporting new browsers,
we don't need to rewrite to that old code, which is nice because rewriting the old
code actually makes your files larger.

Yeah,

so it was one of the really, really cool thing that Babel is doing behind the scenes
to see it. Let's go back into our `admin_article_form.js` and it doesn't really
matter where, but down in our `ReferenceList`, I'm going to say `var stuff = new WeakSet([]);`
`WeakSet` is a, uh, an object that was introduced in es2015. So it's
only about four years old. So most new browser supportive, but some old browsers
don't. So I have my watch going said already rebuilt stuff. I go back and refresh the
build file. You can see it right here. `var stuff = new WeakSet([]);` that works. Now
here's the thing about Babel. He can rewrite new syntax to old syntax, but out of the
box, if there are completely brand new features like brand new objects or brand new
functions, you, those are not rewritten. You need something called a polyfill.
Polyfill is basically a library that adds this object if it doesn't exist for you.
Fortunately Babel can add these things polyfills automatically for you in Encore is
preconfigured to do that. So check it out. Let's go back to our `package.json`. Let's
change this back to support old browsers. Then we'll move over. We will once again
clear the Babel cache and then restart encore.

```terminal-silent
yarn watch
```

Nice. So now I'm gonna go over.

Okay,

refresh that again. In search for a `WeakSet` set. So check this out. It's still says

it still looks normal down here, but if you just look for the word `weak`, you're going
to find a couple of other references on here. All these things on top, this is a
little bit hard to read, but it's actually doing is you can see it importing
something called `var ...js_modules_weak_set...` `core-js/modules/es.weak-set`. This core-js
library is a library full of polyfills. And what happened is that now Babel, realize
they were using that WeakSet in, it automatically added, it requires statement
for this. So this is almost, this is basically the same as if we had gone to the top
of this file and actually said `import 'core-js/modules/es.weak-set'`. This
function actually adds that week set functionality. So it happens automatically for
us. And you may have not realized that we were already using this before. If you look
in `build/app.js`, I remember one of our files is using a string `repeat()` function.

So it's `get_nice_message.js` We have a nice little, a string `'!'.repeat()`
 well, it turns out `.repeat()` is only a few years old as well. So if you
search for `repeat` inside of here, check this out. You can see that repeat is right
there. But before we have more `core-js/modules` imports. So we didn't even realize it,
but at the, when even when we started this tutorial, we accidentally used a function
that was relatively new and maybe didn't work in some of the browsers we support. And
it instantly, uh, added the polyfill forests. That is super powerful idea. By the
way, this is configured in your `webpack.config.js` files are relatively new
feature for Encore. It's this `.configureBabel()` it. This is how you can actually
control the configuration. And it's this `useBuiltIns: 'usage'` and `corejs: 3`
So `useBuiltIns: 'usage'` says, I want you to, um, uh, bring in the polyfills
whenever you see that I am using them. That's the `usage` spot. So pretty cool stuff.