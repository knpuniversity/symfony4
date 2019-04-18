# Autoprovide Jquery Modules

Coming soon...

There's still some work to do on this `new.html.twig`. We bringing some
autocomplete stuff. We also link to one of our own,

yeah,

`public/js/algolia-autocomplete.js` files, but which is actually our last JavaScript by on the
pump directory. Yay. Which is Dr Reddy, very traditional and does some autocomplete
stuff with this. Actually does it add some auto completion to this box, which is
totally not working right now because we've broken everything, so let's fix that.
First thing is in some of our `new.html.twig`, we have a CDN link to an
autocomplete library. I'm going to install that 

```terminal
yarn add autocomplete.js --dev
```

Next, just like before, I'm going to take this `algolia-autocomplete.js`, and
I'm going to move it into my `js/` directory, but I'm not going to make this a new entry
file. I could do that, but really we already have a file and entry file being brought
in on this page. It's `admin_article_form`. So really what `admin_article_form` should
probably do is just use the code inside Algolia autocomplete or should actually call
that code. So I'm gonna Move `algolia-autocomplete.js` into the `components/`
directory, which is kind of meant to be for reusable little components. And this
isn't really written like a component yet because these files and here's your
probably return a value export value, not just run some random code, but we'll work
on that in a second. I'm also going to take the `algolia-autocomplete.css` file
and I'm going to move that all the way up here into my `css/` directory and just because
we can, I'm going to rename that to `.scss`.

Okay,

now that we have these two files over here under `assets/` directory from `admin_article_form.js`
we're going to import those so I can say `import './components/algolia-autocomplete'`
and `import '../css/algolia-autocomplete.scss'`. Getting noticed.
I'm not doing import from on my job stir file because it doesn't return anything yet,
so I'm just importing it so it executes. Um, we're going to make that a little bit
nicer and a second. Now in `new.html.twig`. The great thing is, is we don't need
to import this CSS file anymore, uh, or any of these script files. This is really how
we want our templates to look. A single a call to `{{ encore_entry_script_tags() }}` and a
single call to `{{ encore_entry_link_tags() }}`. All right, so you refresh right now, not
surprisingly, it's still not going to work in the error is our classic 

> $ is undefined

Come from `algolia-autocomplete.js`.

Okay,

so let's get to work inside of that file. Of course we're referencing `$`,
so that means that we need to `import $ from 'jquery'`. We're also using the
autocomplete library in here, so I'm going to say `import autocomplete from 'autocomplete.js'`
Oh wait, that's not quite right because if you look, your honor,
that Jess Library is just a standalone library and you can, you can export it, you
can impart a value from it and use it. But at least the way that we were using auto
complete, we were using it as a jQuery plugin.

Okay.

So we're, we're, and this is a common thing with libraries, they'll kind of have a
main way for you use the library. Then we'll have a jquery plugin way. So we could
refactor our code down here to use the actual kind of official way of doing it. But
we'll see if we can actually get this to work as a jQuery plugin. So what I'm gonna
do is I'm actually gonna hold `command` or `control` + click into `autocomplete.js`.

Okay.

And then on a double click over here so we can get right down to it. So you can see
it normally includes `index.js` is its main file right at the root of the project.
But if you look in `dist/`, Hey` autocomplete.jquery.js`. That's actually what
we were including via `<script>` tag before, so instead of in importing audit plate from,
I'm just going to say `import 'autocomplete.js/dist/autocomplete.jquery'`
Remember we don't import from with Jquery plugins because they actually modify the
jquery object instead of returning a value. All right, so let's try this move over.
Refresh and o 

> jQuery is not defined

Notice it doesn't say "$ is not defined". It says "jQuery is not defined" and 
it's coming from inside of `autocomplete.jquery.js` is coming 
from inside of that third party module on line 241 so this is a tricky thing 
with jQuery plugins. This is actually the second jquery plugin that we've 
worked with. The first one was a bootstrap any for remember when did with bootstrap, 
we didn't have any problems. If you look inside of our `app.js` file,

