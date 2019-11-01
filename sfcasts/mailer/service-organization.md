# Organizing Emails Logic into a Service

We're sending two emails: one from a command and the other from
`src/Controller/SecurityController.php`. The logic for creating and sending these
emails is fairly simple. But even still, I prefer to put all my email logic into
one or more *services*. The *real* reason for this is that I like to have all
my emails in one spot. That helps me remember exactly which emails were sending
and what they contain. After all, emails are a *strange* part of your site that...
you may rarely or *never* see. Like, how often do you do a "password reset" to
check out what that content looks like? Keeping things in one spot... at least
helps with this.

## Creating a Mailer Service

So what we're going to do is, in the `Service/` directory, create a new class
called `FileThatWillSendAllTheEmails`... ah, or, maybe just `Mailer`... it's
shorter. The idea is that this class will have one method for *each* email that
my app sends. Now, if your app sends a *lot* of emails, instead of having just
*one* `Mailer` class, you could instead create a `Mailer/` directory with a bunch
of service classes inside - like one per email. Either way, you're either organizing
all your email logic into a single service or multiple services in one directory.

Start by adding a `__construct()` method. The *one* service that we *know* we're
going to need is `MailerInterface $mailer`... because we're going send some
emails. I'll hit Alt + Enter and go to "Initialize fields" to create that property
and set it.

Ok, let's start with the registration email inside of `SecurityController`. Ok,
to send this email, the only info we need is the `User` object. Create a new
public function `sendWelcomeMessage()` with a `User` argument. Then, I'll grab
the logic from the controller... everything from `$email =` to the sending part...
and paste that in here. It looks like this class *is* missing a few `use`
statements... so I'll re-type the "L" on `TemplatedEmail` and hit tab, then
re-type the `S` on `NamedAddress` and hit tab once more, to add those `use` statements
to the top of the file. And we can change `$mailer` to `$this->mailer`.

I love it! This will simplify life dramatically in `SecurityController`. Delete
all the logic and then above... remove the `MailerInterface` argument and replace
it with our new `Mailer $mailer`. Below, it's as simple as
`$mailer->sendWelcomeMessage($user)`.

That looks really nice! Our controller is much more readable now.

Let's repeat the same thing for our weekly report email. In this case, the two
things we need are the `$author` that we're going to send to - which is a `User`
object - and then the array of articles. Ok, over in our new `Mailer` class,
add a public function `sendAuthorWeeklyReportMessage()`. This will need a `User`
argument called `$author` and an array of `Article` objects.

Time to steal code! Back in the command, copy *everything* related to sending the
email... which in this case includes the entrypoint reset, Twig render, PDF code
*and* the email logic. Paste that into `Mailer`.

This time, we need to inject a few more services - for `entrypointLookup`, `twig`
and `pdf`. Let's add those on top: `Environment $twig`, `Pdf $pdf` and
`EntrypointLookupInterface $entrypointLookup`. I'll do my Alt + Enter shortcut and
go to "Initialize fields" to create those three properties and set them.

Back in the method... oh... that's it! We're already using the properties... and
everything looks happy! Oh, and it's minor, but I'm going to move the
"entrypoint reset" code *below* the render. This is subtle... but it makes sure
that the Encore stuff is reset *after* we render our template. If some *other*
part of our app tries to render a template after calling this method, Encore will
*now* be ready to do things correctly in that template.

Anyways, let's use this in the command. Delete *all* of this logic and... in the
constructor, change the `$mailer` argument to `Mailer $mailer` and then... we get
to remove a *bunch* of things: take off the `$twig`, `$pdf` and `$entrypointLookup`
arguments, clear them from the constructor and remove their properties. If you
*really* want to clean things up, we now have a bunch of "unused" `use` statements
that are totally useless.

Back down, call the method with `$this->mailer->sendWeeklyReportMessage()` passing
`$author` and `$articles`.

Phew! This *really* simplifies the controller & command, and now I know *exactly*
where to look for all email-related code. Let's... just make sure I didn't break
anything. Run:

```terminal
php bin/console app:author-weekly-report:send
```

No errors... and in Mailtrap... yep! 2 emails... with an attachment.

Next, sending emails is scary! So let's add some tests. We'll start by adding a
unit test and later, an integration & functional test.
