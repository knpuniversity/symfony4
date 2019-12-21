# Updating the Mailer Recipe(s)

Coming soon...

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