we imported bootstrap and it just worked. Now bootstrap actually modifies jquery, but
bootstrap is a well written jQuery plugin, which means inside that booth bootstrap.
If he looked at it, it actually doesn't import on jquery and then modifies it. But
this Algolia `autocomplete.js` plugin is not Weldon written. What it does is it
simply starts referencing jquery as if it were a global variable instead of trying to
import it. But since we're not using global variables anymore, it doesn't work. So
I'm a jquery plugins are kind of a special monster. They've been for the most part of
the system, has been around for so long. It doesn't always play well with kind of the
new way of doing things, which Jake named Jerry Koo. Jquery plugins in general are
getting less and less popular, but so the basically the module, the filing we're
trying to import is written incorrectly. Fortunately Webpack amazingly has a way
around this and `webpack.config.js`, it doesn't matter where, but we
already have an example down here. There's a spot called `.autoProvidejQuery()`
uncomment that go and restart encore 

```terminal-silent
yarn watch
```

and when it finishes moved back over and refresh,

took it out, no errors. And if I start typing in this audit complete box, it works.
So `.autoProvidejQuery()` is something that basically fixes old code like this. And
I don't use it until I have to, when it actually does is every time that it finds a
`jQuery`, or a `$` variable anywhere in any of the code that we use,
including our own code and that variable is um, an initialized. It replaces that with
`require('jquery')`. So it fixes this problem by actually rewriting the broken code.

Okay,

so not works, but while we're here, I want to make one small improvement. And that's
this. If you look inside the `admin_article_form.js`, we include both the, this,
our JavaScript file here and the CSS file for Algolia autocomplete. But if you think
about it, this CSS valley here, this is meant to support the Algolia autocomplete. So
really that CSS file, that Sass file is really a dependency of `algolia-autocomplete.js`
here's what I mean. I want you to take out the `import` and move it into
`algolia-autocomplete.js` and make sure you update the path that is just a little bit
nicer. This file is now defined defining the CSS that it needs in order for it to be
used. Wherever the heck gets used from an `admin_article_form.js`, all we need
to do is just require import that one JavaScript file and that one JavaScript file
takes care of important in the CSS that it needs. The result is exactly the same. It
just is a little bit of a cleaner way of doing it.

Okay.

All right. The last thing that I think we can clean up is um, we're so used to
writing code like this with document that ready and just having all the code do
stuff. We really need to start thinking about reusable components. So instead of
Algolia autocomplete doing stuff, let's actually have an `export` of value, like a
function that can initialize all this functionality. So check it out instead of
`$(document).ready()`? I'm going to say `export default function` and I'm going to have
this, I'm going to require three arguments. The `$elements`, jquery elements that we
want this behavior attached to the `dataKey`, which is going to be used down here as a
way of a defining where you get the data from on the Ajax call. Um, this is something
we built an earlier tutorial, so I'm not going to go into all the details and the
`displayKey`, which is another key down near the bottom, which says which field on the
JSON to actually render.

So what I'm basically done is I've taken all this cohere and I've taken out the
specific parts and replace them with generic variables. So now I'm going say
`$elements.each()` for the `dataKey`, we can put a little thing here that says 
`if (dataKey)` and `data = data[dataKey]`. And down here we'll just call, call
`cb(data)`, not on here. Oh, `displaKey: displayKey`. The point is this
file doesn't do anything anymore. It just exports a re usable function in 
`admon_article_form.js`. Now we're going to `import autocomplete from './componenta/algolia-autocomplete'`

Yeah. Okay. Yeah.

Then down here I'll say `const $autoComplete =` m to find that same j query element as
before. `.js-user-autocomplete`.

Yeah,

so this is the exact same um, selector we were using before and js user autocomplete.

Wow.

And then if not `$autoComplete.is(':disabled')` Then we're going to call that function
`autocomplete()` referencing the very, we brought in here `$autoComplete` comma
`'users'`. That was the `dataKey` we had before an `'email'` that was the `displayKey`. 
It's we're calling that function, which is really useful. By the way, the reason I'm doing
this `:disabled` is the way we originally made these forums is this author field
is actually disabled on the edit endpoint, so I don't want to actually add the
functionality and less it doesn't have disabled. So for your question now, yeah, I
think that worked out of the planes nicely and just to make sure I didn't mess up my
edit page, I'll go back to `/admin/article` and at one of these and yes it looks good
so you can see it disabled here so it doesn't add that functionality. This is much
nicer.