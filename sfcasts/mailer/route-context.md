# Router Request Context: Fix Paths in the CLI

We've sent the email, but it's missing its core content: info about the articles
that each author wrote last week. That's no problem for us: we're already passing
an `articles` variable to the template via `context()`. In the template, replace
the `<tr>` with `{% for article in articles %}`, add the `<tr>`, a `<td>` and
print some data: `{{ loop.index }}` to number the list, 1, 2, 3, 4, etc,
`{{ article.title }}` and finally, how about: `{{ article.comments|length }}`.

That's good enough. Double check that by running the command:

```terminal-silent
php bin/console app:author-weekly-report:send
```

And... in Mailtrap... we're good.

## Why is the Link Broken

*Now* let's turn to the glaring, horrible bug in our email! Ah! As I mentioned a
few minutes ago, if you hover over the link its, gasp, broken! For some reason,
it points to `localhost` not our *real* domain... which is `localhost:8000` - close,
but not right.

Hmm. In the template... yea... that looks right: `{{ url('app_homepage') }}`.
Ok, then why - when we click on the link - is it broken!

We know that the `url()` function tells Symfony to generate an *absolute* URL.
And... it *is*.  I'll run "Inspect Element" on the broken link button. Check out
the `href`: `http://localhost` *not* `localhost:8000`. The *same* thing would
happen if you deployed this to production: it would *always* say `localhost`.
The URL *is* absolute... it's just wrong!

Why? Think about it: in the registration email - where this *did* work - how did
Symfony know what our domain was when it generated the link? Did we configure that
somewhere? Nope! When you submit the registration form, Symfony simply looks at
what the *current* domain is - `localhost:8000` - and uses *that* for all absolute
URLs.

But when you're in a console command, there is no request! Symfony has *no* idea
if the code behind this site is deployed to `localhost:8000`, `example.com`,
or `????NEED FUN DOMAIN HERE`. So, it just guesses `localhost`... which is *totally*
wrong.

If you're sending emails from the command line - or rendering templates for *any*
reason that need absolute URLS - you need to help Symfony: you need to *tell* it
what domain to use.

## Setting router.request_context

To fix this, start by looking inside our `.env` file. One of our keys here is called
`SITE_BASE_URL` and it's the URL to our app. But, but, but! This is *not* a standard
Symfony environment variable and Symfony is *not* currently using this. Nope, this
is an environment variable that *we* invented in our file uploads tutorial for a
totally different purpose. You can see it used in `config/services.yaml`. It has
*nothing* to do with Symfony.

*Anyways*, to fix the path problem, you need to set two special parameters. The
first is `router.request_context.scheme`, which you'll set to `https` or `http`.
The other is `router.request_context.host` which, for our local development, will
be `localhost:8000`.

Now obviously, we don't want to hardcode these - at least not the second value:
it will be different on production. Instead, we'll need to set these as a new
environment variable. And... hey! In `.env`, we already have one... except that
we kind of need to split it into two pieces. Hmm.

Check this out, create two new environment variables: `SITE_BASE_SCHEME` set to
`https` and `SITE_BASE_HOST` set to `localhost:8000`. Back in `services.yaml`,
use these values: `%env(SITE_BASE_SCHEME)%` and `%env(SITE_BASE_HOST)%`

Cool!

## Using Environment Variables... in Environment Variables

The only problem with this setup is that we *do* have some duplication. Fortunately,
one of the properties of environment variables is that... um... they can contain
environment variables! For `SITE_BASE_URL`, set it to `$SITE_BASE_SCHEME` - yep,
that's legal - `://` and then `$SITE_BASE_HOST`.

I *love* that trick. Anyways, now that we've set those two parameters, Symfony
will use *them* to generate the URL instead of trying to guess it. Try the command
one last time:

```terminal-silent
php bin/console app:author-weekly-report:send
```

And... check it out in Mailtrap! Yes! *This* time the link points to
`localhost:8000`.

Next! Let's talk about attaching files to an email. Hmm, but to make it more
interesting, let's *first* learn how to generate a styled PDF.
