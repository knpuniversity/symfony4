# Functional Test

Coming soon...

Yeah.

When we originally were configuring our a male trap configuration, I was a bit lazy
and I put it into my `.env` file directly. So I had like literally cop, I configured
the MailTrap DSN stuff here. The downside of this is that fit that `.env` committed to
the repository and MailTrap isn't that sensitive, but you typically don't want to
commit, you know, user names and passwords into your repository. So let's actually
fix this kind of properly. I'm going to copy the MailChimp debt DSN and where this
should go is this should go into it, `.env.local` file

if you don't have one of those yet, you can create one. See, I've actually already
created one. So I could use a separate database, uh, in my local environment. We're
going to pass this here, but that `.env.local` file overrides, anything `.env`,
but it's not committed to the repository because it's ignored by our `.gitignore`
file. So great. We've now properly solved that in back in the end, I'm going to use
the um, original setting for this, which was just `smtp://localhost`, which is
not gonna work, which means people will get heirs and they'll know that they need to
configure this. There's also another reason I made this change, which will become
obvious in a few minutes. Now, my goal right now is a functional testing emails. So
right now we know that we have a registration page, so let's create a functional test
for the registration page and see how sending emails fits into this process. So I'm
going to be lazy and run over and run 

```terminal
php bin/console make:functional-test
```

and I immediately get an heir that I'm missing some packages. copy of the
`composer required browser-kit` Panther is actually not technically needed in this
case. This is a great library for testing in a real browser using JavaScript. So I'm
just gonna we don't need that right now. So I'm just going to say 

```terminal
composer require browser-kit --dev
```

and we'll wait for that to install. once it finishes

I'll clear the screen and we'll try and `make:functional-test` again. 

```terminal-silent
php bin/console make:functional-test
```

Perfect. All
right, so what we want to functionally test is actually our `SecurityController` as
our `SecurityController::register()` method. So I'll fall on this convention here by
saying country `SecurityControllerTest`. Uh, that can be a finishes and it creates a
very simple functional test class instead of our `tests/` directory. Uh, same thing as
last time. I actually prefer this to go into my `Controller/` directory. So I'm can
create `Controller/` directory instead of `tests/` and let's move our test instead of there
and I'll add a little `\Controller` namespace to it.

now the method we're going to be testing is called `register()` So for consistency, I'm
going to say `testRegister()` inside of here. Alright, so functional testing, pretty
easy. We're going to create a `$client` here, which is going to help us make
requests. In this case we want to make your request to `/register`, 
`assertResponseIsSuccessful()` is great, and I'm gonna remove the `assertSelectorTestContains()` 
And instead I'm going to paste in a finished test here. So what this does
is it's going to go to the register page. It's then going to use the crawler to find
our register button here. And then it's going to use that to fill out the form. So if
you looked at the HTML of our actual fields here, you'd see that these are the format
of their names. It's a little bit ugly. We're basically filling out the form. I'm
using a random email here, taking the agreed terms box, and then submitting that
form. And at the bottom we're asserting that the response will redirect. So that's
basically a way to say, to assert that the form did submit successfully. It didn't
have a validation error because it redirected.

All right, so this should work right

after all our, that end, that local has our real mail trap, um, credentials in it. So
our test is gonna send an email, the male trap, but everything should work. So let's
try it. I'm gonna run 

```terminal
php bin/phpunit tests/Controller/SecurityControllerTest.php
```

So just runs that one test and so we got deprecation
notices of course, but Whoa, what happened here? Okay, it says it actually failed and
you see a giant HTML, not very easy to read unless you go all the way to the top and
you can see that it got a five 

> Failed asserting that the Response is redirected. 

500 internal server error. In fact down here, look, it could not 

> Connection could not be established with host tcp://localhost:25 

that's interesting that,
sounds like it's actually coming from the sending the email, but why is it trying to
connect to a `localhost` when our configuration is set up to talk to MailTrap? Well
here is a little gotcha about the that `.env` system. It's done this way on purpose, but
if you don't, aren't aware of it. It can be surprising and that's this in the test
environment and the test environment only `.env.local` file is not loaded.
The reason for that is that a, in theory you're `.env.test` file should set up
everything you need for the test environment. Um, and you actually don't want any
developers local override that because the test environments should be set up to be,
have, um, a lot configuration that's always kind of repeatable, uh, and always done
the exact same way. So the point is, since the `.env.local` is not being loaded, it's
actually using the `.env` settings for `MAILER_DSN`, which is connecting to `localhost`.

Okay, so how can we fix this? Well, the simplest answer is to copy that, the `MAILER_DSN`
from that `.env.local` into that `.env.test`. Now this is not a great
solution for one reason and that's because this file is committed and we don't really
want to commit the settings. However, you can get around that by creating 
`.env.test.local` file, yes, that's allowed. That would be a file that's not
committed because it ends in `.local`, but as loaded only in the test environment.
I'm not going to do that right here, that we get around the committing problem. I'm
not going to do it here though because I'm going to show you a better solution in the
second, but this should hopefully get our test passing.

```terminal-silent
php bin/phpunit tests/Controller/SecurityControllerTest.php
```

