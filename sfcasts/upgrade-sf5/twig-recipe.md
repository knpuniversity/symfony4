# Twig Recipe

Coming soon...

The new framework bundle gave us this new routing file, config routes, dev
framework.yaml and this is a little, um, end point you can go to, to test your four,
four or 500 air pages. As I mentioned, this used to come from twig bundle, which is
why it would have a tweaked dynamic file with almost the exact same import. This is a
problem if you split that over and run debug router and scroll to the top, you're
going to see we actually have two competing, uh, route patterns right now. So the
truth is that this tweet, .yaml file needs to be deleted. And actually what we need
to do is we need to update the twig bundled recipe. So I'm going to run composer
recipes again and we'll see that. In fact, one of them that's outdated is the twig
bundled recipe. So let's skip down here and get some information about the twig on
the recipe and then I'll copy the recipe install command for that one.

All right, perfect. So let's look at what changes this made. I, you can see it, uh,
created a really modified three different files. So I'm going to get add dash P and
for the first file you can see that it had something called exception controller null
and it also deleted all of our custom code. Again, that's because the update system
is not that smart. It just completely replaces the latest recipe over on top of your
file. So we want to keep our custom changes, but we might want to add this exception
controller no thing except we're not sure exactly what that is. So this is a great
way for your time. And I'm going to go over to Google and search for hub twig bundle
so we can find the twig bundle, get hub repository and head down to the change log.
And this is really the best way to find out what's going on in here.

So if we look at 4.4 0.0, you'll see it talks about the exception controller. It says
deprecated, tweaked, that exception controller option. So basically that's now
deprecated, set it to null and use framework, that air controller configuration
instead. So what they're saying is that the tweak that exception controller option is
deprecated and to tell twig bundles specifically that you're not using it. Basically
to remove the deprecation, we're going to want to set it to null and of course if
you're doing something custom with the exception controller, you're going to need to
move that to air controller and read a bit more about that. So long way of saying
that we do want this change, I'm going to copy this, but we don't want to commit
everything. So I'll hit Q, I'll do it, get status and say get checkout config
packages tweaked at Yammel to undo those changes and of course you have to make sure
you spell that correctly. Then we'll spin back over here, tweak packages, config
packages, tweak .yaml and at the bottom I'll add conception controller null.

All right,

so now I'll go back to get add dash P first yes, we want to accept that change. This
is the Symfony, that lock file. We'll accept that. And then the next file that it
tells us about is actually based at H team on that twig, which we definitely don't
want to accept any of this because our stuff is all custom. So I'll hit it and for
no, and that's it, except there's is one new test file called toy that young down
here. But real quick, let's do get checkout templates based that HTML twig to undo
those changes. And then we'll go look at this config packages test, tweak .yaml file.
So this is a file that will be loaded only in the test environment and it sets strict
variables to true, this is a really minor thing. You could go find this PR. Uh, it's
basically allowing us to, uh, if you use a variable that, um, doesn't exist in your
test environment, this tells it to throw an exception. So it's a better default than
silently failing, which is what it did before. So we'll add, get add config packages,
test twig that yanno great. That was easy except notice it did not delete

the config routes. Dev twigged .yaml file, which is what I expected.

And in fact [inaudible].

If you run composer recipes, twig /Tway dash bundle, composer recipes at Symfony
/twig dash bundle and copy its recipe URL, you'll find that there is no config routes
file anymore. It's not in the recipe. This is a shortcoming of the RSP update system.
It's not that smart. It's not smart enough to realize that there used to be a config
routes, Deb [inaudible] yam on the old recipe, but it's not in their new recipe so it
should delete it. It's not smart enough to realize that. So we need to delete this
manually. This doesn't happen that often, but this is one of the cases where it,
where it happens.

Okay,

it's been back over and you get status one more time and I'll get, I'll commit that
we're updating. Updating Symfony twigs, a Symfony /twig bundle.

All right here I'm gonna run a composer recipes again and let's do one other one
Symfony. Slash. Mailer. It's the next one down on the list. So I'll say oppose
recipes, Symfony slash, mailer. Cause that gives me a nice command here to see what's
kind of up day. I'll copy the recipes and stock command run that. And this one
created one new file apparently, but that's where it gets status. Apparently the only
made changes to dot N so I'll do get SP and what you can see here is it changed some
of the comments that I gave you. It said, um, uh, and Symfony 4.4 and higher the
syntax for the null transparent as null colon slash. Slash. Um, and then it gives you
an example of the SMTP, uh, example in here. Um, and you can see this here. This top
line is actually my code here. So this is what's being replaced. So in Symfony 4.4,
um, the, the syntax for using the Knoll transport actually changed.

So I don't want to actually just have these as comments. So I'm to heads no to this
change, yes to the Symfony of that lock file. And then do get checkout dash end and
then go over to my desk and file and down here for the mailer stuff we are going to
use, we do need to change these syntax to use the new normal a thing. And actually
you can use anything here for Tafolla = no colon //null which is actually what's in
the docs. So really we didn't want the recipe update in this case but it kind of
reminded me that Symfony of that mailer 4.4 had a backwards compatibility change that
we needed to check into.

Symfony mailer is also another one where I can go to [inaudible] dot com /Symfony's
/mailer where it's a pretty good idea to check out the change log. Since Symfony
mailer went from um, still wasn't stable into Symfony 4.4. So you can see here it's
talking about the changes, a couple of the backwards compatibility changes that might
be relevant to you. We're going to talk more about some of these later. If you run a
composer recipes, again, there's one other one that is a relevant here to Symfony
mailer. It's Symfony SendGrid mailer, a package that helps us send with SendGrid,
some that run composer recipes or I'm just going to skip, skip and say composer
recipes. Install Symfony /SendGrid dash mailer dash dash force dash V. and then we'll
do get add dash P to figure out what that did. And the only change that made, Oh,
this here, this no colon and /no, that's the chain that this made on the say.

Why did that, the other change that made is down here in the same dotN file is it
changed the mailer DSN example from what we had before to send grid colon //key at
defaults. That's another one of the changes that are similar to the, uh, no one
above. That's another change that was made in simply pour wine for the format that
you use for SendGrid changed. So I'm going to yes to that. Notice these are both
comments. So these are just example code. But if we were using, um, the other thing
we need to check is a, see if we're using this the old format in our.in that local
file. So open up that and that local, I'm not using it in the project at this point,
but if I did have a mailer DSN using SendGrid inside that file, I'd want to make sure
that it was using the new updated format. So this recipe format, this recipe updates
actually notifying us of a change in how that package is configured. And then down
here will say yes, of course, to the Symfony dot lock file,

and we'll commit that.

Let's do one more get status. Yep. We're good. We'll come at that with updating
Symfony mailer recipe packages. All right. At this point, we're most of the way
through updating the recipes. Let's get the last few done and let's keep on going.