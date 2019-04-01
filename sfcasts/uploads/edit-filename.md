# JavaScript for Editing a Reference

To make this all work, but to avoid going *totally* insane and coding JavaScript
for the next 30 minutes, we're going to turn the printed string into an input
text body and, on "blur" - so when we click *away* from it, we'll make an AJAX
request to save the new filename.

Let's copy the original filename code and replace it with
`<input type="text" ` and `value="` that original filename stuff. Let's also
add two classes: one from Bootstrap to make things look nice and another -
`js-edit-filename` - so that we can *find* this field in JavaScript. Oh, one more
detail: add a `style` attribute with `width: auto` - just another styling thing.

[[[ code('0fdef9e539') ]]]

Next: copy the `js-` class name and head back up to the constructor. We're going
to do the same thing we did with our delete link:
`this.$element.on('blur')`, this time with `.js-edit-filename` and then our arrow
function. Inside that, call a new function: `this.handleReferenceEditFilename()`
and pass that the `event`.

[[[ code('95e5e413d8') ]]]

Keep going: copy the method name, scroll down a bit, and create that function,
which will accept an `event` object. Let's also steal the first two lines from
`handleReferenceDelete()`: we're going to start the exact same way. 

[[[ code('a64bd0a91f') ]]]

Heck, we're going to make an AJAX request to the same URL! Just with the `PUT` method 
insteadof `DELETE`.

When we send that AJAX request, we're only going to send one piece of data: the
`originalFilename` that's in the text box. But I want you to pretend that we're
allowing *multiple* fields to be updated on the reference. So, more abstractly,
what we were *really* want to do is find the reference that's being updated from
inside `this.references`, change the `originalFilename` data on it, JSON-encode
that *entire* object, and send it to the endpoint.

If that doesn't make sense yet, don't worry. To find the reference object that's
being updated right now, say `const reference = this.references.find()` and pass
this an arrow function with a reference argument. Inside, `return reference.id === id`.

[[[ code('b03d540f57') ]]]

This loops over all the references and returns the first one it finds that matches
the id... which *should* only be one. Now change the `originalFilename` property
to `$(event.currentTarget)` - that will give us the input element - `.val()`.

[[[ code('1c337f6b7f') ]]]

Ok! We're ready to send the AJAX request! Copy the first-half of the AJAX call from
the delete function, remove the `.then()` stuff, change the method to `PUT` and,
for the data, just pass `reference`.

[[[ code('ee0134ed54') ]]]

There *is* a small problem with this - so if you see it, hang on! But, the idea
is cool: we're sending up *all* of the reference data. And yes, this *will* send
more fields than we need, but that's ok! The deserializer just ignores that extra
stuff.

Testing time! Refresh the whole page. Oh wow - we have an extra `<` sign! As cool
as that looks, let's scroll down to render and... there it is - remove that.

Refresh again. Let's tweak the filename and then click off to trigger the "blur".
Uh oh!

> Cannot set property `originalFilename` of undefined.

Hmm. Look back at our code: for some reason it's not finding our reference. Oh, duh:
`return referenced.id === id`.

Ok, let's see if I've *finally* got everything right. Refresh, add a dash to the
filename, click off and... 500 error! That's progress! Open the profiler for
that request in a new tab. Ok: a "Syntax Error" coming from a `JsonDecode`
class. Oh, and look at the data that's passed to the `deserialize()` function!
That's not JSON!

Silly mistake. When we set the `data` key to the `reference` object, jQuery doesn't
send up that data as JSON, it uses the standard "form submit" format. *We* want
`JSON.stringify(reference)`.

[[[ code('c4ef5b001b') ]]]

I think we've got it this time. Refresh, tweak the filename, click off and... no
errors! Check out the network tab. Yeah `200`! The response returns the updated
`originalFilename` and, if you scroll down to the request body... cool! You can
see the raw JSON that was sent up.

## Validation

The *last* thing we need to do is... add validation. I know, it's always that annoying
last detail once you've got the "happy" path working perfectly. But, right now,
we could leave the filename *completely* blank and our system would be ok with
that. Well ya know what? I am totally *not* ok with that!

Ultimately, our endpoint modifies the `ArticleReference` object and *that* is
what we should validate. Above the `originalFilename` field, add `@NotBlank()`
and let's also use `@Length()`. The length can be 255 in the database, but let's
use `max=100`. 

[[[ code('6857c42f72') ]]]

Then, inside our endpoint, there's no form here, but that's fine.
Add the `ValidatorInterface $validator` argument. And right after we update the
object with the serializer, add `$violations = $validator->validate()` and pass
it the `$reference` object. Then if `$violations->count() > 0`,
`return $this->json($violations, 400)`.

[[[ code('402d3c5446') ]]]

We're actually *not* going to handle that in JavaScript - I'll leave rendering
the errors up to you - you could highlight the element in red and print the error
below... whatever you want.

But let's at *least* make sure it works. Clear out the filename, hit tab to blur
and... there it is! A 400 error with our beautiful error response. To handle this
in JavaScript, you'll chain a `.catch()` onto the end of the AJAX call and then
do whatever you want.

Ok, what else can we add to our upload widget? How about the ability to reorder
the list. That's next.
