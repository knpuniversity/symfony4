# Secrets Management Setup

My favorite new feature in Symfony 4.4 and 5 - other than the fact that Messenger
and Mailer are now stable - is probably the new secrets management system, which
*is* as cool as it sounds.

## Secrets?

Here's the deal: every app has a set of config values that need to be different
from machine to machine, like different on my local machine versus production.
In Symfony, we store these as environment variables.

One example is `MAILER_DSN`. While developing, I want to use the `null` transport
to *avoid* sending real emails. But on production, this value will be different,
maybe pointing to my SendGrid account.

We reference environment variables with a special syntax - this one is in
`config/packages/mailer.yaml`: `%env()%` with the variable name inside: `MAILER_DSN`.

If you look at the full list of environment variables, you'll notice that there
are two types: sensitive and non-sensitive variables.

For example, `MAILER_DSN` is a "sensitive" variable because the production value
probably contains a username & password or API key: something that, if someone
got access to it, would allow them to use our account. So, it's not something that
we want to commit to our project.

But other values are *not* sensitive, like `WKHTMLTOPDF_PATH`. This might need to
be *different* on production, but the value is not sensitive: we don't need to keep
it a secret. We *could* actually commit its production value somewhere in our app
to make deployment easier if we wanted to.

So... why are we talking about this? Because, these sensitive, or "secret"
environment variables make life tricky. When we deploy, we need to *somehow* set
the `MAILER_DSN` variable to its secret production value, either as a real
environment variable or probably by creating a `.env.local` file. Doing that safely
can be tricky: do you store the secret production value in a config file in
this repository or in some deploy script? You can, but then it's not very secure:
the less people that can see your secrets - even people on your team - the better.

## The Vault Concept

One general solution to this problem is something called a vault. The basic idea
is simple: you encrypt your secrets - like the production value for `MAILER_DSN` -
and then store the *encrypted* value. The "place" where the encrypted secrets are
stored is called the "vault". The secrets inside can only be *read* if you have
the decryption password or "private key".

This makes life easier because now your secrets can safely be stored in this
"vault", which can just be a set of files on your filesystem or even a cloud
vault service. Then, when you deploy, the only "secret" that you need to have
available is the password or private key. Some vaults also allow other ways
to authenticate.

## Introducing Symfony's Secrets "Vault"

None of this "vault" stuff has anything to do with Symfony: it's just a cool concept
and there are various services & projects that support the idea - the most
famous being HashiCorp's Vault.

But, in Symfony 4.4, a new secrets system was added to let us do *all* this cool
stuff out-of-the-box.

Here's the goal: instead of having `MAILER_DSN` as an environment variable, we're
going to move this to be an "encrypted secret".

## Dumping an Env Var for Debugging

To see how this all works clearly, let's add some debugging code to dump the
`MAILER_DSN` value. Open `config/services.yaml` and add a new bind - `$mailerDsn`
set to `%env(MAILER_DSN)%` - so we can use this as an argument somewhere. I forgot
my closing quote... which Symfony will "gently" remind me in a minute.

Next, open `src/Controller/ArticleController.php`. In the homepage action,
thanks to the bind, we can add a `$mailerDsn` argument. Dump that and die.
Now, refresh the homepage. Booo. Let's go fix my missing quote in the YAML
file. Refresh again and... perfect: the current value is `null://null`.

That's no surprise: that's the value in `.env` and we are *not* overriding it
in `.env.local`.

## Converting an Env Var to a Secret

Ok, as *soon* as you have an environment variable that you want to *convert* to
a secret, you need to fully *remove* it as an environment variable: do *not* set
it as an environment variable anywhere anymore. I'll remove `MAILER_DSN` from
`.env` and if we *were* overriding it in `.env.local`, I would also remove
it from there.

Not surprisingly, when you refresh, we're greeted with a great big ugly error:
the environment variable is not found.

## Bootstrapping the Secrets Vault

So how *do* we make `MAILER_DSN` an encrypted secret? With a fancy new console
command:

```terminal
php bin/console secrets:set MAILER_DSN
```

That will ask us for the value: I'll go copy `null://null` - you'll learn why I'm
choosing that value in a minute - and paste it here. You don't see the pasted
value because the command hides the input to be safe.

## The Public/Encryption & Private/Decryption Keys

Hit enter and... awesome! Because this was the *first* time we added something
to the secrets vault, Symfony needed to *create* the vault - and it did that
automatically. What does that *actually* mean? It means that it created several
new files in a `config/secrets/dev` directory.

Let's go check them out: `config/secrets/dev`. Ooooo.

To "create" the secrets vault, Symfony created two new files, which represent
"keys": a private *decrypt* key and a public *encrypt* key. If you look inside,
they're just fancy text files: they return a long key value.

The public encrypt file is something that *is* safe to commit to your repository.
It's used to *add*, or "encrypt" a secret, but it can't *read* encrypted secrets.
By committing it, other developers can add new secrets.

The private decrypt key - as its name suggests - is needed to decrypt and *read*
secrets.

## One set of Secrets per Environment

Now *normally*, the "decrypt" key is *private* and you would *not* commit it to
your repository. However, as you may have noticed, Symfony maintains a different
set of secrets per *environment*. The vault *we* created is for the `dev`
environment only. In the next chapter, we'll create the vault for the `prod`
environment.

Anyways, because secrets in the `dev` environment usually represent safe "defaults"
that aren't terribly sensitive, it's ok to commit the private key for the
`dev` environment. Plus, if you *didn't* commit it, other developers on your team
wouldn't be able to run the app locally... because Symfony wouldn't be able to
read the dev secrets.

## Committing the dev Keys

Let's add these to git:

```terminal
git status
```

Then `git add config/secrets` and also add `.env`:

```terminal-silent
git add config/secrets .env
```

This added *all* 4 files. The other two files store info about the secrets
themselves: each secret will be stored in its own file and the "list" file just
helps us get the full list of secrets that exist. Commit this:

```terminal
git commit -m "setting up dev environment vault"
```

## %env()% Automatically Looks for Secrets

And *now* I have a pleasant surprise: go over and refresh the homepage. It works!
That's by design: the `%env()%` syntax is smart. It *first* looks for a `MAILER_DSN`
environment variable. If it finds one, it uses it. If it does *not*, it *then*
looks for a `MAILER_DSN` secret. *That's* why... it just works.

## bin/console secrets:list

To get a list of *all* the encrypted secrets, you can run:

```terminal
php bin/console secrets:list
```

Yep - just one right now. Add `--reveal` to see the values. By the way, this
"reveal" *only* works because the decrypt file exists in our app.

Next: our app will *not* currently work in the `prod` environment because there
is no `prod` vault and so no `MAILER_DSN` `prod` secret. Let's fix that and
talk a bit about deployment.
