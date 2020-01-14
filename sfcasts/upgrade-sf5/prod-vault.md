# Production Secrets

Whenever you add a new secret, you need to make sure that you add it to the `dev`
environment *and* the `prod` environment. That's because each *set* of secrets,
or "vault" as I've been calling it, is specific to the *environment*. This vault
of secrets, for example, will *only* be loaded in the `dev` environment. So,
unless we *also* add `MAILER_DSN` to a `prod` vault, the `prod` environment will
be *totally* broken. And busted production environments are... a bummer.

## Creating the Production (prod) Vault

So, how *do* we add this key to a `prod` environment vault? With the same command
as before: `secrets:set`, but *this* time with `--env=prod`. We'll set the
`MAILER_DSN` secret:

```terminal-silent
php bin/console secrets:set --env=prod MAILER_DSN
```

I'll paste in my production SendGrid value... which you can't see because the
command hides the input to be safe.

Cool! *Just* like last time, because this is the first time we've added a key to
the `prod` vault it automatically *created* the vault for us, which just means
that it created the decrypt and encrypt keys.

## Production Encrypt & Decrypt Keys

Just like with the `dev` environment, the encrypt key file *is* safe to commit
to your repository. Heck, you could post it onto the Internet! It only gives people
the power to *add* things to your vault, which is probably something that you do
want any developer to be able to do.

But the decrypt key file should *not* be committed to the repository. It is
*incredibly* sensitive: it has the power to decrypt *all* of your production
secrets! We decided that it was *probably* ok to commit the `dev` decrypt key...
because the dev keys are probably not very sensitive, but you should *not* commit
this one. Or, if you do - just realize that everyone who has access to view files
in your repository will have access to all your secrets... and you might as well
just commit them as plain-text values.

We'll talk more about the decrypt key in a few minute.

Let's add the new vault files to git:

```terminal
git add config/
```

Then:

```terminal
git status
```

Oh! This did *not* add the private decrypt key. That's no accident: our `.gitignore`
file is *specifically* ignoring this. This line was added when we updated the
`symfony/framework-bundle` recipe.

## Listing & Revealing prod Secrets

Anyways, *just* like with the `dev` vault, we can list the secrets:

```terminal
php bin/console secrets:list --env=prod
```

And because my app *does* currently have the decrypt key, we can add `--reveal`
to see their values:

```terminal-silent
php bin/console secrets:list --env=prod --reveal
```

## Secrets are Committed

Ok, let's commit!

```terminal
git commit -m "Adding MAILER_DSN to prod vault"
```

Do you realize how awesome that was? We just *safely* committed our secrets to
the repository! They're version controlled, we can see that they were added on
a pull request and even see who and when they were added. That's a huge step!

## Deploying with the Decrypt Key

Now, instead of needing to figure our how and where to securely store *all* our
sensitive values so that we can add them to our app when we deploy, there is now
just *one* sensitive value: the decrypt key file.

When we deploy to production, the *only* thing we need to worry about now is
creating that decrypt file with this long value inside. How? That depends completely
on your deploy. For example, with SymfonyCloud - which is what we use - we set
the key that's inside of the decrypt file as a SymfonyCloud "variable". Then, on
build, we use that to re-create the private, decrypt file. Whoever or whatever is
responsible for deploying your app should be the *one*, um, "thing" that has access
to the private key.

## Seeing the prod Secret Value

Let's go make sure this whole `prod` vault thing works. Right now, if we refresh
the page, it still shows us the null value because we are *still* in the `dev`
environment.

Open up your `.env` file and, temporarily, change `APP_ENV` to `prod`. Then, find
your console and clear the cache:

```terminal
php bin/console cache:clear
```

I don't need to add `--env=prod` *now* because that is *already* the current
environment thanks to the `APP_ENV` change.

Ok, go try it! Refresh and... yes! That's the value from the `prod` vault! Symfony
automatically used the private key to decrypt it.

## And if the Decrypt Key is Missing?

What would happen if the decrypt key wasn't there? Let's find out! Temporarily
delete the decrypt key - but make sure you can get it back - if you lose this
key, you won't *ever* be able to decrypt your secrets and you'll need to create
a *new* private key and re-add them all again.

Refresh now to see... oh! Giant 500 page... but we can't see the error. Check
out the logs:

```terminal
tail var/log/prod.log
```

And... there it is:

> Environment variable not found: MAILER_DSN

Of you *don't* have the private key... bad things will happen. Let's go
*undelete* that private key file. Refresh: all better. Let's also change back to
the `dev` environment to make life nicer.

So... that's it! You have a `dev` vault and a `prod` vault, you can commit your
encrypted secrets via git and you only need to handle one sensitive value: the
private decrypt.

But... what if a developer needs to locally override a value in the `dev`
environment? For example, in our `dev` vault, `MAILER_DSN` uses the `null` transport
so that emails are *not* sent. What if I need to temporarily change Mailtrap so
that I can test the emails?

The answer: the "local" vault... a little bit of coolness that will open up a
couple of neat possibilities. That's next.
