# Console Command

Coming soon...

so far we've created exactly one email. We've done lots of cool stuff with that, but
we still have just this one email. So let's introduce a second email to our system.
And what's going to be unique about this email is that instead of us sending it, for
example, when we register, we're going to send this from within a console command.
And that's actually gonna change a couple of things. It's also gonna give us the
opportunity to, um, and as soon as we have two MLS, we're actually gonna start to see
a little bit of duplication between them, which we can also fix. So we're going to
create a new custom console command. And the idea is that one of the fields on user
is called `$subscribeToNewsletter`. And this is actually a field that's meant to be
used by the authors on our site. So once a week we're going to have a Cron job that's
going to find all the users in the system with `$subscribeToNewsletter` equal to
`true`. And it's going to send them a report about the articles that they wrote during
the week. So like, Hey, end of the week, here are the five articles that you
published this week. So the kickoff, the command

let's go around 

```terminal
php bin/console make:command
``` 
 
and let's call this `app:author-weekly-report:send` Perfect move over. We can look 
in the `src/Command` directory
and here is our shiny new console command. All right, so let's start customizing this
a bit. We don't need any arguments or options and I'll change the description to send
weekly reports to authors. Now the first thing we're going to need to do here is
we're going to need to query for all the users that have this `$subscribeToNewsletter`
equal to `true`. Um, I don't have to, but I'm going to write a custom 
`UserRepository` method for that real quick. So down here I'll say 
`public function findAllSubscribedToNewsletter()`. This will return an `array`. 
Well, they're real simple queer
here, we'll say `$this->createQueryBuilder()`. Use the `u` alias then 
`->andWhere('u.subscribeToNewsletter = 1')`, and then `->getQuery()` and `->getResults()`. 
And then above this, I'm going to advertise that this returns not exactly an array of 
actually it returns a an array of users.

well, `User[]`. Alright to use this inside of our
console command, we will do our normal thing where we override the constructor. We
will add a

`public function __construct()`. And you noticed when they did that it actually
filled in a name argument and called parent constructor. I'm actually going to remove
that, but one of the unique things about console commands is that when you, um, over
on the construct you actually need to call the parent constructors so that I can set
some stuff up. It's not that important. It's just a detail that you need to take care
of. Now we can do our normal `UserRepository $userRepository` arguments. I'll go hit
option All + Enter and select "Initialize fields" to create that property and set it. Perfect.
All right, down to the bottom, I'm going to clear everything out except for our nice
`$io` object, which is a nice object to help us just print things. And here. We'll start
with `$authors = $this->userRepository->findAllSubscribedToNewsletter()`, and
then Z we extra fancy. Let's create a progress bar. So I'll say `$io->progressStart()`
start. Then we'll feed for each of our `$authors` as `$author`.

Then inside of here we can say `->progressAdvance()` to go. Want ahead. Oh, and of
course for the progress start I had to tell it how many things we're going to have on
our progress far. So I'll do `count($authors)` and we'll leave the four H empty for a
second. And at the bottom we'll say `$io->progressFinish()`. Finally, `$io->success()`
to say 

> Weekly reports were sent to authors!

Perfect. So I'm not doing anything
with the authors yet, but let's just see if this is working. I'll go up and copy my
console, command my console name and scroll down in your 

```terminal
php bin/console app:author-weekly-report:send
```

And there it is so fast you couldn't even see the progress bar moving.

All right, so the next thing we need to do is inside of this for each we need to find
all of the authors that this all of the articles that this author has written in the
past week. So to do that, I'm going to open the article repository and we'll add a
new one, a function to it called `findAllPublishedLastWeekByAuthor()` and we'll
take a cert, a single argument, which is going to be a `User` object called the `$author`.
And this is also gonna return to and `array` of um, articles. In fact, let's advertise
that on top. We'll say `@return Article[]`. Once again,
it's a pretty simple queer here we'll say `return $this->createQueryBuilder()`,
passing the `a` is the alias

and then we need to and where's here? The first one is we need to say `->andWhere()`
`a.author` is going to be equal to the `:author` will set that parameter in a second and
then `andWhere('a.publishedAt > :week_ago')`. Go now to settle
those parameters. I'll say `setParameter()` for the `author`. We're going to pass the
`$author` variable, the `User` object, so it will query for only that author and then for
the and then call `setParameter()` again and pass it a `week_ago` and pass it a 
`new \DateTime()` and we can say `-1 week`. Finally the bottom was like `getQuery()`, do the
normal `getQuery()` and then `getResult()`. Love it. All right, back in a command. Same
thing to use this we're going to auto wire

`ArticleRepository $articleRepository`. Alt + Enter to initialize that field and then
down here in the bottom inside we will say 
`$articles = $this->articleRepository->findAllPublishedLastWeekByAuthor()` 
and pass that `$author`. Now with both of these
cases, both queering for the authors and also querying for the articles, this does
assume that we don't have tons of authors and each article and each author doesn't
have a tons of articles published in the past week. So if you had many, many, many
authors, you might need to make this command a little smarter to only query for some
little by little. So that you don't return all of them at once, but that's not really
what we're talking about in this tutorial. So I'm just doing it the simple way. Down
here, up a little. We'll say before we asked you to send emails, we'll say if 
`count($articles)` is zero, then we're just going to `continue;`

> Skip authors who do not have published articles for the last week. 

Alright. I think we're good. I mean, there's no email logic in here yet. This is just a fun
exercise and creating articles. So let's spin back over once more. Copy of that, run
that command 

```terminal-silent
php bin/console app:author-weekly-report:send
```

and perfect. So next, let's actually send an email from inside of this
template. See what unique challenges this has, uh, because we're running it from the
console environment.