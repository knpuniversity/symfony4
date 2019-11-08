# Functional Testing with Emails

When we originally added our Mailtrap configuration... I was a bit lazy. I put
the value into `.env` file. Because that file is *committed*... we *really*
shouldn't put any sensitive values into it. Well, I guess you could argue that
Mailtrap credentials aren't *that* sensitive, but let's fix this properly. Copy
the `MAILER_DSN` and open `.env.local`.

If you don't have a `.env.local` file yet, just create it. I already have one
so that I can customize my local database credentials. Anything in this file
will *override* `.env`, but because it's ignore by our `.gitignore`, the values
won't be committed.

Back in `.env`, let's set `MAILER_DSN` back to the original value, which was
`smtp://localhost`.

And yes, this *does* mean that when a developer clones this project, unless they
customize `MAILER_DSN` in their *own* `.env.local` file, they'll get an error.
We'll talk more about that in a few minutes.

## Creating a Functional Test

Back to my *real* goal: writing a functional for the registration page. Because
a successful registration causes an email to be sent... I'm curious how that will
work inside an automated test.

To create the test, let's be lazy and run:

```terminal
php bin/console make:functional-test
```

And... we immediately get an error: we're missing some packages. I'll copy the
`composer require browser-kit` part. Panther isn't *technically* needed to write
functional tests - and this error message is fixed in a new version of this bundle.
But, Panther *is* an awesome way to write functional tests that rely on JavaScript.

Anyways, run

```terminal
composer require browser-kit --dev
```

... and we'll wait for that to install. Once it finishes, I'll clear the screen
try `make:functional-test` again:

```terminal-silent
php bin/console make:functional-test
```

Access granted! I want to add a functional test for the `SecurityController` -
specifically the `SecurityController::register()` method. I'll follow the same
convention we used for the unit test: call the test class `SecurityControllerTest`.

Done! This creates a simple functional test class directly inside of `tests/`.
We don't *have* to, but to make this mat the `src/Controller` directory structure,
create a new `Controller/` folder inside of `tests/`... and move the test file there.
Don't forget to add `\Controller` to the end of the namespace.

And, again, to stay somewhat conventional, let's tename the method to `testRegister()`.

## Writing the Registration Functional Test

We won't get *too* deep into the details of how to write functional tests, but
it's a *pretty* simple idea. First, we create a `$client` object - which is almost
like a "browser": it will help us make requests. In this case, we want to make
a `GET` request to `/register` to load the form. The `assertResponseIsSuccessful()`
is a helper assertion from Symfony that will make sure the response wasn't an error
or a redirect.

Now... I'll remove the `assertSelectorTextContains()`... and paste in the rest
of this test. Let's see: this goes to `/register`, finds the `Register` button
by its text, and then fills out all of the form fields. These funny-looking
values are literally the *name* attributes of each element if you looked at the
source HTML. After submitting the form, we assert that it redirected - which
is an easy way to assert that the form submit *was* successful.

We've registered on the site... about 100 times. So we *know* it works... and
so this test *should* pass. Whenever you say that something "should" work in
programming... do you ever get the feeling that you're about to see an explosion?
Ah, I'm sure nothing bad will happen in this case. Let's try it!

At your terminal, run *just* this test with:

```terminal
php bin/phpunit tests/Controller/SecurityControllerTest.php
```

Deprecation notices of course... and... woh! It failed! And dumped some *giant*
HTML... which is impossible to read... unless you go *all* the way to the top.
Ah!

> Failed asserting that the Response is redirected: 500 internal server error.

And down in the HTML itself:

> Connection could not be established with host tcp://localhost:25

## The test Environment Doesn't Read .env.local

Huh. That's coming from sending the email... but why is it trying to connect to
`localhost`? Our configuration in `.env.local` is set up to talk to MailTrap.

Well... there's a little gotcha about the `.env` system. I mean... it's a feature!
When you're in the `test` environment, the `.env.local` file is *not* loaded.
In *every* other situation - like the `prod` the `dev` environments - it *is*
loaded. But in `test`, it's *not*. It's madness!

Well, it definitely *is* surprising the first time you see this, but there *is*
a reason for this. In theory, your committed `.env.test` file should contain
*all* the configuration needed for the `test` environment to work... on any
machine. And so, you actually *don't* want my local values from `.env.local`
to override them: that might *break* how the tests are supposed to behave.

The point is, since the `.env.local` is not being loaded in our tests, it's
using the `.env` settings for `MAILER_DSN`... which is connecting to `localhost`.

How can we fix this? The simplest answer is to copy the `MAILER_DSN` from
`.env.local` into `.env.test`. This isn't a *great* solution because `.env.test`
is committed... and so we would once again be committing our Mailtrap credentials
to the repository. You *can* get around this by creating a `.env.test.local`
file - that's a file that would be loaded in the `test` environment but *not*
committed - but let's just do this for now to see if we can get things working.
Later, we'll talk about a better option.

Ok, go tests go!

```terminal-silent
php bin/phpunit tests/Controller/SecurityControllerTest.php
```

This time... it passes! Spin back over and inside Mailtrap... there it is! The
test *actually* sent an email! Wait... is that what we want? Next, let's improve
this by *preventing* emails from our test from *actually* being delivered. Then,
we'll talk about how we can add *assertions* in our test to make *sure* that
the right email was sent.
