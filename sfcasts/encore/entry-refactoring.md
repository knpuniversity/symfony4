# Entry Refactoring

Coming soon...

This point, my goal is to get rid of all the rest of the stuff that we have in our
public director, like the CSS and JavaScript files. So let's look at this admin
article, form dot. Js. Uh, this is actually used in our admin section. So if you go
to, let's go to /admin /article. If you need to log in and log in as admin one of the
spacebar Dotcom Password, he engaged.

Hm.

And then click to edit any of the pages. So we have a little bit of JavaScript that
handles things like drop zone, um, and some other functionality. So if you can only
look at,

yeah,

so if you look at this template, it lives and templates are Gladman edit that age
Tamala twig and scroll down. Okay. So we have, here's our traditional link tag for
Admin Article Form Dot. Js. We're also including some other JavaScript files which
will handle the second, but this is very similar to what we just did. We want to do
is take our admin article, format js first and just move it into our assets. Js
Directory. This is going to be yet another entry file. So next in Webpack dot config
dot js I'll copy the ad entry and we're going to have one called admin article form.
What's points at that Avnet article? Form Dot js file. And finally inside the edit,
that age from a twig, we'll change this to use our encore interest group tags or that
same admin article form fit because we just updated our Webpack dot config dot js
file move over, rerun yarn watch and perfect splits everything up.

Okay.

But not surprisingly when we refresh, we get a JavaScript error are very familiar.
Dollar sign is not defined because the first thing we do in that file is we've talked
to jquery. So this is easy. Now we're just updating our files to use a new way of
doing things in Fort Dawson from j query and we are good to go.

Nice.

So in addition to things out of our public

directory, I also want to get rid of all of these script tags in. The reason is the
script tags. There's nothing wrong with including external scripts, but the script
tags are creating global variables. So the new way of doing JavaScript is you never
want to have undefined variable. So if we're going to use a dollar sign variable, we
need to import dollar sign, but check it out. We're just referencing drop them. Where
did that come from? Well the answer is it's a global variable created by this drop
some JavaScript file and there's also one in here for sortable as well down near the
Bob. I do not want to do that anymore. I don't want to rely on global variables. So
we are going to go and to kill both of those script tags and it's not a, we're going
to install them via yarn. So find your terminal, go to your open tab and run yarn,
add drop zone space sortable js Dash Dash Dev. I've already looked up for those exact
package names to make sure that those are correct, that finishes. And then we can go
over into our admin article form that js. And these will truly be undefined now. So
if we refresh, we'll get good. Excellent. Air Drops is not a fine. So we'll import
the drop zone from drop zone and we'll import sortable from sortable js

and now it works. But there's one other thing that you may have noticed in our edit
template. We also have the CDN link to the, to the um, the CSS file. We also don't
need that anymore. Get rid of that. And instead in Article Form Dot Js, we can just
import the CSS from drop zone directly. So I'm a hold command or control here to
click in the drop zone, which takes us over here and use a little trick

to kind of look at this. I'm going to double click drops on here. That'll take us to
that spot. And I can see inside this there is a drop some dot CSS file. So that's
actually the path that we want to include. I'm going to require, and we can actually
do it here, we can say import, drop zone /and demand, just the path dist /drop zone
dot CSS. So most of the time we're lazy and we just actually say the module name, but
it's totally illegal to say the module names /a specific file name and that will
import it. Now, as soon as we do that, if you go back over to encore, you can see
that for the admin article form. See, wow, that list is getting really long, but
inside of here it's actually rendering some CSS files now. So we'll flip back over,
go to edit age and en suite and just say encore entry link tags, admin article form.

Okay.

All right, so let me move over, refresh and God, I can see the styling here is coming
from drop zone. If free quick Jopson and actually still working just fine. This a
JavaScript has also included on another page. It is actually the the new form page.
If we go back to /admin /article and you click create, we still have some problems
inside of here, so I'll close up node modules, go back into templates. Article Admin
knew that age to molest swig. There is js admin article form. We're just gonna
replace that with our script tag over here. And then we'll do the same thing for the
link tag. So an encore entry link tags, admin article form over a refresh, and it's
still totally going to be broken because we are still young. This is kind of from
auto complete that jquery that men, so that's because this thing still has a couple
of external libraries, uh, that we need to take care of next. So we've sort of half
fixed this new template, but half haven't fixed it yet. So let's do that next.