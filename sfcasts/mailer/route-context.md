# Router Request Context: Fix Paths in the CLI

We sent the email, but it's missing its core content: info about the articles
that each author wrote last week. That's no problem for us: we're already passing
an `articles` variable to the template via `context()`. In the template, replace
the `<tr>` with `{% for article in articles %}`: 

[[[ code('8cbb5d987a') ]]]

add the `<tr>`, a `<td>` and print some data: `{{ loop.index }}` 
to number the list, 1, 2, 3, 4, etc, `{{ article.title }}` and finally, 
how about: `{{ article.comments|length }}`.

[[[ code('4d375d3efe') ]]]

That's good enough. Double check that by running the command:

```terminal-silent
php bin/console app:author-weekly-report:send
```

And... in Mailtrap... we are good.

## Why is the Link Broken

*Now* let's turn to the glaring, horrible bug in our email! Ah! As I mentioned a
few minutes ago, if you hover over the link its, gasp, broken! For some reason,
it points to `localhost` not our *real* domain... which is `localhost:8000`. Close,
but not right.

Hmm. In the template... yea... that looks right: `{{ url('app_homepage') }}`.
Ok, then why - when we click on the link - is it broken?

[[[ code('d899eda6a7') ]]]

We know that the `url()` function tells Symfony to generate an *absolute* URL.
And... it *is*. I'll run "Inspect Element" on the broken link button. Check out
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
or `lolcats.com`. So, it just guesses `localhost`... which is *totally* wrong...
but probably better than guessing `lolcats.com`?

If you're sending emails from the command line - or rendering templates for *any*
reason that contain paths - you need to help Symfony: you need to *tell* it
what domain to use.

## Setting router.request_context

To fix this, start by looking inside our `.env` file. One of our keys here is called
`SITE_BASE_URL`. 

[[[ code('3522bc679b') ]]]

*It* is the URL to our app. But, but, but! This is *not* a standard
Symfony environment variable and Symfony is *not* currently using this. Nope, this
is an environment variable that *we* invented in our file uploads tutorial for a
totally different purpose. You can see it used in `config/services.yaml`. It has
*nothing* to do with how Symfony generates URLs.

*Anyways*, to fix the path problem, you need to set two special parameters. The
first is `router.request_context.scheme`, which you'll set to `https` or `http`.
The other is `router.request_context.host` which, for our local development, will
be `localhost:8000`.

***TIP
In Symfony 5.1, instead of setting these 2 parameters, you can set 1 new piece of config:

```yml
# config/packages/routing.yaml
framework:
    router:
        # ...
       default_uri: 'https://example.org/my/path/'
```
***

Now obviously, we don't want to hardcode these - at least not the second value:
it will be different on production. Instead, we need to set these as new
environment variables. And... hey! In `.env`, the `SITE_BASE_URL` is *almost*
what we need... we just need it to be kind of split into two pieces. Hmm.

Check this out, create two new environment variables: `SITE_BASE_SCHEME` set to
`https` and `SITE_BASE_HOST` set to `localhost:8000`. 

[[[ code('356162f44f') ]]]

Back in `services.yaml`, use these values: `%env(SITE_BASE_SCHEME)%` 
and `%env(SITE_BASE_HOST)%`

[[[ code('414154c720') ]]]

Cool!

## Using Environment Variables... in Environment Variables

The problem is that we now have some duplication. Fortunately, one of the
properties of environment variables is that... um... they can contain environment
variables! For `SITE_BASE_URL`, set it to `$SITE_BASE_SCHEME` - yep, that's legal -
`://` and then `$SITE_BASE_HOST`.

[[[ code('3a4b5c4236') ]]]

I *love* that trick. Anyways, now that we've set those two parameters, Symfony
will use *them* to generate the URL instead of trying to guess it.

***TIP
This works, but if you need to override the scheme or host in `.env.local`, you would
also need to repeat the `SITE_BASE_URL=` to set it again. A better solution would
be to set the `SITE_BASE_URL` just once using a config trick in `services.yaml`:
```
parameters:
    env(SITE_BASE_URL): '%env(SITE_BASE_SCHEME)%://%env(SITE_BASE_HOST)%'
```
***

Try the command one last time:

```terminal-silent
php bin/console app:author-weekly-report:send
```

And... check it out in Mailtrap! Yes! *This* time the link points to
`localhost:8000`.

Next! Let's talk about attaching files to an email. Hmm, but to make it more
interesting, let's *first* learn how to generate a styled PDF.
