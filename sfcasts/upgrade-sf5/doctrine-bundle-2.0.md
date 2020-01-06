# Upgrading to DoctrineBundle 2.0

For checkout. Our deprecations right now there's actually a lot of stuff related to
doctrine and the doctrine part of upgrading 75 is a little bit weird because at the
same time Symfony is going from version four does a version five. There is a new
version of doctrine bundle and doctrine is splitting some libraries in certain ways.
There's a lot of stuff going on. So if I search specifically for doctrine bundle, we
will see something here that actually says that there is some missing get metadata
driver class in a doctrine bundle doctrine extension class. Basically this is it. A
very simple way of also saying that doctrine bundle is another thing that needs to
upgrade. Now if we look, if we go to, if we Google for doctrine bundle and find it's
get up repository. If you did some digging into this, you would find out that the
version of this package that we need is actually version two, version 2.0 that's the
first version that's um, uh, compatible with Symfony five.

There's also a version one point 12, which is currently being maintained, but that
doesn't support somebody five. So we need to get up to version 2.0 so let's start
with our simplest way of doing this, which is always composer up doctrine at
/doctrine and dash bundle to see if maybe that gets us up to a high enough version
and it does upgrade us but only to one that 12 dot. Six okay, so probably we need to
go into our composer.json file and fix its version constraint. So I'll search for
doctrine /doctrine and Nasha bundle and here and interesting. It's actually not in my
composer. Dot. JSON on file. That means it's a dependency of something else. Let's do
a little bit more digging. So I'm going to say composer. Why doctrine /doctrine a
dash bundle. Ah, and this tells me that doctrine bundle is required by fixtures,
bundle and migration bundle. But the real reason we have it is because we use the O R
M pack. Neo or unpack actually allows version one or two of the library. So I want to
have a little bit more control over a doctrine /doctor in Baltimore cause I want to
force version two. I do not want version one to be allowed anymore. So to do that I'm
going to say composer, unpack Symfony /R M dash pack. When we do that, it's actually
going to remove our M pack from my composer J's on file and it's going to replace it

with the libraries that were in there. So these are actually the three libraries that
are in the RM pack. So it actually put those here. And then I see now is I can say I
actually want only version 2.0 of doctrine /doctrine bundle. So now we are forcing
version two which is the one we need. All right, so let's try to update this. I'm
gonna move over and say composer update doctrine /doc and bash bundle and this is not
going to work for the very simple reason that our project currently has a doctrine
fixtures bundle of 3.2 which requires doctrine bundle 1.6 so apparently we also need
to update doctrine fixtures bundle. So let's just try that. I'm going to copy that
library name and we will say composer update doctrine bundle and doctrine fixtures
bundle. We may need to,

I may need to bump this to a different major version. I don't know. We're just going
to try it. But when we went back over it didn't work again but this reason, but this
time it's for the same reason. But now it's doctrine migrations bundled. So let's
copy that and see if we can white list that one as well. So now we're allowing
doctrine bundle doctrine, fish bundle and doctrine migrations bundle all to update
and it still doesn't work. But this time it's because doctrine migrations bumbled
requires doctrine migrations 2.2 and apparently we're locked at a lower version of
that

[inaudible].

Yes, I were locked at 2.1 0.1 so I realize these are a little bit difficult to read
but if you get into it you can find the problem. So we could add doctrine /migrations
to the end of this and try it again. Or we can actually add dash dash with dash
dependencies that says allow any of these three bundles to update or their
dependencies. And dr migrations is actually I'd dependency of doctrine migrations
bundle. So I'm trying to always update as little as possible and expand it little by
little as we know we need to update more and this time it works well except it
explodes in the bottom, but we'll talk about that in a second. If you look up here
you can see then I upgraded doctor migrations, bundle doctrine, fixtures, bundle.
Those are both minor version upgrades and doctrine bundle was a major version from
version one version two so it shouldn't be surprising down here that it actually
exploded because there are some backwards compatibility breaks between doctrine
Bumble one and dr Mo too. One other thing I want to point out is that doctrine cache
bundle was removed. That's no longer required by doctrine. Vulva version two you
shouldn't use it anymore. It's not needed. But if you were using it in your app and
you weren't requiring it directly, it's now been removed.

All right, so let's see what's going on with this. Cannot auto wire service problem
down here because we just upgraded a major version. What we really need to do is go
to doctrine bundle and look for some sort of a change log or an upgrade log and they
do have one here for upgrading to 2.0 and there's actually quite a bit of stuff here
about dropping old versions of PHP. Um, uh, and most of it is not that important.
However, there are, there is one specific thing that is very important here and that
is the that previously if you wanted to get the doctrine service, you could use the
registry interface and you should now use manager registry. Where do we use registry
interface off? You move over to your terminal and run, get grep registry interface.
You'll see that we use this in every single repository class.

This is actually something that make entity generates for us and it used to generate
it with this registry interface type bet. So this is a fairly simple but fix that we
just need to get through. I'm actually going to open every single repository class
that I have and on all of them we're going to change registry interface here to
manage your registry. Now you want to get the one from doctrine. Slash. Persistence.
There's also one from doctrine. /common. Slash. Persistence. That's another one of
those doctrine changes that's happening right now. They used to have a package called
doctrine common, which contained lots of common different libraries. Doctors is now
splitting those into their own packages. So the persistence directory of doctrine
common is now its own package. So you should use the new one. The old one is a
deprecated. What makes it a little more complicated is the change log actually
references the old one, but you should, you should use the new one from just
doctrine. Persistence, not from common. Also clean up my old registry interface you
statements. So let's just repeat that a bunch more times. Rent, manage your registry,
move the you statements, manage your registry, remove the use statement, manage your
registry

