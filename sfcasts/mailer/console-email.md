# Using a Base Email Template

We found all the authors that want to receive an update about the articles
they wrote during the last 7 days. Now, let's *send* them that update as an email.

If you downloaded the course code, you should have a `tutorial/` directory with an
`inky/` directory and a file inside called `author-weekly-report.html.twig`. Copy
that and throw it into `templates/email/`.

[[[ code('6413fbfadf') ]]]

Nice! This template is already written using the Inky markup: the markup that Inky
will translate into HTML that will work in any email client. But mostly, other
than a link to the homepage and the user's name, this is a boring, empty email:
we still need to print the core *content* of the email.

## Designing, Configuring & Sending that Email

Let's open up `welcome.html.twig`, steal the `apply` line from here, and paste it
on top of the new template. This will translate the markup to Inky *and* inline
our CSS. At the bottom, add `endapply`... and I'll indent everything to satisfy
my burning inner need for order in the universe!

[[[ code('2cf07ef0ef') ]]]

To *send* this email, we know the drill! In the command, start with
`$email = (new TemplatedEmail())`, `->from()` and... ah: let's cheat a little.

[[[ code('063efee3c4') ]]]

Go back to `src/Controller/SecurityController.php`, find the `register()` method
and copy *its* `from()` line: we'll probably always send *from* the same user.
And yes, we'll learn how *not* to duplicate this later. I'll re-type the
"S" on `NamedAddress` and hit tab to add the missing `use` statement on top.

[[[ code('d646cfb25a') ]]]

Ok, let's finish the rest: `->to()` with `new NamedAddress()`
`$author->getEmail()` and `$author->getFirstName()`,

[[[ code('a383b8b3c3') ]]]

`->subject('Your weekly report on The Space Bar!')` and

[[[ code('36d52ece7c') ]]]

`->htmlTemplate()` to render `email/author-weekly-report.html.twig`.

[[[ code('0d7aa70fc5') ]]]

Do we need to pass any variables to the template? *Technically*... no: the only
variable we're using so far is the built-in `email` variable. But we *will* need
the articles, so let's call `->context([])`. Pass this an `author` variable...
I'm not sure if we'll actually need that... and the `$articles` that this author
recently wrote.

[[[ code('ddfe1deac6') ]]]

Done! Another beautiful `Email` object. We're a machine! How do we send it? Oh,
we know that too: we need the mailer service. Add a *third* argument to the
constructor: `MailerInterface $mailer`. I'll do our usual Alt+Enter trick and
select "Initialize Fields" to create that property and set it.

[[[ code('9fee46579d') ]]]

Back down below, give a co-worker a serious "nod"... as if you're about to take
on a task of great gravity... but instead, send an email:
`$this->mailer->send($email)`.

[[[ code('88a060774a') ]]]

Love that. In our fixtures, thanks to some randomness we're using, about 75% of
users will be subscribed to the newsletter. Before we run the command, let's make
sure the data is fresh... with recent article created dates. Run:

```terminal
php bin/console doctrine:fixtures:load
```

This *should* add enough users and articles that about 1-2 authors will be subscribed
to the newsletter *and* have recent articles. Try that command:

```terminal-silent
php bin/console app:author-weekly-report:send
```

Ha! It didn't explode! It found 6 authors... or really, 6 users that
are subscribed to the newsletter... but anywhere from 0 to 6 of these might
*actually* have recent articles. Spin over to Mailtrap. If you *don't* see any
emails - try reloading the fixtures again... just in case you got some bad random
data, then re-run the command. Oh, and if you got an error when running the command
about too *many* emails being sent, you've hit a limit on Mailtrap. The free plan
only allows sending 2 emails each 10 seconds. In that case, ignore the error - because
two emails *did* send - or reload your fixtures to hopefully send less emails.

We have exactly *one* email: phew! So... we *rock*! Or do we?

I see a few problems. First, the link to the homepage is broken: it links to
`localhost`. *Not* `localhost:8000` - or whatever our *real* domain is - just
`localhost`. When you send emails from a console command... your paths break.
More on that later.

## Base Email Template

The second problem is more obvious... and it's my fault: this email is missing
the cool header and footer we had in the other email! Why? Simple: in
`welcome.html.twig`, we have a header with a logo on top and a footer at the bottom.
In `author-weekly-report.html.twig`? I forgot to put that stuff!

Ok, I *really* did it on purpose: we probably *do* want a consistent layout for
every email... but we definitely do *not* want to duplicate that layout in *every*
email template.

We know the fix! We do it *all* the time in normal twig: create a base template,
a base *email* template. In the `templates/email` directory, add a new file
called, how about `emailBase.html.twig`.

And... I'll close a few files. In `welcome.html.twig`, copy that *entire* template
and paste in `emailBase`. Then... select the *middle* of the template and delete!
We basically want the header, the footer and, in the middle, a block for the
content. Add `{% block content %}{% endblock %}`.

[[[ code('099c3cde96') ]]]

That block name could be anything. Now that we have *this* nifty template, back
in `welcome.html.twig`, life gets simpler. On top, start with
`{% extends 'email/emailBase.html.twig' %}`. Then, delete the `apply` and `endapply`,
and replace it with `{% block content %}`... and `{% endblock %}`.

[[[ code('6f231377b0') ]]]

If you're wondering why we don't need the `inky_to_html` and `inline_css` filter
stuff anymore, it's because the contents of this template will be put into a block
that is *inside* of those same filters. The content *will* go through those filters...
but we don't need to worry about adding them in *every* template.

Now we can delete most of the content: all we really need is the welcome row...
and down below, we can get rid of the bottom and footer stuff. Celebrate
your inner desire for order by *un-indenting* this.

Perfecto! Repeat this beautiful code in `author-weekly-report.html.twig`:
`{% extends 'email/emailBase.html.twig' %}`, `{% block content %}` and *all* the
way at the bottom, `{% endblock %}`. We can also remove the `container` element...
and unindent.

[[[ code('1ac75ed0ce') ]]]

That felt *great*! Let's see how it looks: run our weekly report:

```terminal-silent
php bin/console app:author-weekly-report:send
```

And... move back over! Woo! Now *every* email can easily share the same "look".

Next, let's finish the email by making it dynamic. *And*, most importantly, let's
figure out why our link paths are broken. You need to be extra careful when you
send an email from the command line.
