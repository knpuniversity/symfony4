# Bundles give you Services

Yo guys! Welcome to episode *2* of our Symfony 4 series! This is a *really* important
episode because we're going to take just a *little* bit of time to *really* understand
how our app works. I'm talking about how configuration works, the significance of
different files and a *whole* lot more.

And yea, there's a reason we're doing this work *now* and not in a distant, future
episode: by understanding a few fundamentals, *everything* else in Symfony will
make a *lot* more sense. So let's dig in and get to work!

## Code Download

As always, if you code along with me, we instantly become best friends. Download
the course code from this page and unzip it. Inside, you'll find a `start/` directory
with the same code you see here.

Open the `README.md` file for a space riddle and instructions on how to get the
app setup. The last step will be to find a terminal, move into the project and run:

```terminal
./bin/console server:run
```

to start the built-in PHP web server. Ok! Let's load up our app! Find your browser
and go to `http://localhost:8000`. Welcome back to... "The Space Bar": the latest
and greatest intergalactic news and sharing site for astronauts and friendly aliens
across the universe. Or, it *will* be when we're finished.

## Services: Objects that do Work

Let's start off stage 2 of our journey with a pop quiz: what did I say was the
*most* important part of Symfony? If you said Fabien... you're technically right,
but the *real* answer is: services. Remember: a service is an object that does
work: there's a logger object and a Twig object.

To get a list of the services that we can access, you can go to your terminal,
open a new tab, and run:

```terminal
./bin/console debug:autowiring
```

For example, to get the logger service, we can use the `LoggerInterface` type-hint.
You can see this in our controller: yep, as soon as we added an argument with the
`LoggerInterface` type-hint, Symfony knew to pass us the logger service.

## Where do Services Come From? Bundles

But... where do these service objects come from? I mean, *somebody* must be creating
them in the background for us, right? *Totally*! It's not very important yet, but
every service is stored inside another object called the *container*. And each
service has an internal name, just like routes.

And what exactly puts these services *into* the container? The answer: *bundles*.
Bundles are Symfony's *plugin* system. Look inside `config/` and open a `bundles.php`
file there. Yep, our app has *seven* bundles so far - basically seven plugins.
We installed 6 of these in episode 1: the recipe system automatically updates this
file when you require a bundle. Sweet!

Let's put this all together: Symfony is *really* nothing more than a collection of
services. And bundles are what actually make those services available. For example,
MonologBundle is responsible for giving us the logger service.

Bundles can do *other* things - like add routes. But they really have one main job:
bundles give you services. If you add a bundle, you get more services, and remember,
services are *tools*.

So let's install a new bundle and play with some new tools!
