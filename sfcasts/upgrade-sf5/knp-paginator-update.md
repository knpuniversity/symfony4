# Upgrading KnpPaginatorBundle

Coming soon...

[inaudible].

If you look at the list of deprecations that we have right now, you see a bunch down
here, we call this tree builder root thing. This is a a low level function that third
party bundles use. And if you kind of look through here, you can see stock document
extensions being mentioned then or M and Mongo DB. But if you dug into this, you'd
actually find that is also from stop document sessions and also from KB page later.
So basically stopped doctrine extensions, bundle and candy page native model. Both
need to be updated. So let's start with campy page Nader, Bumble first. So the
laziest thing to do here is to get the package name. I'll copy it from my composer to
JSON file, move over, run a composer update KP lab /can be paginated bundle. We'll
just see if doing a minor version upgrade. You know, maybe 2.8 to 2.9 or two point 10
we'll see if that takes care of the problem and absolutely nothing happens.

So that didn't fix it. It's possible that they haven't fixed this bug. The point is
right now we need to actually go and look into this a bit. Some of the Google for the
library and find their get hub page. And as you can see from compose from piece of
unit, we're using version 2.8 right now. And if I click on releases here, wow, you
can see that the latest version is actually 5.0 and it says that we added Symfony
five support. So we actually need to go up to version five of this library. Okay, so
no problem. Let's go over here and change our version to care to 5.0 now of course
when we update, since we're updating over a major version, actually a few major
versions, we're going to need to check the change log to make sure there are no
breaking changes that affect us. But let's worry about that in a second. All right,
so let's move over and once again I'll do composer update campy labs //Cain P page.

Wonderful.

And this one fails. Again, it's interesting. It says we tried to get 5.0 of the
bundle, but it requires 7.2 [inaudible] and it my PHP version 7.3 0.6 is overwritten
by config. That platform, that PHP version 7.1 0.3 so this is a fancy way of saying
that the version of PHP that I'm using is too old for version five of this library,
but not actually the VR version I'm using. I actually using 7.3 0.6 but in my
composer dot JSON file,

I searched for config. There's a spec I have added to my project a config platform at
PHP spot and I've said it the 7.1 0.3 this is a way where you can tell composer, no
matter what PHP version I'm using, this is the version you used by the project. So
update anything. Don't give me any versions of packages that require a PHP version
greater than that. It's kind of a best practice to set this to whatever you have on
production. So if we're going to go to version five of this, we need to use a new
version of PHP, which is fun. I'm actually going to change this to 7.2 0.5 why 7.2
0.5 because that happens to be the version of PHP that's required for Symfony five.
Now this is the really important about down here, but for consistency. I'll also go
up here and uh, put it in my requires section. All right, so let's try that again.
That composer update command and it fails again this time because it's trying to
install version 5.0 but if you kind of look down here, the campy page knitted bundled
requires came something called P components and basically 5.0 of the bundle requires
version two of the components, but we're currently locked on version 1.3

now can be components is not something that actually appears in my post dot JSON
file. It's something that I have as a transitive dependency. I have it in my project
because came P a bundle requires it. So basically we want it to, uh, we need to tell
composer that it's okay if came P components also updates. Uh, and the way to do that
is by adding the dash dash with dash dependencies flag that says update KP page in
their bundle only but also allow any of its dependencies to update. And this time it
works. Now the thing to notice here is that we upgraded from version one of came
people components to version two and we went from version two to version five of KP
page and enter bundle. That's fine. But that means we need to, because those are
major updates and we need to go and check their change logs.

So as an example, I'll go to campy page and enter bundle and on there you can find
their change log and you can kind of look to see what the breaking changes were for
version three looks like the drop PHP support version four drops some old versions of
PHP twig and Symfony and version five they added a return type two page Nater aware
interface, which is not something I'm using my project. So this update is totally
fine for me. I would also, in a real project, repeat this for actually, let's just do
it. Let's repeat this for campy components. I'll just Google for that, find it's get
hub page and check out. It's

checkout, it's change

log. And we're looking for the 2.0 release cause that's where it shows any breaking
changes right there. Um, and there's nothing that, uh, uh, affects me. So we're good.
So that's an example of a more complicated updates. So next, let's actually talk
about another one stuff. Doctrine extensions, which is where all of these warnings
come. Let's upgrade that to a version that supports a Symfony five.
