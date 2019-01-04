# A bit of Security Cleanup

There's *one* last piece of business that we need to clean up. In `ArticleAdminController`,
we created this endpoint... but we didn't add any security on it! It's open entirely
to the world: there's no `@IsGranted` annotation above the method *or* above the
class.

## Securing the new Endpoint

Now... this *might* be ok - this endpoint just returns some boring, non-sensitive
HTML anyways. But, let's be cautious.

The *tricky* thing is that this endpoint is used on both the new and edit form
pages. On the `new` page, we require you to have `ROLE_ADMIN_ARTICLE`. But on
the edit page, we use a special voter that gives you access if you have
`ROLE_ADMIN_ARTICLE` *or* if you are the author of the article.

So, hmm - our endpoint needs to be available to anyone that has `ROLE_ADMIN_ARTICLE`
*or* is the author of at least one article. A little odd, but we can make that happen!

The *proper* way to solve this is to create a new voter and call `@IsGranted()`
with a new attribute we invent, like `ADMIN_ARTICLE_FORM`. The voter would handle
that attribute and have all the logic inside.

But... because we only need to use this security logic on this *one* endpoint, and
because I'm feeling lazy, let's instead put the logic right in the controller.
We can always move it to a voter later if we need to re-use it.

First, add `@IsGranted("ROLE_USER")` to *at least* make sure the user is logged in.
Then, inside the method, if *not* `$this->isGranted('ROLE_ADMIN_ARTICLE')` *and*
`$this->getUser()->getArticles() === 0`, then we should not have access. Wait,
but the `->getArticles()` method is not auto-completing for me.

[[[ code('82a91b7b17') ]]]

Oh, I know why! Go to the top of this class and change the base class from
`extends AbstractController` to `extends BaseController`.

[[[ code('76ab130f57') ]]]

Reminder: `BaseController` is a controller that *we* created. It extends
`AbstractController` but it adds a return type to `getUser()` with *our* `User`
class so we get auto-completion.

Back down in our method, we can say
`$this->getUser()->getArticles()->isEmpty()`, which is a method on Doctrine's
Collection object. So, if we don't have `ROLE_ADMIN_ARTICLE` *and* we are not the
author of any articles, `throw $this->createAccessDeniedException()`.

[[[ code('293626e260') ]]]

Done! And just to make sure I didn't completely break things, if I change the
location to "Near a star"... yea! It *still* loads.

## Fetch EXTRA_LAZY

What *really* made adding this security easy was being able to call
`$this->getUser()->getArticles()`. The *problem* is that if this user is the author
of 200 articles, then this will query for 200 rows of articles *and* hydrate those
into 200 full objects, *just* to figure out that, yes, we *are* the author of at
least one article. All we *really* need is a quick *count* query of the articles.

Fortunately, we can tell `isEmpty()` to do that! Open `User` and look for that `articles`
property. At the end of the `@OneToMany` annotation, add `fetch="EXTRA_LAZY"`. We
talked about this option in our Doctrine relations tutorial. With this set, if
we simply try to *count* the articles - which is what `isEmpty()` does - then
Doctrine will make a quick COUNT query instead of fetching all the data. Nice!

[[[ code('1899bf524a') ]]]

## Using the @method in BaseController

Ok, *one* more thing - and it's also unrelated to forms. Open `BaseController`.
By extending `AbstractController`, this class gives us all the great shortcut
method we love but it *also* overrides `getUser()` so that our editor knows that
this method will return *our* specific `User` class.

After we did this, a wonderful SymfonyCasts user pointed out that the `getUser()`
method on the parent class is marked as `final` with `@final`. When something is
`final` it means that we are *not* allowed to override it. Symfony *could* enforce
this by changing the method to be `final protected function getUser()`. Then,
we would get an error! But, Symfony often uses the softer `@final` comment, which
is just documentation, either to prevent breaking backward compatibility or because
it's harder for Symfony to unit test code that has final methods.

Anyways, the method is *intended* to be final, which means that we're not supposed
to override it. So, delete the method in our class. There's another nice solution
anyways: above the class add `@method User getUser()`.

[[[ code('7265981498') ]]]

That's it! That does the *exact* same thing: it hints to our IDE that the `getUser()`
method returns our `User` object. Back in `ArticleAdminController`, if we delete
`getArticles()` and re-type... yep! It works!

Phew! Amazing job people! That was a *huge* topic to get through. Seriously, congrats!

The Symfony form system is both massively powerful and, in some places, quite complex.
It has the power to make you incredibly productive or just as *unproductive* if you
use it in the wrong place or the wrong ways. So, be smart: and follow these two rules.

One: if your form looks quite different than your entity, either *remove* the
`data_class` option and use the associative array the form gives you to do your
work *or* bind your form to a model class. Two: if your form has a complex frontend
with a lot of AJAX and updating, it might be easier - *and* a better user experience -
if you skip the form and write everything with JavaScript. Use this tool in the right
places, and you'll be happy.

Let me know what you guys are building! And, as always, if you have any questions,
ask us down in the comments.

Alright friends, see ya next time.
