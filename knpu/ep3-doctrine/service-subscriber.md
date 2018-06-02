# Service Subscriber: Lazy Performance

Our nice little Twig extension has a not-so-nice problem! And... it's subtle. 

Normally, if you have a service like `MarkdownHelper`:

[[[ code('b7859e2e94') ]]]

Symfony's container does *not* instantiate this service until and *unless* you
actually use it during a request. For example, if we try to use `MarkdownHelper`
in a controller, the container will, of course, instantiate `MarkdownHelper`
and pass it to us.

But, in a different controller, if we *don't* use it, then that object will *never*
be instantiated. And... that's perfect! Instantiating objects that we don't need
would be a performance killer!

## Twig Extensions: Always Instantiated

Well... Twig extensions are a special situation. *If* you go to a page that renders
any Twig template, then the `AppExtension` will *always* be instantiated, even if we
don't use any of its custom functions or filters. Twig needs to instantiate the
extension so that it *knows* about those custom things.

But, in order to instantiate `AppExtension`, Symfony's container first needs to
instantiate `MarkdownHelper`. So, for example, the homepage does *not* render
anything through markdown. But because our `AppExtension` *is* instantiated,
`MarkdownHelper` is *also* instantiated.

In other words, we are *now* instantiating an *extra* object - `MarkdownHelper` -
on *every* request that uses Twig... even if we never actually use it! It sounds
subtle, but as your Twig extension grows, this can become a real problem.

## Creating a Service Subscriber

We *somehow* want to tell Symfony to pass us the `MarkdownHelper`, but not *actually*
instantiate it until, and unless, we need it. That's totally possible.

But, it's a little bit tricky until you see the whole thing put together. So, watch
closely.

First, make your class implement a new interface: `ServiceSubscriberInterface`:

[[[ code('69197e7e44') ]]]

This will force us to have one new method. At the bottom of the class, I'll go to
the "Code"->"Generate" menu - or `Command`+`N` on a Mac - and implement
`getSubscribedServices()`. Return an array from this... but leave it empty for now:

[[[ code('3b2d62c741') ]]]

Next, up on your constructor, *remove* the first argument and replace it with
`ContainerInterface` - the one from Psr - `$container`:

[[[ code('9354338302') ]]]

Also rename the property to `$container`:

[[[ code('e55cf53a9a') ]]]

## Populating the Container

At this point... if you're *totally* confused... no worries! Here's the deal:
*when* you make a service implements `ServiceSubscriberInterface`, Symfony will
suddenly try to pass a *service container* to your constructor. It does this by
looking for an argument that's type-hinted with `ContainerInterface`. So, you *can*
still have *other* arguments, as long as one has this type-hint.

But, one important thing: this `$container` is *not* Symfony's big service container
that holds hundreds of services. Nope, this is a mini-container, that holds a
*subset* of those services. In fact, right now, it holds zero.

To tell Symfony *which* services you want in your mini-container, use
`getSubscribedServices()`. Let's return the one service we need: `MarkdownHelper::class`:

[[[ code('d0d643699c') ]]]

When we do this, Symfony will basically autowire that service *into* the mini
container, *and* make it public so that we can fetch it directly. In other words,
down in `processMarkdown()`, we can use it with
`$this->container->get(MarkdownHelper::class)` and then `->parse($value)`:

[[[ code('b03e77cfd1') ]]]

At this point, this might feel like just a more complex version of dependency
injection. And yea... it kinda is! Instead of passing us the `MarkdownHelper`
directly, Symfony is passing us a *container* that *holds* the `MarkdownHelper`.
But, the *key* difference is that, thanks to this trick, the `MarkdownHelper`
service is *not* instantiated until and unless we fetch it out of this container.

## Understanding getSubscribedEvents()

Oh, and to *hopefully* make things a bit more clear, you can actually return a
key-value pair from `getSubscribedEvents()`, like `'foo' => MarkdownHelper::class`:

```php
class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    // ...
    public static function getSubscribedServices()
    {
        return [
            'foo' => MarkdownHelper::class,
        ];
    }
}
```

If we did this, it would *still* mean that the `MarkdownHelper` service is autowired
into the mini-container, but we would reference it internally with the id `foo`.

If you just pass `MarkdownHelper::class` as the value, then that's also used as
the key.

The end result is exactly the same as before, except `MarkdownHelper` is lazy!
To prove it, put a die statement at the top of the `MarkdownHelper` constructor.

Now, go back to the article page and refresh. Not surprising: it hits the die
statement when rendering the Twig template. But now, go back to the homepage.
Yes! The *whole* page prints: `MarkdownHelper` is never instantiated.

Go back and remove that die statement.

Here's the super-duper-important takeaway: I want you to use *normal* dependency
injection everywhere - just pass each service you need through the constructor,
*without* all this fancy service-subscriber stuff.

But then, in just a *couple* of places in Symfony, the main ones being Twig extensions,
event subscribers and security voters - a few topics we'll talk about in the future -
you should consider using a service subscriber instead to avoid a performance hit.
