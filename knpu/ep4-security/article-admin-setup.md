# Article Admin & Low-Level Access Controls

Each Article's `author` is now a proper relationship to the `User` entity, instead
of a string. That's great... except that we haven't updated anything else yet in
our code to reflect this. Refresh the homepage. Yep! A big ol' error:

> Exception thrown rendering the template
> Catchable Fatal Error: Object of Class
> `Proxies\__CG__\App\Entity\User` cannot be converted to string.

Wow! Two important things here. First, whenever you see this "Proxies" thing, ignore
it. This is an internal object that Doctrine sometimes wraps around your entity
in order to enable some of its lazy-loading relation awesomeness. The object looks
and works *exactly* like `User`.

Second, the error itself basically means that something is trying to convert our
`User` object into a `string`. This makes sense: in our template, we're just
rendering `{{ article.author }}`:

[[[ code('8bf05bd9cd') ]]]

That *was* a `string` before, but now it's a `User` object.

We *could* go change this to `article.author.firstName`. *Or*, we can go into
our `User` class and add a `public function __toString()` method. 
Return `$this->getFirstName()`:

[[[ code('368a875c5e') ]]]

As *soon* as we do that... we're back!

## Adding the Edit Endpoint

What I *really* want to talk about is controlling access in your system on an
*object-by-object* basis. Like, User A can edit *this* `Article` because they are
the author, but not that *other* `Article`. Open `ArticleAdminController` and add
a new endpoint: `public function edit()`:

[[[ code('97dd31d704') ]]]

Add the normal route with a URL of `/admin/article/{id}/edit`. I won't give it a name yet:

[[[ code('84e8a6c7e2') ]]]

Next, add an argument to the method: `Article $article`:

[[[ code('225d06730b') ]]]

Because `Article` is an *entity*, SensioFrameworkExtraBundle - a bundle we installed
a long time ago - will use the `{id}` route parameter to query for the correct `Article`.

To see if this is working, `dd($article)`:

[[[ code('ebb5e2152c') ]]]

Oh, and remember: this *entire* controller class is protected by `ROLE_ADMIN_ARTICLE`:

[[[ code('d296e6976c') ]]]

To get a valid `Article` ID, find your terminal and run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article'
```

Ok - we'll use 20. Fly over to you browser and... hit it: `/admin/article/20/edit`.
That bounces us to the login page. Use an admin user: `admin2@thespacebar.com`
password `engage`.

Perfect! We're back on the `Article` edit page, access is *granted* and Doctrine
queried for the `Article` object.

## Planning the Access Controls

And *this* is where things get interesting. I want to *continue* to require
`ROLE_ADMIN_ARTICLE` to be able to go to the *new* article page. But, down here, if
you're *editing* an article, I want to allow access if you have `ROLE_ADMIN_ARTICLE`
*or* if you are the *author* of this `Article`. This is the *first* time that we've
had to make an access decision that is *based* on an object - the `Article`.

## Manually Denying Access

Start by moving `@IsGranted()` from above the class to above the `new()` method:

[[[ code('1f64c57918') ]]]

Thanks to this, our `edit()` endpoint is temporarily open to the world.

Right now, we're looking at article `id` 20. Go back to your terminal. Ok, this
article's author is user 18. Find out who that is:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM user WHERE id = 18'
```

Ok, cool: the author is `spacebar4@example.com`. Go back to the browser, go to the
login page, and log in as this user: `spacebar4@example.com`, password `engage`.

Perfect! We still have access but... well... *anyone* has access to this page
right now.

The *simplest* way to enforce our custom security logic is to add it *right* in
the controller. Check it out: `if ($article->getAuthor() !== $this->getUser())`
and if `!$this->isGranted('ROLE_ADMIN_ARTICLE')`, then
`throw $this->createAccessDeniedException('No access!')`:

[[[ code('a20716532c') ]]]

The `$this->isGranted()` method is new to us, but simple: it returns true or false
based on whether or not the user has `ROLE_ADMIN_ARTICLE`. We also haven't seen this
`createAccessDeniedException()` method yet either. Up until now, we've denied access using
`$this->denyAccessUnlessGranted()`. It turns out, that method is just a shortcut
to call `$this->isGranted()` and then `throw $this->createAccessDeniedException()` if
that returned false. The cool takeaway is that, the way you *ultimately* deny access
in Symfony is by throwing a special exception object that this method creates. Oh,
and the message - `No access!` - that's only shown to developers.

Let's try it! Reload the page. We *totally* get access because we *are* the author
of this article. Mission accomplished, right? Well... no! This sucks! I don't want
this important logic to live in my controller. Why not? What if I need to re-use
this somewhere else? Duplicating security logic is a bad idea. And, what if I need
to use it in Twig to hide or show an edit link? That would *really* be ugly.

Nope, there's a better way: a wonderful system called voters.
