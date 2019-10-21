# Console Email

Coming soon...

So we're in some of our new custom console command. We found all the authors that
want to receive a weekly update about the articles that they wrote during the week.
So we're iterating over the authors and they were finding all of the articles that
that author published each week. Sit down here. If they have published at least one
article, that's when we want to send them a nice little email. So if you download the
course code, you should have a `tutorial/` directory with an `inky/` email and one more
file inside called `author-weekly-report.html.twig`. I'm going to copy
that file name and then put that into our `templates/email/` directory. Now you check
this file out. This is already written in the inky markup, the markup that a inky
will translate into nice table layouts that will work in any email client. But mostly
this is just a static, uh, file. We do have a URL to the homepage down here. We are
printing out the name of the user. Um, but that's about it. In fact, we still need to
do some more work down here to actually make the articles themselves dynamic.

in fact, to actually get that uh, inky stuff applied, we actually need to add the
filter. So I'm going to go into `welcome.html.twig` and I'm gonna steal the
apply from there. And then inside here I will paste that. So that's going to
transform all this markup, an inky and in line both of those CSS files, so down all
in the bottom. Then I'll say and apply and I'll just indent everything in so it looks
nice.

okay, so we're ready to send this email. Let's go back into our command and we
already know how to start. It's just `$email = new ...` and because we're going to use a
template for this

`$email = (new TemplatedEmail())` and they'll just start setting the data on that. And
actually for the `->from()`, I'm going to cheat here and go back into our 
`src/Controller/SecurityController.php` and find my `register()` method. I'm going 
to use the same from as
before. Our `alienmailer@example.com` and the name is `'The Space Bar'`. This is
actually the first bit of duplication that we're going to have between these two
emails. Probably all of your emails are going to be from the same address and you're
not really going to want to be duplicating this everywhere across your site on all
emails. So that's something that we are going to work on in a little bit. I'm going
to read type the S on named address and hit tab a so that it as the use statement on
topless file for me. Now for the two, once again I could just put an email address
here but instead I'm gonna say `new NamedAddress()` cause that allows me to put an email
and a name and we'll say `$author->getEmail()`. And then for the second argument the name
will say `$author->getFirstName()` and then it's just the normal email. So normal
information `->subject('Your weekly report on The Space Bar!')`.

Now for the age, for the actual content, we'll use the `->htmlTemplate()` trick and we'll
say `email/author-weekly-report.html.twig` and honest thing is
we need to pass it as any variables we need to pass in. Now, right now the only
variable we're using is the email variable, um, which is uh, which, which the email
system gives us for free. But we are going to need to pass on that at least one of
the variables. So I'm going to say `->context([])` And we know that
we're going to need to pass in. Let's pass on the `$author` object just in case we need
it. And the big thing we needed is we need to pass any `$articles` so we can print
information about those articles. And that's it. That was a beautiful uh, email
object. How do we send that email? Well that's nothing new and we need the mailer. So
we need to auto wire the mailer service. So I'm gonna the top of the class here and
I'll say `MailerInterface $mailer`, add that as an argument. Put initialize fields to
create that property and set it. And finally down here we will say 
`$this->mailer->send($email)`. Love that.

Yeah.

Okay. So let's just see if this works. And actually we'll see. We have some data
fixtures in here that actually load some user information
and you can see about 75% of the time he's in faker a, they're going to subscribe to
the newsletter. So just by chance we will hopefully have a couple of users in our
database that will already be subscribed to the, uh, to receive this. So let's try it
then. 

```terminal
php bin/console app:author-weekly-report:send
```

and okay.
It looks like that sent eight emails pretty quick. Let's go over to here and they're
not there. Oh, and you know why this is actually, we should, we can do this a little
bit better here.

That'll be,

it's actually a little, add a little `$io->note()`
Oh, I mean, no, if it's actually just skipping cause it has no articles. So let's run
that again and yup. In fact Yeah.

So let's move over and I'm actually going to make sure my fixtures are nice and
fresh. I'll say 

```terminal
php bin/console doctrine:fixtures:load
```

We'll make sure that all
of our, we have some articles that are fresh in the database because our fixtures
will make, always make sure that there are some articles that are recent and then
let's run our `app:author-weekly-report:send` again 

```terminal-silent
php bin/console app:author-weekly-report:send
```

and it looks like six authors
were sent emails. Now we might not get six emails because it only means there are six
authors that subscribe to the newsletter. They might not all have articles, so some
of them might have exited, but hopefully we got at least one email sent and you did
exactly one email. So if you didn't see any emails, try reloading your fixtures again
until you get one that it actually shows up. And there it is. Now you'll notice that
there is actually a couple of problems here. One of them is that our image is missing
and the other one is that this link down here is broken. You can look in the bottom
left. It just says local host. We're going to talk about that in a second, but there
is actually a path problem happening and you can see it in the age to most source if
you scan for a while past all the ugly inline styles if you searched for

[inaudible].

So yeah, the link at the bottom is the right, we're going to talk about that a
second.

The other problem is that this kind of is missing the footer in the head or we had
before. If we look at one of our previous emails, so this really nice like header
with an image and this foot are down here and that's probably stuff that we're going
to want repeated, uh, in every single email. Um, and the reason it's missing is very
simple. If you look at the welcome template, it had the header up here with the logo
down here on the bottom. It had that nice footer down here and in the author report
and weekly, it didn't have any of that. It just jumps straight to the content of the
email. And we did that on purpose. Um, because I don't want to duplicate all that
stuff to fix that. We couldn't just copy a the header and footer into author weekly
report, but of course we know that's probably not a good idea cause it's all going to
be duplicated. So the answer is to do something we've done in tweak templates since
the beginning of time. Create a base layout. So in the `email/` directory, under brand
new file called, how about `emailBase.html.twig`?

And what I'm going to do inside of here is close a few files. I'm going to go over to
my `email/welcome.html.twig`, I'm going to copy that entire template paisan
to email base and then we're going to keep the footer stuff but the entire middle of
this. So basically what we're going to have here is we're going to have the
container, the class header and the class footer and the class bottom. And in the
middle here I'm going to say `{% block content %}{% endblock %}`

So this forms our, our layout. Now thanks to this and `welcome.html.twig`. We can
delete almost everything.

Nothing that I have

so does that welcome. We can delete all the key stuff here actually want to start
with is our normal `{% extends 'email/emailBase.html.twig' %}` and it's just a really
simple template. I don't need to have the apply and key filter anymore because this
content is going to be instead replace that with `{% block content %}` and then 
`{% endblock %}`.

We don't need that film, that filtered stuff anymore because all the contents out of
here is going to be put into the block content which is inside of that same filter.
Now of course we can also delete most of the content here. All we really need is the
row welcome and then down the bottom we can get rid of the class foot or cop stuff
and I'm also going to unindexed this so it looks a little bit better. Perfect. Oh
man, I've got an extra email in my temple up there. We can do the same thing over and
`author-weekly-report.html.twig`. We will `{% extends 'email/emailBase.html.twig' %}`

`{% block content %}` and all that in the bottom. We'll do `{% endblock %}`
and then we can simplify things. So I'll take off that container. This guys, the rest
of it already is actually the content we want of the email. So I'll also uninvent
that one as well.

all right, so let's try this. Easiest way to try it is to send my author weekly
report, 

```terminal-silent
php bin/console app:author-weekly-report:send
```

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