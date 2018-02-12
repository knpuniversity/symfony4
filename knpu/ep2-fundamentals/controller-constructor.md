# Constructors for your Controller

Autowiring works in exactly two places. First it works for controller actions.
Arguments can either have the same name as a route wildcard - that's actually *not*
autowiring - just a feature of controllers - *or* have a type-hint for a service:

[[[ code('91128e11bb') ]]]

***TIP
Actually, there are a *few* other types of arguments you can get in your controller.
You'll learn about them as we continue!
***

The second place autowiring works is the `__construct()` method of services:

[[[ code('48b8c5da3a') ]]]

And actually, this is the *real* place where autowiring is meant to work: Symfony's
container - and its autowiring logic - is really good at *instantiating* objects.

## Binding Non-Service Arguments to Controllers

In `services.yaml`, we added an `$isDebug` *bind* and used it inside of `MarkdownHelper`:

[[[ code('052a0e859a') ]]]

So... could we also add an `$isDebug` argument to a controller function? It certainly
makes sense, so let's try it!

Add `bool $isDebug` - the `bool` part is optional, it doesn't change any behavior.
Below, just dump it:

[[[ code('c1acf05ee4') ]]]

Try it. Refresh! And... woh! It does *not* work!

> Controller `ArticleController::show()` requires that you provide a value for
> the `$isDebug` argument.

This... probably *should* work, and there's a good chance that it *will* work in
the future. The `bind` functionality is relatively new. And it has *one* edge-case
that does not currently work: you cannot use it to bind non-service arguments
to controllers.

I know, kinda weird. But, we're working on it - and it might already work by the
time you watch this. Yay open source!

## Adding a Constructor to your Controller

Remove the `$isDebug` argument. So... how can we access non-service values - like
parameters - from inside our controller? The answer is simple! Remember: our
controller is a *service*, just like `MarkdownHelper`. And we're now *pretty* good
at working with services, if I do say so myself.

***TIP
In Symfony 4.1, the base `AbstractController` will have a `$this->getParameter()`
shortcut method.
***

Add `public function __construct()` with a `bool $isDebug` argument. Then, dump
that variable and `die`:

[[[ code('f71087649b') ]]]

Immediately when we refresh... it works! I'll press `Alt`+`Enter` and select
"Initialize fields" to create an `$isDebug` property and set it. I don't actually
need to use this - but let's keep it as an example - I'll add a comment:

[[[ code('ba5355163e') ]]]

So, it's not as *convenient* as fetching a value via an argument to your controller
action, but it works *just* the same. And actually, like I mentioned earlier, the
container's job is really to *instantiate* services. And so autowiring should *really*
only work for `__construct()` functions! In fact, the only reason that it *also*
works for controller actions is for convenience! Yep, one core Symfony dev once said to
another:

> Hey! Autowiring is _great_! But since it's *so* common to need services in a controller
> function, maybe we should make it work there too!

And then some *other* core developer said:

> Oh man, that sounds great! Virtual high-five!!

This is not really that important. But the point is this: Symfony's container is *great*
and *instantiating* service objects and using autowiring to pass values to their
constructor. But *every* other function call - um, except for controller actions -
will not have this magic. So don't expect it.

Thanks to `bind`, we can define what values are passed to specific argument names.
But, we can go further and control what value should be passed for a specific *type*
hint. That's next.
