# Voters

We need to centralize this logic so that it can be reused in other places:

[[[ code('17a225535c') ]]]

How? Well... it may look a bit weird at first. Remove all of this logic and replace
it with: `if (!$this->isGranted('MANAGE', $article))`:

[[[ code('2f08bea85f') ]]]

Hmm. I'm using the same `isGranted()` function as before. But instead of passing
a *role*, I'm just "inventing" a string: `MANAGE`. It *also* turns out that
`isGranted()` has an optional *second* argument: a piece of *data* that is relevant
to making this access decision.

Don't worry - this will *not* magically work somehow. If you try it... yep!

> Access denied.

## Hello Voter System

Let me explain what's happening. *Whenever* you call `isGranted`, or one of the
other functions like `denyAccessUnlessGranted()`, Symfony executes what's known
as the "Voter system". Basically, it takes the string - `MANAGE`, or
`ROLE_ADMIN_ARTICLE` - and it asks each voter:

> Hey voter! Do you know how to decide whether or not the current user has
> this string - `ROLE_ADMIN_ARTICLE` or `MANAGE`?

In the core of Symfony, there are basically two voters by default: `RoleVoter`
and `AuthenticatedVoter`. When you pass *any* string that starts with `ROLE_`,
the `RoleVoter` says:

> Ah, yea! I totally know how to determine if the user should have access!

Then, it checks to see if the `User` has that role and returns `true` or `false`.
The other voter "abstains" - which means it doesn't vote - and so access is entirely
granted or denied by that one voter.

When you pass any string that starts with `IS_AUTHENTICATED_`, like
`IS_AUTHENTICATED_FULLY`, the *other* voters says:

> Oh. This is me! I know how to check this!

And it returns `true` or `false` based on *how* authenticated the user is and which
of those three `IS_AUTHENTICATED_` strings we passed.

## Adding our Custom Voter

The *really* cool thing is that we can add our *own* custom voters. Right now,
when we call `isGranted()` with the string `MANAGE`, both voters say:

> Hmm, no, we don't understand what this is

They both "abstain" from voting. And when nobody votes, access is denied by default.
So our goal is clear: introduce a *new* voter that understands how to handle the
string `MANAGE` and an `Article` object. By the way, up until now, I've been calling
this `MANAGE` string a role... because it has usually started with `ROLE_`. But
actually, it's generally called a "permission attribute". Some permission attributes
are roles, but some are other strings handled by other voters.

Oh, and why did I choose the word `MANAGE`? I just made that up. If you need
different permissions for edit, show and delete, you would use different
attributes for each - like `EDIT`, `SHOW`, `DELETE` - and create a voter that can
handle all of those. You'll see soon. My case is simpler: I'll use `MANAGE` for
*any* operation on an Article - for example, for editing, deleting or publishing
it.

Ok, let's *finally* create our voter!
