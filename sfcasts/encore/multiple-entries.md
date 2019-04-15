# Multiple Entries

Coming soon...

On the article show page. If you check out of the console, we have an air dar sign is
undefined coming from articles show dot js. This shouldn't be surprising if you look
at our code here. So in our template at the bottom, we've been including a j ass
article show dot js. So if we look inside of our public directory, I'll close build
for a second article showed that jazz was a very simple traditional JavaScript file
that we made in the previous tutorial. But guess what, dollar sign doesn't exist
anymore. Now if you look closely on this page, you'll see that that at the bottom
that we do include the APP dot js file first before article show dot js and of course
the APP dot js file does import dot j query. But as we learned, this does not make a
global variable. It just makes a local variable.

So it does not work in article show dot js and that's fine. We want to refactor this
to work nicely, uh, to, to be processed by what? WESTPAC. Because ultimately what we
want to do up here as important dollar sign, not rely on it to already be there cause
that's another property of these, um, of the new way of doing things because you
never, every file is a standalone file. So if we need a dollar sign variable inside
of this file, regardless of who's using it, we're going to need to import it on top.
Of course you can't do that yet because this hasn't processed by Webpack. Before we
actually get into this, I'm gonna do a little bit of organization down here and my js
directory, I'll credit new components directory. I'm going to move, get nice messages
into that. And then you can see I have the build air here. I'll update my import
statements appointed to that.

All right, next, I originally put this code into its own file because this code only
relates to our article show page. So we couldn't do this. We could basically copy all
of this code here and we can put it into app dot js and it would work just fine. But
sometimes instead of having one big JavaScript file, you instead want to have
multiple files, uh, page specific JavaScript and page specific CSS. It's just a
matter of how you're building your APP and your preference. So in this case, I don't
want to have, I don't want to put the article showed how js code into APP dot js
because it will just make it unnecessarily big on all the other pages. So instead
we're going to create a second entry file. Check this out. I'm going to take article,
showed that js and I'm going to move it to the js directory. Next I'm going to go
into the Webpack dot config dot js file and up here with add entry, I'm going to add
a second entry call article on their scores show pointing at assets js article showed
at js now,

okay,

when we build went back, it's going to load app dot js and ultimately build an APP
dot js file and an APP dot CSS file and then it's going to load article, show that js
and build an article showed that js and an article. That article showed that CSS
file. If we have CSS.

Okay.

Each entry point is like a standalone application and were ultimately

okay.

So this will allow us to have the all the JavaScript and CSS we need needed the
article show page being built.

And now that we have this new article show entry, instead of show that age dot twig
instead of our script tag, we're going to say an encore entry script tags and we'll
say article_show. Notice I don't have any uh, link tags anywhere. They're not hiding
on top either. That's because so far article should at js is not including any CSS.
So it's not going to create an article showed at CSS file yet. All right cause we
just made a change to our Webpack config file. I'm going to go and run yarn watch
again.

Okay.

And yes, there it is. You can see APP made these files and article show made just
this one file articles show dot js by the way, I could have talked about the temp
copy entry point but I won't,

I won't know.

So let's go back over and low reload and air dollar sign is not defined. It's the
same air of course, because we still don't have a dollar sign variable instead of
article show. Remember every file is like its own unique snowflake. So if you need a
variable in a file, you need to import it. So important dot aside from j query now to
refresh, boom, that is gone. And we can do other, a little silly heart thing we built
on an earlier tutorial. No notice. As I mentioned, we're not including any CSS from
here, so it's not actually a rendering and article showed that CSS file and sometimes
you might have this situation. But now that we have this setup, we can actually be a
little fancier in r_articles that sdss party of this contents of this file.

Okay,

is actually for the article show page, which does not really need to be included on
every page anymore. So I'm gonna take all of this stuff down here, remove it from
this. And at the root of the CSS directory, I'm going to create a new file called
article_showed to SPSS and paste that stuff

and see what the APP dot js and articles show. Dot js are both cut entry points. They
both kind of the find everything you need for that page. App Dot scss an article
showed a sdss are the same way. So the top of article showed a scss. I don't strictly
need to do this, but I want to do an import for helper /variables that scss. I'm not
using any of variables here, but the point is this file is rendered independently of
APTA scss. So if you need to do some bootstrapping on top of some variables, then you
should do it on top of this file. It's a standalone CSS file.

Okay.

And now that we have this,

okay,

the top of article showed up to ask me and say import.dot/css/article showed up stss
and now if you flip over, suddenly we have an article showed at CSS being rendered
and you can see it's actually splitting. We have actually an extra JavaScript file
here as well. That's because as soon as we important jquery into our article, showed
that jazz, it figured out a better way to split the files up. So this is a piece
probably includes jquery. Well we don't care because it automatically renders it.

Okay.

All right. Now they're the new CSS file and our show to ace on twig and a copy of the
scripts block chains that to style sheets and then changes to encore entry link tags
and that should do it. All right. Move over. Refresh. Yes, everything looks good. The
hearts don't works. If I inspect element on this page, you can see at the top here in
the head, we have app dot CSS, so our main CSS file and then we do have our page
specific article showed us CSS and it's the same thing at the bottom of the page.
Even though it's a little more confusing, we have these, uh, uh, these first few
piles are his first two files are part of the APP dot js entry point and then article
showed out js is included afterwards.

Okay.

[inaudible] system works very, very smartly.