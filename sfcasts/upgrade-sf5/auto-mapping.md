# Validation Auto-Mapping

Head over to `/admin/article` and log in as an admin user:
`admin1@thespacebar.com` password `engage`. Use this unchecked admin *power*
to go to `/admin/article` and click to create a new article.

I *love* the new "secrets" feature... but what I'm about to show you might be
my *second* favorite new thing. It actually comes from Symfony 4.3 but was
improved in 4.4. It's called: validation auto-mapping... and it's one more step
towards robots doing my programming for me.

Start by going into `templates/article_admin/_form.html.twig`:

[[[ code('b96f0b1534') ]]]

This is the form that renders the article admin page. To help us play with
validation, on the button, add a `formnovalidate` attribute:

[[[ code('7abdb8e694') ]]]

Thanks to that, after you refresh, HTML5 validation is *disabled* and we can submit
the entire form blank to see... our server-side validation errors. These come from
the annotations on the `Article` class, like `@Assert\NotBlank` above `$title`:

[[[ code('b31444ab91') ]]]

So it's *no* surprise that if we remove the `@Assert\NotBlank` annotation...
I'll move it as a comment below the property:

[[[ code('67584fadad') ]]]

That's as good as deleting it. And then re-submit the blank form... the validation
error is *gone* from that field.

## @Assert\EnableAutoMapping

Ready for the magic? Go back to `Article` and, on top of the class, add
`@Assert\EnableAutoMapping()`:

[[[ code('4af500c810') ]]]

As *soon* as we do that, we can refresh to see...  Kidding! We refresh
to see... the validation error is *back* for the `title` field!

> This value should not be null

Yep! A `@NotNull` constraint was *automatically* added to the property! How the
heck did that work? The system - validation auto-mapping - automatically adds
sensible validation constraints based off of your Doctrine metadata. The
Doctrine `Column` annotation has a `nullable` option and its *default* value is
`nullable=false`:

[[[ code('34a91a034d') ]]]

In other words, the `title` column is required in the database! And so,
a constraint is added to make it required on the form.

Auto-mapping can *also* add constraints based *solely* on how your code is written...
I'll show you an example of that in a few minutes. Oh, and by the way, to get the
most out of this feature, make sure you have the `symfony/property-info` component
installed.

```terminal-silent
composer show symfony/property-info
```

If that package doesn't come up, install it to allow the feature to get as *much*
info as possible.

## Auto-Mapping is Smart

Let's play with this a bit more, like change this to `nullable=true`:

[[[ code('c450de3380') ]]]

This means that the column should now be *optional* in the database. What happens
when we submit the form now? The validation error is gone: the `NotNull` constraint
was *not* added.

Oh, but it gets even *cooler* than this. Remove the `@ORM\Column` entirely - we'll
pretend like this property isn't even being saved to the database. I also need
to remove this `@Gedmo\Slug` annotation to avoid an error:

[[[ code('2d7e4fa64a') ]]]

What do you think will happen now? Well think about it: the auto-mapping system
won't be able to ask Doctrine if this property is required or not... so my guess
is that it *won't* add any constraints. Try it! Refresh!

Duh, duh, duh! The `NotNull` validation constraint is back! Whaaaaat? The
Doctrine metadata is just *one* source of info for auto-mapping: it can also
look directly at your *code*. In this case, Symfony looks for a setter method.
Search for `setTitle()`:

[[[ code('19b4d076da') ]]]

Ah yes, the `$title` argument is type-hinted with `string`. And because that
type-hint does *not* allow null, it assumes that `$title` must be required
and adds the validation constraint.

Watch this: add a `?` before `string` to make `null` an allowed value:

[[[ code('01b6285e81') ]]]

Refresh now and... the error is gone.

## Avoiding Duplicate Constraints

Let's put *everything* back to where it was in the beginning. What I *love*
about this feature is that... it's just so smart! It accuarely *reflects*
what your code is already communicating.

And even if I add back my `@Assert\NotBlank` annotation:

[[[ code('b001f5f384') ]]]

And refresh... check it out. We don't get 2 errors! The auto-mapping system is
smart enough to realize that, because I added a `NotBlank` annotation constraint
to this property, it should not *also* add the `NotNull` constraint: that would
basically be duplication and the user would see two errors. Like I said, it's smart.

## Automatic Length Annotation

And it's not all about the `NotNull` constraint. The length of this column in the
database is 255 - that's the default for a `string` type. Let's type a super-creative
title over and over and over and over again... until we know that we're above that
limit. Submit and... awesome:

> This value is too long. It should have 255 characters or less.

Behind-the-scenes, auto-mapping *also* added an `@Length` annotation to limit this
field to the column size. Say goodbye to accidentally allowing large input... that
then gets truncated in the database.

## Disabling Auto-Mapping when it Doesn't Make Sense

As *cool* as this feature is, automatic functionality will never work in *all*
cases. And that's fine for two reasons. First, it's *your* choice to opt-into
this feature by adding the `@EnableAutoMapping` annotation:

[[[ code('ee67ef65cc') ]]]

And second, you can disable it on a field-by-field basis.

A great example of when this feature can be a problem is in the `User` class.
Imagine we added `@EnableAutoMapping` here and created a registration form bound
to this class. Well... that's going to be a problem because it will add
a `NotNull` constraint to the `$password` field! And we *don't* want that!

[[[ code('ea70ba64c1') ]]]

In a typical registration form - like the one that the `make:registration-form`
command creates - the `$password` property is set to its hashed value only
*after* the form is submitted & validated. Basically, this is not a field the
user sets directly and having the `NotNull` constraint causes a validation error
on submit.

How do you solve this? You could disable auto-mapping for the whole class. Or,
you could disable it for the `$password` property *only* by adding
`@Assert\DisableAutoMapping`:

```php
// src/Entity/User.php

class User implements UserInterface
{
    // ...
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\DisableAutomapping()
     */
    private $password;
    // ...
}
```

This is the *one* ugly case for this feature, but it's easy to fix.

## Configuring Auto-Mapping Globally

Oh, and one more thing! You can control the feature a bit in
`config/packages/validator.yaml`. By default, you need to enable auto-mapping
on a class-by-class basis by adding the `@Assert\EnableAutoMapping` annotation:

[[[ code('324cf053f6') ]]]

But, you can also *automatically* enable it for specific namespaces:

[[[ code('93c3eeb3bd') ]]]

If we uncommented this `App\Entity` line, every entity would get auto-mapped
validation without needing the extra annotation. I like being a bit more explicit -
but it's your call.

Next, ready to talk about something super geeky? No, not Star Trek, but that
would awesome. This is probably *even* better: let's chat about password
hashing algorithms. Trust me, it's actually *pretty* neat stuff. Specifically,
I want to talk about safely *upgrading* hashed passwords in your database to stay
up-to-date with security best-practices.
