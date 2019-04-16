# Bootstrap & the Curious Case of jQuery Plugins


so I'm going to comment that out with a, a comment that says that we could uncomment
that to support legacy code. Of course what we want to do is actually get rid of this
stuff. So we're going to copy on a move all of my inline script from my base template
and paste it into this file. And cool thing is where she importing `$`. So
that's why it's called `$` down hand down here. It's just local variables.



Okay.

So if we refresh this, it doesn't work well, it's sort of works. Check this out. It
says, you know, untie type error. Some Webpack stuff. Defaulted dropdown is not a
function. If you click here, it's having a problem with this.and Trump down thing.
Okay, so this actually makes sense. That `dropdown()` function comes from bootstrap. It's
one of the functions that bootstrap adds to jQuery.

And right now we're running all of our existing uh, code here and then we're
importing bootstrap. So it's not adding the function in time. It's actually a little
bit more to it than that. But that's basically the idea and that's fine because we
want to get rid of this kind of code anyways. We don't want to bootstrap to be
included. By the way, popper is just included here because it's a dependency of
bootstrap. So let's actually do this properly. We're going to remove both of those
and then I'm going to re add bootstrap via

```terminal
yarn add bootstrap --dev
```

by the way, you'll see it later. But there's lots of ways to search for packages. Uh, they
usually have good names, but I've never share. You can search for them yet. 9.7
million downloads, that's the one we're looking for. So that's how you would figure
out the correct package name. All right, so that downloaded, and you'll notice here a
little arrow that says

> bootstrap has an unmet pure dependency popper

We'll come back to that in a second. So in `app.js` installing it is not enough.
We're not actually using inside of here. So we need to do up here is say `import`
and then say `bootstrap`. No, nothing that saying `import $ from` or anything
like that. Boost jquery plugins are weird because they don't return a value. They
actually modify jQuery and add functions to it. In fact,

okay,

I'm gonna make that note here

and internally, the way it knows to do this is that because bootstrap was a
well-written library inside that bootstrap file, it actually imports jQuery just like
we are here. And when you import, uh, uh, when two different modules, when two
different files important the same file, they get back the same instance. So
basically we sat `$` here a second later bootstrap internally is going to
import that same jQuery object in, it's going to modify it. So by the time we get
after line 12 on Hatan now has new functions attitude. But you may have seen the
build errors. If you go back and look at our yarn, it says failed to compile.

>This dependency was not found

`popper.js` In `bootstrap.js`. So `bootstrap.js` itself depends on jQuery, but it
also depends on this library called popper.js and again, the way it does that
internally because it's well written, is it imports `popper.js` because popper.js
is not something that's inside of our project right now. We get this really nice error
and I'll says down here, we can install with `npm install --save popper.js`.
We're using yarn. So we will kind of take that recommendation, but we'll install it via
yarn. So I'll go back to my open tab,

```terminal
yarn add popper.js --dev
```

And as soon as that finishes,

uh,

Webpack doesn't know to rebuild yet because we haven't made any changes. So I'm just
going to go over here and just add a space and save. That triggers a rebuild and the
build is successful. So move back over. Refresh.

Okay,

no errors. And it works.