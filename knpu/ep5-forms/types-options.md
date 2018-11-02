# Field Types & Options

Our form has an input `text` field and a `textarea`... which is *interesting* because,
in our form class, all *we've* done is give each field a name. We... actually have
*not* said *anything* about what "type" of field each should be.

But, we *now* know that, because we've bound our form to our entity, the form type
"guessing" system is able to *read* the Doctrine metadata, notice that `content`
looks like a big field, and "guess" that it should be a textarea.

## Setting the Field Type

Field type guessing is cool! But... it's not meant to be perfect: we need a way
to take control. How? It turns out that the `add()` method has *three* arguments:
the field name, the field *type* and some options.

For `title` pass, `TextType::class` - the one from `Form\Extensions`. Go back to
the form refresh and... absolutely *nothing* changes! Symfony was already "guessing"
that this was a `TextType` field.

## So Many Built-in Field Types

Google for "Symfony forms", click into the Symfony form documentation and find a
section called "Built-in Field Types". Woh. It turns out that there are a *ton*
of built-in field types. Yep, there's a field type for *every* HTML5 field that
exists, as well as a few other, special ones.

Click into `TextType`. In addition to choosing which *type* you need, every type
is super configurable. Many of these options are global, meaning, the options can
be used for *any* field type. A good example is the `label` option: you can set
a `label` option for *any* field, regardless of its type.

But other options are specific to that field type. We'll see an example in a minute.

Check out this `help` option: we can define a "help" message for any field.
That sounds awesome! Back in the form class, add a third argument: an options
array. Pass `help` and set it to "Choose something catchy".

[[[ code('2bfb18109d') ]]]

Let's go check it out! Refresh! I like that! Nice little help text below the field.

## The Form Profiler

This *finally* gives us a reason to check out one of the *killer* features of
Symfony's form system. Look down at the web debug toolbar and find the little
clipboard icon. Click that.

Yes! Say hello to the form profiler screen! We can see our *entire* form and the
individual fields. Click on the `title` field to get all sorts of information about
it. We're going to look at this *several* more times in this tutorial and learn
about each piece of information.

## Form Options in Profiler

Under "Passed Options" you can see the `help` option that we just passed. But,
what's *really* cool are these "Resolved Options". This is a list of *every* option
that was used to control the rendering & behavior of this *one* field. A lot of
these are low-level and don't directly do anything for *this* field - like the CSRF
stuff. That's why the official docs can sometimes be easier to look at. But, this
is an *amazing* way to see what's *truly* going on under the hood: what are *all*
the options for this field and their values.

And, yea! We can override *any* of these options via the third argument to the
`add()` method. Without even reading the docs, we can see that we could pass a
`label` option, `label_attr` to set HTML attributes on your label, or a `required`
option... which actually has nothing to do with validation, but controls whether
or not the HTML5 `required` attribute should be rendered. More on that later.

Anyways, let's add another, more *complex* field - and use its options to totally
transform how it looks and works.
