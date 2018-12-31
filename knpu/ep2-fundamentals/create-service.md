# Creating Services!

Open `ArticleController` and find the `show()` action:

[[[ code('fe6ed52a95') ]]]


I think it's time to move our markdown & caching logic to a different file. Why?
Two reasons. First, this method is getting a bit long and hard to read. And second,
we can't *re-use* any of this code when it's stuck in our controller. And...
bonus reason! If you're into unit testing, this code cannot be tested.

On the surface, this is the *oldest* trick in the programming book: if you want to
re-use some code, move it into its own function. But, what we're about to do will
form the *cornerstone* of almost *everything* else in Symfony.

## Create the Service Class

Instead of moving this code to a *function*, we're going to create a new class and
move into a new *method*. Inside `src/`, create a new directory called `Service`.
And then a new PHP class called `MarkdownHelper`:

[[[ code('8a7ea64a18') ]]]

The name of the directory - `Service` - and the name of the class are not important
at all: you can put your code *wherever* you want. The power!

Inside, let's add a public function called, how about, `parse()`: with a string
`$source` argument that will *return* a `string`:

[[[ code('7278669110') ]]]

And... yea! Let's just copy our markdown code from the controller and paste it here!

[[[ code('71081cb3ee') ]]]

I know, it's not going to work yet - we've got undefined variables. But, worry about
that later. Return the string at the bottom:

[[[ code('d3b0f774dc') ]]]

And... congrats! We just created our first service! What? Remember, a service
is just a class that does work! And yea, this class does work! The *really* cool
part is that we can *automatically* autowire our new service.

Find your terminal and run:

***TIP
Since *Symfony 4.2* this command only shows service aliases. 
If you want to see all the services you can pass a `--all` option.
***

```terminal
./bin/console debug:autowiring
```

Scroll up. Boom! There is `MarkdownHelper`. It already lives in the container, just
like all the core services. That means, in `ArticleController`, instead of needing
to say `new MarkdownHelper()`, we can autowire it: add another argument:
`MarkdownHelper $markdownHelper`:

[[[ code('c33d2aa9c4') ]]]

Below, *simplify*: `$articleContent = $markdownHelper->parse($articleContent)`:

[[[ code('a83cad80e4') ]]]

Ok, let's try it! Refresh! We expected this:

> Undefined variable `$cache`

Inside `MarkdownHelper`. But hold on! This *proves* that Symfony's container is
*instantiating* the `MarkdownHelper` and then passing it to us. So cool!

## Dependency Injection: The Wrong Way First

In `MarkdownHelper`, oh, update the code to use the `$source` variable:

[[[ code('1e89513943') ]]]

Here's the problem: `MarkdownHelper` *needs* the cache and markdown services.
To say it differently, they're *dependencies*. So how can we *get* them from here?

Symfony follows object-orientated best practices... which means that there's no
way to *magically* fetch them out of thin air. But that's no problem! If you *ever*
need a service or some config, just *pass them in*.

The easiest way to do this is to add them as arguments to `parse()`. I'll show you
a different solution in a minute - but let's get it working. Add
`AdapterInterface $cache` and `MarkdownInterface $markdown`:

[[[ code('ed6dbcb896') ]]]

If you try it now... it fails:

> Too few arguments passed to `parse()`: 1 passed, 3 expected.

This makes sense! In `ArticleController`, *we* are calling `parse()`:

[[[ code('3f7dd46bc7') ]]]

This is important: that whole autowiring thing works for controller actions, because
that is a unique time when *Symfony* is calling our method. But everywhere else,
it's good old-fashioned object-oriented coding: if *we* call a method, *we* need
to pass all the arguments.

No problem! Add `$cache` and `$markdown`:

[[[ code('53266c914a') ]]]

And... refresh! It works! We *just* isolated our code into a re-usable service.
We *rule*. Go high-five some strangers!

## Proper Dependency Injection

Then come back! Because there's a *much* better way to do all of this. Whenever you
have a service that depends on *other* services, like `$cache` or `$markdown`, instead
of passing those in as arguments to the individual *method*, you should pass them
via a *constructor*.

Let me show you: create a `public function __construct()`. Next, move the two arguments
into the constructor, and create properties for each: `private $cache;` and
`private $markdown`:

[[[ code('7199848b89') ]]]

Inside the constructor, set these: `$this->cache = $cache` and `$this->markdown = $markdown`:

[[[ code('8229708335') ]]]

By putting this in the constructor, we're basically saying that *whoever* uses
the `MarkdownHelper` is *required* to pass us a cache object and a markdown object.
From the perspective of this class, we don't care *who* uses us, but we *know*
that they will be *forced* to pass us our dependencies.

Thanks to that, in `parse()` we can safely use `$this->cache` and `$this->markdown`:

[[[ code('4b1982f878') ]]]

One of the advantages of passing dependencies through the constructor is that it's
easier to call our methods: we *only* need to pass arguments that are specific
to that method - like the article content:

[[[ code('de94ffcce3') ]]]

And, hey! We can also remove the extra controller arguments. And, on top, we don't
*need* to, but let's remove the old `use` statements:

[[[ code('45e3340ece') ]]]

## Configuring the Constructor Args?

But there's still one *big* question! How did nobody notice that there was a
thermal exhaust pipe that would cause the whole Deathstar to explode? And also,
because the container is responsible for *instantiating* `MarkdownHelper`, how
will it know what values to pass? Don't we need to somehow *tell* it that it needs
to pass the cache and markdown services as arguments?

Actually, no! Move over to your browser and refresh. It just *works*.

Black magic! Well, not really. When you create a service class, the arguments to
its constructor are *autowired*. That means that we can use any of the classes
or interfaces from `debug:autowiring` as type-hints. When Symfony creates our
`MarkdownHelper`:

[[[ code('e8277b7d9b') ]]]

It knows what to do!

Yep, we just organized our code into a brand new service and touched *zero* config
files. This is huge!

Next, let's get smarter, and find out how we can access core services that *cannot*
be autowired.
