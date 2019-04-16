# Css

Coming soon...

We're on a mission to refactor all the old `<script>` tags and `<link>` tags out of our, of
our our templates and we took care of him. All of the `<script>` tags and our base
template. Snellen scroll up and look at the stylesheet. So we are including a
bootstrap as an external. Also we have fontawesome, which is actually right now
committed to our uh, `public/css` directory. And then you have some customers just like
styles dot CSS, um, right here. So let's start here. First thing is I'm going to do
is remove bootstrap because in the same way that you can uh, install on require third
party JavaScript libraries, you can install and require third party CSS.

So `app.jss` we already are importing a single `app.css` file. We could go
right here and we could import a bootstrap CSS file. But what I prefer to do is
actually important one CSS file. And then from within that CSS file, I'm actually
going to use the `@import` syntax to import any other CSS files. And Neat. Now you
might be thinking, don't we need to install the bootstrap CSS? And the answer is no.
We already installed it a few minutes ago. In `node_modules/`, I'll look for `bootstrap/`.
When means all bootstrap. It actually came with the JavaScript but it also came with
the CSS, so it's already there. The only tricky thing is that we know that from
JavaScript and we can say import the name of the module and that will get the
JavaScript library, but we can't do the same thing for CSS because we don't want the
JavaScript library.

Instead we can just use the path we want. So in this case probably `dist/css/bootstrap.css`,
so check it out. Want to say `@import` and then we're going to say
`~bootstrap` and then `/dist/css/bootstrap.css`. The tilda they part
here is special. When you want to reference the `node_modules/` directory from within a
CSS file you started with till that. That's a little bit different than how we do it
in JavaScript and JavaScript. It knows that we are referencing it just because we
don't have the `./` in the front of it and then it's after that it's just the path to
the file so it's sort of just that simple. So we go over and refresh. The page still
looks exactly the same, so it was one of the trick that you can do an Encore and we
can actually just change, shorten this to `~bootstrap`. Ooh, let's try that
refresh. That still looks great.

Okay.

The reason this works is a little extra thing. We added an encore, we already know on
that one we just import a module named like this. It goes into the `package.json`.
It looks for the main keyword search for Maine. There it is. So that's how it knows
to get the disc jazz bootstrap dot js file. Sometimes libraries will also include
these `style` or `scss` attributes and when they do, you can just say at important till
they bootstrap and because we're inside of a CSS file, it's going to look inside of
that `package.json` for a `style` attribute and if there is one, it's going to
require this file so it's just a short cut to the exact same thing that we were doing
a second ago. All right, so let's keep going here. The next thing we haven't based on
age on tweak is fontAwesome. I'm going to delete that and `<link>` tag.

Yeah,

and celebrate by actually deleting the `public/css/font-awesome.css` file. I
don't want to commit that stuff anymore. And this entire `fonts/` directory, yes, we are
deleting things that I never should have committed in the first place.

Okay.

Now to get it FontAwesome. We can install it. 

```terminal
yarn add font-awesome --dev
```

because I've already double check that. That's the name of the library that I
want.

When it finishes,

we'll go over here and in `node_modules/` I'll search for `font-awesome/`. There it is.
And Yeah, here's `css/`, `less/`, `scss/` whatever we want. And fortunately if you look inside
of here for for `"style"`, it has one of those special attributes that we were just
using. So you had, that literally means that we can go in here and say 
`@import '~font-awesome'` and that's it.

Yeah.

So let's go over, move over at refresh. And this was working down here. This right
here. That is a font awesome icon. And that is still working. Now this was maybe even
cooler than you think

because as we know, the way fine, awesome works. As we pointed to a CSS file, that
CSS file points to these font files here. But those files need to be public. So close
up node modules and check out our `public/build/` bill directory. Whoa. We now have a `fonts/`
directory. So when font awesome internally boot Webpack saw that fontawesome CSS
file. It was referring to these font files in `node_module/`. So it actually copied them
into our `fonts/` directory and then made sure that the final code inside of our CSS
files, uh, have a, have a, have the correct updated URL to these paths. So it
actually rewrites the URLs to go here. He didn't even adds a little hash here, so if
he's font files ever updated, that hash would update automatically and it would bust
cache. So basically all of that is just handled for us. In fact, we're going to see
one more example of this with the last `<style>` tag, someone to remove `styles.css`
and then we're going to open a `css/styles.css`. I'll copy all of this code here
now, then going to delete that old file and an ar `app.css`. I will overwrite my
background blue and paste all of that.

No, that should work, right, no problems. But you can immediately see there's a
module not found error. And if we go and check that out, let's see here. It says
module build, not found module, not found, can't resolve `../images/space-nac.jpg`
 And we can see this right here. Before we were listening, we had a
background image that was `../images`, which when we are in the CSS or Afteri that
point in to `../images/space-nav.jpg`. So we've not broken that. The
really cool thing is we got a build error. We can't accidentally break our pads and not
realize it. So all we can do now is I'm actually going to take this `image/` directory,
I'm going to go to cut, and we're going to move this into our `assets/` directory. Boom.
So it's gone from here. It's right here instead. And if I just kind of re save this
file to trigger a new build, there it is bill that successful. And you can see it's
happy here. And this is part of this top navigation here. So I'm going to refresh. It
works perfectly. And even better than that, if you look on the `build/` directory, there's
an `images/` directory now, which has our `space-nav.jpg`.

Yeah,

right there.

Yeah.

So once again, Webpack sees this path, sees that we're referring to this `assets/images/`,
directory and it realizes that we need to have this `space-nav.jpg`
public. So it actually moves it into the `images/` directory, then rewrites our
final CSS to point to this path. The great thing is all we need to do is we just need
to worry about writing our code correctly. We need to worry about, hey, if I have, if
I need to reference a background image, I just need to use a relative path to where
it is physically on my filesystem. I don't need to worry about where these files are
going to be moved to because Webpack is going to move them for us and it's going to
take care of rewriting the paths when it does.

Yeah.

Now this did actually break a couple of `<img>` tags on our sites because a couple of
his other files are referenced with the `<img>` tags and you can't really reference
things with image tags because these files are no longer in our public directory. So
we're gonna talk about how to fix that in a few minutes. One of the pointed out, we
will fix it.