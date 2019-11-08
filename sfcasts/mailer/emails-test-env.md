# Email Delivery & Assertions in Tests

We *just* got our registration functional test to pass. But to do it, we had to
configure the test environment with our Mailtrap credentials. And that means that
each time we run our tests, an email is being sent to Mailtrap!

Ok, in reality, because we're using Mailtrap... we're not *really* delivering
test emails to *real* people. But delivering emails inside our tests is a bummer
for a few reasons: it adds a lot of garbage to Mailtrap, it slows down our tests
*and* it means that we need to worry about configuring *real* Mailtrap credentials
*just* to check if our tests pass.

In reality, we don't *really* need emails to be sent in the `test` environment.
We *do* want the `Email` objects be created and processed by Mailer... but if at
the *last* second Mailer... just... didn't *actually* deliver them... that would
be cool! We could *try* to do this by, maybe adding an if statement around
`$this->mailer->send()` if we're in the `test` environment... but that would be
a pain... and *ugly*.

## The Null Transport

*Way* earlier in the tutorial, I mentioned that the *way* a the email is actually
*delivered* is called a "transport". This is using the `smtp` transport to talk to
the `localhost` server. In `.env.local`, this is *also* using the `smtp` transport
to talk to Mailtrap server. So far, `smtp` is the *only* transport that we've seen.

Well, prepare to be amazed! Introducing the *laziest*, do-nothing... but mysteriously
useful transport ever: the `null` transport! When you deliver an email via the
`null` transport... your email goes... nowhere.

Hey! That's *exactly* what we want to do in the test environment! Inside `.env.test`,
change `MAILER_DSN` to `smtp://null`.

Side note! This syntax *changed* in Symfony 4.4 to `null://default` - where the
*start* of the string defines the transport *type*. We'll talk more about transports
in a few minutes when we start using SendGrid.

Anyways, let's try the test *now*:

```terminal-silent
php bin/phpunit tests/Controller/SecurityControllerTest.php
```

It passes and... yea! There was *no* actual email sent to Mailtrap: we no longer
need to have it configured to use our tests.

## Using the Null Transport by Default?

But wait, there's more! The `null` transport is *perfect* for the test environment.
And... it might *also* be a good candidate as the *default* transport.

Hear me out. If a new developed cloned this project, they would *not* have a
`.env.local` file. And so, out-of-the-box, mailer would be using the
`smtp://localhost` setting. Imagine that this developer is really a designer that
wants to work on the registration process. Well... surprise! The *moment* they
submit the form successfully, they'll be met with a lovely 500 error. And they'll
be off to find you to figure out how to fix it. Nobody wants that!

That's why using the `null` transport in `.env` might be a *perfect* default. Then,
if someone *actually* wants to test emails, *then* they can take some time to
configure their `.env.local` file to use Mailtrap.

Let's do this: change `MAILER_DSN` to `smtp://null`. Use `null://default` on
Symfony 4.4 or higher.

And now, in `.env.test`, we don't need to override anything: remove `MAILER_DSN` there.

## Asserting Emails were Sent

We can now use the site *and* run our tests without needing to manually configure
mailer. Cool! But we can still make our functional test a *little* bit cooler.

In `SecurityControllerTest`, we *are* testing that the registration form works.
But we are *not* actually testing that an email *was* in fact sent or... that
the email has the right details.

And, while that might not be a huge deal, we actually *can* add assertions about
the email. Well, actually *I* can't do it because *this* project uses Symfony 4.3.
In Symfony 4.4, a number of new features were added to make this a *breeze*.

Google for "Symfony 4.4 mailer testing" to find a blog post about this fancy new
stuff. It's... just... awesome. The setup is the same, but after each request,
you can *now* use a bunch of new assertions to check that the correct number of
emails were sent, that they were sent to the right person, the subject... anything!

In our test class, *after* submitting the form, I'll paste in some assertions
that I will use... once I upgrade this app to Symfony 4.4. This checks that
one email was sent and then *fetches* the `Email` object, which you can then
use to make sure *any* part of it is correct.

I'll comment these out for now.

Next, it's time to send some *real* emails and get ready for production! Let's
register with a cloud provider and configure it in our app.
