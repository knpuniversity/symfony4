# Fixtures: Seeding Dummy Data!

We're creating our dummy article data in a *really*... uh... dummy way: with a,
sort of, "secret" endpoint that creates an almost-identical article, each time we
go there:

[[[ code('be36e50caa') ]]]

Honestly, it's not great for development: every article on the homepage pretty much
looks the same.

Yep, our dummy data sucks. And, that's important! If we could load a *rich* set
of random data easily, we could develop and debug faster.

## Installing DoctrineFixturesBundle

To help with this, we'll use a great library called DoctrineFixturesBundle...
but with our own spin to make things *really* fun.

First let's get it installed. Find your terminal and run

```terminal
composer require orm-fixtures --dev
```

And yep, this is a Flex *alias*, and we're using `--dev` because this tool will
help us load *fake* data for development... which is *not* something we need in
our production code. If you've ever accidentally replaced the production database
with dummy data... you know what I mean.

## Generating Fixture with make:fixtures

Perfect! When it finishes, generate our *first* fixture class by running:

```terminal
php bin/console make:fixtures
```

Call it `ArticleFixtures`. It's *fairly* common to have one fixture class per
entity, or sometimes, per group of entities. And... done!

Go check it out: `src/DataFixtures/ArticleFixtures.php`:

[[[ code('d00c92c2f1') ]]]

The idea behind fixtures is *dead* simple: step (1) we write code to create
and save objects, and then step (2), we run a new console command that executes
all of our fixture classes.

## Writing the Fixtures

Open `ArticleAdminController`: let's start stealing some code! Copy *all* of our
dummy article code, go back to the fixture class, and paste! We need to re-type
the `e` on `Article` and hit tab so that PhpStorm adds the `use` statement for
us on top:

[[[ code('7eb682e27d') ]]]

Then, at the bottom, the entity manager variable is called `$manager`:

[[[ code('564ba5f78d') ]]]

Back in the controller, just put a `die('todo')` for now:

[[[ code('9008f0b3b0') ]]]

Someday, we'll create a proper admin form here.

And... that's it! It's super boring and it only creates one article... but it should
work! Try it: find your terminal and run a new console command:

```terminal
php bin/console doctrine:fixtures:load
```

This will ask if you want to continue because - important note! - the command will
*empty* the database first, and *then* load fresh data. Again, *not* something
you want to run on production... not saying I've done that before.

When it finishes, find your browser, and refresh. It works!

## Creating Multiple Articles

But... come on! We're going to need more than *one* article! How can we create
multiple? First, we're going to do it the easy... but, kinda boring way. A `for`
loop! Say: for `$i = 0; $i < 10; $i++`. And, *all* the way at the bottom, add the ending
curly brace. We need to call `persist()` in the loop, but we only need to call `flush()`
once at the end.

Cool! Try it again:

```terminal
php bin/console doctrine:fixtures:load
```

Then, refresh! Awesome! Except that the articles are still *totally* boring and
identical... we'll talk about that in the next chapter.

## BaseFixture Class for Cooler Looping

But first, let me show you a *cooler* way to create multiple articles. In the
`DataFixtures` directory, create a new class called `BaseFixture`. Make it abstract,
and extend the normal class that all fixtures extend... so... `Fixture`:

[[[ code('67b6ca4e18') ]]]

Here's the idea: this will *not* be a fixture class that the bundle will execute.
Instead, it will be a *base* class with some cool helper methods. To start, copy
the `load()` method and implement it here. Re-type `ObjectManager` to get its `use`
statement:

[[[ code('e5d26b351a') ]]]

Oh, `ObjectManager` is an interface implemented by `EntityManager`, it's
not too important: just think "this is the entity manager".

Next, and this won't make sense *yet*, create a private `$manager` property, and
set it inside the `load()` method:

[[[ code('6bb7578217') ]]]

Finally, create an `abstract protected function` called `loadData()` with that same
`ObjectManager` argument:

[[[ code('caa1d61778') ]]]

Back in `load()`, call this: `$this->loadData($manager)`:

[[[ code('4fce89370a') ]]]

So far, this doesn't do anything special. Back in `ArticleFixtures`, extend the
new `BaseFixture` instead. I'll also cleanup the extra `use` statement:

[[[ code('bf70b6074e') ]]]

*Now*, instead of implementing `load()`, implement `loadData()` and make it protected:

[[[ code('f2b9a310eb') ]]]

And... yea! The fixture system will call `load()` on `BaseFixture`, that will call
`loadData()` on `ArticleFixtures` and... well... everything will work exactly like
before.

## Adding the createMany Method

So... why did we just do this? Go back to the `BaseFixture` class and, at the
bottom, I'm going to paste in a little method that I created:

[[[ code('0600164013') ]]]

Oh, and to make PhpStorm happy, at the top, add some PHPDoc that the `$manager`
property is an `ObjectManager` instance:

[[[ code('2bceede11a') ]]]

Anyways, say hello to `createMany()`! A simple method that we can call to create
multiple instances of an object. Here's the idea: we call `createMany()` and pass
it the class we want to create, how *many* we want to create, and a callback
method that will be called each time an object is created. That'll be our chance
to load that object with data.

Basically, it does the `for` loop for us... which is not a *huge* deal, except
for two nice things. First, it calls `persist()` for us, so we don't have to:

[[[ code('2133b723e0') ]]]

Ok, cool, but not *amazing*. But, this last line *is* cool:

[[[ code('2f4ddbcb39') ]]]

It won't matter yet, but in a future tutorial, we will have *multiple* fixtures classes.
When we do, we will need to be able to reference objects created in one fixture class
from *other* fixture classes. By calling `addReference()`, all of our objects are
automatically stored and ready to be fetched with a key that's their class name plus
the index number.

The point is: this is going to save us some serious work... but not until the *next*
tutorial.

Back in `ArticleFixtures`, use the new method: `$this->createMany()` passing
it `Article::class`, 10, and a function:

[[[ code('90510eff04') ]]]

This will have two args: the `Article` that was just created and a count of which
article this is. Inside the method, we can remove the `$article = new Article()`,
and instead of a random number on the slug, we can use `$count`:

[[[ code('0372f09dc8') ]]]

At the bottom, the persist isn't hurting anything, but it's not needed anymore.

Finish the end with a closing parenthesis and a semicolon:

[[[ code('bd124b8777') ]]]

So, it's a *little* bit fancier, and it'll save that important reference for us.
Let's try it! Reload the fixtures again:

```terminal
php bin/console doctrine:fixtures:load
```

No errors! Refresh the homepage: ah, our same, boring list of 10 identical articles.
In the next chapter, let's use an awesome library called Faker to give each article
rich, *unique*, realistic data.
