# Multiple Entries

Coming soon...

On the article show page. If you check out of the console, we have an error 

> $ is undefined 

coming from `articles_show.js`. This shouldn't be surprising if you look
at our code here. So in our template at the bottom, we've been including a 
`js/article_show.js`. So if we look inside of our `public/` directory, I'll close `build/`
for a second article showed that js was a very simple traditional JavaScript file
that we made in the previous tutorial. But guess what, `$` doesn't exist
anymore. Now if you look closely on this page, you'll see that that at the bottom
that we do include the `app.js` file first before `article_show.js` and of course
the `app.js` file does import jQuery. But as we learned, this does not make a
global variable. It just makes a local variable.

So it does not work in article show dot js and that's fine. We want to refactor this
to work nicely, uh, to, to be processed by Webpack. Because ultimately what we
want to do up here as import `$` not rely on it to already be there cause
that's another property of these, um, of the new way of doing things because you
never, every file is a standalone file. So if we need a `$` variable inside
of this file, regardless of who's using it, we're going to need to import it on top.
Of course you can't do that yet because this hasn't processed by Webpack. Before we
actually get into this, I'm gonna do a little bit of organization down here and my `js/`
directory, I'll credit new `components/` directory. I'm going to move, `get_nice_messages.js`
into that. And then you can see I have the build error here. I'll update my import
statements appointed to that.

All right, next, I originally put this code into its own file because this code only
relates to our article show page. So we couldn't do this. We could basically copy all
of this code here and we can put it into `app.js` and it would work just fine. But
sometimes instead of having one big JavaScript file, you instead want to have
multiple files, uh, page specific JavaScript and page specific CSS. It's just a
matter of how you're building your APP and your preference. So in this case, I don't
want to have, I don't want to put the `article_show.js` code into `app.js`
because it will just make it unnecessarily big on all the other pages. So instead
we're going to create a second entry file. Check this out. I'm going to take 
`article_show.js` and I'm going to move it to the `js/` directory. Next I'm going to go
into the `webpack.config.js` file and up here with `addEntry()`, I'm going to add
a second entry call `article_show` pointing at `./assets/js/article_show.js` now

okay,

when we build Webpack, it's going to load `app.js` and ultimately build an `app.js`
file and an `app.css` file and then it's going to load `article_show.js`
and build an `article_show.js` and an `article_show.css` file. If we have CSS.

Okay.

Each entry point is like a standalone application and were ultimately

okay.

So this will allow us to have the all the JavaScript and CSS we need needed the
article show page being built.

And now that we have this new `article_show` entry, instead of show that age dot twig
instead of our `<script>` tag, we're going to say an `{{ encore_entry_script_tags() }}` and we'll
say `article_show`. Notice I don't have any uh, `<link>` tags anywhere. They're not hiding
on top either. That's because so far `article_how.js` is not including any CSS.
So it's not going to create an `article_show.css` file yet. All right cause we
just made a change to our `webpack.config.js` file. I'm going to go and run 

```terminal
yarn watch
```

again.

Okay.

And yes, there it is. You can see `app` made these files and `article_show` made just
this one file `articles_show.js` by the way, I could have talked about the 
`_temp_copy` entry point but I won't,

I won't know.

So let's go back over and low reload and error 

> $ is not defined

It's the same error of course, because we still don't have a `$` variable instead of
`article_show`. Remember every file is like its own unique snowflake. So if you need a
variable in a file, you need to import it. So `import $ from 'jquery';` now to
refresh, boom, that is gone. And we can do other, a little silly heart thing we built
on an earlier tutorial. No notice. As I mentioned, we're not including any CSS from
here, so it's not actually a rendering and `article_show.css` file and sometimes
you might have this situation. But now that we have this setup, we can actually be a
little fancier in our `_articles.scss` party of this contents of this file.

Okay,

is actually for the article show page, which does not really need to be included on
every page anymore. So I'm gonna take all of this stuff down here, remove it from
this. And at the root of the `css/` directory, I'm going to create a new file called
`article_show.scss` and paste that stuff

and see what the `app.js` and `articles_show.js` are both cut entry points. They
both kind of the find everything you need for that page. `app.scss` an 
`article_show.scss` are the same way. So the top of `article_show.scss`. I don't strictly
need to do this, but I want to do `@import 'helper/variables.scss`. I'm not
using any of variables here, but the point is this file is rendered independently of
`app.scss`. So if you need to do some bootstrapping on top of some variables, then you
should do it on top of this file. It's a standalone CSS file.

Okay.

And now that we have this,

okay,

the top of `article_show.js` say `import '../css/article_show.scss;`
and now if you flip over, suddenly we have an `article_show.css` being rendered
and you can see it's actually splitting. We have actually an extra JavaScript file
here as well. That's because as soon as we important jquery into our `article_show.js`
it figured out a better way to split the files up. So this is a piece
probably includes jquery. Well we don't care because it automatically renders it.

Okay.

All right. Now they're the new CSS file and our `show.html.twig` and a copy of the
scripts block chains that to stylesheets and then changes to `{{ encore_entry_link_tags() }}`
and that should do it. All right. Move over. Refresh. Yes, everything looks good. The
hearts don't works. If I inspect element on this page, you can see at the top here in
the head, we have `app.css`, so our main CSS file and then we do have our page
specific `article_show.css` and it's the same thing at the bottom of the page.
Even though it's a little more confusing, we have these, uh, uh, these first few
piles are his first two files are part of the `app.js` entry point and then 
`article_show.js` is included afterwards.

Okay.

[inaudible] system works very, very smartly.