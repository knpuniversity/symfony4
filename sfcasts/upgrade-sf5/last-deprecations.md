# Fixing the Last Deprecations

Let's talk about the deprecation list on the homepage right this second, and there
are 12 things here, but as you remember, we're kind of need to ignore everything that
has the word persistence in it cause this is not a deprecation related to Symfony. So
if you kind of look down here, there are two of these that are related to Symfony and
actually it kind of looks like they're the same thing. It's about a text area size
extension should implement a static get extended types method. This text area size
extension is a form type extension that we built. So let's go take a look at that.
It's in source form type extension, text area size, extension. And immediately when
you get in here you can see PHP storm is really mad. It says class must be declared
abstract or implement method. Get extended types. That's the error you get when you
have a class that implements the interface.

But you're missing one of the methods from that interface. But actually that's not
totally true. If you hold command or control and click into that class, you can see
that there is no get extended types. There's a get extended type that's the old
deprecated method, but no get extended types. It's not actually on the interface,
it's just described above the method. This is due to Symfony's deprecation policy. If
Symfony just added this new get extended types of method to be interface in Symfony
4.4 it would have broken our app on me upgraded. So instead it describes that you
need this and then warns you to actually do it via the deprecation system. So our job
is to add this new static get extended types method that returns an Iterable. So
let's go to our class down here and we'll say public static function and get extended
types and we'll make it return in Iterable.

And very simply in here we can return an array and it's really a small change. We're
just going to return the same thing we did last time, but inside of an array, now
let's get extended type method is removed in Symfony five we don't need it at all. We
do need to keep it temporarily though because again, for backwards compatibility, it
physically lives on the interface. So if we remove it from our class, we're going to
get a a PHP error. As long as that, a comment that says not used anymore, remove in
5.0 cool. So that should take care of that. So if we go back and close, refresh the
homepage, open back up the deprecations and search for persistence. Yes, at least on
this page right now, all of the Symfony deprecations are gone. But that doesn't mean
that all of our deprecations are gone because we at the very least need to surf
around the site and check out some other cages. Before I do that though, there's one
other thing that I want you to do to try to trigger deprecation warnings. Move over
to your console and run bin console. Cache clear. This is going to force continue.
This is going to force Symfony to rebuild its container, which is, which itself can
sometimes contain some deprecation warnings. So I'm going to refresh the page now and
you can see there's still 10 down here.

Oh, interesting. But one of these 10 is now something different. The common to admin.
Controller IX extends a controller that is deprecated use abstract controller. So
it's a great little trick to sometimes get a few extra deprecations. So let's go find
this comment. Admin controller, class source controller, common admin controller, and
very simply we needed to change extends controller to extends abstract controller and
I'll also remove the use statement. Those two classes are identical. The only thing
that's different is that if you use abstract controller, you can't use this->get or
this->container->get to fetch a service by its ID. That's an old deprecated way of
doing things anyway, so hopefully you're not doing it. [inaudible] okay, so let's
just surf around a little bit on the site. Um, let's try the registration page.

I will register as Symfony nerd@example.com. Any password agree to the terms register
and, and Whoa, an air that shouldn't happen when you upgrade to a Symfony 4.4 but
this is coming from Symfonys and mime component, which is part of a mailer. And
because mailer was experimental until Symfony 4.4 there were some breaking changes
from 4.3 to 4.4 we actually saw this one mentioned earlier in the change log for a
Symfony mailer, so let's go in. It's a super easy change. We just needed to change
named address to address everywhere in the system. So I'm going to go over and do get
grap name and address and we can see it's used in set from listener mailer and mailer
tests. So let's go change those. I'll start inside of source service mailer dot PHP
up top. I'll change it named address to address. Then I'll search for name, address
and remove the named part and two other places. I'll close that. Next we are going to
event listener set from listener and we'll make the same change and they take the
names off of it and on top and also when we use it below. And the last place is
actually inside the test tests. Service mailer mailer test, let's see, change the,
remove the name down, the use statement and then it's used into other places below.

It's now moved back over. I'll refresh, will give me a validation error. So I'll
change the email to something unique a to terms and cool, we're good. So at this
point, the best thing to do is just to try to use the site, see if you can trigger
any other deprecation warnings. Um, of course if you have a form submit or an Ajax
call, you're not going to see the web debug toolbar for that,

but the deprecations are being logged. So one nice thing you can do here is you can
tail the VAR log dev dot log file. This is a file that that Symfony writes to in the
development environment and it writes lots of debugging information in here. But one
of the things that it logs in here are the deprecations. You can see user deprecated.
So I'll hit control C and let's run that again. But let's have grep for deprecated.
And this is going to get some extra noise inside of here cause it's going to grab the
persistence proxy stuff. Um, but basically now we can search, we can search around,
we can click on articles and maybe we'll go to, we have a small admin section. /admin
slash. Comments. We'll go to there.

Oh of course I am not logged in as an admin, but if you wanted to you could log in as
an admin and makes sure that page looks good and then come back over here and
basically look through these deprecations. So I should probably filter this list to
remove the ones go with persistence here. But you can see this one is doctrine,
persistence, doctrine, persistence. And if you looked all the way down this list,
you're going to see that all of these are actually coming from the doctrine
persistence library thing that we saw earlier. So as best as we can tell right now,
everything is looking great in our site before we, but there's two last things we
need to do to be sure. One of them is that we might have deprecations inside of our
console commands. So I'm going to clear the screen here.

Open up a new tab. If you're on Ben console, I actually have a couple of custom
console commands here. One of them's called article stats. It's kind of a fake
console command. You pass it, um, article stats, the slug of any article, and it just
prints out some fake data and it worked perfectly. But if we go back over here, check
this out. If you look closely, there's a lot of uh, doctrine, persistent stuff in
here. But the last thing is return value of article. Stats command should always be
of type int since Symfony 4.4 no return. So this is a new change in Symfony 4.4 where
your custom command classes actually need to have a return value in execute. So I'll
go to source command and let's look inside the article stats command. So inside of
execute, we must now return, uh, an integer. And what you want to do down at the
bottom is return zero. This ends up being the return code of the command returns.
Zero means successful. You could return one to indicate that it returned
unsuccessfully. So I'll copy that and I'll go into my other custom console command.
And at the bottom, say it returns zero. I was just looking up here to make sure I
don't have any other returns anywhere else.

Cool. And now we should be done. So now that we think we have all the deprecations
gone, we should probably be ready for Symfony five, but we're not 100% sure this is
one. I would deploy this code to production and then watch deprecations that log file
prod that deprecations that log file on production for a few hours or a few days and
make sure that nothing new gets into that except for the, uh, the doctrine stuff.
Once nothing new is going into that, you know, you're good and you're ready for
Symfony five. So next, let's, let's get up to 75. How'd we do that? Well, we've
already done all this work, so it turns out that switching to 75 is just a little
composer trick.

The last thing that you can do locally is you can run your tests. So I'm going to say
bin /PHP unit built into the simple PHP unit wrapper is the superpower of keeping
track of all your deprecations that are hit by your tests and dumping those out at
the bottom of the test. This is downloading PHP unit because a new version of PHP
unit and after it finishes, it runs and, and you can see they're actually not
surprised. And there are a lot of doctrine, persistence things down here. But if you
look closely, that is the only stuff that we see down here. There's nothing here that
is a Symfony related deprecation. So we are good.

