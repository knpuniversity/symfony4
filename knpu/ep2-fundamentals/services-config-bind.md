# services.yaml & the Amazing bind

When Symfony loads, it needs to figure out *all* of the services that should be in
the container. Most of the services come from external bundles. But we *now* know
that *we* can add our *own* services, like `MarkdownHelper`. We're unstoppable!

*All* of that happens in `services.yaml` under the `services` key:

[[[ code('9e35eff985') ]]]

This is *our* spot to add *our* services. And I want to demystify what the config
in this file *actually* does:

[[[ code('46d2bec83a') ]]]

All of this - except for the `MarkdownHelper` stuff we *just* added - comes
standard with every new Symfony project.

## Understanding _defaults

Let's start with `_defaults`:

[[[ code('dfe67b136e') ]]]

This is a special key that sets *default* config values that should be applied to
*all* services that are registered in this *file*.

For example, `autowire: true` means that any services registered in this file should
have the autowiring behavior turned on:

[[[ code('77d81ef024') ]]]

Because yea, you can *actually* set autowiring to `false` if you want. In fact,
you could set `autowiring` to `false` on just *one* service to override these defaults:

```yaml
services:
    _defaults:
        autowire: true
    # ...
    App\Service\MarkdownHelper:
        autowire: false
    # ...
```

The `autoconfigure` option is something we'll talk about during the last chapter
of this course - but it's not too important:

[[[ code('25e12c171c') ]]]

We'll also talk about `public: false` even sooner:

[[[ code('3cd10bfbce') ]]]

The point is: we've established a few *default* values for any services that this
file registers. No big deal.

## Service Auto-Registration

The *real* magic comes down here with this `App\` entry:

[[[ code('3a112ac29d') ]]]

This says:

> Make *all* classes inside `src/` available as services in the container.

You can see this in real life! Run:

```terminal
php bin/console debug:autowiring
```

At the top, yep! Our controller and `MarkdownHelper` appear in this list. And any
*future* classes will also show up here, automatically.

But wait! Does that mean that all of our classes are *instantiated* on every single
request? Because, that would be *super* wasteful!

Sadly... yes! Bah, I'm kidding! Come on - Symfony kicks way more but than that!
No: this line simply tells the container to be *aware* of these classes. But services
are *never* instantiated until - and *unless* - someone asks for them. So, if we
didn't ask for our `MarkdownHelper`, it would never be instantiated on that request.
Winning!

## Services are only Instantiated Once

Oh, and one important thing: each service in the container is instantiated a maximum
of *once* per request. If *multiple* parts of our code ask for the `MarkdownHelper`,
it will be created just *once*, and the *same* instance will be passed each time.
That's *awesome* for performance: we don't need *multiple* markdown helpers... even
if we need to call `parse()` multiple times.

## The Services exclude Key

[[[ code('fdf5abb615') ]]]

The `exclude` key is not too important: if you *know* that some classes don't need
to be in the container, you can exclude them for a small performance boost in the
`dev` environment only.

So between `_defaults` and this `App\` line - which we have given the fancy name -
"service auto-registration" - everything just... works! New classes are added to
the container and autowiring handles most of the heavy-lifting!

Oh, and this last `App\Controller\` part is not important:

[[[ code('43dd70bd34') ]]]

The classes in `Controller\` are *already* registered as services thanks to
the `App\` section. This adds a special `tag` to controllers... which you
just *shouldn't* worry about. Honestly.

Finally, at the bottom, if you need to configure *one* service, this is where
you do it: put the class name, then the config below:

[[[ code('8dc8fb95a5') ]]]

## Services Ids = Class Name

And actually, this is *not* the *class name* of the service. It's *really* the
service *id*... which happens to be equal to the class name. Run:

```terminal
php bin/console debug:container --show-private
```

*Most* services in the container have a "snake case" service id. That's the best-practice
for re-usable bundles. But thanks to service auto-registration, *our* service id's
are equal to their class name. I just wanted to point that out.

## The Amazing bind

Thanks to *all* of this config... well... we don't need to spend much time in this
config file! We *only* need to configure the "special cases" - like we did for
`MarkdownHelper`.

And actually.. there's a *much* cooler way to do that! Copy the service id and
delete the config:

[[[ code('423fc895dc') ]]]

If we didn't do *anything* else, Symfony would once-again pass us the "main" Logger
object.

Now, add a new key beneath `_defaults` called `bind`. Then add `$markdownLogger`
set to `@monolog.logger.markdown`:

[[[ code('fd4c21f987') ]]]

Copy that argument name, open `MarkdownHelper`, and rename the argument from
`$logger` to `$markdownLogger`. Update it below too:

[[[ code('7133032178') ]]]

Ok: `markdown.log` still only has one line. And... refresh! Check the file...
hey! It worked!

I *love* `bind`: it says:

> If you find *any* argument named `$markdownLogger`, pass this service to it.

And because we added it to `_defaults`, it applies to *all* our services. Instead
of configuring our services one-by-one, we're creating project-wide *conventions*.
Next time you need this logger? Yep, just name it `$markdownLogger` and keep coding.

Next! In addition to services, the container can *also* hold flat configuration:
called *parameters*.
