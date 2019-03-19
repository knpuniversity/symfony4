# Edit Endpoint & Deserialization

I want more fancy! Seriously, we're going to add pretty much *everything* we can
think of to make this a sweet, flexible, sort of, file "gallery". What about allowing
the user to *update* a file reference?

Okay, well, we're not going to allow the user to update the *actual* attached file,
there's just no point. Want to upload a newer version of a file? Just delete
the old one and upload the new one. Feature, done!

But we *could* allow them to change the filename. Remember: this is the *original*
filename. And, yea, if they uploaded a file called `astronaut.jpeg`, it would
be totally cool to let them change that to something else after. Let's do it!

## The Update API Endpoint

Let's keep thinking about our `ArticleReference` routes as a set of nice, RESTful
API endpoints. We already have an endpoint to create and delete an `ArticleReference`.
This will be an endpoint to *edit* a reference... except that the only field the
user will be allowed to edit will be the `originalFilename`.

Copy the beginning of our delete endpoint, paste, close it up and we'll
call this `updateArticleReference()`. Keep the same URL, but change the route name
to `admin_article_update_reference` - it should be *reference*, not *references*,
let's fix that in both places - I don't think I'm referencing that route name
anywhere. And instead of `methods={"DELETE"}`, use `methods={"PUT"}`.

Cool! Let's think about how we want this endpoint to work. First, our JavaScript
will send a request with a JSON body that contains the data that should be updated
on the `ArticleReference`. In this case, the data will have only one field:
`originalFilename`.

## Deserializing JSON

So far, we've been using `$this->json()` to turn an object or multiple objects
into JSON. This uses Symfony's serializer behind the scenes. Now we're going
to use the serializer to do the opposite: to turn JSON *back* into an
`ArticleReference` object. That's called deserialization and... it's... pretty
freakin' awesome!

Let's add a few more arguments: `SerializerInterface $serializer` and `Request` -
the one from `HttpFoundation` - so we can read the raw JSON body.

To automagically turn the JSON into an `ArticleReference` object, say
`$serializer->deserialize()`. The serializer only has these two methods: `serialize()`
and `deserialize()`. This method needs the raw JSON from the request - that's
`$request->getContent()`, what *type* of object to turn this into -
`ArticleReference::class` - and the *format* of the data: `json`, because the
serializer can also handle XML or any crazy format you dream up.

Finally, we can pass some options - called "context". By default, `deserialize()`
will always create a *new* object... but we want it to update an *existing*
object. To do that, pass an option called `object_to_populate` set to `$reference`.
Oh, and when we've been *serializing*, we've been passing a `groups` option, which
tells the serializer to put the properties from the "main" group into the JSON.
We can do the same thing here: we don't want a clever user to be able to update
the internal filename or the `id`: we need to restrict their power to changing
the `originalFilename`.

Above `$originalFilename`, turn the groups value into an array and give it a second
group: `input`.

In the controller, *way* back down here, set `groups` to `input`. So if any other
fields or passed, they'll just be ignored.

And... yea, that's it! We *do* need to think about validation - but, pff, we'll
handle that later - like in 2 minutes. Right now we can celebrate with
`$entityManager->persist($reference)`... which we technically don't need because
this isn't a new object, but I usually add it, and `$entityManager->flush()`.

What should we return? Typically after you edit a resource in an API, we return
that resource again. Scroll all the way up to our upload endpoint and steal the
JSON logic. We could also refactor this into a private method if we wanted to avoid
duplication. Back down in *our* method, paste, rename the variable to `$reference`
and use 200 as the status code: we're not *creating* a resource in this case.

Ok, that endpoint should be good! Or at least, we're ready to hook up our JavaScript
so we can find out if it explodes when we use it! That's next.
