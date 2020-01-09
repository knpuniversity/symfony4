# Fixing our Deprecations: Form, Controller & Mailer

We are now *super* close to fixing *all* the deprecation warnings that block us
from going to Symfony 5. Let's check out the current list for the homepage.
There are *technically* 12 deprecations. But remember, we can ignore all the
ones from `doctrine/persistence` because they're not related to Symfony.

## Form getExtendedTypes() Deprecation

With that in mind... if you look closely, there are really only *two* real
deprecations left... and they look like the same thing: something about
`TextareaSizeExtension` should implement a static `getExtendedTypes()` method.

This `TextareaSizeExtension` class is a "form type extension" that we built in
an earlier tutorial. Let's go check it out:
`src/Form/TypeExtension/TextareaSizeExtension.php`

And... PhpStorm is *immediately* mad at us:

> class must be declared abstract or implement method `getExtendedTypes()`.

This is the error you see when you have a class that implements an interface but
is *missing* one of the methods that the interface requires. But in this case,
that's not *technically* true. Hold command or control and click the interface
to jump to that file.

In reality, there is *no* `getExtendedTypes()` method on this interface! It has
`getExtendedType()` - that's the old, deprecated method - but no
`getExtendedTypes()`. It's not actually on the interface, it's just *described*
on top of the class in comments.

You're seeing Symfony's deprecation system in action. If Symfony suddenly added
this new `getExtendedTypes()` method to the interface in Symfony 4.4, it would
have broken our app when we upgraded. That would violate Symfony's
backwards-compatibility promise... which basically says: we will *not* break your
app on a minor upgrade.

Instead Symfony *describes* that you need this method and *warns* you to add it
via the deprecation system. It *will* be added to the interface in Symfony 5.0.
Our job is to add this new static `getExtendedTypes()` method that returns
`iterable`.

We got this! At the bottom of our class, add
`public static function getExtendedTypes()` with an `iterable` return type.
Inside, return an array with the same class as the old method.

As soon as we do this, the old, `getExtendedType()` method won't be called anymore.
And it will be *gone* from the interface in Symfony 5.0. But we *do* need to keep it
temporarily... because, again, for backwards compatibility, it *does* still exist
on the interface in Symfony 4.4. If we removed it from our class, PHP would
be super angry. I'll add a comment:

> not used anymore, remove in 5.0

Cool! Let's go close the profiler, refresh and open the new deprecations list.
And... hey! Ignoring the `doctrine/persistence` stuff, our homepage is now *free*
of deprecations!

Does that mean our app is ready for Symfony 5? Ah... not so fast: we still need
to do a few more things to be *sure* that no deprecated code is hiding.

## Clearing the Cache to Trigger Deprecations

For example, sometimes deprecations hide in the cache-building process. Find your
terminal and run:

```terminal
php bin/console cache:clear
```

This will force Symfony to rebuild its container, a process which *itself* can
sometimes contain deprecation warnings. Refresh the homepage now: still 10
deprecation warnings but... oh! One of these is different!

> `CommentAdminController` extends `Controller`: that is deprecated, use
> `AbstractController` instead.

## Controller to AbstractController

Let's go find this: `src/Controller/CommentAdminController.php`. Very simply:
change `extends Controller` to `extends AbstractController`. I'll also remove
the old `use` statement.

These two base-classes work *almost* the same. The only difference is that,
once you use `AbstractController`, you can't use `$this->get()` or
`$this->container->get()` to fetch services by their id.

## Mailer: NamedAddress to Address

Ok! Another deprecation down and the homepage is *once* again not triggering
any deprecated code. Let's surf around and see if we notice any other deprecations...
how about the registration page: `SymfonyNerd@example.com`, any password,
agree to the terms and... woh! That's not a deprecation... that's a huge error!

In theory, you should *never* get an error after a "minor" version upgrade - like
Symfony 4.3 to 4.4. But this is coming from Symfony's Mime component, which is
part of Mailer. And because Mailer was experimental until Symfony 4.4 there
*were* some breaking changes from 4.3 to 4.4. We saw this one mentioned earlier
when we looked at the Mailer CHANGELOG. Basically, `NamedAddress` is now called
`Address`.

Where do we use `NamedAddress`? Let's find out! At your terminal, my favorite way
to find out is to run:

```terminal
git grep NamedAddress
```

It's used in `SetFromListener`, `Mailer` and `MailerTest`. Let's do some updating.
I'll start with `src/Service/Mailer.php`: change the `use` statement from
`NamedAddress` to `Address`, then search for `NamedAddress` and remove the `Named`
part here and in one other place.

Next is `EventListener\SetFromListener`. Make the same change on top... and below.
The last place is inside of `tests/`: `Service\MailerTest`. Let's see: remove
`Named` from the `use` statement... and then it's used below in 2 places.

Got it! Let's try the registration page now: refresh and... validation error.
Change to a new email, agree to the terms and... got it!

Ok, the deprecations are gone from the homepage and registration page at *least*.
Are we done? How can we be sure?

Next, let's use a few more tricks to *really* be sure the deprecations are gone.
