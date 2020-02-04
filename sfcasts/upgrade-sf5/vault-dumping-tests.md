# Prod Vault Optimization & Vault for Tests

Now that we know that environment variables override secrets, we can use that
to our advantage in two ways.

## Dumping Secrets on Deploy: secrets:decrypt-to-local

The first thing is that, during deployment, we can dump our production secrets
into a local file. Check it out. Run:

```terminal
php bin/console secrets:decrypt-to-local --force --env=prod
```

And... no output. Lame! *SO* lame that, in Symfony 5.1, this command *will* have
output - that pull request was already merged.

Anyways, this just created a new `.env.prod.local` file... which contains *all*
our `prod` secrets... which is just one right now. This means that, when we're in
the `prod` environment, it will read from *this* file and will *never* read secrets
from the vault.

Why... is that interesting? Um, good question. Two reasons. First, while deploying,
you can add the decrypt key file, run this command, then *delete* the key file.
The private key file then does not need to live on your production server at all.
That's one less thing that could be exposed if someone got access to your servers.

And second, this will give you a *minor* performance boost because the secrets
don't need to be decrypted at runtime.

Now, you might be thinking:

> Ryan! You crazy man, you've got the brains of a watering can! We went to *all*
> this trouble to encrypt our secrets, and now you want me to store them in
> plain text on production? Are you mad?

I never get mad! The truth is, your sensitive values are *never* fully safe on
production: there is always *some* way - called an "attack vector" - to get them.
If someone gets access to the files on your server, then they would already have
your encrypted values *and* the private key to decrypt them. Storing the secrets in
plain text but *removing* the decrypt key from production is really the same thing
from a security standpoint.

The point is: there's no security difference. Let's delete the `.env.prod.local`
file, because we don't need it right now.

## Secrets for the test Environment

The *other* interesting thing that we can do now that we understand that environment
variables override secrets is related to the `test` environment. Because... our
`test` environment is *totally* broken.

Think about it: in the `test` environment, there is *no* vault! And so there is
*no* `MAILER_DSN` secret. Do we *also* need a test vault? Nah. There's a simpler
solution.

First, let's *run* our tests to see what's going on:

```terminal
php bin/phpunit
```

Ignore the deprecation warnings. Woh! *Huge* error. If you look closely... yep:

> Environment variable not found: "MAILER_DSN"

## New in 4.4: Easier HTML Errors in Tests

By the way, trying to find the error message inside the HTML in a test... *sucks*.
But it's easier in Symfony 4.4 because Symfony dumps the error as a comment on the
top of the HTML. It also.... yep! Puts that same comment at the bottom. So actually...
I didn't need to scroll so far up.

## Secrets in the Test Environment

So we *do* need to specify a `MAILER_DSN` secret to use in the `test` environment.
But for simplicity, instead of making another vault, let's just add it to `.env.test`.
I'll copy my old `null` transport value from `.env`, and put it into `.env.test`:

[[[ code('d78bb4528a') ]]]

Done! So *really*, when you need to add a new secret, you need to add it to your
dev vault, prod vault *and* `.env.test`.

Let's try the tests again:

```terminal
php bin/phpunit
```

Much better! So... that's it for the secrets system! Pretty freakin' cool! Let's
clean up our debugging code... nobody likes data being dumped on production. I'll
remove the bind:

[[[ code('866ddc4149') ]]]

then go to `ArticleController` and take out the `$mailerDsn` stuff there:

[[[ code('e688e8e0ad') ]]]

Next, let's talk about a really cool new feature called "validation auto mapping".
It's a wicked-smart feature that automatically adds validation constraints based
on your Doctrine metadata and also based on the way that your PHP code is written
in your class.
