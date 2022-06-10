# Processing Encore Files through inline_css()

We just used Encore to build an `email.scss` file that we want to process through
`inline_css()` to style our emails. The *problem* is that, instead of building
just *one* `email.css` file in `public/build`, it split it into two for performance
reasons. That wouldn't be a problem, except that the *way* Webpack splits the files
might change over time - we can't guarantee that it will *always* be these two files.
To make matters worse, an Encore production build will add a dynamic "hash" to
every file - like `email.123abc.css`.

*Basically*... pointing `inline_css()` directly at these two files... isn't going
to work.

## How Dynamic Files are Normally Rendered

This is why, in `base.html.twig` we simply use `encore_entry_link_tags()`
and it takes care of everything. How? Behind the scenes, it looks in the
`public/build/` directory for an `entrypoints.json` file that Encore builds.
This is the *key*: it tells us *exactly* which CSS and JS files are needed
for each entrypoint - like `app`. Or, for `email`, yep! It contains the two CSS
files.

The *problem* is that we don't want to just output `link` tags. We actually need
to read the *source* of those files and pass *that* to `inline_css()`.

## Let's create a new Twig Function!

Since there's no built-in way to do that, let's make our *own* Twig function
where we can say `encore_entry_css_source()`, pass it `email`, and *it* will figure
out all the CSS files it needs, load their contents, and return it as one big,
giant, beautiful string.

[[[ code('90624228a5') ]]]

To create the function, our app already has a Twig extension called `AppExtension`.
Inside, say `new TwigFunction()`, call it `encore_entry_css_source` and when
this function is used, Twig should call a `getEncoreEntryCssSource` method. 

[[[ code('7201d02949') ]]]

Copy that name and create it below: `public function getEncoreEntryCssSource()` with
a `string $entryName` argument. This will return the `string` CSS source.

[[[ code('9a5f7dc259') ]]]

Inside, we need to look into the `entrypoints.json` file to find the CSS filenames
needed for this `$entryName`. Fortunately, Symfony has a service that already
does that. We can get it by using the `EntrypointLookupInterface` type-hint.

For reasons I don't want to get into in this tutorial, instead of using normal
constructor injection - where we add an argument type-hinted with
`EntrypointLookupInterface` - we're using a "service subscriber". You can learn
about this in, oddly-enough, our
[tutorial about Symfony & Doctrine](https://symfonycasts.com/screencast/symfony-doctrine/service-subscriber).

To fetch the service, go down to `getSubscribedServices()` and add
`EntrypointLookupInterface::class`. 

[[[ code('817057c49f') ]]]

Back up in `getEncoreEntryCssSource()`, we can say 
`$files = $this->container->get(EntrypointLookupInterface::class)` -
that's how you access the service using a service subscriber - then
`->getCssFiles($entryName)`.

[[[ code('ce969c4f4d') ]]]

This will return an array with something like these two paths. Next, `foreach`
over `$files as $file` and, above create a new `$source` variable set to an empty
string. All we need to do now is look for each file inside the `public/` directory
and fetch its contents.

[[[ code('82542b6ca3') ]]]

## Adding a publicDir Binding

We *could* hardcode the path to the `public/` directory right here. But instead,
let's set up a new "binding" that we can pass through the constructor. Open up
`config/services.yaml`. In our
[Symfony Fundamentals Course](https://symfonycasts.com/screencast/symfony-fundamentals/services-config-bind),
we talk about how the global `bind` below `_defaults` can be used to allow
scalar arguments to be autowired into our services. Add a new one:
`string $publicDir` set to `%kernel.project_dir%` - that's a built-in parameter -
`/public`.

[[[ code('11fc14383f') ]]]

This `string` part before `$publicDir` is optional. But by adding it, we're
*literally* saying that this value should be passed if an argument is exactly
`string $publicDir`. Being able to add the type-hint to a bind is a new
feature in Symfony 4.2. We didn't use it on the earlier binds... but we could have.

Back in `AppExtension`, add the `string $publicDir` argument. I'll hit
"Alt + Enter" and go to "Initialize fields" to create that property and set it.

[[[ code('663ec2572e') ]]]

Down in the method, we can say
`$source .= file_get_contents($this->publicDir.$file)` - each `$file` path should
already have a `/` at the beginning. Finish the method with `return $source`.

***TIP
To avoid missing CSS if you send your emails via Messenger (or if you send multiple emails
during the same request), "reset" Encore's internal cache before calling `getCssFiles()`

```diff
+ $files = $this->container
+     ->get(EntrypointLookupInterface::class)
+     ->reset();

$files = $this->container
    ->get(EntrypointLookupInterface::class)
    ->getCssFiles($entryName);
// ...
```
***

[[[ code('4f31937a60') ]]]

Whew! Let's try this! We're already running Encore... so it already dumped the
`email.css` and `vendors~email.css` files. Ok, let's go send an email. I'll hit
back to get to the registration page, bump the email, type any password, hit
register and... wow! No errors! Over in Mailtrap... nothing here... Of course!
We refactored to use Messenger... so emails are *not* sent immediately!

By the way, if that *annoys* you in development, there *is* a way to handle
async messages immediately while coding. Check out the Messenger tutorial.

Let's start the worker and send the email. I'll open another tab in my terminal
and run:

```terminal
php bin/console messenger:consume -vv
```

Message received... and... message handled. Go check it out! The styling look great:
they're inlined and coming from a proper Sass file.

And... we've made it to the end! You are now an email *expert*... I mean, not
just a Mailer expert... we *really* dove deep. Congrats!

Go forth and use your great power responsibly. Let us know what cool emails you're
sending... heck... you could even *send* them to us... and, as always, we're here
to help down in the comments section.

Alright friends, seeya next time!
