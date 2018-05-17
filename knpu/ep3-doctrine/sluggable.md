# Sluggable & other Wonderful Behaviors

We're using Faker to generate a random slug for each dummy article. Thanks to
this, back on the homepage, look at the URLs: they're *truly* random slugs: they
have no relation to the title.

But, really, shouldn't the slug be generated from the title? What I mean is,
if I set the Article's title, *something* should automatically convert that into
a slug and make sure it's unique in the database. *We* shouldn't need to worry about
doing that manually.

And... yea! There's a *really* cool library that can do this, and a *bunch* of other
magic! Google for `StofDoctrineExtensionsBundle`, and then click into its documentation.

Ok, let me explain something: there is a normal, PHP library called DoctrineExtension,
which can add a lot of different *behaviors* to your entities, like sluggable, where
you automatically generate the slug from another field. Other behaviors include
Loggable, where each change to an entity is tracked, or Blameable, where the user
who created or updated an entity is automatically recorded.

## Installing StofDoctrineExtensionsBundle

This *bundle* - `StofDoctrineExtensionsBundle` - helps to *integrate* that library
into a Symfony project. Copy the `composer require` line, find your terminal, and
paste!

```terminal-silent
composer require stof/doctrine-extensions-bundle
```

While that's working, let's go check out the documentation. This is a *wonderful*
library, but its documentation is *confusing*. So, let's navigate to the parts
we need. Scroll down to find a section called "Activate the extensions you want".

As we saw, there are a *lot* of different, possible behaviors. For performance
reasons, when you install this bundle, you need to *explicitly* say which behaviors
you want, like `timestampable`, by setting it to true.

## Contrib Recipes

Move back to the terminal to see if things are done. Oh, interesting! It stopped!
And it's asking us if we want to install the recipe for StofDoctrineExtensionsBundle.
Hmm... that's weird... because Flex has already installed *many* other recipes
*without* asking us a question like this.

But! It says that the recipe for *this* package comes from the "contrib" repository,
which is open to community contributions. Symfony has *two* recipe repositories.
The main repository is closely controlled for quality. The second - the "contrib"
repository - has some basic checks, but the community can freely contribute recipes.
For security reasons, when you download a package that installs a recipe from *that*
repository, it will ask you first before installing it. And, there's a link if you
want to review the recipe.

I'm going to say yes, permanently. *Now* the recipe installs.

## Configuring Sluggable

Thanks to this, we now have a shiny new `config/packages/stof_doctrine_extensions.yaml`
file:

[[[ code('6ee4477af8') ]]]

*This* is where we need to enable the extensions we want. We want `sluggable`.
We can use the example in the docs as a guide. Add `orm`, then `default`. The `default`
is referring to the *default* entity manager... because some projects can actually
have *multiple* entity managers. Then, `sluggable: true`:

[[[ code('4dc8291008') ]]]

As *soon* as we do this... drumroll... absolutely nothing will happen. Ok, behind
the scenes, the bundle *is* now looking for slug fields on our entities. But, we
need a *little* bit more config to activate it for `Article`. Open that class and
find the `slug` property.

Now, go *back* to the documentation. Another confusing thing about this bundle
is that the documentation is split in two places: this page shows you how to
configure the *bundle*... but *most* of the docs are in the *library*. Scroll up
and find the [DoctrineExtensions Documentation][doctrine_extensions_docs] link.

Awesome. Click into `sluggable.md`. Down a bit... it tells us that to use this feature,
we need to add an `@Gedmo\Slug()` annotation above the slug field. Let's do it! Use
`@Gedmo\Slug`, then `fields={"title"}`:

[[[ code('7d70322fe7') ]]]

That's all we need! Back in `ArticleFixtures`, we no longer need to set the slug
manually. Try it out: find your terminal, and load those fixtures!

```terminal
php bin/console doctrine:fixtures:load
```

No errors! That's a *really* good sign, because the `slug` column *is* required in
the database. Go back to the homepage and... refresh! Brilliant! The slug is clean
and *clearly* based off of the title! As an added benefit, look at how some of
these have a number on the end. The Sluggable behavior is making sure that each
slug is *unique*. So, if a slug already exists in the database, it adds a `-1`
, `-2`, `-3`, etc. until it finds an open space.

## Hello Doctrine Events

Side note: this feature is built on top of Doctrine's *event* system. Google for
"Doctrine Event Subscriber". You'll find a page on the Symfony documentation that
talks about this very important topic. We're not going to create our own event
subscriber, but it's a really powerful idea. In this example, they talk about how
you could use the event system to automatically update a search index, each time
any entity is created or updated. Behind the scenes, the sluggable features works
by adding an event listener that is called *right* before saving, or "flushing",
any entity.

If you ever need to do something automatically when an entity is added, updated
or removed, think of this system.

Next, let's find out how to rescue things when migrations go wrong!


[doctrine_extensions_docs]: https://github.com/Atlantic18/DoctrineExtensions/tree/v2.4.x/doc
