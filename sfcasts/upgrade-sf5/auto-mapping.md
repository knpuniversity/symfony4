# Validation Auto-Mapping

Head over to `/admin/article` and log in as an admin user:
`admin1@thespacebar.com` password `engage`. Use this unchecked, powerful admin
access to go to `/admin/article` and click to create a new article.

I *love* the new "secrets" Symfony... but what I'm about to show you might be
my *second* favorite new feature. It actually comes from Symfony 4.3 but was
improved in 4.4. It's called: validation auto-mapping... and it's one more step
towards robots doing my programming for me.

Start by going into `templates/article_admin/_form.html.twig`. This is the form
that renders the article admin page. To help us play with validation, on the button,
add a new `formnovalidate` attribute.

Thanks to that, after you refresh, HTML5 validation is *disabled* and we can submit
the entire form blank to see... our server-side validation errors. These come from
the annotations on our `Article` class, like `@Assert\NotBlank` above `$title`.

So it's *no* surprise that if we remove the `@Assert\NotBlank` annotation...
I'll move it as a comment below the property - that's as good as deleting it - and
then re-submit the blank form... the validation error is *gone* from the title field.

## @Assert\EnableAutoMapping

Ready for the magic? Go back to `Article` and, on top of the class, add
`@Assert\EnableAutoMapping()`.

As *soon* as we do that, we can refresh to see... a Rick Roll! Kidding! We refresh
and... the validation error is *back* for the `title` field!

> This value should not be null

Yep! An `@NotNull` constraint was *automatically* added to the property! How the
heck did that work? The system - validation auto-mapping - automatically adds
some sensible validation constraints based off of your Doctrine metadata. The
Doctrine `Column` annotation has a `nullable` option its *default* value is
`nullable=false`. In other words, the `title` column is required in the database!

Auto-mapping can *also* add constraints based *solely* on how your code is written...
I'll show you an example of that in a few minutes. Oh, and by the way, to make sure
you get the most out of this feature, make sure you have `symfony/property-info`
installed.

```terminal-silent
composer show symfony/property-info
```

If that package doesn't come up, install it to allow the feature to get as *much*
info as possible.

## Auto-Mapping is Smart

Let's play with this a bit more, like change this to `nullable=true`. The means
that the column should now be *optional* in the database. What happens when we
submit the form now? The validation error is gone: the `NotNull` constraint was
*not* added.

Oh, but it gets even *cooler* than this. Remove the `@ORM\Column` entirely - we'll
pretend like this property isn't even being saved to the database. I also need
to remove this `@Gedmo\Slug` annotation just to avoid an error.

What do you think will happen now? Well think about it: the auto-mapping system
won't be able to ask Doctrine if this property is required or not... so my guess
is that it *won't* be add any constraints. Try it! Refresh!

Duh, duh, duh! The `NotNull` validation is back! Whaaaaat? The Doctrine metadata
is just *one* source of info for auto-mapping: it can also look directly at your
source code. In this case, Symfony looks for a setter method. Search for
`setTitle()`. Ah yes, The `$title` argument is type-hinted with `string`. And because
that type-hint does *not* allow null, it assumes that `$title` must be required.

Watch this: add a `?` before `string` to make `null` an allowed value. Refresh
now and... the error is gone.

## Avoiding Duplicate Constraints

Let's put *everything* back to go back to where it was in the beginning. What I
*love* about this feature is that... it's just so smart! It simply *reflects*
what your code is already communicating.

And even if I add back the my `@Assert\NotBlank` annotation and refresh... check
it out. We don't get 2 errors! The auto-mapping system is smart enough to realize
that, because I added a `NotBlank` annotation constraint on this property, it should
not *also* add the `NoNull` constraint: that would basically be duplication and
the user would see two errors. Like I said, it's smart.

## Automatic Length Annotation

And it's not all about the `NotNull` constraint. The length of this column in the
database is 255 - that's the default for a `string` type. Let's type a super-creative
title over and over and over and over again... until we know that we're over that
limit. Submit and... awesome:

> This valid is too long. IT should have 255 characters or less.

Behind-the-scenes, auto-mapping *also* added a `@Length` annotation to limit this
field to the database limit. Say goodbye to forgetting this field and letting
long data get truncated!

## Disabling Auto-Mapping when it Doesn't Make Sense

As *cool* as this feature is, automatic functionality will never work in *all*
cases. And that's fine for two reasons. First, it's *your* choice to opt-into
this feature by adding the `@EnableAutoMapping` annotation. And second, you can
disable it on a field-by-field basis.

A great example of when this feature can be a problem is in the `User` class.
Imagine we added `@EnableAutoMapping` here and created a registration form to
help create this object. Well... that's going to be a problem because it will add
a `NotNull` constraint to the `$password` field! And we *don't* want that!
In a typical registration form - like the one that the `make:registration-form`
command creates - the `$password` property is set to its hashed value *after*
the form is validated. Basically, this is not a field the user sets directly
and having the `NotNull` constraint causes a validation error on submit.

How do you solve this? You could disable auto-mapping for the whole class. Or,
if you want, just disable it for the `$password` property by adding
`@Assert\DisableAutoMapping`. This is the *one* ugly case for this feature, but
it's easy to fix.

## Configuring Auto-Mapping Globally

Oh, and one more thing! You can control this feature a bit in
`config/packages/validator.yaml`. By default, you need to enable auto-mapping
on a class-by-class basis by adding the `@Assert\EnableAutoMapping` annotation.
But, you can also *automatically* enable it for specific namespaces. If we
uncommented this `App\Entity` line out, every entity will get auto-mapped
validation without the extra annotation. I like being a bit more explicit - but
it's your call.

Next, ready to talk about something super geeky? No, not Star Trek, but that
would awesome. This is *even* better: let's chat about password hashing algorithms
and safely *upgrading* hashed passwords in your database to stay up-to-date with
security best-practices.
