# Router Request Context: Fix Paths in the CLI

move back over. There it is and yes you can see it ascending with that nice layout

[inaudible]

so let's actually make this stuff dynamic here. Now as a reminder, we're already
sending in an `$articles` variable, so this is actually now going to be very, very easy.
I'm just gonna get rid of this `<tr>` here, say `{% for article in articles %}` and for
`<tr>` here we'll say, how about `{{ loop.index }}` That's a nice old secret there.
It's ITR, not for the first TD. We'll say loop that index and those just give us a nice little
number and then `{{ article.title }}` and finally we can do a little `{{ article.comments|length }}`
to below preview there. All right, so I'll spin back over.

```terminal-silent
php bin/console app:author-weekly-report:send
```

It would have
been console to run that report and cool. There it is. 11 comments and we're good to
go. If you want to get extra fancy, we get to actually turn this into a link, but
actually we already have a link and the link is broken, so if you look inside of
here, we do have a link down here to the homepage using `{{ url('app_homepage') }}`
But if I click on that link, it just goes to localhost

Now we know that the,

we know that the `url()` function here tells Symfony to generate this as an absolute URL.
But if you look in the H, if you look actually do inspect element on that link here,
you're going to see that in this actually `href="http://localhost` not `localhost:8000`.
And the same would happen if this were on production would say `localhost` instead of
you know, your real domain.com the issue is that when you run it, the way that
Symfony figures out what your apps, what the domain name is of your site is normally
like if we fill out the registration form, when, when we submit the registration
form, Symfony looks at what the URL is that's being submitted from and uses that as
the domain name. When you run a console, command Symfony actually has idea what
domain name the site is. And so it just guesses `localhost`, which of course is wrong.
So that's the trick with sending, um, and you're sending
uh, emails with a console command is you need to worry about that. So the way to fix
this is a little bit of configuration now first in the `.env` file, one of the
things that we already have configured on our site.

Okay.

And this is for a totally different reason. Uh, we already have this `SITE_BASE_URL`
environment variable that we've defined for our actual application. I'll show you if
you go to `config/services.yaml` this is actually something that we use for our upload
functionality of our site. It tells us kind of like where they uploads actually
exists. So it has nothing to do with Symfony. This is just something that we decided
to have. Now one of the things that you can do is to fix this problem by uploading is
you can actually tell Symfony what your domain specifically is. Now to do that,
you're going to say it router, that you have to set up a special parameter called
`router.request_context.scheme`. And you're gonna set that to something like
`https`. And then there's another one called `router.request_context.host`

And you'll set that to, in our case, something like the `localhost:8000` or
whatever your domain name is. Now obviously we don't want to hard code those in here.
And really we already have an environment variable that has that information kind of
mixed up into it. So here's what we can do. We can create two new environment
variables. We don't call it `SITE_BASE_SCHEME`. And we'll start that.
The `https` make another one called `SITE_BASE_HOST` equal to `localhost:8000`
now the cool thing about these is that we can use these inside of
`services.yaml` for those values. So instead of hard footing H to BS here you can say
`%env(SITE_BASE_SCHEME)%` And then the
same thing down here for below we can say for the host we can say `%env(SITE_BASE_HOST)%`

Cool.

Now the only problem with this setup is that we do have some duplication. Now like
segues URL is duplicated with these types of things. So to fix that, we can actually
leverage a nice little trick with environment variables, which is actually, um, we
can reference environment variables in here. So we replace `https` with
`$SITE_BASE_SCHEME` that is legal and `://` and then `$SITE_BASE_HOST`
So basically broken or `SITE_BASE_URL` are on the
smaller pieces and use that to set these to kind of magic, very important variables
that need parameters need to be set. And this is actually what Symfony uses when it
generates absolute you where else? So now when we spin back over and run our console
command, let's move back over and go to MailTrap and this time asks `localhost:8000`
at this pointing to our correct URL. Got next. Let's do some attachments.