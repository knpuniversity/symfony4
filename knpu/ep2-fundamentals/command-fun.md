# Fun with Commands

Time to make our command a bit more fun! Give it a description: "Returns some
article stats":

[[[ code('49bdd8ba7f') ]]]

Each command can have *arguments* - which are strings passed after the command
and *options*, which are prefixed with `--`, like `--option1`:

```bash
php bin/console article:stats arg1 arg2 --option1 --opt2=khan
```

Rename the argument to `slug`, change it to `InputArgument::REQUIRED` - which
means that you *must* pass this argument to the command, and give it a description:
"The article's slug":

[[[ code('0da8199b87') ]]]

Rename the *option* to `format`: I want to be able to say `--format=json` to get
the article stats as JSON. Change this to `VALUE_REQUIRED`: instead of just `--format`,
this means we need to say `--format=something`. Update its description, *and*
give it a default value: `text`:

[[[ code('888e9548a9') ]]]

Perfect! We're not *using* these options yet, but we can already go back and run
the command with a `--help` flag:

```terminal
php bin/console article:stats --help
```

Actually, you can add `--help` to *any* command to get all the info about it - like
the description, arguments and options... including a bunch of options that apply
to *all* commands.

## Customizing our Command

Ok, so the `configure()` method is where we set things up. But `execute()` is where
the magic happens. We can do *whatever* we want here!

To get the argument value, update the `getArgument()` call to `slug` and rename
the variable too:

[[[ code('54d6cec6cf') ]]]

Let's just invent some article "data": give this array a `slug` key and, how about,
`hearts` set to a random number between 10 and 100:

[[[ code('0712b44dde') ]]]

Clear out the rest of the code, and then add a `switch` statement on
`$input->getOption('format')`. Here's the plan: we're going to support two different
formats: `text` - don't forget the `break` - and `json`:

[[[ code('a84531f7a2') ]]]

If someone tries to use a different format, yell at them!

> What kind of crazy format is that?

[[[ code('f104db3c77') ]]]

## Printing Things

Notice that `execute()` has two arguments: `$input` and `$output`:

[[[ code('66cd506204') ]]]

Input lets us *read* arguments and options. And, you can even use it to ask questions
interactively. `$output` is all about *printing* things. To make both of these
even *easier* to use, we have a special `SymfonyStyle` object that's *full* of
shortcut methods:

[[[ code('2926245bc4') ]]]

For example, to print a list of things, just say `$io->listing()` and pass the
array:

[[[ code('a5a06dee50') ]]]

For `json`, to print raw text, use `$io->write()` - then `json_encode($data)`:

[[[ code('0bf65e2461') ]]]

And... we're done! Let's try this out! Find your terminal and run:

```terminal
php bin/console article:stats khaaaaaan
```

Nice! And now pass `--format=json`:

```terminal-silent
php bin/console article:stats khaaaaaan --format=json
```

Woohoo!

## Printing a Table

But... this listing isn't very helpful: it just prints out the *values*, not the
keys. The article has 88... what?

Instead of using listing, let's create a *table*. 

Start with an empty `$rows` array. Now loop over the data as `$key => $val` and
start adding rows with `$key` and `$val`:

[[[ code('89dc0382d6') ]]]

We're doing this because the SymfonyStyle object has an awesome method called
`->table()`. Pass it an array of headers - `Key` and `Value`, then `$rows`:

[[[ code('e4b8a97e77') ]]]

Let's rock! Try the command again without the `--format` option:

```terminal-silent
php bin/console article:stats khaaaaaan
```

Yes! *So* much better! And yea, that `$io` variable has a *bunch* of other features,
like interactive questions, a progress bar and more. Not only are commands *fun*,
but they're super easy to create thanks to MakerBundle.

Oh my gosh, you did it! You made it through Symfony Fundamentals! This was serious
work that will *seriously* unlock you for *everything* else you do with Symfony!
We now understand the configuration system and - most importantly - *services*.
Guess what? Commands are services. So if you needed your `SlackClient` service,
you would just add a `__construct()` method and autowire it!

***TIP
When you do this, you need to call `parent::__construct()`. Commands are a rare
case where there is a parent constructor!
***

With our new knowledge, let's keep going and start mastering features, like the
Doctrine ORM, form system, API stuff and a lot more.

Alright guys, seeya next time!
