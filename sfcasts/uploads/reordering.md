# Reordering the Files

What else do you want to add to our file gallery widget? How about allowing
them to be reordered? Yea, that isn't *really* related to uploading either, but
a lot of people asked for it... so, let's do it!

## Adding the position Field

To start, the `ArticleReference` entity needs a field that can store its order
in the list. Find your terminal and run:

```terminal
php bin/console make:entity
```

Update `ArticleReference` and add one new field `position`. This is an integer
and make it not nullable. Cool!

Go find the property... there it is. Make it default to 0: until the user
decides to reorder stuff, setting them all to 0 is fine.

[[[ code('58764a5770') ]]]

Create the migration with the usual:

```terminal
php bin/console make:migration
```

and go to the `src/Migrations` directory so we can make sure it doesn't contain
any surprises. Looks perfect! Close that and run:

```terminal
php bin/console doctrine:migrations:migrate
```

## Adding the Sortable Library

Ok, the database is ready! For the frontend, there are a *ton* of libraries that
can help you sort and reorder stuff. I'm going to use one called Sortable. It's
got a lot of support and *tons* of options. We'll need a few of them.

If you're using Webpack Encore, I'd recommend installing this via yarn and then
importing the library when you need it. Because we're not, I'll Google for
"sortablejs cdn". It's this one, from jsdelivr - the first is a different library.
It turns out "Sortable"... is a pretty generic name.

Click to copy the HTML+SRI script tag, then go find the edit template. Scroll down
to the JavaScript block and... paste!

[[[ code('73bf340679') ]]]

Hey! We *now* have a global `Sortable` variable.

## Integrating Sortable

Next, open `admin_article_form.js` and scroll up to the constructor so we can
start using this. Here's the plan: we're going to make each element - each "row" -
sortable. And when we finish dragging, we'll send an AJAX request to save the
new positions.

Add `this.sortable = Sortable.create()`. We're storing the *instance* of our new
sortable object onto a property because we'll need it later. Pass this the *parent*
of the elements that should be sortable. So... hmm... in our case, we want to attach
sortable to the `<ul>` element that's around everything. Fortunately, that's *exactly*
what `this.$element` represents! So we can say `this.$element`, and, this actually
wants a raw HTMLElement, not a jQuery object, so add `[0]`.

[[[ code('23eafae906') ]]]

Give it a test! Refresh... and grab... sweet! When we finish ordering, nothing
*saves* yet, but we'll get there.

## Making it Nicer!

Before we do, I think we can make this a bit nicer. Pass a second argument to `create()`:
an array of options. Pass one called `handle` set to `.drag-handle`.

[[[ code('2902c02a9e') ]]]

With this, instead of being able to grab *anywhere* to start sorting, we'll only
be able to grab elements with this class. Down in render, how about, *before*
the text field, add `<span class="drag-handle">`, and `fa` and `fa-reorder`.

[[[ code('e33579ff10') ]]]

Oh, and *while* we're making this fancy, add `animation: 150`... it just makes it
look cooler. Try it! There's our drag handle and... nice - it's a bit smoother.

[[[ code('7c7fc5c144') ]]]

This library doesn't require *any* CSS, which is cool... but we *can* make it
a little nicer by adding some. In the `public/css/` directory, open `styles.css`.
This is a nice, boring, normal CSS file that's included on every page.

Add `.sortable-ghost`. When you're dragging, Sortable adds this class to *where*
the element will be added if you stop sorting at that moment. Give this a background
color. Oh, and also, give the `drag-handle` a `cursor: grab`.

[[[ code('df41a1fa43') ]]]

Try it one more time - do a force refresh if it doesn't show up at first. And...
there's the blue background!

Ok, the database is setup and the frontend is ready. Next, let's add an
API endpoint to save the positions and make sure they're rendered in the right
order.