and done

so. Now if we move our eye over, I can just run bin console a second ago. That would
have exploded with that same air. Things now seem to be working now because we just
upgraded doctrine. Bundles are very important. A librarian because we just upgraded
version one, version two. I also want to upgrade its recipe. So if you run composer
recipes, you'll see that it was one of the library's important libraries that has an
update available. And I said, Hey, let's just wait until later. So let's go ahead and
do that. Now I'm going to run a composer recipes doctrine /dr dash bundle, which
gives us some information about the updates and then I'll copy the, uh, update
command and paste that. Then as usual, you can see this modified several different
files. We're going to commit this stuff very carefully, so I'll clear the screen and
do my get add bash P, and the first change is inside of the dot end file.

There are some, uh, comment changes including an example for how to use Postgres QL,
but one of the really important things that it mentions here is a new note that says
that the server version is now required in this file or in config packages.com. Dot.
Yammel. This is basically where you say that you're using my SQL version 5.7 or
you're using Maria DB version 11.1 or Postgres. Um, version 11. Doctor needs to know
this so that it knows how, what kind of queries to make. So you can either put this
at the end of your, um, database you wear out like this, or you can commit it into
doctrine.yaml which is what I'm going to do because I prefer to have my server
version that I use on production committed into a file so that the SQL that's
generated every, anywhere on any machine is always consistent.

So I am gonna do, I do want to accept all of these new, uh, comment changes, but I
want to keep my old database. You where else I'm gonna copy my old database. You were
all hit. Why to accept these changes and then hit cue to exit out of this. I'm not
going to move over and close a couple of things up. Let's open our dot and fear. I'll
search for database URL and I'm going to put my old value right there. All right, so
let's keep going. I'll run, get add dash P again. We'll say yes to our database. URL
change, yes to that compose that JSON change. In fact, let me hit Q again. Let's go
ahead and add the stuff that we know we want. Compose that JSON, compose it out, lock
a Symfony, that lock and all those source repository changes are the ones that we
made so we know that those changes are good.

I'll run get status again. There we go. Now we can just walk through the remaining
changes so you can see it removed. Doc from cache bundle. I already talked about
that. And the next change changes are in doctrine that Yamhill, and there are several
interesting things here. The first thing is that there used to be a parameter that
[inaudible] that was called [inaudible] database. You were out, this was basically a
work around around a doctrine bundle air that would happen if the database you were
environment variable wasn't set. These days, the database URL is always set it at the
very least in your dot end file. So we don't need that anymore. So that's just a good
thing to get rid of.

Okay.

The second thing is the driver PDO. My SQL is no longer needed inside of there. Our
database URL always contains whether or not we're using my SQL or Postgres Postgres,
so it's just not necessary in here. Server version is actually moved down here. I'll
talk about that in a second. And the next thing is interesting changes. This char set
and stuff down here. This is really important if you're using my SQL so that your
database tables are allow are can store UTF eight data starting in document bundle
2.0 these are the default values so you no longer need them. So that's just a nice
little cleanup. Now down here it talks about server version and how you need to set
that either here or inside your die and file. We are going to set the server version
here and I'll uncomment this out in just a second. The last change here is a little
naming strategy thing. How if this is how it names your tables and columns, uh,
there's a new number aware_strategy, which is basically the same. So we're going to
say yes to that as well.

Then I'm going to hit Q to quit out of this and go back and find that config packages
doctrine.yaml and let's [inaudible] out the server version and adjust this to
whatever your server version is. All right, flip back over. I'll clear the screen
again. Get add dash P say yes to our server version change. And then the last
significant changes in config packages, prod doctrine.yaml. This is the file that
sets up your doctrine metadata to be cacheed in the prod environment. Previously, the
way we did this is when we installed the original old recipe, it actually created
several different, um, a cache services down here under the services key and then
used them up here for the metadata cache driver and the query cache driver starting
in document bundle 2.0 it basically creates these services for you so that you can
have a much simpler configuration inside of this file.

So this is a great change. We're going to say yes to it and we're done. Phew. So
let's commit that with upgrading to doctrine. Bundle 2.0 then to celebrate. Let's
move over and check how things are looking. So I'll go back to my homepage refresh
and we're down to 11 deprecations. Let me open these up now. One thing you're going
to see in here is there's a lot of things that are still about doctrine and they all
mentioned doctrine. Persistence 1.3 that doctrine /persistence library is one of
those that was extracted from doctrines slash. Comment [inaudible] so why are we
getting all these doctrine persistence things? The answer is actually these are not
things that we have done and they're not even things that doctrine bundle is doing.
If you do some research on this, these are things that, there's two things I want to
say about this. First of all, while this is a deprecation, this is not a Symfony
deprecation. This is talking about something that's going to change. Um, when we
upgrade doctrine persistence in the future, we're only interested in upgrading
Symfony four to Symfony five. So this is a deprecation that we do not need to fix
right now. The other thing is that this deprecation is actually not coming from our
code. It's coming from doctrine itself. Doctrine. Slash. O. R, M.

there's currently a pull request open on doctrine /RM number seven nine five three
that removes these up. It's that updates doctrine /ORM to remove these doctrines.
/persistence. Deprecations. It's currently targeted for version 2.8 of doctrine ORM.
So hopefully in some in the future that will be up. That will be merged and released
and you can upgrade to doctrine /ORM 2.8 and it will remove these deprecations. But
as I said, they're not a problem right now. So as we continue working through these
deprecations, we are going to ignore any of the ones from doctrine /persistence cause
they're not blockers for upgrade and Symfony. So next, let's start squashing the last
few deprecations that we have.
