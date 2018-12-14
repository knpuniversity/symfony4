# Leveraging Custom Field Options

Our `UserSelectTextType` field work great! I've been high-fiving people all day about
this! But now, imagine that you want to use this field on multiple forms in your
app. That part is easy. Here's the catch: on some forms, we want to allow the email
address of *any* user to be entered. But on *other* forms, we need to use a custom
query: we only want to allow *some* users to be entered - maybe only admin users.

To make this possible, our field needs to be more flexible: instead of looking for
*any* `User` with this email, we need to be able to *customize* this query each time
we use the field.

## Adding a finderCallback Option

Let's start inside the transformer first. How about this: add a new argument to
the constructor a `callable` argument called `$finderCallback`. Hit the normal
Alt+Enter to create that property and set it.

[[[ code('563eb50ba5') ]]]

Here's the idea: whoever instantiates this transformer will pass in a callback that's
responsible for querying for the `User`. Down below, instead of fetching it directly,
say `$callback = $this->finderCallback` and then, `$user = $callback()`. For convenience,
let's pass the function  `$this->userRepository`. And of course, it will need
the `$value` that was just submitted.

[[[ code('1b6b6c67dc') ]]]

Cool! We've now made this class a little bit more flexible. But, that doesn't *really*
help us yet. How can we allow this `$finderCallback` to be customized each time we
use this field? By creating a brand new field *option*.

Check this out: we know that `invalid_message` is *already* an option in Symfony
and we're changing its default value. But, we can invent *new* options too! Add a
new option called `finder_callback` and give it a default value: a callback that
accepts a `UserRepository $userRepository` argument and the value - which will be
a `string $email`. Inside return the normal `$userRepository->findOneBy()` with
`['email' => $email]`.

[[[ code('62b5a2dd4d') ]]]

Next, check out the `build()` method. See this array of `$options`? That will *now*
include `finder_callback`, which will either be our default value, or some other
callback if it was overridden.

Let's break this onto multiple lines and, for the second argument to
`EmailToUserTransformer`, pass `$options['finder_callback']`.

[[[ code('71e9a67f67') ]]]

Ok! Let's make sure it works. I'll hit
enter on the URL to reload the page. Then, change to `spacebar2@example.com`,
submit and... yes! It saves!

The *real* power of this is that, in `ArticleFormType`, when we use
`UserSelectTextType`, we can pass a `finder_callback` option if we need to do a
custom query. *If* we did that, it would override the default value and, when we
instantiate `EmailToUserTransformer`, the second argument would be the callback
that *we* passed from `ArticleFormType`.

## Investigating the Core Field Types

This is how options are used internally by the core Symfony types. Oh, and you
probably noticed by now that *every* field type in Symfony is represented by a
normal, PHP class! If you've ever want to know more about how a specific field or
option works, just open up the class!

For example, we know that this field is a `DateTimeType`. Press Shift+Shift and
look for `DateTimeType` - open the one from the Form component. I love it - these
classes will look a lot like our own custom field type class! This one has a `build()`
method that adds some transformers. And if you scroll down far enough, cool! Here
is the `configureOptions()` method where *all* of the valid options are defined
for this field.

Want to know how one of these options is used? Copy its name and find out! Search
for the `with_seconds` option. No surprise: it's used in `buildForm()`. If
you looked a little further, you'd see that this is eventually used to configure
how the data transformer works.

These core classes are a great way to figure out how to do something advanced
or to get inspiration for your own custom field type. Don't' be afraid to dig!

Next: let's hook up some auto-complete JavaScript to this field.
