# Configuring a Bundle

Let's dump this markdown object: I want to know exactly what this object *actually*
is: `dump($markdown);die;`:

[[[ code('da578d0f4b') ]]]

Refresh the article page! Ok, it's an instance of some `Max` object - probably from
the bundle or some library it's using. And it looks like it has a `features` array
where you can turn some features on and off.

So here's the *burning* question: because the bundle *gives* us this service
automatically, how can we *configure* it? I mean, what if I want to turn some of
these features on or off, or I want to swap the class from `Max` to a different
class from the bundle?

The answer is... science! I mean... configuration! Imagine you're the bundle author:
you can probably think of the types of things a user might want to change. So, you
setup a simple configuration array that can be used to control those things.

And yea... that's basically how it works, except the config is in YAML.

## Dumping Bundle Configuration

And there's an *awesome* way to find out *all* of the configuration options for a
bundle without reading the documentation... because *I* know, we all like to skip
reading the docs!

The name of this bundle is `KnpMarkdownBundle`. At your terminal, use that to
dump its config:

```terminal
./bin/console config:dump KnpMarkdownBundle
```

Boom! Say hello to a big YAML example of all of the config options for this bundle.
Sometimes, the keys are self-explanatory. But other times - you'll want to
cross-reference this with the bundle's docs to find out more.

In this case, down below on the docs, it tells us that the bundle ships with a number
of different parsers: it looks like it defaults to this "max" parser: fully-featured,
but a bit slow.

## Configuring the Parser

To prove we can do it, let's try to change to the "light" parser. According to the
docs, we can do that by using the `knp_markdown`, `parser`, `service` config and
setting its value to `markdown.parser.light`.

Ok! But... where should this config live? Move over to your project and look in the
`config/` directory and then `packages/`. Create a new file called `knp_markdown.yaml`.
Then, copy the configuration, paste it here and change the service to the one from
the docs: `markdown.parser.light`:

[[[ code('dc6ba38084') ]]]

Before we see what that did, find your terminal and run:

```terminal
./bin/console cache:clear
```

This... is a bummer. Normally, Symfony is smart-enough to rebuild its cache
whenever we change a config file. But... there's currently a bug in Symfony
where it does *not* notice *new* config files. So, for now, we need to do this on
the rare occasion when we add a new file to config. It should be fixed soon.

So... what did this config change... actually... do? Well, because the purpose of
a bundle is to give us services, the purpose of *configuring* a bundle is to *change*
how those services behave. That might mean that a service will suddenly use a different
class, or that different arguments are passed to a service object. As a user, it
doesn't really matter to us: the bundle takes care of the ugly details.

Ok, refresh!

Ah, in this case the change is obvious! Our markdown parser is now an instance of
a `Light` class. Cool!

## More about Bundle Configuration

Now: why did I put this in a file named `knp_markdown.yaml`? Is that important? Actually,
no! As we'll learn soon, Symfony automatically loads *all* files in `packages/`,
and their names are meaningless, technically!

The *super* important part is the root - meaning, non-indented - key: `knp_markdown`.
*Each* file in `packages/` configures a different bundle. Any configuration under
`knp_markdown` is passed to the KnpMarkdownBundle. Any config under `framework`
configures FrameworkBundle, which is Symfony's one, "core" bundle:

[[[ code('2fe3270ab0') ]]]

And yea, `twig` configures TwigBundle:

[[[ code('01cbe1fc62') ]]]

Every bundle has its *own* set of valid config. Heck, let's go check out Twig's
config:

```terminal
./bin/console config:dump TwigBundle
```

or we can just the config key instead:

```terminal
./bin/console config:dump twig
```

Say hello to *all* of the valid options for TwigBundle. Of course, these keys
are explained more on the TwigBundle docs... but isn't this awesome?

Next: the service container has been hiding something *huge* from us... like
"dark matter" huge. Let's find out what it is.
