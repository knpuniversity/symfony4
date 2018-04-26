# Using Faker for Seeding Data

The problem *now* is that our dummy data is super duper boring. It's all the *same*
stuff, over, and over again. Obviously, as good PHP developers, you guys know that
we could put some random code here and there to spice things up. I mean, we already
have a random `publishedAt` date.

But, instead of creating that random data by hand, there's a *much* cooler way.
We're going to use a library called Faker. Google for Faker php to find the GitHub
page from Francois Zaninotto. Fun fact, Fancois was the *original* documentation
lead for Symfony 1. He's awesome.

Anyways, this library is all about creating dummy data. Check it out: tou can use
it to generate random names, random addresses, random text, random letters, numbers
between this and that, paragraphs, street codes and pretty much anything else you
can dream up! Basically, it's awesome.

## Installing Faker

So let's get this installed. Copy the composer require line, move over and paste.
But, add the `--dev` at the end, because we're going going to use this library for
our fixtures - it's not needed on production.

```terminal-silent
composer require fzaninotto/faker --dev
```

## Setting up Faker

When that finishes, head back to its docs so we can see how to use it. Ok: we just
need to say `$faker = Faker\Factory::create()`. Open our `BaseFixture` class: let's
setup Faker in this, central spot. Create a new protected `$faker` property. And
down below, I'll say, `$this->faker =` and look for a class called `Factory` from
Faker, and `::create()`.

We should also add some PHPDoc above the property so PhpStorm knows what type of
object it is. Hold Command - or Ctrl - and click the `create()` method: it's see
what this returns exactly. Apparently, it returns a `Generator`.

Cool! Above the property, add `/** @var Generator */` - the one from `Faker`.

Perfect! Now, using Faker will be *super* easy.

## Generating Fake Data

Open `ArticleController`. We already have a little bit of randomness. But, Faker
can even help here: change this to if `$this->faker->boolean()` where the first
argument is the chance of getting true. Let's use 70: a 70% change that each article
will be published.

And below, we had this *long* expression to create a random date. *Now* say,
`$this->dateTimeBetween('-100 days', '-1 days')`.

I love it! Finally, down for `heartCount`, use another Faker function:
`$this->faker->numberBetween(5, 10)`.

After these few improvements, let's make sure it works! Find your terminal and run:

```terminal
php bin/console doctrine:fixtures:load
```

No errors and... back on the browser, it still works. Of course, the *big* problem
is that the title, author and article images are *always* the same. Faker *does*
have methods to generate random titles, random names and even random images. But,
the *more* realistic your fake data is, the better it makes your life as a developer.

## Generating Controller, Realistic Data

So here's the plan: go back to `ArticleFixtures`. At the top, I'm going to paste
in a few static properties.

These represent some realistic article titles, article images that exist, and two
realistic article authors. So instead of making *completely* random titles, authors
and images, we'll randomly choose from this list.

And Faker can *even* help us with this. For title, say `$this->faker->randomElement()`
and pass `self::$articleTitles`. We'll let Faker do all the hard work.

For `setSlug()`, we *could* continue to use this, but there is also a `$faker->slug`
method. The slug will now be totally different than the article title, but honestly,
who cares?

Next, for author, do the same thing: `$this->faker->randomElement()` and pass this
`self::$articleAuthors`. Copy that, and repeat it one more time for the `imageFile`,
this time using `self::$articleImages`.

Awesome! Let's reload our fixtures *one* more time:

```terminal-silent
php bin/console doctrine:fixtures:load
```

No errors! Find your browser and refresh. Oh, it's *so* much better.

If creating nice, random data seemed like a small step, it's not! Having rich data
that you can easily load will increase your velocity for creating new features and
fixing bugs. It's *totally* worth it.

Next! Let's install a *really* cool library with automatic slugging super-powers.
