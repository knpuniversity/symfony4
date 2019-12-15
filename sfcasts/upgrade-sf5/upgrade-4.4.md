## Upgrading to Symfony 4.4

Coming soon...

And yes, that might seem a bit redundant. but changing the version up
here to 4.4 point star gives you very clear control and then the Symfony extra, the
extra Symfony required on here is an added layer that ultimately really helps with
performance. So let's get to work here a little bit. I'm actually going to update all
of my uh, existing formats here. So I'm gonna change this caret 4.24 point, uh, four
point star Sydney asset and a copy of that and repeat that on a bunch of other things
and upgraded Symfony messengers to the latest notice I'm skipping or Oh, our M pack

PACS are allow any version of the libraries that have inside of them so you don't
have to control the versions of those. If you do want more control over the
individual libraries in it, then use composer unpack and that will remove the pack
and put the individual libraries inside of this and then you can control them and
Webpack Encore bundle is not one of the normal repositories. So we can skip that one
and then we'll do gamble. And also don't forget the required tab down here. So
browser, Kip will up up update, debug bundle. We can just do that. That end maker
bundles, one of those that's external so you don't have to manage that one. A monolog
bundle is also another one

that is a, it doesn't follow the same version of strength. If you're not sure if
something does, you can always, I don't know, I have to come up with a tip here.
We'll change the PHP and a bridge, leave profile pack and do VAR dumper. Perfect
scenarios in the 4.4 point star everywhere. Down here on the Stephanie extra we're
allowing 4.4 point star so we should be good. All right, so let's go over here. I'm
going to run composer up Symfony slash. Star one last time and perfect. You can see
upgraded the last few things that were missing from that. So congratulations. We are
on Symfony four even though Symfony is just a bunch of independent libraries, we've
upgraded them all together successfully once. Somebody for now before I move on,
there was one other thing that I noticed when I was going through this composer, that
JSON thing.

Notice that the dot end component is in my required Def when simply 4.0 came out.
That end was a tool that was only used in the devel only recommended to be used in
the development environment. That's not true anymore. We actually want your Symfony
dot M to be in your require key so you can use it on production. Also down here for a
different reason, I have monolog bundle. That's our logger and easy Corp easy log
handle or something that helps with logging. Logging should also be something that we
have always inside of our, um, composer require not a required DEP. So here's what
we're gonna do. I'm gonna copy the Symfony. Dot N move over here. We're gonna compose
it. Remove dash, dash dev Symfonys /dot. N and then I will copy monolog bundle one,
paste that and then go over and copy the easy Corp easy log handler one and paste
that. So an easy way to move that from the required depth to the requires just to
completely remove the package. Now when we do that, our code explodes because our
code does need the Symfony dot M to be installed and it temporarily is not installed.
You also notice if I run and get status that uh, by removing those that actually
removed their recipes,

now I'm going to re add them by same composer require without the dash dash dev that
we'll re add them into the required line. Yep. You can see them right here and then
it's actually going to reinstall the latest version of their recipe, which means that
the recipe actually could be slightly newer than the one. So I'm actually going to
add my composer out, JSON and my composer not lock and my Symfony had that locked
file. And I'm going to do get add dash P. this is something that's gonna allow me to
look at all the changes that were made, the recipes line by line and if you look here
in the bundles that PHP had actually just moved this to the bottom order of the
bundles doesn't matter. So I'm going to hit why to accept those changes in monolog
dot. Yammel because it re-installed the recipe. It actually removed one of my custom
changes, so I'm going to say no to that. Um, and then in this monolog to file,
interesting. It actually removed this excluded for a forest thing and replace it with
excluded HTTP codes thing. We're going talk more about updating recipes in a second,
but this is an example of an in recipe of a recipe update. Apparently in a newer
version of monolog

bundle, there is a new excluded HTTP codes, which is actually a little bit more
flexible than just excluded for forests. This is going to exclude four fours and four
or fives. So I'm actually going to hit yes to accept that and then I'll do get check
out config packages, dev monolog, Diane molds, remove that. A change that we didn't
actually want. And now you can commit these changes. So we're fully on 74.4. We fixed
some packages that were in the required dev for four. Um, and we're good. So next,
let's talk about updating recipes, because sometimes when a recipe updates, you
actually want that app to work on that saying.
