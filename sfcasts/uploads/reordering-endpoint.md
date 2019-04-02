# Reordering Endpoint & AJAX

Let's upload *all* of these files. How nice is that? One fails because it's the
wrong type and another fails because it's too big. But we get nice errors and
all the rest worked. *And* this gives us a *lot* more to play with for reordering!

## Getting the Sorted Ids

To make an AJAX call when we finishing dragging, add a new option: `onEnd`
set to an arrow function. Inside `console.log(this.sortable)` - that's the sortable
object we stored earlier `.toArray()`.

[[[ code('40ae8fba6a') ]]]

Check it out: refresh the page, drag one of these... and go look at the console.
Woh! Those are the reference ids... in the right order! Try it again: move this one
up and... yep! The id 11 just moved up a few spots.

But... how the heck is this working? How does sortable know what the ids are?
Well, honestly... we got lucky. It knows thanks to the `data-id` attribute
that we put on each li! We added that for our *own* JavaScript... but the Sortable
library *also* knows to read that!

## The Reorder Endpoint

This is amazing! This is the *exact* data we need to send to the server! Open
up `ArticleReferenceAdminController` and find `downloadArticleReference()`. If
you look closely, about half of the methods in this controller have an `{id}` route
wildcard where the id is for an `ArticleReference`. Those endpoints are actions that
operating on a single *item*. The other half of the endpoints, the ones on top,
*also* have an `{id}` wildcard, but these are for the `Article`.

What about *our* new endpoint? We'll be reordering *all* of the references for
one article... so it's a bit more like these ones on top. Copy this entire
action for getting article references, change the name to
`reorderArticleReferences` and put `/reorder` on the URL. Make this a
`method="POST"` and name it `admin_article_reorder_references`.

[[[ code('c5a02f68dc') ]]]

If you're wondering about the URL or the method `POST`, well, this endpoint isn't
very RESTful.. it doesn't fit into the nice create-read-update-delete model...
and that's ok. Usually when I have a weird endpoint like this, I use POST.

Inside the method, here's the plan: our JavaScript will send a JSON body containing
an array of the ids in the right order. This array exactly. Add the `Request`
argument so we can get read that data and the `EntityManagerInterface` so we can
save stuff.

[[[ code('681b6393bd') ]]]

To decode the JSON *this* time, it's so simple! I'm going to skip using Symfony's
serializer. Say `$orderedIds = json_decode()` passing that `$request->getContent()`
and true so it gives us an associative array. 

[[[ code('de25c962b9') ]]]

Then, if `orderedIds === false`, something went wrong. Let's `return this->json()` and, 
to at least *somewhat* match the validation responses we've had so far, let's set 
a detail key to, how about, `Invalid body` with 400 for the status code.

[[[ code('0695c7b424') ]]]

## Using the Ordered Ids to Update the Database

Ok, cool: we've got the array of ids in the *new* order we want. Use this to say
`$orderedIds = array_flip($orderedIds)`. This deserves some explanation. The original
array is a map from the position to the id - the keys are 0, 1, 2, 3 and so on.
After the flip, we have a *very* handy array: the key is the *id* and the value
is its new position.

[[[ code('c1d8fa3100') ]]]

To use this, `foreach` over `$article->getArticleReferences() as $reference`. And
inside, `$reference->setPosition()` passing this `$orderedIds[$reference->getId()]`
to look up the new position.

[[[ code('a46f4b1225') ]]]

And yes, we *could* code more defensively - like checking to make sure each array
key was actually sent. And I *would* do that if this were a public API that other
people used, or if invalid data could cause some harm.

Anyways, at the bottom, save: `$entityManager->flush()`.

[[[ code('fad4d9b1c8') ]]]

## Sending the AJAX Request

Ok, let's hook up the JavaScript! Back in `admin_article_form.js`, scroll up...
let's see - find the `onEnd()` of sortable. Say `$.ajax()` and give this the
`url` key. For the URL, remember, the `ul` element has a `data-url` attribute,
which is the path to the `admin_article_list_references` route, so
`/admin/article/{id}/references`. Not by accident, the URL that *we* want is
that plus `/reorder`.

[[[ code('2a7a47fe56') ]]]

So let's do a *little* bit of code re-use... and a little bit of hardcoding: in general,
I don't worry *too* much about hardcoding URLs in JavaScript. Copy
`this.$element.data('url')` from below, paste, and add `/reorder`. Then, method
set to `POST` and `data` set to  `JSON.stringify(this.sortable.toArray())`.

[[[ code('f19f00d8a7') ]]]

Ok, let's do this! Move over and refresh. No errors yet... Move "astronaut-1.jpg"
down two spots and... hey! A 200 status code on that AJAX request! That's a good
sign. Refresh and... aw! It's right back up on top!

## Changing the Endpoint Order

Oh wait... the problem is that we're not *rendering* the list correctly! This
list loads by making an Ajax request. In the controller... here's the endpoint:
`getArticleReferences()`. And it gets the data from `$article->getArticleReferences()`.
The *problem* is that this method doesn't know that it should order the reference's
by position.

Open up the `Article` entity and, above `$articleReferences`, add
`@ORM\OrderBy({"position"="ASC"})`.

[[[ code('ce849729b9') ]]]

Let's go check out the endpoint: I'll click to open the URL in a new tab. Woohoo!
`astronaut-1.jpg` is *third*! Refresh the main page. Boom! The astronaut is right
were we sorted it. Let's move it down a bit further... move the Symfony Best Practices
up from the bottom and refresh. The sorting sticks. Awesome!

Next, instead of saving the uploaded files locally, let's upload them to AWS S3.
