# Integration Testing Emails

I *also* want to test the method that sends the weekly update email. But because
the *real* complexity of this method is centered around generating the PDF, instead
of a unit test, let's write an *integration* test.

In `MailerTest`, add a second method: `testIntegrationSendAuthorWeeklyReportMessage()`.

[[[ code('0491cae511') ]]]

Let's start the same way as the first method: copy *all* of its code except for
the asserts, paste them down here and change the method to
`sendAuthorWeeklyReportMessage()`. 

[[[ code('5222720454') ]]]

This needs a `User` object... but it also needs an array of articles. 
Let's create one: `$article = new Article()`. These articles are passed to the 
template where we print their title. So let's at least populate that 
property: `$article->setTitle()`:

> Black Holes: Ultimate Party Pooper

[[[ code('f34776c5d4') ]]]

Use this for the 2nd argument of `sendAuthorWeeklyReportMessage()`: an array with
just this inside.

[[[ code('85864a9866') ]]]

## Unit Versus Integration Test

It's time to think strategically about our mocks. Right now, *every* dependency
is mocked, which means it's a *pure* unit test. If we kept doing this, we could
probably make sure that whatever `render()` returns is passed to the PDF
function... and even assert that whatever *that* returns is passed to the
`attach()` method. It's not bad, but because the *logic* in this method isn't
terribly complex, its usefulness is limited.

What *really* scares me is the PDF generation: does my Twig template render correctly?
Does the PDF generation process work... and do I *really* get back PDF content?
To test this, instead of mocking `$twig` and `$pdf`, we could use the *real* objects.
That would make this an *integration* test. These are often more useful than unit
tests... but are also much slower to run, and it will mean that I really *do* need
to have `wkhtmltopdf` installed on this machine, otherwise my tests will fail.
Tradeoffs!

So here's the plan: use the *real* `$twig` and `$pdf` objects but *keep* mocking
`$symfonyMailer` and `$entrypointLookup`... because I don't *really* want to send
emails... and the `$entrypointLookup` doesn't matter unless I want to test that
it *does* reset things correctly between rendering 2 PDFs.

## Become an Integration Test!

To make this test *able* to use real objects, we need to change `extends` from
`TestCase` to `KernelTestCase`. 

[[[ code('6b5233c3f6') ]]]

That class extends the *normal* `TestCase` but gives us the ability to boot Symfony's 
service container in the background. Specifically, it gives us the ability, 
down in the method, to say: `self::bootKernel()`. 

[[[ code('08fda33c2d') ]]]

*That* will give us the ability to fetch *real* service objects and use them.

## Fetching out Services

So we'll leave `$symfonyMailer` mocked, leave the `$entrypointLookup` mocked, but
for the `Pdf`, get the *real* `Pdf` service. How? In the test environment,
we can fetch things out of the container using the same type-hints as normal. So,
`$pdf = self::$container` - `bootKernel()` set that property - `->get()` passing
this `Pdf::class`. Do the same for Twig: `self::$container->get(Environment::class)`.

***TIP
Starting in Symfony 5.3, instead of `self::$container`, use `static::getContainer()` to get the container
from inside a test. Also, calling `bootKernel()` is no longer needed.
***

[[[ code('593bb3f2ab') ]]]

I love that! Again, the *downside* is that you really *do* need to have `wkhtmltopdf`
installed correctly *anywhere* you run your tests. That's the *cost* of doing this.

Before we try it, at the bottom, we don't have any asserts yet. Let's add at
least one: `$this->assertCount()` that 1 is the count of `$email->getAttachments()`.

[[[ code('a7bb9b8e57') ]]]

We *could* go further and look closer at the attachment... maybe make sure that
it looks like it's in a PDF format... but this is a good start.

*Now* let's try this. Find your terminal and run our normal:

```terminal
php bin/phpunit
```

It *is* slower this time... and then.. ah! What just happened? Two things. First,
because this booted up a *lot* more code, we're seeing a *ton* of deprecation
warnings. These are annoying... but we can ignore them.

## Caching Driver in the test Environment

The *second* thing is that... the test failed! But... weird - not how I expected:
something about APCu is not enabled. Huh? Why is it suddenly trying to use APCu?

The cause of this is specific to our app... but it's an interesting situation.
Open up `config/packages/cache.yaml`. 

[[[ code('f25dc5970f') ]]]

See this `app` key? This is where you can tell Symfony *where* it should store 
things that need to be added to cache at runtime - like the filesystem, redis or APCu. 
In an earlier tutorial, we set this to a parameter that we invented: `%cache_adapter%`.

This allows us to do something cool. Open `config/services.yaml`. 

[[[ code('01ffd005ae') ]]]

Here, we set `cache_adapter` to `cache.adapter.apcu`: we told Symfony to store cache in APCu.
And... apparently, I don't have that extension installed on my local machine.

Ok... fine... but then... how the heck is the website working? Shouldn't we be
getting this error everywhere? Yep... except that we *override* this value in
`services_dev.yaml` - a file that is *only* loaded in the `dev` environment. Here
we tell it to use `cache.adapter.filesystem`.

[[[ code('c26ed52e54') ]]]

This is great! It means that we don't need any special extension for the cache system
while developing... but on production, we use the superior APCu.

The problem *now* is that, when we run our tests, those are run in the `test`
environment... and since the `test` environment doesn't load `services_dev.yaml`,
it's using the default APCu adapter! By the way, there *is* a `services_test.yaml`
file... but it has nothing in it. In fact, you can delete this: it's for a feature
that's not needed anymore.

So, honestly... I *should* have set this all up better. And now, I will. Change
the default cache adapter to `cache.adapter.filesystem`. 

[[[ code('358c24e414') ]]]

Then, *only* in the `prod` environment, let's change this to `apcu`. To do that, 
rename `services_dev.yaml` to `services_prod.yaml`... and change the parameter inside
to `cache.adapter.apcu`.

[[[ code('a718d31b61') ]]]

Now the `test` environment *should* use the filesystem. Let's try it!

```terminal
php bin/phpunit
```

And... if you ignore the deprecations... it worked! It actually generated the PDF
inside the test! To *totally* prove it, real quick, in the test,
`var_dump($email->getAttachments())`... and run the test again:

```terminal-silent
php bin/phpunit
```

Yea! It's *so* ugly. The attachment is some `DataPart` object and you can see
the crazy PDF content inside. Go take off that dump.

Ok, the *last* type of test is a *functional* test. And this is where things get
more interesting... especially in relation to Mailer. If we want to make a
functional test for the registration form... do we expect our test to send a
*real* email? Or should we disable email delivery somehow while testing? And, in
both cases, is it possible to submit the registration form in a functional test
and then *assert* that an email *was* in fact sent? Ooo. This is good stuff!
