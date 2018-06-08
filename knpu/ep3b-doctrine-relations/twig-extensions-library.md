# The Twig Extensions Library

Let's bring this section to life by listing *all* of the comments in the system.
Ah man, with all our tools, this is going to be really easy! First, to query
for the comments, add the `CommentRepository $repository` argument:

[[[ code('29588eddbf') ]]]

Then, `$comments = $repository->`, and we *could* use `findAll()`, but I'll use
`findBy()` passing this an empty array, then `'createdAt' => 'DESC'` so that
we get the newest comments on top:

[[[ code('89cb68ea30') ]]]

Clear out the `render()` variables: we only need to pass one: `comments` set to `$comments`:

[[[ code('656ba09299') ]]]

Perfect! Next, to the template! Below the `h1`, I'll paste the beginning of a table
that has some Bootstrap classes and headers for the article name, author, the comment
itself and when it was created:

[[[ code('e2c5bbb9e2') ]]]

No problem! In the `tbody`, let's loop: `for comment in comments`, and `{% endfor %}`:

[[[ code('ede15a7751') ]]]

Add the `<tr>`, then let's print some data! In the first `td`, we need the article
name. But, to make it *more* awesome, let's make this a *link* to the article. Add
the `a` tag with `href=""`, but keep that blank for a moment. Inside, hmm, we have
a `Comment` object, but we want to print the article's title. No problem! We can
use our relationship: `comment.article` - that gets us to the `Article` object -
then `.title`.

For the `href`, use the `path()` function from Twig. Here, we need the *name* of
the route that we want to link to. Open `ArticleController`. Ah! There it is:
`name="article_show`:

[[[ code('3b12fd9aef') ]]]

Close that and, back in the template, use `article_show`. This route needs a `slug`
parameter so add that, set to `comment.article.slug`:

[[[ code('36f7cce464') ]]]

Dang, those relationships are handy!

Let's keep going! Add another `td` and print `comment.authorName`:

[[[ code('0eaf378695') ]]]

Give the next `td` a `style="width: 20%"` so it doesn't get too big. Then, print
`comment.content`:

[[[ code('ffd2b6d03e') ]]]

Finally, add a `td` with `comment.createdAt|ago`:

[[[ code('838603bac8') ]]]

Cool! Let's see if we made any mistakes. Find your browser, refresh and... boom!
A big, beautiful list of *all* of the comments on the site. Oh, but eventually
on production, this will be a *huge* number of results. Let's put it on our todo
list to add pagination.

## The N+1 Query Problem

Hmm, it works, but check out the web debug toolbar: 11 queries. This is that same
annoying N+1 problem that we talked about earlier. The first query is the one we
expect: SELECT all of the comments from the system. But, as we loop over each comment
and fetch data from its related article, an *extra* query is made to get that data.

Like, this query fetches the data for article id 181, this does the same for
article id 186, and so on. We get 11 queries because we have 1 query for the comments
and 10 more queries for the 10 related articles.

Hence, the N+1 problem: 10 related object plus the 1 original query. So, the question
is, how can we solve this, right? Well, actually, a better question is: *should*
we solve this? Here's the point: *you* need to be aware of the fact that Doctrine's
nice relationship lazy-loading magic makes it easy to accidentally make *many* queries
on a page. But, it is *not* something that you always need to solve. Why? Well, a
lot of times, having 10 extra queries - especially on an admin page - is no big
deal! On the other hand, maybe 100 extra queries on your homepage, well, that probably
*is* a problem. As I *always* like to say, deploy first, then see where you have
problems. Using a tool like Blackfire.io makes it *very* easy to find *real* issues.

Anyways, we *will* learn how to fix this in a few minutes. But, ignore it for now.

## Installing Twig Extensions

Because... we have a minor, but more immediate problem: some comments will probably
be pretty long. So, printing the *entire* comment will become a problem. What I
*really* want to do is show some sort of preview, maybe the first 30 characters
of a comment.

Hmm, can Twig do that? Go to [twig.symfony.com][twig] and click on the
Documentation. Huh, there is actually *not* a filter or function that can do this!
We could easily add one, but instead, search for "Twig extensions" and click on the
[documentation for some Twig extensions library][twig_extensions].

*We* know that if we need to create a custom Twig function or filter, we create
a class called a Twig extension. We did it in an earlier tutorial. But *this* is
something different: this is an open source library *called* "Twig Extensions".
It's simply a collection of pre-made, useful, Twig extension classes. Nice!

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
integrate into your app. Most importantly, this means that while this library *will*
give us some Twig extension PHP classes, it will *not* register those as services
or make our Twig service aware of them. *We* will need to configure things by hand.

Go back to the terminal and, oh! Let's play a thrilling game of Pong while we wait.
Go left side, go left side, go! Boooo!

*Anyways*, ooh! This installed a *recipe*! I committed my changes before I started
recording. So let's run:

```terminal
git status
```

to see what changed. Beyond the normal Composer files and `symfony.lock`, the recipe
created a *new* file: `config/packages/twig_extensions.yaml`. Ah, go check it out!

[[[ code('c9ddc57709') ]]]

Nice! As we just talked about, the library *simply* gives us the extension classes,
but it does *not* register them as services. So, to make life easier, the Flex recipe
for the library *gives* us the exact configuration we need to finish the job! Here,
we can activate the extensions by uncommenting the ones we need:

[[[ code('33acfcd655') ]]]

Actually - because knowledge is power! - there are a few things going on. Thanks
to the `Twig\Extensions\TwigExtension: ~` part, that class becomes registered as
as service. Remember: each class in the `src/` directory is *automatically* registered
as a service. But because this class lives in `vendor/`, we need to register it
by hand. Oh, and the `~` means `null`: it means we don't need to configure this service
in any special way. For example, we don't need to configure any arguments.

Second, thanks to the `_defaults` section on top, specifically `autoconfigure`,
Symfony *notices* this is a Twig Extension by its interface, and automatically
notifies the Twig service about it, without us needing to do anything.

*All* of this means that in `index.html.twig`, we can now immediately add `|truncate`:

[[[ code('046f84f085') ]]]

In fact, *before* we even try it, go back to your terminal and run:

```terminal
php bin/console debug:twig
```

This nice little tool shows us *all* of the functions, filters and other goodies
that exist in Twig. And, ha! We now have a filter called `truncate`!

So, try it: find your browser, go back to the Manage Comments page, and refresh!
It's perfect! Oh, and don't forget about the other cool stuff this Twig Extensions
library has, like `Intl` for date or number formatting and, actually, `Date`,
which coincidentally has a `time_diff` filter that works like our `ago` filter.

Next! Let's add a search form to the comment admin page.


[twig]: https://twig.symfony.com/
[twig_extensions]: http://twig-extensions.readthedocs.io/en/latest/
