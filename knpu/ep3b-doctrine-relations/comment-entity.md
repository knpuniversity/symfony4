# Adding a Comment Entity

Hey friends! I mean, hello fellow space-traveling developer... friends. Welcome,
to part *two* of our Doctrine tutorial where we talk *all* about... relationships.
Oh, I *love* relationships, and there are so *many* beautiful types in the universe!
Like, the relationship between two old friends, as they high-five after a grueling
trip between solar systems. Or, the complex relationship between a planet and a moon:
a perfect gravitational dance between BFF's. And of course, the most *incredible*
type of relationship in *all* of the galaxy... database relationships.

Sure, we learned a *ton* about Doctrine in the first tutorial, but we *completely*
avoided this topic! And it turns out, database relationships are *pretty* darn
important if you want to build one of those "real" applications. So let's *crush*
them.

## Project Setup

As always, to have the *best* possible relationship with Doctrine, you should totally
code along with me. Download the course code from this page. After you unzip the file,
you'll find a `start/` directory that will have the same code you see here.
Check out the `README.md` file for setup instructions, and the answer to this
KnpU space riddle: 

> My name sounds white & fluffy, but I'm not! And instead of *blocking* the sun,
> I orbit it.

Need to know the answer? Then download the course code! Anyways, the last setup step
will be to open a terminal, move into the project directory and run:

```terminal
php bin/console server:run
```

to start the built-in web server. Then, celebrate by finding your browser, and
loading `http://localhost:8000`. Hello: The Space Bar! Our hot new app that helps
spread *real* news to curious astronauts across the galaxy.

And thanks to the *last* tutorial, these articles are being loaded dynamically
from the database. But... these comments at the bottom? Yea, *those* are still
hardcoded. We need to fix that! And this will be our first relationship: each
Article can have *many* Comments. But, more about that later.

## Creating the Comment Entity

In the `src/Entity` directory, the *only* entity we have so far is `Article`:

[[[ code('b7de454ea2') ]]]

So *before* we can talk about relationships, we *first* need to build a `Comment`
entity. We *could* create this by hand, but the generator is so much nicer:

Open a new terminal tab and run:

```terminal
php bin/console make:entity
```

Name the entity Comment. Then, for the fields, we need one for the author and one
for the actual comment. Add `authorName` as a string field. And yea, *someday*,
we might have a `User` table. And then, this could be a *relationship* to that table.
But for now, keep it as a simple string.

Next, add `content` as a `text` field, and also say no to nullable. Hit enter one
more time to finish up.

Oh, but *before* we generate the migration, go open the new `Comment` class:

[[[ code('5c77c90dea') ]]]

No surprises: `id`, `authorName`, `content` and some getter & setter methods.
At the top of the class, let's add `use TimestampableEntity`:

[[[ code('a714563ae3') ]]]

That will give us `$createdAt` and `$updatedAt` fields.

*Now* head back to your terminal and run:

```terminal
php bin/console make:migration
```

When that finishes, go find the new file. We *just* want to make sure that this doesn't
contain any surprises. For example, if you're working on multiple branches, then
your database may be out-of-sync *before* you run `make:migration`. If that happens,
the migration file would contain *extra* changes that you'll want to remove. In this
case, it looks *great*:

[[[ code('f48b29d6f6') ]]]

Go back to your terminal and, migrate!

```terminal
php bin/console doctrine:migrations:migrate
```

Perfect! We have an `article` table and *now* a `comment` table. But, they are
*not* friends yet. Time to add a relation!
