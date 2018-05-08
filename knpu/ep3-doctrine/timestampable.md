# Activating Timestampable

Ok, let's add timestampable! First, you need to activate it, which again, is described
*way* down on the bundle's docs. Open `config/packages/stof_doctrine_extensions.yaml`,
and add `timestampable: true`.

Second, your entity needs some annotations. For this, go back to the library's
docs. Easy enough: we just need `@Gedmo\Timestampable`.

Back in our project, open `Article` and scroll down to find the new fields. Above
`createdAt`, add `@Timestampable()` with `on="create"`. Copy that, paste above
`updatedAt`, and use `on="update"`.

We should be ready! Find your terminal, and reload the fixtures!

```terminal
php bin/console doctrine:fixtures:load
```

No errors... but to make sure it's working, run:

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM article'*
```

Yes! They're set! And each time we update, the `updated_at` will change.

## The TimestampableEntity Trait

I *love* Timestampable. Heck, I put it *everywhere*. And, fortunately, there is
a *shortcut*! Yea, we did *way* more work than we needed to.

Let me show you: completely delete the `createdAt` and `updatedAt` fields that we
so-carefully added. And, remove the getter and setter methods at the bottom too.

But now, *all* the way on top, `use TimestampableEntity`.

Yea! Hold Command or Ctrl and click to see that. *Awesome*: this contains the
*exact* same code that we had before! If you want Timestampable, *just* use this
trait, generate a migration and... done!

Oh, about that, there *could* be some slight column differences between these columns
and the original ones we created. Let's check that. Run:

```terminal
php bin/console make:migration
```

> No database changes were detected

Cool! The fields in the trait are identical to what we had before. So, we can
already test things with:

```terminal
php bin/console doctrine:fixtures:load
```

Thank you `TimestampableEntity`!


Ok guys! I hope you are *loving* Doctrine! We just got a *lot* of functionality
fast. We have magic - like Timestampable & Sluggable - rich data fixtures, and a
rocking migration system.

One thing that we have *not* talked about yet is production config. And... that's
because it's already setup. The Doctrine recipe came with its own `prod` config
file, which makes sure that anything that *can* be cached, *is* cached. This means
you get nice performance, out-of-the-box.

The *big* topic that we have *not* talked about yet is Doctrine relations. And
that'll be the topic of our next tutorial! We'll setup cool relationship, join
tables together, high five, and *really* make a rich database.

All right guys, see you next time.
