# Adding a Custom Voter

Time to create our new Voter class! To do it... we can cheat! Find your
terminal and run:

```terminal
php bin/console make:voter
```

Call it `ArticleVoter`. It's pretty common to have *one* voter per object that you
need to decide access for. Let's go check it out
`src/Security/Voter/ArticleVoter.php`.

## supports()

Nice! Voters are a bit simpler than authenticators: just two methods. Here's how
it works: whenever anybody in the system calls `isGranted()` with *any* permission
attribute string, the `supports()` method on your voter will be called. It's
*our* job to decide whether or not our voter knows how to vote.

The `$attribute` argument will be the *string* passed to `isGranted()` and `$subject`
is the *second* argument - the `Article` object for us. The example in the generated
code is actually pretty good. Let's say that our voter knows how to vote if the
`$attribute` is `MANAGE` and if the `$subject` is an `instanceOf Article`. 

If we `return false` from supports, nothing happens: Our `ArticleVoter` doesn't
vote and it's up to some *other* voter to handle things. But if we return `true`,
Symfony immediately calls `voteOnAttribute()`. *This* is where our logic goes to
determine access. If we return true, access will be granted. If we return false,
access will be denied.

## voteOnAttribute()

Symfony passes us the same `$attribute` and `$subject`, as well as something called
the `$token`. The token is a lower-level object that you don't see *too* often.
But, you can use it to get access to the `User` object.

I'm going to start in this method by helping my editor. At the top, add
`/** @var Article $subject */` to say that the `$subject` variable is an `Article`
object. We can safely do this because of the `supports()` method: `$subject` will
*definitely* be an `Article` at this point.

Below this, it's pretty common to have a voter that votes on *multiple* attributes,
like `EDIT` and `DELETE`. We don't need it, but I'll keep the switch case statement.
Our only case is `MANAGE`.

Excellent! It's time to shine. First, if `$subject->getAuthor() == $user` then
`return true`. The current user is the author and so access *should* be granted.

## Checking for Roles inside a Voter

If they are *not* the author, we need to check for `ROLE_ARTICLE_ADMIN`. But,
hmm. We know how to check if a User has a role in a controller: `$this->isGranted()`.
But, how can we check that from inside of a voter? Or, from inside any service?

The answer is.... with the `Security` service! We actually *already* know this service!
Add a `public function __construct` method with a new `Security` argument: the one
from the Symfony component. I'll hit Alt+Enter and select "Initialize Fields" to
create that property and set it.

Do you remember *where* we used this service before? It was inside `MarkdownHelper`:
it's the last argument *way* over here. We used it because it gives us access to
the current `User` object. But, there's one *other* thing that the `Security` class
can do. Hold Command or Ctrl and click to open it. It has a `getUser()` method
but it *also* has an `isGranted()` method! Awesome! The `Security` service is the
*key* to get the `User` *or* check if the user has access for some permission attribute.

Back down in our voter logic, it's now very simple: if
`$this->security->isGranted('ROLE_ADMIN_ARTICLE')`, then `return true`.
At the bottom, instead of `break`, `return false`: if both of these conditions are
*not* met, access denied.

Ok, let's try this! Move over, refresh and... access granted! Symfony calls the
`supports()` method, that returns true, and because we're logged in as the author,
access is granted. Comment out the author check real quick. Try it again. Access
denied! Put that back.

## @IsGranted with a Subject

Voters are *great*. And using them to centralize this kind of logic will keep your
security code solid. But, there's *one* small thing that now seems *impossible* to
do. First, open `ArticleAdminController`. We can actually shorten this to the
normal `$this->denyAccessUnlessGranted('MANAGE', $article)`.

Try it - reload the page. Access granted! This does the *exact* same thing as before.
But... what about using the `@IsGranted` annotation? Hmm... now there's a problem:
can we use the annotation and still, somehow, pass in the `Article` object? Actually,
yes!

Add `@IsGranted`, pass it `MANAGE` and then a second argument: `subject="article"`.
That's it! When you use `subject=`, you're allowed to pass this the same name as
any of the *arguments* to your controller. This only works because we used the
feature that automatically queries for the `Article` object and passes it as an
argument. These two features combine *perfectly*. But, if you're ever in a situation
where your "subject" isn't a controller argument, no worries, just use the normal
`denyAccessUnlessGranted()` code. But, remove it in this case.

Let's... try it! Access granted! That was too easy. Go back to the voter and
comment-out the author check again - let's *really* make sure this is working.
Now... yes! Access denied! Go put that code back.

Oh my gosh friends, we did it! We *killed* this tutorial! We have a great
authentication system that allows both login form authentication *and* API
authentication! We have a rich dynamic roles system and a voter system where we can
control access with *any* custom rules. Oh, I love security! I hope you guys are
feeling empowered to create your simple, complex, crazy, whatever authentication
system you need. As always, if you have questions, ask us down in the comments.

Alright people, seeya next time!