So I spin it back over. I'll pair the skiing around the test and it passes and that's
surprising but check out trap. Yeah, you can actually see there is welcome to
spacebar. This actually sent an email, um, in that test, so great. But is that really
what we want? Do we really want real emails to be sent to MailTrap in our test
environment? Well on the one hand MailTrap does have a really nice API, so in theory,
in your functional tests, you could actually connect to that API and run assertions
on the email messages that were sent to it. That's cool. But I'm going to show you a
better way to do that in Symfony anyways. The real downside to sending a real emails
to middle trap is that it slows down your tests because it's sending a real email. If
MailTrap is down, we could get heirs.

And also we requires the test environments to have this sensitive key somehow stored
in it. We could commit it to the repository, but that's not a deal. So there's a
better way in the test environment, we don't really need our emails to be sent. We
want them to do creative, we want them to be processed in the system. But if at the
last second they weren't sent, that would be fine. So for example, if you look in
source service and mailer, you know quite literally if `$this->mailer->send()`, if we
skipped that in the test environment, I would be okay with that. It would make sure
that everything else is working. We're just not testing that last step. Of course,
you know, wrapping all them this or `$mailer->send()` is kind of annoying and a bit ugly
in our code. So we're going to do it in a different way.

Now remember from earlier in the tutorial, I talked about how the way that you
actually sent an email in mailer is called a transport. So this is using the `SMTP`
transport to talk to the `localhost` server in `.env.local`. This is also using
the `SMTP` transport to talk to the MailTrap server. So the only transport we've seen
so far is SMTP. But another one that you can use is called the `NULL` transport. The
no transport sends her a message nowhere because it's a great, uh, and that makes it
a great option for the test environment. So check this up into `.env.test` , change
this to `smtp://null`. Now as a note, the syntax for this changed and
simply 4.4, it's actually `null://default`. I want to talk more about
how the format of these DSNS and how those relate to transports in a few minutes when
we talk about SendGrid. All right, so now let's go to the M. now let's go back to our
tests. Run them 

```terminal-silent
php bin/phpunit tests/Controller/SecurityControllerTest.php
```

and they pass and there's no actual email sent to mail trap. So we
don't need to have MailTrap configured the emails. That last step is not going to
make the test pass. So cool. Right? Well, actually, maybe you want to go one step
further.

Maybe the `NULL` transport is actually a good candidate for the, as the default
transport. So hear me out right now. If a new, if a new person pulled down this
project, they wouldn't have `.env.local`. So then project would use `smtp://localhost`
Now if you, for example, had a designer that wanted to work on the
registration page, get a designer and they wanted to go through the registration
process, this means that when they went to the registration process, they would
actually get a 500 error because the welcome email couldn't be sent to the `localhost`
SMTP transport. That's kind of annoying. It means they actually would need to create
it that end, that local file and properly configure their `MAILER_DSN` just to get
through the registration process. So maybe having a null transport is a great
default. And then if someone actually wants to test emails, then they could, uh,
configure it to use, um, MailTrap. So I'm actually gonna change the `MAILER_DSN` here to
`smtp://null` And again, in 4.4 this has `null://default`
Now, if anybody pulls down the project, they won't get sent any emails, they'll
immediately be able to use the site without hitting 500 errors

in a, `.env.test`. We don't have to override anything. Now last thing I want to talk
about, in `SecurityControllerTest` is right now we're just testing that the
registration page works. We're not actually testing that an email was sent or
anything above that email. And that might be okay with you, but you might actually
want to say, I want to assert inside of here that during this request when we
submitted the form, an email was sent in Symfony 4.3 which is what we're using.
That's not possible without setting up some sort of system by yourself. But in
Symfony 4.4 is possible and it's super easy. So I'm going to go before it Symfony 4.4
mailer testing to find a blog post called new and some people from for PHP unit
assertions for email messages. And this is awesome. You can see a setup very similar
to ours. Um, where they're making a request and afterwards there is now in simply 4.4
a bunch of built in assertions where you can assert that to emails that were sent and
you're gonna assert lots of things about the email, like it was sent to this person.
The text body contains walk them to Symfony and so on.

So in our case as an example is after we submitted the form,

I'm going to paste in a couple of examples, certs that we could do. We actually say
`$this->assertEmailCount(1)` that's a really important one. That one was sent.
Then you could say `$this->getMailerMessage(0)` and get the zero index to the
one message that was sent in. This gives you back an `Email` object, which is an
instance of fill in the blank because I can't remember. And that then allows you to
assert stuff like that. The two header is sent to Fabi and@Symfony.com so I'm not
going to go through all of those, but this is wonderful. The way this works behind
the scenes is during the previous request, as you send emails, whether you send them
to MailTrap or the null transport Symfony keeps a list of all the emails and then
down here, this allows you to actually then go look at those emails and run
assertions on them. So I'm going to comment this out for now so that our tests
actually passes on 74.3 that's an excellent feature to look forward to in 4.4 all
right, next, let's talk about setting this stuff up for production and actually
sending to a real cloud provider. I'm on production. We're going to use SendGrid.