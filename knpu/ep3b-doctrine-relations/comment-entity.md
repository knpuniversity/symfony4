# Adding a Comment Entity

Hi friends! I mean, hello fellow space-traveling developer... friends. And welcome,
to our *second* Doctrine tutorial where we talk *all* about... relationships.
Oh, I *love* relationships, and there are so many beautiful types in the universe!
Like, the relationship between two friends, as they high-five after a long trip
between solar systems. Or, the complex relationship between a planet and a moon:
a perfect gravitational dance between BFF's. And of course, the most *incredible*
type of relationship in the galaxy... database relationships.

Yep, we learned a *ton* about Doctrine in the first tutorial, but we *completely*
avoided this topic! And it turns out, database relationships are *pretty* darn
important in real applications. So let's *dominate* them.

## Project Setup

As always, to have the *best* possible relationship with Doctrine, you should totally
code along with me. Download the course code from this page. After you unzip the file,
you'll you'll find a `start/` directory that will have the same code you see here.
Check out the `README.md` file for setup instructions, and the answer to this
KnpU-original space riddle: 

> I'm not white and fluffy, but pieces of me *do* orbit the sun. What am I?

Want to know? Then download the course code! Anyways, the last setup step will be
to open a terminal, move into the project directory and run:

```terminal
php bin/console server:run
```

to start the built-in web server. Then, celebrate by finding your terminal, and
loading `http://localhost:8000`. Hello: The Space Bar! Our hot new app that helps
spread *real* news to curious astronauts across the galaxy.

And thanks to the *last* tutorial, these articles are being loaded dynamically
from the database. But... these comments at the bottom? Yep, *those* are still
hardcoded. We need to fix that! And this will be our first relationship: each
Article can have *many* Comments. But, we'll get to that.

## Creating the Comment Entity

Before we do, in the `src/Entity` directory, the *only* entity we have so far is
`Article`. So before we talk about relationships, let's *first* build the `Comment`
entity. We *could* create this by hand, but I like the generator better.

Open a new terminal tab and run:

```terminal
php bin/console make:entity
```

Let's create a new entity called Comment. Then, for the fields, we need one for the
author and one for the actual comment. Add `authorName` as a string field. And yea,
*someday*, we might have a `User` table. And then, this could be a *relationship*
to that table. But for now, keep it as a simple string.

Next, add `content` as a `text` field, and also say no to nullable. Hit enter one
more time to finish up.

Oh, but *before* we generate the migration, go open the new `Comment` class. No
surprises: `id`, `authorName`, `content` and some getter & setter methods. At the
top of the class, let's add `use TimestampableEntity`.

This will give us `createdAt` and `updatedAt` fields.

*Now* head back to your terminal and run:

```terminal
php bin/console make:migration
```

When that finishes, go find the new file. We just want to make sure that this doesn't
contain any surprise. For example, if you're working on multiple branches, then
your database may be out-of-sync *before* you run `make:migration`. If that happens,
the migration file could contain *extra* changes that you'll want to remove. In this
case, it looks *great*.

Go back to your terminal and, migrate!

```terminal
php bin/console doctrine:migrations:migrate
```

Perfect! We have an `article` table and *now* a `comment` table. But, they have
*no* relation between them... yet. Let's add one next!
