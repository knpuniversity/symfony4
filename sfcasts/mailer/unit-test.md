# Unit Testing our Emails

Other than code organization, one of the benefits of putting logic into a service
is that we can unit test it. Ok, to be *fully* honest, this chapter doesn't have
a lot to do with Mailer. Unit tests *pretty* much look the same no matter *what*
you're testing. But unit testing is a great practice... and hate when code does
weird things... especially code that sends emails.

## make:unit-test

Let's use MakerBundle to bootstrap a test for us. At your terminal, run:

```terminal
php bin/console make:unit-test
```

And answer `MailerTest`. This generates a *super* simple unit test file:
`tests/MailerTest.php`. The idea is that this will test the `Mailer` class,
which lives in the `Service/` directory. Inside `tests/`, create a new
`Service/` directory to match this and move `MailerTest` inside. You typically
want your unit test directory structure to match your code. Inside the file,
let's also remember to add these `\Service` namespace.

## Running the Tests

Ok! Our test asserts that true is true! I'm not so easily convinced... we better
run our tests to be sure. At your terminal, run the tests with:

```terminal
php bin/phpunit
```

This script is a small wrapper around PHPUnit... and it will *install* PHPUnit
the first time you run it. The... it passes!

Oh! But it *did* print out a deprecation notice. One of the superpowers of this
wrapper around PHPUnit - called the phpunit-bridge - is that it prints out warnings
about any deprecated code that the code in your tests hit. This is great tool
when you're getting ready to upgrade your app to the next major Symfony version.
But more on that in a different tutorial. We'll just ignore these.

## Writing the Unit Test

Let's get to work! So... what *are* we going to test? Well, we probably want to
test that the mail was actually *sent*... and maybe we'll assert a few things
about the `Email` object itself. Unit tests always start the same way: by
instantiating the class you want to test.

Back in `MailerTest`, rename the method to `testSendWelcomeMessage()`. Then add
`$mailer = new Mailer()`. For this to work, we need to pass this our 4 dependencies:
objects of the types `MailerInterface`, `Twig`, `Pdf` and `EntrypointLookupInterface`.
In a unit test, instead of. using *real* objects that really *do* send emails...
or render Twig templates, we use mocks.

For the first, say `$symfonyMailer = this->createMock()`... and because the first
argument needs an instance of `MailerInterface`, that's what we'll mock:
`MailerInterface::class`.

To make sure we don't forget to actually *send* the email, we can add an assertion
to this mock: we can tell PHPUnit that the `send` method *must* be called exactly
once. Do that with `$symfonyMailer->expects($this->once())` that the
`->method('send')` is called.

Let's create the other 3 mocks: `$pdf = this->createMock(Pdf::class)`... and the
other two are for `Environment` and `EntrypointLookupInterface`:
`$twig = $this->createMock(Environment::class)` and
`$entrypointLookup = $this->createMock(EntrypointLookupInterface::class)`.

These three objects aren't even used in this method... so we don't need to add
any assertions to them or add any behavior. Finish the `new Mailer()` line by
passing `$symfonyMailer`, `$twig`, `$pdf` and `$entrypointLookup`. Then, call
the method: `$mailer->sendWelcomeMessage()`. Oh, to do *this*, we need a `User`
object.

We could mock the `User` object, but as a general rule, I like to mock services
but manually instantiate simple "data" objects, like Doctrine entities. The reason
is these classes don't have dependencies and it's usually simple to put whatever
data you want on them. Start with `$user = new User()`. And... let's see... the
only information that we use from `User` is the email and first name.

Call `$user->setFirstName()`. Let's pass the name of my brave co-author for this
tutorial: `Victor`! For `$user->setEmail()`, use `victor@symfonycast.com`. Pass
`$user` into the method.

By the way, if you're enjoying the tutorial, you can thank Victor by emailing
him photos of your cat *or* by sending tuna directly to his cat Ponka.

And... done! We're not asserting anything down *here*... but we *do* have one
built-in assert above: our test will fail unless the `send()` method is called
exactly once.

Let's try this! Fly over to your terminal, I'll clear my screen, then run:

```terminal
php bin/phpunit
```

It passes!

## Asserting Info on the Email

But, the tricky thing is that the majority of this method is about creating the
`Email`... and we're not testing what *that* object looks like at all. And...
maybe you don't need to? I typically only unit test things that scare me. But
let's *at least* test a few basic things.

How? An easy way is to return the email from each method: `return $email` and
then advertise that this method returns a `TemplatedEmail`. I'll do the same
thing for the other method: `return $email` and add the `TemplatedEmail` return
type.

You don't *have* to do this, but it'll make our unit test more useful and keep
it simple. *Now* we can say `$email = $mailer->sendWelcomeMessage()`.

And now... we're asserting values on a simple object. I'll paste in some asserts.
These check the subject, that the email is sent to exactly one person *and* checks
to make sure that the "to" has the right info.

Let's try this: move over and run:

```terminal-silent
php bin/phpunit
```

All green! Next, let's do this same thing for the author weekly report email.
Actually... the "email" part of this method is dead simple. The *complex* part
is the PDF-generation logic. Want to test to make sure the template *actually*
renders and the PDF is *truly* created? We can't do that with a unit test, but
we *can* with an integration test. That's next.
