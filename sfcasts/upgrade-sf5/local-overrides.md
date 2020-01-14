# Overriding Secrets Locally & Test Secrets

What if I need to override a secret value on my local machine? `MAILER_DSN` is a
perfect example: in the `dev` secrets vault, it's set to use the `null` transport.
What if I need to see what the emails look like and I so I want to override that
locally to send to Mailtrap?

Well, we *could* run over here and say:

```terminal
php bin/console secrets:set MAILER_DSN
```

And then modify the vault value. But... ugh - then I have to be *super* careful
not to commit that change... and eventually... I probably will on accident.
Fortunately... there's a built-in solution for this!

## Setting a Secret into the "Local" Vault

Pretend like you want to override the `MAILER_DSN` value, but add an extra flag
to the end `--local`:

```terminal-silent
php bin/console secrets:set MAILER_DSN --local
```

So far... this looks identical to before. I'll paste in my Mailtrap value... which
the command hides for security reasons. And... *fascinating*! This didn't change
our `dev` vault at all! Nope, it apparently added the secret to `.env.dev.local`.


Symfony allows you to create a `.env.local` file as a way to the values in `.env`.
And thanks to our `.gitignore`, `.env.local` is ignored from git. Well, it's not
as common, but you can *also* create a `.env.dev.local` file. It works the same
way: it overrides `.env` and isn't committed. The only difference - which is super
minor - is that it's *only* loaded in the `dev` environment.

The point is: this "local" vault thing is nothing more than a fancy way of setting
an environment variable to this "local" file.

## Environment Variables Override Secrets

And... wait: that's kind of beautiful! I mentioned earlier that when you use the
environment variable system - when you use that `%env()%` syntax - Symfony *first*
looks for `MAILER_DSN` as an *environment* variable. If it finds it, it uses it.
And only if it does *not* find it, does it *then* go and try to see if it is a
secret.

So now, in the `dev` environment on my machine, it *will* find `MAILER_DSN` as
an environment variable! Go refresh the page to prove it. There it is: my local
override.

You can use this cool `secrets:set --local` thing if you want... but really all
you need to understand is that if you want to override a secret value locally,
just set it as an environment variable.

And, personally, I don't love having `.env.local` *and* `.env.dev.local` - it just
seems pointless. So I would delete `.env.dev.local` and instead put my overridden
`MAILER_DSN` directly into `.env.local`.

But, don't do that - delete the override entirely - it'll help me show you one
more thing.

Now that we understand that environment variables override secrets, we can
unlock three possibilities. The first is what we just saw: we can override a
secret locally by creating an environment variable.

## Dumping Secrets on Deploy: secrets:decrypt-to-local

The second thing is that, during deployment, we can dump our production secrets
into a local file. Check it out. Run:

```terminal
php bin/console secrets:decrypt-to-local --force --env=prod
```

And... no output. Lame! *SO* lame that in Symfony 5.1 this command *will* have
output - that pull request is already merged.

Anyways, this just created a new `.env.prod.local` file... which contains *all*
or `prod` secrets... which is just one right now. This means that, when we're in
the `prod` environment, it will read from *this* file and will *never* read secrets
from the vault.

Why... is that interesting? Um, good question. Two reasons. First, while deploying,
you can add the decrypt key file, run this command, then *delete* the key file.
The private key file then does not need to live on your production server at all.
That's one less thing that could be exposed if someone got access to your servers.

And second, this will give you a *minor* performance boost because the secrets
don't need to be decrypted at runtime.

Now, you might be thinking:

> Wait, wait, wait Ryan: we went to *all* this trouble to encrypt our secrets,
> and now you want me to store them plaintext on production? Are you mad?

I never get mad! The truth is, your sensitive values are *never* fully safe on
production: there is always *some* way - called an "attack vector" to get them.
If someone gets access to the files on your server, then they would already have
your encrypted keys *and* the private key to decrypt them. Storing the secrets in
plaintext but *removing* the secret from production is really the same thing from
a security standpoint.

The point is: there's no security difference. Let's delete the `.env.prod.local`,
we don't need that locally.

## Secrets for the test Environment

The *third* interesting thing that we can do now that we understand that environment
variables override secrets relates to the `test` environment. Because... our
`test` environment is *totally* broken right now.

---> HERE

Think about it in the test environment, there is not
going to be a mailer, DSN environment variable set right now, but we can leverage the
new thing we learned to fix that. So first I'm gonna run over and say PHP bin /PHP
unit to run our tests

and

you can ignore the deprecation warnings. Whoa, huge doctors. And if you look at this,
look at this environment variable, not found mailer_D S N by the way, new fund feeder
and Symfony. Um, 4.4 is that because these air pages are hard to read and the test
environment dumps the exception as a comment on the top of the uh, HTML. And it also,
if I remember correctly, yup, dumps it on the bottom. So actually didn't need to
scroll up so far. I could've seen that right here. Love that. So there is no mailer
DSN in a value. And then in the environment variable in the, in the test environment,
the way to fix this is very straight forward. Put it in your dot and dot test file,
which is committed. So I'm actually going to use my old Knoll value. I'll copy it
from my dot N here, other DSN,

but that into dot M. dot test. So now in the test environment, there will be a real
environment variable, so it doesn't need to live in the secrets vault. So really when
you add a new in secret to the vault, you need to add it in the dev environment, the
prod environment, and put it into your dot M. dot test file so that it's in every
single environment. Let's run our tests with one more time and they pass. Beautiful.
Okay. That's it for the new vault feature. Let's just do a little cleanup on our
project here. I'm gonna remove the mailer DSN, a bind that I created, and then go
into our article controller and take out the mailer DSN stuff.

Okay,

perfect. Now our project is back to it. Next, let's talk about a really cool new
feature called auto mapping. And validation are really a smart system that
automatically adds validation constraints for you based on your doctor metadata. And
also the way your actual PHP code is written inside of your class.
