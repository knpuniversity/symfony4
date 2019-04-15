# Single Runtime Chunk

Coming soon...

Hi back to the homepage and just click any of these articles here. One of the bits of
JavaScript that we uh, added an earlier tutorial and artery factor does this little
heart icon, which makes an Ajax call and increments the heart thing. So a bit of fake
code, but behind the scenes is making an Ajax call man, a little bootstrap tool tips.
When I hover over this it says click to like, so we know that this page template,
this page is article /showed at age two months wig. And I'll remind you that this
page has its own, uh, entry point its own article showed at jobs dot js. So let's go
on to assets jazz article show dot js and what we can do here is let's find the
anchor tag here. It is a h Ref Papa's on the multiple lines, so it's a little bit
readable and had a title = click to like, so all we need to do is copy this js like
article class here and inside the article should have js right on top.

I'll say jazz like article, that tool tip, which is a jquery plugin that bootstrap
ads easy, right? Well let's try it. Refresh and it doesn't work. Tool tip is not a
function. This may be surprised you, maybe it didn't surprise you. So if you think
about it at the bottom of our page, the uh, APP dot js script tags are loaded first.
And if you'll remember instead of APP dot js we import jquery and then we import,
bootstrap and bootstrap. This adds all of those functions like tool tip, they add
them to the dollar sign variable. So you might think that because then our articles
show that js has loaded. You might think that an article show dot js when you
important jquery, you're getting the jquery object that has already been modified,
pay, bootstrap. And for the most part that's true. When two different files import
the same module, they get the exact same object and memory.

However, by default Webpack treats different. Entry points is two totally separate
applications. So if we import dollar sign from here or if we import dollar sign from
getting nice message for example, which is it, which is important by this, they'll
get the same jquery variable object. But if when we important dollar sign from
article show dot js since that's a different entry point, it gets a completely
isolated environment and for the most part this is a good thing. We want our entry
points to behave like completely isolated environments. It doesn't mean that jquery
is downloaded two times, it just means that they are, we are given two instances of
them. So the fix is simple to import, bootstrap, however, refresh and yet this time
it works. So the reason I'm showing you this, uh, there's one other reason I'm
showing you this and that is to talk about a feature that we looked at very briefly.
The very beginning of our tutorial, which is there we go. Disabled single runtime
check. I want you to change is to enable single runtime chunk cause we just modified
Webpack come back over and control c and run it. Yarn. Watch again.

Yeah.

If you look closely, you're going to immediately see a difference. Every single entry
point is now pointing to eighth new file called runtime dot js, which basically means
it's a new file that needs to be a new script tag that needs to be included before
every single file, uh, before every single entry point. Of course, that's not
something that we need to worry about because I want to refresh the page and view
source our tweet functions. Take care of rendering this for us. There's two important
things I want you to know about this runtime dot js. The first thing is it contains
some of Web pacs runtime code. By enabling the single runtime chunk. What you're
doing is you're actually saying, hey bootstrap, instead of adding all of this code at
the beginning of APP dot js and in the beginning of article show dot js just include
it once in runtime dot.

Js, which means you have a, everything's a slightly smaller. The other thing is that
this runtime dot js which is pretty small, contains a couple of like internal ids
that point to our, our files, which might change more rapidly. So by isolating
things, by moving though a runtime code into Webpack dot. Js, it actually means that
our other JavaScript files might change less often. And anytime your JavaScript files
change, less often, it means they're better for caching. So the runtime dot js will
change a little bit more often, but it is smaller. So that's less of a big deal. But
there's one, there's side effect of this, and I'm not sure if it's a good side effect
or a bad side effect. Go back to article share dot js and comment out the import
bootstrap. If you go back and refresh the page.

Okay.

It works. So the side effect is that when you have that single runtime chunk, it
means that all the modules are shared across, um, all of your entry points. They work
a little bit more like a single application. It's not necessarily a bad thing. You
just need to be aware of that. In fact, it might be exactly what you want in your
application.