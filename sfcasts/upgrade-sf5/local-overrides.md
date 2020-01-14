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
secret locally by creating an environment variable. The other two deal with
a performance optimization on production *and*... or test environment... which
is currently broken. That's next.
