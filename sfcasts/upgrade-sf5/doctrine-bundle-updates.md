# DoctrineBundle Updates & Recipe Upgrade

Coming soon...

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
