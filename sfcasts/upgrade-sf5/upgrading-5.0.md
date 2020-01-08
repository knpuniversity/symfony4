# Upgrading to Symfony 5.0

Coming Soon...

Our app is now ready for Symfony 5.0 and we know because we have done various things
to figure out that there are no deprecations left. Well there are a few but they
relate to doctrine persistence, not something that we need to worry about when we're
upgrading Symfony. So how do we upgrade from Symfony four to 4.4 to Symfony 5.0 and
the answer is that we already know we're going to follow the exact same process that
we used to upgrade from 4.3 to 4.4 so open up your composer dot JSON file. Remember
what we're really doing is updating all of these Symfony /libraries, well not all of
them, a few of them are not part of the main repository like monolog bundle, but all
of these that have 4.4 point star, we need to change those to 5.0 point star. And
also down here in the extra Symfony requires section.

We'll need to change this also to 5.0 point star, which is a little performance boost
to tell composer only to focus on that version. So very simply I can do a find
replace so for 4.4 point star we replace that with 5.0 point star and I'll hit
replace all. Now of you'll probably want to make sure that the D didn't accidentally
match any non Symfony packages that have that same version. Looks like we are good so
we are now ready to go so just like before I'll actually close this tab and hit
control C to get out of my log. Just like when we upgraded before, well now say
composer, update Symfony, /star and it's just that simple except this is not going to
work and it's probably not going to work in your project either. We're likely to see
a bunch of dependency errors.

Our first one is about doctrine ORM. This is a fancy way of saying that the current
dr NorAm is not compatible with Symfony five which means that we need to update it.
Is this similar to when we were updating a third party libraries because they used
deprecated code. In this case, we needed to update this third party library so that
we can get a version that is compatible as Symfony five and there might, and actually
we're sort of hoping that there's a version compatible with somebody five. It's
possible that there isn't one yet. So let's add doctrine /or M to our list of
packages to update and try it again and we get a another air, the exact same thing
this time about KP labs that can't be markdown bundle. So again, the easiest thing to
do here is to add that to our composer update list to say, Hey, if that needs to be
updated to a new version, let's do it.

And now we get the exact same error again with campy snappy bundle. So you kind of
get into this repetitive process of trying to figure out all of the different
libraries that you need to upgrade so that everything is compatible with Symfony five
now you could just run composer update and be done with it because that will allow
all packages to be updated. That's not my preference because I like to have a little
more control. And update as little as possible at every time, but I wouldn't make
this process a lot easier but let's keep going the hard way. I'm going to copy KP
labs can be snappy bundle. Add that to the update command and try it again and we'll
see this a couple more times. This time with leap. Imagine bundle so I will copy that
and paste it next with one up fly system bundle, same thing, copy that and paste it
so that we're allowing it to upgrade and then Sensio /framework, extra bundle. We'll
add that to our now very long composer update command.

Now the next era looks a little bit different. You can see it's talking about
doctrine or M and it's talking about doctrine and stantiating. If you look closely at
this, what this is saying is that in order to get Symfony for support, we really need
doctrine ORM version 2.7 but version 2.7 requires doctrine instantiate or 1.3 and our
library is currently locked at version 1.2 now our project doesn't use doctrine
instantiate or directly it's a dependency of doctrine. Slash. ORM. This is, we saw
this earlier with something that I'll have to look up. This is a situation where you
need to tell a composer, Hey, update document or M but also allow any of its
dependencies to update. So we will add the dash dash with dash dependencies flag to
the end of my command that fixes that air and gets us to the next one.

Next Elan Slack Bumble version 2.2 0.1 requires PHP 7.3 and if you remember we said a
config platform PHP and our composer Jay sent is 7.2 0.5 if you did a little digging,
what you'd find out is that the latest version of the next SeaLand Slack bundle
requires BHB 7.3 and we need that new version in order to get Symfony five support.
So like it or not, we're going to need to start using PHP 7.3 fortunately I'm already
using PHP 7.3 locally. I just need to go change my config platform PHP to 7.3 and
also makes sure that we have 7.3 on production. So inside my composer.json, I'll look
for platform. There it is. Let's use 7.3 0.0 and then we don't really need to change
this but there's also a spot under the require key. I'll that to 7.3 0.0 and we'll
run that command again.

Yeah

and here we kind of get a,

this kind of goes back to um, the first areas we're getting, which is basically this
is a long way of saying that we need to let next Elan Slack bundle also update. So
I'm going to copy it, paste its name and yes we also get another air related to campy
time bundle. It's not sad. The current version we have is requires Symfony
translation, which doesn't allow five we need to update to a new version. So let's
also add KMP labs /Canva time bundle to our gigantic composer update command, which
again, if you want to cheat you can just say composer update with no arguments and
that's an easier way to do this. And it works. It is upgrading a ton of packages
including our Symfony stuff to Symfony 5.0 0.2 that is awesome.

We also know because we didn't change any of our, any of our other constraints
instead of composed at JSON that any packages that are being updated are only
upgrading of minor versions. For example, next one Slack bundle went from 2.1 to 2.2
we knew even before looking at this list that that didn't go to version three because
if you look inside of here we have carrot 2.1 this allows to point anything. So long
way of saying that we don't need to, this was a safe update because things only
upgraded a minor version. Now that's not entirely true because you've looked at next
line /Slack. This did go from version to diversion three that's because that's a
transitive dependency. That's not something that's in our composer.json, we only,
it's only in our project because next Allen Slack Bumba uses it so that should be
safe cause we're probably not using that code to directly. But if you want to be
extra safe, you could check out the change log of any libraries that went up. A major
version like this. But Hey, we're done. We are now in simply five. So check it out.
I'll refresh the homepage. It works of course, on the first dry.

Yeah. What does the rest of word [inaudible]

and when we clicked to go to the logs, actually shows me a warning. Failed to unsee.
Realize the security token from the session. I think that's a onetime temporary thing
because of an update or refresh it again. There we go. Now I had the deprecations.
Everything works. We still those annoying doctrine, persistence things, but we are on
Symfony five. So next, let's start looking at a couple of the new features, uh, of
75. Some of my favorite things that you can now play with.
