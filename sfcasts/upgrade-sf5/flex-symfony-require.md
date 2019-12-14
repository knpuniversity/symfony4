# Flex, Versioning & extra.symfony.require

Coming soon...

Hey friends today we get to talk about new stuff, something exciting, Symfony five
specifically how to upgrade our applications to Symfony five what that process looks
like and then once we get there and talking about some of my favorite new features.
Now Symfony five does not do anything, does not make any huge changes. There aren't,
there isn't a completely new directory structure or some earth shattering new
paradigm like Symfony flex and the recipes and that's great. It's that's because
Symfony is in a really great spot right now and it's not that Symfony isn't doing
anything. It's constantly releasing new features. Symfony releases a new minor
release every six months. That's 4.1 4.2 4.3 4.4 and those always come with new
features. So this is [inaudible]. We're going to talk about how to upgrade our
application from Symfony four all the way to Symfony five that's going to include
finding and fixing deprecations so we can upgrade our application without breaking
it.

It also a brand new thing for this upgrade tutorial is the idea of updating the
recipes. There are some brand new commands as as a part of flex, they're gonna help
us make sure that our project structure is up to date with the latest best practices.
And once we get to 75, we're going to talk about some of my favorite new features. As
usual, if you truly want to composer update your skills, download the course code and
code along with me. When you unzip the file, you'll find a start directory with the
same code that you see here.

This is a Symfony 4.3 project, but the project originally started on 4.0 so it has
some of, so it has a really good mixture of some outdated practices and we're going
to see that as we go on. Now to uh, follow the read me.md file for all the setup
instructions. The last step will be to find a terminal move into the project and use
a Symfony web server to run Symfony surf. That will spin up our application at local
host colon 8,000 so let's move over. Go to local as a thousand. Well, I do local host
colon 8,000 to see the space bar, the application that we've been building for the
last two years in Symfony for. All right, so what does it even mean to upgrade
Symfony? Because Symfony is really a number, a large number of standalone libraries.
Well, if you look at the project and open the composer DJs on file, you can see that
this project now is pretty big and has a lot of dependencies, but about half of these
start with Symfony /so when we're talking about upgrading Symfony, we're talking
about upgrading all of these things that start with Symfony /and actually not
everything. There are a few packages like Symfony /Webpack Encore bundle then are
part of the main Symfony repository. They have their own versioning strategy and you
can up upgrade them whenever you want, but the vast majority of these Symfony /things
are the Symfony components and you can see they all start with four. They're on
version four currently and we're going to upgrade those altogether.

Now before I begin, if you started your project and Symfony 4.0 then inside of this
file you're going to have something called Symfony. /LTS. In my project, I used to
have it but I order already removed it. That was a package that kind of helped you
manage all of these many dependencies so that you could keep them on the right
version early on in the Symfony release cycle that was removed and replaced with
something else. So if you have Symfonys /LTS, you can run composer, remove Symfony
/LTS to get rid of that right now what that was replaced with is a special bit of
config down here. See if we can find it called extra Symfony require. This is a
special key that's read by Symfony flex. Remember simply flex is the plugin for
composer. That helps give us the recipe system and a few other things. Simply flex
reads, extra Symfony require and it does too and it really does two things with them.
With that. First of all behind the scenes, what it basically does is it says any of
those Symfonys /repositories should be locked at SIM at version 4.3 point star. So if
we go back up here, you can see that for example, Symfony /form is carrot 4.0 that's
technically means in composer land. That means that this is that if we ran composer
updates that would download a version, uh, the latest, uh, that could download 4.0
4.1 4.2 4.3 or 4.4 whatever is released.

But thanks to Symfony flex in this require key down here, 4.3 point star. When he
runs Symfony update, it's actually going to lock it on Symfony 4.3. Now the second
thing that this does, and really the more of the main reasons it exists is for
performance. Because we have this like extra Symfony require thing down here for 4.3
point star flexes able to filter out a bunch of extraneous versions and it basically
makes composer run much faster. So to show you how this works, I'm actually going to
spin over here right now and open up a new terminal tab and just run composer update
Symfony. /stock. Now, if we didn't have Symfony flex, we would expect that Mo, this
when we run this, one of the packages that would be updated will be Symfony form and
when we would expect it to update it to 4.4 because that's released right now. But
let me update it. The extra Symfony require,

you can see it says restricting packages listed in Symfony /Symfony to 4.3 point
star. And in fact you looked down here, look at Symfony form. It did upgrade it but
only to the latest 4.3 release, not 4.4. So everything stayed on the 4.3 release. You
can see it also updated a couple of the other libraries that aren't part of the main
Symfony umbrella. These are kind of the little standalone libraries. They technically
start with Symfony /but they have their own versioning structure. So it also upgraded
those. But those aren't, um, um, restricted in the same way by that 4.3 point star
thing. So this point composer update there, thanks to that, just upgraded us the
patch versions. So next we are going to change that extra Symfony required to 4.4 and
actually upgrade our project to 4.4. Yes, we are going to upgrade a Symfony five, but
first we need to upgrade a Symfony 4.4 so we can see all the deprecations and fix
them. Let's do that next.
