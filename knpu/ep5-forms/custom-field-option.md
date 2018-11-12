# Leveraging Custom Field Options

Our `UserSelectTextType` field work great! I've been high-fiving people all day about
this! But now, imagine that you want to use this field on multiple forms in your
app. That part is easy. Here's the catch: on some forms, we want to allow the email
address of *any* user to be entered. But on other forms, we need to use a custom
query - we only want to allow *some* users to be entered - maybe only admin users.

So let's make our field more flexible: instead of looking for *any* `User` with
this email, let's make it possible to *control* this query, each time you use
the field.

## Adding a finderCallback Option

Let's start inside of the transformer first. How about this: add a new argument to
the constructor a `callable` argument called `$finderCallback`. Hit the normal
Alt+Enter to create that property and set it.

Here's the idea: whoever instantiates this transformer will pass in a callback that's
responsible for querying for the `User`. Down below, instead of fetching it directly,
say `$callback = $this->finderCallback` and then, `$user = $callback()`. For convenience,
let's pass the function  `$this->userRepository`. And of course, it will need
the `$value` that was just submitted.

Cool! We've now made this class a little bit more flexible. But, that doesn't *really*
help us yet. How can we allow this `$finderCallback` to be customized each time we
use this field? By creating a brand new field *option*.

Check this out: we know that `invalid_message` is already a valid option in Symfony
that does something. But, we can *absolutely* invent new options! Add a new option
called `finder_callback` and give it a default value: a callback that accepts a
`UserRepository $userRepository` and the value - which will be a `string $email`.
Inside return the normal `$userRepository->findOneBy()` with `['email' => $email]`.

Thanks to this, we've created a new `finder_callback` option that can be overridden
when we're configuring this field type. And, it has a nice default if we don't need
to.

Also, check out the `build()` method. See this array of `$options`? That will *now*
include `finder_callback`, which will either be our default value, or some other
function if it was overidden.

Let's break this onto multiple lines and, for the second argument to
`EmailToUserTransformer` pass `$options['finder_callback']`.

Ok! There is *one* little piece missing, but let's make sure it works. I'll hit
enter on the URL to reload the page. Then, I'll change to `spacebar2@example.com`,
submit and... ye! It saves!

The *real* power of this is that, in `ArticleFormType`, when we use
`UserSelectTextType`, we can pass a `finder_callback` option if we need to do a
custom query. If we did that, it would override the default value and, when we
instantiate `EmailToUserTransformer`, the second argument would be the callback
that *we* passed from `ArticleFormType`.

This is how options are used internally by the core Symfony types. Oh, and you
probably noticed that, just like our `UserSelectTextType`, *every* field type in
Symfony is a normal, PHP class! If you're ever want to know more about how a specific
field or option works, just open it up!

For example, we know that this field is a `DateTimeType`. Press Shift+Shift and
look for `DateTimeType` - open the one from the Form component. This class will
look a lot like our class! It has a `build()` method and adds some transformers.
If you scroll down far enough, cool! There is the `configureOptions()` method where
*all* of the valid options defined are for this field.

Want to know how one of those options is used? Copy its name and find out! Let's
search for the `with_seconds` option. No surprise: it's used in `buildForm()`. If
you looked a little further, you'd see that this is eventually used to configure
how the data tranformer works.

These core classes are a great way to figure out how to do something really advanced
or to get inspiration for your own custom field type.
