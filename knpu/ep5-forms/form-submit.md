# Handling the Form Submit

Creating the form class and rendering was... easy! Now it's time to talk about handling
the form *submit*. Notice: we haven't configured *anything* on our form about what
*URL* it should submit to. When we rendered it, we used `form_start()` and... that's
it! Inspect element on the form. By default, `form_start()` creates a `form` tag
with *no* action attribute. And when a `form` tag has *no* `action=`, it means
that it will submit right back to this *same* URL.

That's the most common way of handling forms in Symfony: the same controller is
responsible for both *rendering* the form on a GET request *and* handling the form
*submit* on a POST request. The way you *do* this always follows a similar pattern.

## The Form Submit Logic

First, get the `$request` object by type-hinting `Request` - the one from
`HttpFoundation`. Next, add `$form->handleRequest($request)` and then
`if ($form->isSubmitted() && $form->isValid())`. Inside the `if`,
`dd($form->getData()`.

[[[ code('b99603219a') ]]]

Okay, so... this requires a *little* bit of explanation. First, yea, the
`$form->handleRequest()` makes it *look* like the submitted data is being read
and processed on *every* request, even the initial GET request that renders
the form. But, that's not true! By default, `handleRequest()` *only* processes
the data when this is a `POST` request. So, when the form is being submitted.
When the form is originally loaded, `handleRequest()` sees that this is a `GET`
request, does nothing, `$form->isSubmitted()` returns false, and then the
un-submitted form is rendered by Twig.

But, when we `POST` the form, ah, that's when `handleRequest()` does its magic.
Because the form knows all of its fields, *it* grabs all of the submitted data
from the `$request` automatically and `isSubmitted()` returns `true`. Oh, and later,
we'll talk about adding validation to our form. As you can guess, when validation
fails, `$form->isValid()` returns false.

So, wow! This controller does a lot, with very little code. And there are *three*
possible flows. One: if this is a GET request, `isSubmitted()` returns false and
so the form is passed to Twig. Two, if this is a POST request but validation
fails, `isValid()` returns false and so the form is *again* passed to Twig, but
*now* it will render with errors. We'll see that later. And three: if this is a
POST request and validation *passes*, both `isSubmitted()` *and* `isValid()`
are true, and we finally get into the `if` block. `$form->getData()` is how
*we* access the final, normalized data that was submitted.

Phew! So, let's try it! Find your browser and create a very important article about
the booming tourism industry on Mercury. Submit!

Yes! It dumps out *exactly* what we probably expected: an array with `title` and
`content` keys. It's not too fancy yet, but it works nicely.

## Saving the Form Data

To insert a new article into the database, we need to use this data to create an
`Article` object. There is a *super* cool way to do this automatically with the
form system. But, to start, let's do it the manual way. Add `$data = $form->getData()`.
Then, create that object: `$article = new Article()`,
`$article->setTitle($data['title']);`, `$article->setContent($data['content'])`, 
and the author field is also required. How about, `$article->setAuthor()` with
`$this->getUser()`: the current user will be the author.

[[[ code('c15bd1515d') ]]]

To save this to the database, we need the entity manager. And, hey! We *already*
have it thanks to our `EntityManagerInterface` argument. Save with the normal
`$em->persist($article)`, `$em->flush()`.

Awesome! The *last* thing we *always* do after a successful form submit is
redirect to another page. Let's use `return this->redirectToRoute('app_homepage')`.

[[[ code('c8b292c69d') ]]]

Time to test this puppy out! Refresh to re-post the data. Cool! I... *think* it worked?
Scroll down... Hmm. I don't see my article. Ah! But that's because only *published*
articles are shown on the homepage.

## Adding an Article List Page

What we *really* need is a way to see *all* of the articles in an admin area. We
have a "new" article page and a work-in-progress edit page. Now, create a new method:
`public function list()`. Above it, add the annotation `@Route("/admin/article")`.
To fetch all of the articles, add an argument: `ArticleRepository $articleRepo`,
and then say `$articles = $articleRepo->findAll()`. At the bottom, render a
template - `article_admin/list.html.twig`- and pass this an `articles` variable.

[[[ code('ace31ab538') ]]]

Oh, and I'll cheat again! If you have the Symfony plugin installed, you can put
your cursor in the template name and press Alt+Enter to create the Twig template,
right next to the other one.

Because we're *awesome* at Twig, the contents of this are pretty boring. In fact,
I'm going to cheat again! I'm on a roll! I'll paste a template I already prepared.
You can get this from the code block on this page.

[[[ code('3732970218') ]]]

And... yea! Beautifully boring! This loops over the `articles` and prints some basic
info about each. I also added a link on top to the new article form page.

Oh, there is *one* interesting part: the `article.isPublished` code, which I use to
show a check mark or an "x" mark. That's interesting because... we don't have an
`isPublished` property or method on `Article`! Add `public function isPublished()`,
which will return a `bool`, and very simply, `return $this->publishedAt !== null`.

[[[ code('c2d673ecca') ]]]

If you want to be fancier, you could check to see if the `publishedAt` date is
not null and also not a *future* date. It's up to how you want your app to work.

Time to try it! Manually go to `/admin/article` and... woohoo! *There* is our
new article on the bottom.

And... yea! We've *already* learned enough to create, render *and* process a
form submit! Nice work! Next, let's make things a bit fancier by rendering a success
message after submitting.
