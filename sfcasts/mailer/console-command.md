# Let's Make a Console Command!

We've created exactly *one* email... and done some pretty cool stuff with it.
Let's introduce a *second* email... but with a twist: instead of sending this
email when a user does something on the site - like register - we're going to send
this email from a console command. And that... changes a few things.

Let's create the custom console command first. Here's my idea: one of the fields
on `User` is called `$subscribeToNewsletter`. In our pretend app, if this field
is set to true for an *author* - someone that *writes* content on our site - once
a week, via a CRON job, we'll run a command that will email them an update on what
they published during the last 7 days.

## Making the Command

Let's bootstrap the command... the lazy way. Find your terminal and run:

```terminal
php bin/console make:command
```

Call it `app:author-weekly-report:send`. Perfect! Back in the editor, head to the
`src/Command` directory to find... our shiny new console command. Let's start
customizing this: we don't need any arguments or options... and I'll change the
description:

> Send weekly reports to authors.

The *first* thing we need to do is find *all* users that have this
`$subscribeToNewsletter` property set to `true` in the database. To keep our code
squeaky clean, let's add a custom repository method for that in `UserRepository`.
How about `public function findAllSubscribedToNewsletter()`. This will return
an `array`. Inside, return `$this->createQueryBuilder()`, `u` as the alias,
`->andWhere('u.subscribeToNewsletter = 1')`, `->getQuery()` and `->getResult()`.
Above the method, we can advertise that this *specifically* returns an array of
`User` objects.

## Autowiring Services into the Command

Back in the command, let's autowire the repository by adding a constructor. This
is one of the *rare* cases where we have a parent class... and the parent class
has a constructor. I'll go to the Code -> Generate menu - or Command + N on a Mac -
and select "Override methods" to override the constructor.

Notice that this added a `$name` argument - that's an argument in the parent
constructor - and it *called* the parent constructor. That's important: the parent
class needs to set some stuff up. But, we don't need to pass the command name:
Symfony already gets that from a static property on our class. Instead, make the
first argument: `UserRepository $userRepository`. Hit Alt + Enter and select
"Initialize fields" to create that property and set it. Perfect.

Next, in `execute()`, clear *everything* out except for the `$io` variable, which
is a nice little object that helps us print things and interact with the user...
in a pretty way. Start with
`$authors = $this->userRepository->findAllSubscribedToNewsletter()`.

Well, this really returns *all* users... not just authors - but we'll filter them
out in a minute. To be extra fancy, let's add a progress bar! Start one with
`$io->progressStart()`. Then, foreach over `$authors as $author`, and advance
the progress inside.

Oh, and of course, for `progressStart()`, I need to tell it how *many* data
points we're going to advance. Use `count($authors)`. Leave the inside of the
`foreach` empty for now, and after, say `$io->progressFinish()`. Finally, for a
big happy message, add `$io->success()`

> Weekly reports were sent to authors!

Brilliant! We're not *doing* anything yet... but let's try it! Copy the command
name, find your terminal, and do it!

```terminal
php bin/console app:author-weekly-report:send
```

Super fast!

## Counting Published Articles

Inside the `foreach`, the next step is to find all the articles this user published -
if any - from the past week. Open up `ArticleRepository`... and add a new method
for this - `findAllPublishedLastWeekByAuthor()` - with a single argument: the `User`
object. This will return an `array`... of articles: let's advertise that above.
The query itself is pretty simple: `return $this->createQueryBuilder()` with
`->andWhere('a.author = :author)` to limit to only *this* author - we'll set the
`:author` parameter in a second - then `->andWhere('a.publishedAt > :week_ago')`.
For the placeholders, call `setParameter()` to set `author` to the `$author`
variable, and `->setParameter()` again to set `week_ago` to a
`new \DateTime('-1 week')`. Finish with the normal `->getQuery()` and `->getResult()`.

Boom! Back in the command, autowire the repository via the *second* constructor
argument: `ArticleRepository $articleRepository`. Hit Alt + Enter to initialize
that field.

Down in execute, we can say
`$articles = $this->articleRepository->findAllPublishedLastWeekByAuthor()`
and pass that `$author`.

Phew! Because we're actually querying for *all* users, not everyone will be an
author... and even less will have authored some articles in the past 7 days.
Let's skip those to avoid sending empty emails: if  `count($articles)` is zero,
then `continue`.

By the way, in a real app, where you would have hundreds, thousands or even more
users, querying for *all* that have subscribed is *not* going to work. Instead,
I would make my query smarter by *only* returning users that are authors or even
query for a limited number of authors, keep track of which you've sent to already,
then run the command over and over again until everyone has gotten their update.
These aren't even the only options. The point is: I'm being a little loose with
how much data I'm querying for: be careful in a real app.

Ok, I think we're good! I mean, we're not *actually* emailing yet, but let's
make sure it runs. Find your terminal and run the command again:

```terminal-silent
php bin/console app:author-weekly-report:send
```

All smooth. Next... let's actually send an email! And then, fix the duplication
we're going to have between our two email templates.
