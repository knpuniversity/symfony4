# Final Recipe Updates

Coming soon...

All right, let's finish all this recipe stuff. If I run composer recipes, we're only
focusing right now on the Symfony stuff. So let's get the rest of these done. So I'm
going to copy the PSP in a bridge one and I'll run composer recipes. Install Symfony
/PS unit dash bridge dash dash force, Tash V. and then we're going to follow the same
process as we did before. It says it created five files, but probably just updated
some of those files. So let's do our normal get add dash P. and the thing you can see
here is the app secret. We don't want to change that. Um, so what I'm gonna do, but
this patch Panther app N = Panther thing. If you're not using Symfony Panther, this
doesn't really matter to you. If you are, it's probably is a change that you're going
to want.

It's, uh, activates a new environment that Panther uses. So we're not using Panther,
but let's add this just in case, but I only want the second change. So what I'm gonna
do here is type S which means split. And then I can say no for the first change and
yes for the second change, the next change is didn't that get ignore? It adds, it
ignores a new piece of unit result cache file, which newer versions of pH mean an
output, let's say yes to that. And then the next thing has been slashed PHP unit. It
has a number of subtle updates inside of this file. This is kind of like bin console.
It's usually a file that you're not going to update. So let's go ahead and say yes to
these changes as well. And then the last file is PHP unit. That XML Baton dist
another file that you usually don't touch, but you might have some customizations
inside of there.

You can see the app, it changed from end to server. That's probably a subtle change
but mostly has the same stuff. The biggest change is that it removed this Symfony PHP
and version and Symfony piece of unit. Remove stuff down into environment variables
down here. So I'm going to say yes to this one as well. And then of course the
Symfony. That lock file will say yes to the next Libre that we're going to update. If
you ran composer recipes, you would see his Symfony /routing, so I'm going to run the
update command for that and then repeat my gift add dash P trick.

Oh, of course. I forgot. I'll continue ignoring that and that test. I should have
committed that and then down here in config packages, routing dot Yammer, we see two
changes. Strict requirements has gone in. They added this UTF eight true thing. If
you went and looked at the this library's recipe, you could find the reason behind
those two changes. The first one, UTF eight true is just a new feature in the routing
framework and so and by setting UTF eight true, you're turning that feature on. You
might not need that feature, but I'm going to say yes to this so that we activate the
new UTF eight thing. The second thing is strict requirements. This is actually a
subtle change which I'm responsible for. Where what strict requirements it says is
whether or not the router should throw an exception if you generate a URL but are
missing some wild cards. It's typically strict requirements is typically

no

true in development but false on production just to avoid errors. And the way that we
set these, I reorganized it a little bit just so that we needed less configuration
files. So I'm going to say yes to this change. And then the next one is a Symfony
that locks. We'll say yes to that. And if you get say get status, you'll see there's
a new config packages proud or not that routing file that's part of that
reorganization. I'll show you what that looks like. Config packages, prod Ryan. Dot.
Yammel. It sets that framework, routers strict requirements to null, which is the
setting that you want on production.

And then the last thing you need to do, and again this is a shortcoming of the rest
of the system, is because of my reorganization, we actually deleted this config
packages, test routing. Dot. Yammel file, which has strict requirements to true.
We're able to do that just because it's not needed anymore. The new routing.yaml
file, it's not needed anymore. Um, because that is the default value in Symfony. So I
want to do get RM config packages, test routing. Dot. Yammel Nope, and apparently I
made a change to that file. I did that type of letter G let's try that again. There
we go. So if you didn't have those changes it wouldn't matter at all. It's just a
little bit of cleanup.

All right, so let's run composer. Well actually before we keep going, I don't type of
get status and get commit that we are upgrading PHP unit and routing recipes and that
last change was one that we were rejecting. So I'll say get, check out that M. dot.
Test that change, that change would be fine. It just doesn't matter. All right, run a
couple recipes again and let's keep looking down the list. The next one is Symfony
/security bundle. So I'll run the composer recipes updates line for the security
bundle and then when it's done do get add dash P. now you'll see it's, it appears to
have made a lot of changes to security that Yemen, but that's because we have lots of
customizations to this file and it's replacing our file with its version. So we
actually want to keep our encoders, we want to keep all of our stuff here.

What we're looking for is what is changed. And you can see down here the one change
it actually did that significant is it changed anonymous, true to anonymous lazy.
This is a new feature in Symfony 4.4 and what it basically means is that instead of
security, always running at the beginning of the request and make an authenticated,
the user Symfony doesn't actually have to educate the user until your application
tries to access the user somehow. And this is done so that we can use a better
caching on pages that don't ever check for the authentication. So this is probably a
change that you won't notice even though things are working a little bit differently
behind the scenes. So you don't have to use anonymous lazy, it's just a new default.
So I am going to use that. I'm actually going to say cue to quit out of this.

