# Activating Timestampable

Ok, let's add Timestampable! First, we need to activate it, which again, is described
*way* down on the bundle's docs. Open `config/packages/stof_doctrine_extensions.yaml`,
and add `timestampable: true`:

[[[ code('16a4a99e35') ]]]

Second, your entity needs some annotations. For this, go back to the *library's*
docs. Easy enough: we just need `@Gedmo\Timestampable`.

Back in our project, open `Article` and scroll down to find the new fields. Above
`createdAt`, add `@Timestampable()` with `on="create"`:

[[[ code('843922e923') ]]]

Copy that, paste above `updatedAt`, and use `on="update"`:

[[[ code('4ed322ef87') ]]]

That should be it! Find your terminal, and reload the fixtures!

```terminal
php bin/console doctrine:fixtures:load
```

No errors... but, let's make sure it's actually working. Run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article'
```

Yes! They *are* set! And each time we update, the `updated_at` will change.

## The TimestampableEntity Trait

I *love* Timestampable. Heck, I put it *everywhere*. And, fortunately, there is
a *shortcut*! Yea, we did *way* too much work.

Check it out: completely delete the `createdAt` and `updatedAt` fields that we
so-carefully added. And, remove the getter and setter methods at the bottom too:

[[[ code('7f1ac5957a') ]]]

But now, *all* the way on top, add `use TimestampableEntity`:

[[[ code('10b678938c') ]]]

Yea! Hold `Command` or `Ctrl` and click to see that. *Awesome*: this contains the
*exact* same code that we had before! If you want Timestampable, *just* use this
trait, generate a migration and... done!

And, talking about migrations, there *could* be some slight column differences between
these columns and the original ones we created. Let's check that. Run:

```terminal
php bin/console make:migration
```

> No database changes were detected

Cool! The fields in the trait are identical to what we had before. That means that
we can already test things with:

```terminal
php bin/console doctrine:fixtures:load
```

Thank you `TimestampableEntity`!


## Up Next: Relations!

Ok guys! I hope you are *loving* Doctrine! We just got a *lot* of functionality
fast. We have magic - like Timestampable & Sluggable - rich data fixtures, and a
rocking migration system.

One thing that we have *not* talked about yet is production config. And... that's
because it's already setup. The Doctrine recipe came with its own
`config/packages/prod/doctrine.yaml` config file, which makes sure that anything
that *can* be cached easily, *is* cached:

[[[ code('3ef42da764') ]]]

This means you get nice performance, out-of-the-box.

The other *huge* topic that we have *not* talked about yet is Doctrine *relations*. But,
we should *totally* talk about those - they're awesome! So let's do that in our
next tutorial, with foreign keys, join queries and high-fives so that we can create
a *really* rich database.

Alright guys, seeya next time.
