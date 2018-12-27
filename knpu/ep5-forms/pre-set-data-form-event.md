# PRE_SET_DATA: Data-based Dynamic Fields

On our form class, we're creating the `specificLocationName` field in two places:
it's up in `buildForm()` and duplicated down inside of `setupSpecificLocationNameField()`.
Because duplication is a *bummer*, let's fix it by calling
`$this->setupSpecificLocationNameField()` from `buildForm()`.

Except... hmm, there's a minor mismatch: in `buildForm()`, we're working with a
form *builder* object, but the method expects a `FormInterface` object. It's a
weird situation where these two objects *happen* to have the same `add()` method,
but they are two totally different classes.

We're going to work around this by leveraging another form event. Remove the block
where we first add the `specificLocationName` field. Oh, and we can remove the
`$location` variable now too.

Let's think about how we could re-add this field using events: we basically want
Symfony to call our callback, the moment the underlying "data" is set onto
the form - the `Article` object. Use `$builder->addEventListener()` and listen on an
event called `FormEvents::PRE_SET_DATA`. Two things: first, this time, we're attaching
the event to the entire *form*, which means our callback will be passed info about
the entire form. That's *usually* want you want: listening to a single field like
we did before was a bit of a hack to allow us to remove and re-add the field at
*just* the right moment.

[[[ code('e0025809d7') ]]]

Second, how do we know to use `PRE_SET_DATA`? When exactly is that called? Open
`ArticleAdminController`: in the `edit()` action, we pass `createForm()` an `Article`
object. When that happens, Symfony dispatches this `PRE_SET_DATA` event. In general,
the `FormEvents` class itself is a *great* resource for finding out when each event
is called and what you can do by listening to it. I won't do it here, but if you
hold Command or Ctrl and click the event name to open that class, you'll find great
documentation above each constant.

Add the callback with the same `FormEvent $event` argument. Then, get the underlying
data with `$data = $event->getData()`. *We* know that this must be either an
`Article` object or possibly `null`. If there is *no* data, just return and do nothing:
we don't want to add the field at *all* for the new form.

[[[ code('6ed3c9837b') ]]]

If there *is* data, call `$this->setupSpecificLocationNameField()` and pass it
`$event->getForm()`. This time, `$event->getForm()` will be the top-level form,
because we added the listener to the top-level builder. For the location, pass
`$data->getLocation()`.

[[[ code('a81576363f') ]]]

Cool! This code *should* work just like before. But actually, while events are nice,
if I need to tweak my form based on the underlying data - like we're doing here -
I prefer to *avoid* using events and just use the `$options['data']` key. It's just
a bit simpler. But, both solutions are fine.

Anyways, let's try it! I'll hit enter on the address bar to get a fresh page. And...
yep! Because "Near a star" is selected as the location, the next field loaded with
the correct list of stars.

We are now *fully* ready for the last, fancy step: adding JavaScript and AJAX to
dynamically change the `specificLocationName` select options when the location
changes. And... that's probably the easiest part!
