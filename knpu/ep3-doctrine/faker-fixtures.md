# Using Faker for Seeding Data

The problem *now* is that our dummy data is super, duper boring. It's all the *same*
stuff, over, and over again. Honestly, I keep falling asleep when I see the homepage.
Obviously, as good PHP developers, you guys know that we could put some random code
here and there to spice things up. I mean, we *do* already have a random `$publishedAt`
date:

[[[ code('24bcf4aa9e') ]]]

But, instead of creating that random data by hand, there's a *much* cooler way.
We're going to use a library called Faker. Google for "Faker PHP" to find the GitHub
page from Francois Zaninotto. Fun fact, Francois was the *original* documentation
lead for symfony 1. He's awesome.

Anyways, this library is all about creating dummy data. Check it out: you can use
it to generate random names, random addresses, random text, random letters, numbers
between this and that, paragraphs, street codes and even winning lottery numbers!
Basically, it's awesome.

## Installing Faker

So let's get it installed. Copy the composer require line, move over and paste.
But, add the `--dev` at the end:

```terminal-silent
composer require fzaninotto/faker --dev
```

Because we're going to use this library for our fixtures only - it's not needed
on production.

## Setting up Faker

When that finishes, head back to its docs so we can see how to use it. Ok: we just
need to say `$faker = Faker\Factory::create()`. Open our `BaseFixture` class: let's
setup Faker in this, central spot. Create a new protected `$faker` property:

[[[ code('98bca45dfa') ]]]

And down below, I'll say, `$this->faker =` and look for a class called `Factory` from
Faker, and `::create()`:

[[[ code('085ed469bb') ]]]

We should also add some PHPDoc above the property to help PhpStorm know what type
of object it is. Hold `Command` - or `Ctrl` - and click the `create()` method:
let's see what this returns exactly. Apparently, it returns a `Generator`.

Cool! Above the property, add `/** @var Generator */` - the one from `Faker`:

[[[ code('822383c896') ]]]

Perfect! Now, using Faker will be as easy as pie! Specifically, *eating* pie, cause,
that's super easy.

## Generating Fake Data

Open `ArticleFixtures`. We already have a little bit of randomness. But, Faker
can even help here: change this to if `$this->faker->boolean()` where the first
argument is the chance of getting `true`. Let's use 70: a 70% chance that each
article will be published:

[[[ code('25dd14b800') ]]]

And below, we had this *long* expression to create a random date. *Now* say,
`$this->faker->dateTimeBetween('-100 days', '-1 days')`:

[[[ code('b7d8175db6') ]]]

I love it! Down for `heartCount`, use another Faker function:
`$this->faker->numberBetween(5, 100)`:

[[[ code('bb9b49696c') ]]]

After these few improvements, let's make sure the system *is* actually as easy
as pie. Find your terminal and run:

```terminal
php bin/console doctrine:fixtures:load
```

No errors and... back on the browser, it works! Of course, the *big* problem
is that the title, author and article images are *always* the same. Snooze.

Faker *does* have methods to generate random titles, random names and even random
images. But, the *more* realistic you make your fake data, the easier it will be
to build real features for your app.

## Generating Controller, Realistic Data

So here's the plan: go back to `ArticleFixtures`. At the top, I'm going to paste
in a few static properties:

[[[ code('458f79b826') ]]]

These represent some realistic article titles, article images that exist, and two
article authors. So, instead of making *completely* random titles, authors and
images, we'll randomly choose from this list.

But even here, Faker can help us. For title, say `$this->faker->randomElement()`
and pass `self::$articleTitles`:

[[[ code('c076d3dc1d') ]]]

We'll let Faker do all the hard work.

For `setSlug()`, we *could* continue to use this, but there is also a `$faker->slug`
method:

[[[ code('567d01c891') ]]]

The slug will now be totally different than the article title, but honestly, who cares?

For author, do the same thing: `$this->faker->randomElement()` and pass
`self::$articleAuthors`:

[[[ code('455394cad7') ]]]

Copy that, and repeat it one more time for the `imageFile`, this time using
`self::$articleImages`:

[[[ code('27764a4138') ]]]

Awesome! Let's go reload those fixtures!

```terminal-silent
php bin/console doctrine:fixtures:load
```

No errors! Find your browser and, try it! Oh, it's *so* much better.

If creating nice, random data seems like a small thing, it's not! Having rich data
that you can easily load will increase your ability to create new features and
fix bugs fast. It's *totally* worth it.

Next! Let's install a *really* cool library with automatic slugging super-powers.
