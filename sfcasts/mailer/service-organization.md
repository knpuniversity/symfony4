# Service Organization

Coming soon...

Yeah,

we're sending our two emails, one from the command and the other one is being sent
from `src/Controller/SecurityController` and thanks to the templates. The logic for
sending these emails is fairly simple. Uh, that being said, my personal preference is
to move the email logic into services. And the real reason is that I like to have all
of my emails in one spot. I don't want to have to because emails are something that
are sensitive and you want to keep them consistent. I don't like to have the email
code all over my code base. I like to have them in one spot. So what we're going to
do is in the `Service/` directory, I'm going to create a new class called `Mailer`. The
idea in here is that we're going to have a single method for each of the emails that
are app sense. Now if your app sends a lot of emails, instead of having just one
Miller class, it might be better to have a `Mailer/` directory with maybe one service
class per email or per types of emails. The point is I recommend organizing your
email logic into a single service or multiple mailers services that are all in one
spot.

First thing I'm going to do is add a `__construct()` function here. And the one service
that we know we're going to need inside of the mailer is `MailerInterface $mailer`.
Because we're going to need to send some emails, I'll hit the Alt + Enter
shortcuts and go to "Initialize fields" to create that property and set it. All right.
So for the first thing about let's do the one inside of `SecurityController`. So this
is sending our welcome registration email. And really the only thing we need a only
information we need in this method is the user object. So we'll create public
function `sendWelcomeMessage()` with a `User` argument, and then I will go grab the
logic here. Everything from `$email =` to the sending of it and paste that in there. Now
you notice I'm missing a couple of use statements here. So I'll re type the L on
template, an email, hit tab, and then read that the S unnamed address and hit tab.
Um, to add those you statements on top and we can change the `$mailer` to
`$this->mailer` to use the one that we've set on the property

perfect. Now to use this in `SecurityController`, it's just going to simplify things.
So I'm going to delete this logic here in the middle and up here we don't need the
`MailerInterface` directly anymore. So I'll remove that argument and we'll say mailer,
our new `Mailer $mailer` and down

we can say the `$mailer->sendWelcomeMessage($user)`. So that just looks really nice. It
makes our controller more readable. Anyways. All right, so let's repeat the same
thing for our weekly report email.

Now in this case, in order to send a weekly report, the two things we need is
actually the `$author` that we're going to send to, which is a `User` object. And then the
array of articles that that user, um, added this week. So over in our new `Mailer`
class,

alright,

I was at a public function `sendAuthorWeeklyReportMessage()`. This will need a `User`
argument called `$author` and then an array of `Article` objects. All right. Same thing.
We'll go all the way over. We'll go back to our command and we'll have everything
related to sending the email, which in this case is a [inaudible]. We have the entry
point reset rendering the twig template and making the PDF. This is all related to
sending an email, somebody keep it all together, so I'll copy all of that code, paste
that into `Mailer`, and then of course we're going to need a couple extra services for
this `entrypointLookup`, `twig` and `pdf`, so let's add those on top and we'll say
`Environment`. That's the type in for Twig on `Environment $twig`, `Pdf $pdf` and 
`EntrypointLookupInterface $entrypointLookup`. I'll do my Alt + Enter shortcut, 
go to "Initialize fields" to create those three properties and set them. 
Now remove the extra PHP doc as usual.

down here, that's it. So we're already using all the properties. `$this->entrypointLookup`
`$this->twig`, `$this->pdf`, so everything is happy. Now, one minor
thing I am going to do here is this entry point reset. I'm going to move that after
the twig call bigger. This is subtle, but the basically the issue is that we don't
ever want render to be called,

okay.

We always want to reset after a render, um, by putting it after it means that once we
render here, it's gonna make sure that it's reset. That's important because if for if
for example, we ever maybe call this method from a controller, it would render the
render the template here, reset itself, and then if that controller then rendered a
twig template, the uh, Encore stuff would be reset. And so the CSS would render on the
page correctly. So small detail

All right, so in our command, last thing we need to do is just use this. So same
thing as before. I'm actually going to delete all that code, put a little.dot, dot
for it up instead of mailer interface manager, we'll say mailer mailer, and then we
can remove the Environment.

the twig PDF and entry point stuff's on all three of those arguments. Then we can
remove them, the properties down below and up here. And if you really want to get
hard core, we actually at this point have quite a lot of unused, uh, use statements.
I'll clean some of those up. Then all the way down here at the bottom, we're just
going to say `$this->mailer->sendWeeklyReportMessage()` and pass that `$author` and
`$articles`. Perfect. So these really simplified my controller and my command, and I now
have this nice centralized logic. Let's just make sure I didn't mess anything up. So
I'll run my 

```terminal
php bin/console app:author-weekly-report:send
```

and okay, it looks like a
work. No heirs. Flip over to male trap. And yep, there are two emails, the
attachment, everything works. We're centralized and as a bonus, now that we have our
code centralized in service, we can add some unit test to it. Let's do that next.