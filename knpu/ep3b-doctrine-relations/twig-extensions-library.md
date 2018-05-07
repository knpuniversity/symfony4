# The Twig Extensions Library

Let's bring this section to life by listing *all* of the comments in the system.
Ah man, with all of our tools, this is going to be really easy! First, to query
for the comments, add the `CommentRepository $repository` argument. Then,
`$comments = $repository->`, and we *could* use `findAll()`, but I'll use
`findBy()` passing this an empty array and then `'createdAt' => 'DESC'` so that
we get the newest comments on top.

Clear our the `render` variables and pass just one: `comments` set to `$comments`.

Perfect! With that in place, open the template. Right below the `h1`, I'll paste
the beginning of a table with some Bootstrap CSS classes and headers for the
article name, author, the comment itself, and when it was created.

Easy! In the `tbody`, let's loop: `for comment in comments`, and `{% endfor %}`.
Add the `<tr>` and let's print some data! In the first `td`, we need the article
name. But, to make it *more* awesome, let's make this a *link* to the article. Add
the `a` tag with `href=""`, but keep that blank for a moment. Inside, hmm, we have
a `Comment` object, and we want to print the article's title. No problem! We can
use our relationship: `comment.article` - that gets us to the `Article` object -
then `.title`.

For the `href`, use the `path()` function from Twig. But, we get the *name* of
the route that we want to link to. Open `ArticleController`. Ah! There it is:
`name="article_show`. Close that and, back in the template, use `article_show`.
This route requires a `slug` parameter: set it to the article's slug:
`comment.article.slug`.

Dang, those relationships are handy!

Ok, keep going! Add another `td` and print `comment.authorName`. Give the next
`td` a `style="width: 20%"` so it doesn't get too big. Then, print `comment.content`.
Finally, Add a `td` with `comment.createdAt|ago`.

Cool! Let's see if we've made a mistake yet. Find your browser, refresh and...
boom! A big, beautiful list of *all* of the comments on the site. Actually, this
will will eventually be *huge*. Let's put it on our todo list to add pagination
later.

## The N+1 Query Problem

Hmm, but check out the web debug toolbar: 11 queries. This is that same N+1 problem
that we were talking about earlier. The first query is the one we expect: select
all of the comments from the system. But, as we loop over each comment and try
to use the related article's data, an *extra* query is made to get that data.

Like, this query fetches the data for article id 181. Later, when we try to fetch
article 186, it queries for *its* data. And, so on. We get 11 queries because we
have 1 query for the comments and 10 more queries for the 10 related articles.

Hence, the N+1 problem: 10 related object plus the 1 original query. So, the question
is, how can we solve this, right? Well, actually, a better question is: *do* we
need to solve this? I want you to be aware of how lazy-loading makes it easy to
accidentally make *many* queries on a page. But, it is *not* something that I always
solve. Why? Well, a lot of times, having 10 extra queries - especially on an admin
page - is no big deal! On the other hand, maybe having 100 extra queries on your
homepage *is* something you want to solve.

Anyways, we *will* learn how to fix this in a few minutes. But, ignore it for now.

## Installing Twig Extensions

We have a minor, but more immediately problem: some of these comments will become
*long*. So printing the *entire* comment is going to become a problem. What I
*really* want to do is show some sort of preview, maybe the first 30 characters
only.

Hmm, can Twig do that? Go to [twig.symfony.com](https://twig.symfony.com/) and
click on the Documentation. Hmm, there is actually *not* a filter or function
that can do this! Ok, search instead for "Twig extensions" and click on the
[documentation for some Twig extensions library](http://twig-extensions.readthedocs.io/en/latest/).

*We* know that if we need to create a custom Twig function or filter, we create
something called a Twig extension. We did it in an earlier tutorial. This is
something different: this is an open source library *called* "Twig Extensions".
And, it's simply a collection of pre-made, useful, Twig extension classes.

For example, one Twig extension - called `Text` - has a filter called *truncate*!
Bingo! That's *exactly* what we need. Click on the "Text" extension's documentation,
then click the link to install it. Perfect! Copy that `composer require` line.

Then, find your terminal and, paste!

```terminal-silent
composer require twig/extensions
```

## Activating the Twig Extension

While we're waiting for this to install, I want to point out something important:
we're installing a PHP *library*, not a Symfony *bundle*. What's the difference?
Well, a PHP *library* simply contains *classes*, but does *not* automatically
integrate with your application in an way. Specifically, this means that while
this library will give us some Twig extension PHP classes, it will *not* register
those as services or make our Twig service aware of them. *We* will need to configure
things by hand.

Go back to the terminal and, let's play a thrilling game of Pong while we wait.
Go left side, go left side, go! Boooo!

*Anyways*, ooh! This installed a *recipe*! I committed before I started recording
so that we can run:

```terminal
git status
```

to see what changed. Beyond the normal Composer files and `symfony.lock`, we have
the recipe created a *new* file: `config/packages/twig_extensions.yaml`. Go check
it out!

Nice! As we just talked about, the library *simply* gives us the extension classes,
but it does *not* register them as services. So, to help that, the Flex recipe
comes with a configuration that we need to finish the job! Here, we can activate
the extensions by uncommenting the ones we need.

Actually - because understanding is power! - there are a few things going on. Simply
by having `Twig\Extensions\TwigExtension: ~`, that class *now* becomes registered
as as service. Remember: any classes in `src/` are *automatically* registered as
services. But since this class lives in `vendor/`, we need to add it by hand. Oh,
and the `~` means null: it means we don't need to configure this service in any
way, like, we don't need to configure any arguments.

Second, thanks to the `_defaults` section on top, specifically `autoconfigure`,
Symfony *notices* this is a Twig Extension, and automatically notifies the Twig
service about it, without us needing any extra config.

*All* of this means that in `index.html.twig`, we can now immediately add `|truncate`.

In fact, *before* we even try it, go back to your terminal and run:

```terminal
php bin/console debug:twig
```

This nice little tool shows us *all* of the functions, filters and other goodies
that exist in Twig. And, ha! We now have a filter called `truncate`.

So, try it: find your browser, go back to the Manage Comments page, and refresh!
It's perfect! Oh, and don't forget about the other cool stuff this Twig Extensions
library has, like `Intl` for date or number formatting and, actually, `Date`,
which coincidentally has a `time_diff` filter that works like our `ago` filter.

Next! Let's add a search form to the comment admin page.
