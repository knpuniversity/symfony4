# Overriding Secrets Locally (Local Vault)

What if I need to override a secret value on my local machine? `MAILER_DSN` is a
perfect example: in the `dev` secrets vault, it's set to use the `null` transport.
What if I want to see what an email *looks* like... and so I need to override that
value locally to send to Mailtrap?

Well, we *could* run over to the terminal and say:

```terminal
php bin/console secrets:set MAILER_DSN
```

And then modify the vault value. But... ugh - then I have to be *super* careful
not to commit that change... and eventually... I will on accident... and I'll look
super uncool because I accidentally changed a development secret.
*Fortunately*, for absent-minded committers like me, there's a built-in solution
to help!

## Setting a Secret into the "Local" Vault

Pretend like we're going to override the `MAILER_DSN` secret... but add an
extra `--local` flag to the end:

```terminal-silent
php bin/console secrets:set MAILER_DSN --local
```

So far... this looks identical to before. I'll paste in my Mailtrap value... which
the command hides for security reasons. And... *fascinating*! This didn't change
our `dev` vault at all! Nope, it apparently added the secret to `.env.dev.local`.

Quick review about `.env` files: Symfony allows you to create a `.env.local` file
as a way to override values in `.env`. And thanks to our `.gitignore`,
`.env.local` is ignored from Git. And, though it's not as common, you can *also*
create a `.env.dev.local` file. It works the same way: it overrides `.env` and
isn't committed. The only difference - which is super minor - is that it's
*only* loaded in the `dev` environment.

The point is: this "local" vault thing is nothing more than a fancy way of
setting an environment variable to this "local" file.

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

And, personally, I don't love having `.env.local` *and* `.env.dev.local` - it seems
like overkill. So I would delete `.env.dev.local` and instead put my overridden
`MAILER_DSN` directly into `.env.local`.

But... don't do that - delete the override entirely: it'll help me show you one
more thing.

Now that we understand that environment variables override secrets, we can
unlock three possibilities. The first is what we just saw: we can override a
secret locally by creating an environment variable. The other two deal with
a performance optimization on production *and*... our test environment... which
is currently busted! That's next.
