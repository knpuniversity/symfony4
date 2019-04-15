# External Libs

Coming soon...

We've included the APP and tree inside of our base layout, the script tag and the
link tag both live here. So basically anytime any, uh, CSS or JavaScript that's sort
of global to our site, we're going to put into APP dot js. So if you look down here,
I actually do have some inline JavaScript here and I want to refactor all this code
entirely into our encore system. So if you look, the first thing we have is we have
jquery down here, which makes sense because we are renting, renting right below,
right below that. So I'm going to remove it. And not surprisingly, when we do this,
we get a big air. That dollar sign is not defined, no problem. One of the, one of the
most wonderful things about using encore has that you can install these third party
libraries properly. So I can spin over, I'm actually gonna open a third tab here. You
can say yarn, add jquery, Dash Dash Dev. We put this as Dash Dash Dev because
technically we don't need these dependencies, um, production, uh, but it doesn't
really matter.

Okay.

And that's it. We now have jquery in our site, so we already know how to require a
how to import a file that lives right next to us to import a third party file. I'm
going to say import dollar sign from, and then the name of the module j query. Notice
there's no dot /year when you don't have the dot /it knows that you referring to a
third party module and specifically it knows to look in the node modules directory
because that's where it just installed jquery. So if you look down here, there it is
jquery. So it's going and grabbing a nose to go and get the main file from this
repository. In fact, fun fact in case you ever get stuck, how does it know exactly
which file in here to grab? Well, it looks inside the module. It looks in the packs
that JSON File and every package that JSON file is going to have a main key that
points to the one file that should be required. So it's actually requiring this
specific file.

Yeah.

All right, so now that we've imported, we've important jquery from here, we've set it
to a dollar sign variable. That script tag is included up here, so in theory we
should have a dollar sign variable available down there, right? No, it doesn't work
that way. You can still see that we have dollar sign is not the find and this is
coming from, let's see, this not the right spot. Should look at the second one. Here
we go. It's coming down here from our coat, so this is a really important
distinction.

Wow.

When you import a file from within Webpack the file that we important behaves
differently.

Yeah,

I mean is we could literally add a script tag to this same jquery dot js file and it
would give different behavior. That's because a well wouldn't written JavaScript
library has logic internally.

Yeah.

To handle this. If you look inside that jquery dot. JS, it's a little bit hard to
read, but what it's basically doing here is it's detecting it's environment. It's
detecting whether or not it's being used within something like Webpack. It's if if
type of module that exports = an object. If it is, what it does is it actually
returns the jquery function, jquery object from this in the same way that we are
exporting a value from within our get nice message. It's doing the same thing.

If it does not detect that. And this code's not too obvious down here, but what this
actually does is it creates a global variable. So if you include this jquery dot js
as a script tag EOA, it will create a global variable. If you actually import it like
we're doing here, it does not create a global variable. It just creates in returns,
does local variable. That's why once we get into our base studies should it's wig.
The, our son is still undefined and that's actually what we want. We don't want to
work with global variables any more.

Okay?

So the ultimate solution is that we need to refactor all of this code from our base
template into encore properly. However, especially if you are upgrading an existing
site, you might have a lot of, uh, global, uh, code inline scripts like this that
require jquery to be global. If you want, you can say global, that dollar sign.

Okay.

= dollar sign. That would actually make j query global. There's global key here is a
special key by Webpack in it and we'll know what to do with that. So if we go back
and refresh now it actually works.

Yeah,

so I'm going to comment that out with a, a comment that says that we could uncomment
that to support legacy code. Of course what we want to do is actually get rid of this
stuff. So we're going to copy on a move all of my inline script from my base template
and paste it into this file. And cool thing is where she importing dollar sign. So
that's why it's called dollar send down hand down here. It's just local variables.

Okay.

So if we refresh this, it doesn't work well, it's sort of works. Check this out. It
says, you know, untie type air. Some Webpack stuff. Defaulted dropdown is not a
function. If you click here, it's having a problem with this.and Trump down thing.
Okay, so this actually makes sense. That dropdown function comes from bootstrap. It's
one of the functions that bootstrap adds to jquery.

And right now we're running all of our existing uh, code here and then we're
importing bootstrap. So it's not adding the function in time. It's actually a little
bit more to it than that. But that's basically the idea and that's fine because we
want to get rid of this kind of code anyways. We don't want to bootstrap to be
included. By the way, popper is just included here because it's a dependency of
bootstrap. So let's actually do this properly. We're going to remove both of those
and then I'm going to re add bootstrap via yarn, add bootstrap Dash Dash Dev, by the
way, you'll see it later. But there's lots of ways to search for packages. Uh, they
usually have good names, but I've never share. You can search for them yet. 9.7
million downloads, that's the one we're looking for. So that's how you would figure
out the correct package name. All right, so that downloaded, and you'll notice here a
little->that says bootstrap has an unmet pure dependency popper. We'll come back to
that in a second. So in APP dot. JS installing it is not enough. We're not actually
using inside of here. So we need to do up here is say import and then say bootstrap.
No, nothing that saying important dollar sign from or anything like that. Boost
jquery plugins are weird because they don't return a value. They actually modify j
query and add functions to it. In fact,

okay,

I'm gonna make that note here

and internally, the way it knows to do this is that because bootstrap was a
well-written library inside that bootstrap file, it actually imports Jacory just like
we are here. And when you import, uh, uh, when two different modules, when two
different files important the same file, they get back the same instance. So
basically we sat down our sign here a second later bootstrap internally is going to
import that same j query object in, it's going to modify it. So by the time we get
after line $12 on Hatan now has new functions attitude. But you may have seen the
build errors. If you go back and look at our yarn, it says failed to compile. This
dependency was not found. Popper dot. Js In bootstrap dot js. So bootstrap dot js
itself depends on Jquery, but it also depends on this library called popper dot. JS
and again, the way it does that internally because it's well written, is it imports
popper dot. Js because popper dot js is not something that's inside of our project
right now. We get this really nice air and I'll says down here, we can install with
NPM install dash dash say papa dot js. We're using yarn. So we will kind of take that
recommendation, but we'll install it via yarn. So I'll go back to my open tab, yarn,
add Papa Dot Js Dash Dash Dev.

And as soon as that finishes,

uh,

Webpack doesn't know to rebuild yet because we haven't made any changes. So I'm just
going to go over here and just add a space and save. That triggers a rebuild and the
build is successful. So move back over. Refresh.

Okay,

no errors. And it works.