# Email Delivery & Assertions in Tests

I know we're using
Mailtrap... so it's not sending to *real* people... but this will clog up Mailtrap
with messages, slow down m

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
