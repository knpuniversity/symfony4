# The buildView() Method

The autocomplete setup works nicely on the edit page. But, if you click to create
an article... it *looks* like it's working, but it doesn't! This is just the normal
autocomplete from my browser.

There's no JavaScript error and we *do* have the class and the `data-` attribute.
We expected this: we just haven't added the JavaScript to this page!

In `edit.html.twig`, the `javascripts` and `stylesheets` blocks bring in the magic.
Let's solve this in the simplest way possible. Copy both of these blocks. Open `new.html.twig`
and paste! Oh, and I mentioned earlier, that we're going to tweak things so that
the author field is *only* filled in on *create* - we'll disable it on edit. In
other words, once you set the author, it will be permanently set.

That means... we *don't* need any of this stuff on the edit page. Let's delete it
now. By the way, if you *did* need some JavaScript and CSS on both templates and
you did *not* want to duplicate the blocks, you could create a new template, like
`article_admin_base.html.twig`. *It* would extend `content_base.html.twig` and
include the `javascripts` and `stylesheets` blocks. Then, `edit.html.twig` and
`new.html.twig` would extend this.

Anyways, now that the JavaScript and CSS lives in the new template, when we refresh,
we have autocomplete.

## The buildView() Form Class Method

Before we move on, I have one cool thing I want to show you! And, it solves
a problem we have right now. Let's close a few of these files then go to
`UserSelectTextType`. The whole autocomplete system works because we are setting
a default value for our `attr` option with `class` and `data-autocomplete` keys.
Now open `ArticleFormType` where we use this field type. One of the things that we're
*allowed* to do here is override that `attr` option. But, if we did that, this custom
`attr` option would completely *replace* the `attr` default we set for the field!
We would lose all of the special attributes that we need!

No problem. At the bottom of `UserSelectTextType`, go to the Code -> Generate menu,
or command+N on a Mac, select Override methods choose `buildView()`. Notice there's
also a method called `finishedView()` and its purpose is *almost* the identical
to `buildView()`.

Here's what's going on: to render each field, Symfony creates a bunch of *variables*
that are used in the form theme system. We already know about these variables: in
`register.html.twig` we're overriding the `attr` variable. And in our form theme
block, we using different variables to do our work.

And, of course, we know that if we go into the profiler, we can see the *exact*
view variables that exist for *each* field. But... where do these variables come
from? Why does each field have a `full_name` variable? Who added that?

The answer is `buildView()`: Symfony calls this method on *every* field, and it is
*the* place where these variables are created and can be changed.

We do with this `$view` variable, which is kind of a strange object. Start with
`$attr = $view->vars['attr'];`. This `$view` object has a public `->vars` array
property that holds *all* of the things that will eventually become the "variables".
At this point, the core form system has *already* set this variable up for us: it
will either be equal to the `attr` option passed for this field, or empty if nothing
was passed.

Next: grab the class: if a `class` is set on `$attr`, use it, but add a space on
the end. If there is no class yet, so this to be blank. Now, here's the key: let's
*always* `js-user-autocomplete` - that's the class we're using above. Call
`$attr['class'] =` to set the new class string back on.

Oh, and we *also* meed to do the `data-autocomplete-url` attribute. Copy that from
above and say `$attr['data-autocomplete-url']` then equals the generated URL. Perfect!
*Finally*, set *all* of this back onto the view object with `$view->vars['attr'] = $attr`.

Phew! We're done! Now that we're setting the `attr` variable directly, we don't need
to set the option anymore. And the *best* part is that we know our attributes will
be rendered no matter *what* the user passes to the `attr` option.

With any luck, this should work! Move over refresh and cool! Nice work team! The
element still has the class and the data attribute.

And if you open the profiler for this form, you can see this! Click on the author
field and check out the View Variables. So cool! That's *exactly* what we set!

Next: the form system has a *crazy* powerful plugin system. Want to make some tweak
to *every* form or *every* field in your entire app? That's possible, and it's fun!
