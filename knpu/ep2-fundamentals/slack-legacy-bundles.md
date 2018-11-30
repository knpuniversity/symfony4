# Installing Bundles with "Average" Docs

Let's do something fun! Google for SlackBundle - you'll find one called
`nexylan/slack-bundle`. This is a fun library that gives us a *service* that can
send messages to a Slack channel. To install it, find the `composer require`
line, copy that, move over to your terminal and paste:

```terminal-silent
composer require nexylan/slack-bundle php-http/guzzle6-adapter:1.1.1
```

Interesting: this installs the bundle *and* some other library called `guzzle6-adapter`.
Wait for it to install and... it fails!

Don't panic. There are two important things happening. First, this installed *two*
bundles! Cool! You can see both of them inside `bundles.php`:

[[[ code('e1af5d8c18') ]]]

For the SlackBundle, it says "auto-generated recipe". That means that the bundle
doesn't actually *have* a recipe... but Symfony Flex, at least added it to `bundles.php`
for us. By the way, this is not necessarily a bad thing: sometimes a bundle doesn't
really need a custom recipe!

The second bundle *did* have a recipe. Before I started recording, I committed my
changes so far. To see what that recipe did, let's run:

```terminal
git status
```

Interesting: it added a new configuration file called `httplug.yaml`:

[[[ code('4640486eef') ]]]

We don't know what this does, but it probably configures some sensible defaults.
Let's ignore it unless the docs tells us otherwise.

## When composer require Fails

The *second* important thing I want to talk about is... well... this big error!

> The child node "endpoint" at path "nexy_slack" must be configured.

Oof... this is not a great error message. It means that this bundle *requires* some
configuration, which we don't have yet. And since it didn't add a configuration
file via a recipe, we'll need to create it ourselves. But before we do that, the
*most* important thing to understand is this: when you see an error like this after
running `composer require`, Composer *did* finish successfully and the library *was*
installed.

## Configuring the Slack Endpoint

Ok, let's go read the docs so we can figure out how to configure this bundle. Ah,
so *one* of the reasons that installing this bundle isn't smoother is that its documentation
is out-of-date! Hopefully it will be updated soon, but actually, this is a *great*
example of how to navigate less-than-up-to-date docs.

How do I know it's out-of-date? This `AppKernel` thing is a Symfony *3* concept.
*We* don't need to worry about enabling bundles: this is done for us automatically.

If you scroll down... ah, *here* is the configuration. And it says that this is
an example of *default* values... which probably means that we don't need to copy
all of this. Yep, we just need to fill in the parts that are required, so, `endpoint`.

Let's copy part of the configuration file. But... it doesn't tell us *where* to
put this! That's ok! We already know: it can live in *any* file in `config/packages`.
Let's create a new one called `nexy_slack.yaml`. Paste the config, but the only
key we need is `endpoint`:

[[[ code('1e93e5ff2e') ]]]

If you're coding along, here's how this will work. First, you'll need access to
a Slack workspace where you're an admin. If you don't have one, you can create one:
it's free and easy.

Once you've got it, go to your domain `/apps/manage`, then search the App Directory
for "Incoming Webhooks". Click "Add Configuration" to setup a new webhook: I've
already done this.

Thanks to this, you now have a new Webhook URL, which anyone can use to send messages
to your Slack. There's no authentication - the URL is meant to be a secret. Um... yea,
I know you can read mine - I'm super bad at secrets. I'll invalidate it after I
record.

Copy the URL and paste it next to `endpoint`:

[[[ code('e05fb2f682') ]]]

Now, move over and clear your cache:

```terminal
php bin/console cache:clear
```

Again, starting in Symfony 4.0.5, you will *not* need to clear your cache when
adding a new config file.

## What is $this->get()?

Sweet! The bundle is configured, so... how do we use it? Go back to the docs. Below,
yea! Usage! And *this* is where things get *really* interesting. The code says
`$this->get('nexy_slack.client)`. What the heck is that?

Actually, this is something from Symfony *3*... which we do not recommend doing in
Symfony 4 and may or may *not* work, depending on the situation. Basically,
`$this->get()` is, or was, a shortcut to fetch a service by its id. Instead of
doing this, *we* are - of course - going to fetch the service via autowiring.

You guys know the drill: find your terminal and run:

```terminal
php bin/console debug:autowiring
```

And search for "Slack". Wait... nothing!

Yep... this bundle *technically* works with Symfony 4... but it hasn't been fully
updated. And so, it doesn't expose *any* services for autowiring! Right now, there
is *no* way to autowire that `nexy_slack.client` service.

We need to learn a *little* bit more about *public* versus *private* services. And
then take control of things with an autowiring *alias*!