I'll do it, get status and then get check out config packages, security .yaml and
then spin over and go to config packages, security .yaml so that we can manually
change our anonymous too. Lazy. All right, now I'll go back and get add dash P we'll
say yes to that change and yes to the Symfony. That lock file and then we'll commit
get commit dash M upgrading security recipe. All right, this is good. This is getting
kind of boring. Now I'll do composer recipes again and this time we're gonna update
the next one, which is Symfony. Slash. Translation. Repeat our get add dash P.

yeah,

and you can see this time instead of it referencing this percent locale thing
previously what we did was we had a locale parameter, which was something that was
actually set in

config

services.yaml. There was a locale, E N a. If you dug into this, what you'd find is
that for simplification purposes, instead of setting that parameter and then using it
in this one file, we just set the locale in this one file. You don't need to make
this change if you don't want to. It's completely up to you. So I'm going to say yes
to this file. Then yes to the Symfony, that lock file, and then inside of my services
that Jamar, I'm going to manually remove that locale. E M there. Why didn't the
recipe remove that for me? Well, again, that's a, that's another shortcoming. One of
the things that a recipe can do is it can add a parameter tier. I'm going to look
into why that didn't actually do that. Perfect. I'll do it. Get status. We'll do get
add dash P again to add that one change and then do get commit dash M updating
translation recipe. Then run composer recipes again. All right. We are almost there.

Let's do [inaudible]

impose a recipes install for Symfony. Slash. Validator. Then do I get add dash P and
you can see it made one change to about it at Yammel. It's a new auto mapping feature
with Valdez with validation. We're going to talk about, it's commented out, I'll say
yes for that. And the other one is just this Symfony, that lock bylaws. That's an
easy one. We'll say updating validator recipe and actually at this point that's
technically it. That's it for all of the Symfony main Sydney repositories. Symfony
Webpack Encore. Wendell is not one of the main recipe repositories, but while we're
here, let's go ahead and update that one as well. So composer recipes install Webpack
dash Encore dash bundle and this makes a number of changes in the vast majority of
these we're actually going to reject. So it ignores the public /build directory.
That's actually good. We do want that.

Uh, and then uh, because it gives you a starting app dot JS file, it completely ran
over our app dot JS file. So we're going to say no to that. Um, this is just a line
break change. We can say yes to that, but it doesn't matter. And then there's a whole
bunch of things in this file where basically inside the recipe we added more example
code. So in this case, this is just a bunch of example code. It's not actually, it's
all commented out. So you can accept this if you want to or not accept it. I'll
accept this. And then next thing he does his package that JSON, it gives you an a
starting package that JSON, we don't want to override our custom package that JSON.
So we're going to say no to this, no to the end of package that JSON, where we have
another custom change and then this is the Symfony on that lock file.

So we'll say yes to that. And then finally we're in the Webpack dot config dot JS
file. Uh, at the top it adds a little extra safety code. So we might as well say yes
to this. We don't want to override our entry stuff, so we'll say no to that. We don't
really care about the typo here. Say no to that. And then finally, there's a whole
last little bit of code here. Um, let's actually look at this piece by piece on a
clear, the screen and type asked to split this. Now, first thing is we, uh, do
recommend that you have this configure Babel preset N thing inside of there. You can
learn more about that. Um, these two lines here are effectively equivalent, but I'm
gonna use the new version. So I'll say yes to that. We don't want to change any of
our sass loader stuff, so I'll say no to that. And this is a commented outline. Uh,
so we'll just use the new example config there and say yes. And then down here on a
provide jQuery, we want to keep, ah, using that cause we are using that in his
application. So we'll say no. And then, uh, it looks like there's a no new line at
the end. So I'll just say yes to that.

Phew.

So if you do get status, now

I'm going to go, Oh, there's also a new,

you can see a whole bunch of changes here.

So I'm going to say get

check out, including a couple of new files. So this assets CS, app dot CSS. This is
actually a, um, just a new example file. We're not using it in our application. Oops.
It's actually opened up app dot CSS, so we actually don't need that. So I'm gonna
delete assets, CSS app, that CSS and then both the validator actually missed that
earlier. And Webpack Encore, add new test configuration files. These aren't terribly
important. I'll show them to you. App config packages test that .yaml turns off a
certain validator in the test environment that makes a network request. We don't want
that network quest to be made and then went back to Encore dot. Yammel is just some
example. Configure a which I'm not going to talk about. So we will commit both of
those files right there. And then if I do get status, there we go. That's all the
changes. I'm going to commit those with updating Webpack Encore recipe plus missing
validator file.

There we go and I'll get check out those last changes. Ooh. All right, so we run a
composer recipes now we are good and I know that was a lot of work. The vast majority
of those changes we didn't need to make. We were just being extra careful to look at
those changes, see if we actually needed them. And then make them, the really
important ones were really Symfony console, a Symfony framework bundle. These are
kind of like some core things that you probably, you know, the updated some core
files that we want to make sure we're up with. So this point were upgraded Symfony
4.4 we then made sure that our recipes are upgraded to the latest version. So next,
let's start talking about finding and fixing deprecations so that our application is
ready for Symfony five.