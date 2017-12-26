# Symfony Flex & Recipes

It's time to demystify. Something.

Incredible that's been going on behind the scenes. First I want you to commit
everything. With your usual clever.

Message.

Then we're going to install a new feature called Symphony security checker
which is a great tool but we're mostly installing it because I want to show you
something really cool. First to prove it Iran gets status. There are no
changes. Now Ron composer require Sec Checker. Now once again Seth Checker's
should not be a valid composer package. So what is going on. Flip over and open
your composable Jason File. Our project started with just a few dependencies.
One of them is something called Symphony SLAs Flack's. THIS IS SUPER IMPORTANT
symphony flaks is a composer plugging.

In and has two superpowers. The first superpower is the aliased system. Move
over to your browser and you go to Symphonie that S H.

Symphony recipe's server.

We'll talk about that in a second. This is a list a. Search here for security.
You can see the second one here is a library a package called Sensi labs slash
security checker and below it it has aliases sec checks Sep checker security
desk checks security checker.

Thinks to simply flex you can just say compose require security checker or any
of these and it will translate that into the actual package name. It's just a
shortcut system that makes it really easy for you to just install things by
using a very short name. In fact in this case you can see that the library that
was actually added is sent via labs slashed security dasht checker. Great.
That's the first superpower of flux. The second superpower a flaks are is
recipes. Go back to your terminal and yes it did install and notice that it
said simping operations. One recipe configuring Sensi lab slash security
checker.

Running good status.

Oh cool it updated are composed about Jason and composed out lock files as we
expected. But there's two other changes. Symphonia out lock and there's a new
configuration file. So first symphony lock is a file that is managed by
Fleck's. You don't need to worry about it. It just keeps track of what recipes
it's installed. The second Files Config packages dev security underscore
checker. This contains configuration to add a new Binet console command to our
project. The specifics of this configuration aren't important. We'll talk about
this in a future episode. But thanks to this file being automatically added for
us. After just running composer require. We can now run byn console security
call and check. And it just works automatically. That is the point of the
recipe system when you install a package. If that package has a recipe it will
automatically.

Add any configuration files create the directories or even make modifications
to files like your doc get ignore so that the library instantly works this case
the security checker checks to see if that there checks to see if there are any
known vulnerabilities for any packages that we use. And right now there's not.

The recipe actually made one other change. If you look and composer that Jason.
If you run get diff composer that Jaison in addition to adding the package.
Composer added the package of course.

But it also added a new spot in the scripts section. So yes the recipe itself
modified a composer that Jaison what is what's the importance of that. Now
whenever we run. Composer install.

When it finishes it chap runs the security jacker so that's going to tell us if
any of our dependencies have security vulnerabilities. And I won't show up
right now but I'm.

FLACs even has the ability to remove packages and only install the recipes
which makes trying new features really fast and safe. You want to know the
craziest thing about. Remember when we started the project with create. Dasht
project project simply SLAs skeleton. Well if you got a good hub. Ship.

If you want to check out the recipes.

You can find them on Sabena as age and actually click to view that recipe. So
for example for the security checker it takes it to the symphony slash recipes
repository and inside there you can actually look at the configuration files
that will be added for it.

But you want to know the craziest thing about FLACs in the recipes. Remember
when we created the project we ran composer create Dasch project Symphonie SLAs
skeleton. Well go to get her icon slash 70s slash skeleton. Yep. View original
project that we. Cloned is only a single file. Composer that Jaison. So where
did all her other files and directories come from recipes. When we clone this
project when composer and Stolle ran it ran recipes for framer Bondo flex and
these other Libres and that actually built our project automatically around us.
It's not a detail that you really need to care about. It just shows how amazing
the system is. All right next let's do something else but I can't remember what
it is.
