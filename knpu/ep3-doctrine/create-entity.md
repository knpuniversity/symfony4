# Creating an Entity Class

Doctrine is an ORM, or object relational mapper. A fancy term for a pretty cool
idea. It means that each table in the database will have a corresponding *class*
in our code. So if we want to create an `article` table, it means that we need to
create an `Article` *class*. You can *totally* make this class by hand - it's just
a normal PHP class.

## Generating with `make:entity`

But there's a *really* nice generation tool from MakerBundle. We installed MakerBundle
in the last tutorial, and before I started coding, I updated it to the latest version
to get this new command. At your terminal, run:

```terminal
php bin/console make:entity
```

Stop! That word "entity": that's important. This is the word that Doctrine gives
to the classes that are saved to the database. As you'll see in a second, these
are just normal PHP classes. So, when you hear "entity", think:

> That's a normal PHP class that I can save to the database.

Let's call our class `Article`, and then, cool! We can start giving it fields right
here. We need a `title` field. For field "type", hmm, hit "?" to see what *all*
the different types are.

Notice, these are *not* MySQL types, like varchar. Doctrine has its *own* types
that map to MySQL types. For example, let's use "string" and let the length be 255.
Ultimately, that'll create a varchar column. Oh, and because we probably want this
column to be *required* in the database, answer "no" for nullable.

Next, create a field called `slug`, use the `string` type again, and let's make
it's length be 100, and no for nullable.

Next, `content`, set this to `text` and "yes" to nullable: maybe we allow articles
to be drafted without content at first. And finally, a `publishedAt` field with
a type set to `datetime` and yes to nullable. If this field is null, we'll know
that the article has *not* been published.

When you're done, hit enter to finish. And don't worry if you make a mistake. You
can always update things later, or delete the new entity class and start over.

## Investigating the Entity Class

So... what did that just do? Only *one* thing: in `src/Entity`, this command generated
a new `Article` class:

[[[ code('bc744f624f') ]]]

Well... to be fully honest, there is also a new `ArticleRepository` class, but I want
you to ignore that for now. It's not important yet.

Anyways, this `Article` class is your *entity*. And, check it out! It's a normal,
boring PHP class with a property for each column: `id`, `title`, `slug`, `content`,
and `publishedAt`:

[[[ code('9c6b77d40b') ]]]

What makes this class *special* are the annotations! The `@ORM\Entity` above the
class tells Doctrine that this is an entity that should be mapped to the database:

[[[ code('0f54df4082') ]]]

Then, above each property, we have some annotations that help doctrine know how
to store that exact column:

[[[ code('e9557a9f9f') ]]]

Actually, find your browser and Google for "doctrine annotations reference"  to
find a cool page. This shows you *every* annotation in Doctrine and *every* option
for each one.

Back at the code, the properties are *private*. So, at the bottom of the class,
the command generated getter and setter methods for each one:

[[[ code('86b15fb1b1') ]]]

There's one *really* important thing to realize: this class is 100% *your* class.
Feel free to add, remove or rename any properties or methods you want.

And... yea! With one command, our entity is ready! But, the database is still empty!
We need to tell Doctrine to create the corresponding `article` table in the database.
We do this with migrations.
