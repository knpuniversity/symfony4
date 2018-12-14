# Autocomplete Endpoint & Serialization Group

To get our autocomplete fully working, we need an API endpoint that returns a list
of user information - specifically user *email* addresses. We can do that! Create
a new controller for this: `AdminUtilityController`. Make that extend the normal
`AbstractController` and add a `public function getUsersApi()`. To make this a real
page, add `@Route("/admin/utility/users")`. And, just to be extra fancy, let's also
add `methods="GET"`.

[[[ code('f7f31f0b2e') ]]]

The *job* of this endpoint is pretty simple: return an array of User objects as
JSON: I'm not even going to worry about filtering them by a search term yet.

Add the `UserRepository $userRepository` argument and fetch every user with
`$users = $userRepository->findAllEmailAlphabetical()`. Finish this with
`return $this->json()` and, it doesn't really matter, but let's set the user objects
into a `users` key.

[[[ code('06766fe3d1') ]]]

Cool! Copy that URL, open a new tab paste and.... boo! A circular reference has
been detected. This is a common problem with the serializer and Doctrine objects.
Check it out: open the `User` class. By default, the serializer will serialize
*every* property... or more accurately, every property that has a getter method.

## Serialization Groups to the Rescue

But *that* means that it's serializing the `apiTokens` property. And, well, when
it tries to serialize that, it notices its `user` property and so, tries to serialize
the `User` object. You can see the problem. Eventually, before our CPU causes our
computer fan to quit & our motherboard to catch on fire, the serializer notices this
loop and throws this exception.

What's the fix? Well, the thing is, we don't really want to serialize *all* of the
fields anyway! We really only need the email, but we could also just serialize
the same basic fields that we serialized earlier.

Remember: in `AccountController`, we created an API endpoint that returns one `User`
object. When we did that, we told the serializer to only serialize the `groups` called
`main`. Look back in the `User` class. Ah, yes: we used the `@Groups()` annotation
to "categorize" the fields we wanted into a group called `main`.

In `AdminUtilityController`, we can serialize that *same* group. Pass 200 as the
second argument - this is the status code - we don't need any custom headers, but
we *do* want to pass a `groups` option set to `main`... I know a lot of square
brackets to do this.

[[[ code('84efa87576') ]]]

*Now* go back and refresh. Got it! We could add a *new* serialization group to return
even *less* - like maybe just the `email`. It's up to you.

## Adding Security

But no matter *what* we do, we probably need to make sure this endpoint is secure:
we don't want *anyone* to be able to search our user database. But... hmm.. this
is tricky. In `ArticleAdminController`, the `new()` endpoint requires
`ROLE_ADMIN_ARTICLE`.

Copy that role, go back to `AdminUtilityController` and, above the method, add
`@IsGranted()` and paste to use the same role.

[[[ code('6b28df4d1a') ]]]

This is a *little* weird because, in `ArticleAdminController`, the `edit` endpoint
is protected by a custom voter that allows access if you have that same
`ROLE_ADMIN_ARTICLE` role *or* if you are the author of this article. In other words,
it's possible that an author could be editing their article, but the AJAX call
to our new endpoint would *fail* because they don't have that role!

I wanted to point this out, but we won't need to fix it because, later, we're going
to *disable* this field on the edit form anyways. In other words: we will eventually
*force* the author to be set at the moment an article is created.

Next: let's finish this! Let's hook up our JavaScript to talk to the new API endpoint
and *then* make it able to filter the user list based on the user's input.
