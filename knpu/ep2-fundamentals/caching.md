# The Cache Service

Thanks to the 7 bundles installed in our app, we *already* have a bunch
of useful services. In fact, Symfony ships with a *killer* cache system out of
the box! Run:

```terminal
./bin/console debug:autowiring
```

Scroll to the top. Ah! Check out `CacheItemPoolInterface`. Notice it's an alias to
`cache.app`. And, further below, there's another called `AdapterInterface` that's
an alias to that same key.

## Understanding Autowiring Types & Aliases

Honestly, this can be confusing at first. Internally, each service has a unique name,
or "id", just like routes. The internal id for Symfony's cache service is `cache.app`.
That's not very important yet... except that, if you see two entries that are both
aliases to the same service, it means that you can use *either* type hint to get
the *exact* same object. Yep, both `CacheItemPoolInterface` and `AdapterInterface`
will cause the *exact* same object to be passed to you.

So... which one should we use? The docs will recommend one, but it technically does
*not* matter. The only difference is that PhpStorm may auto-complete different methods
for you based on the interface or class you choose. So if it doesn't auto-complete
the method you're looking for, try the other interface.

## Using Symfony's Cache

Let's use the `AdapterInterface`. Go back to our controller. Here's our next mission:
to cache the markdown transformation: there's *no* reason to do that on every request!
At the top of the method, add `AdapterInterface $cache`:

[[[ code('71f2ba326f') ]]]

Cool! Let's go use it! Symfony's cache service implements the PHP-standard cache
interface, called PSR-6... in case you want Google it and geek-out over the details.
But, you probably shouldn't care about this... it just means better interoperability
between libraries. So... I guess... yay!

But... there's a downside.... a *dark* side. The standard is very powerful... but
kinda weird to use at first. So, watch closely.

Start with `$item = $cache->getItem()`. We need to pass this a cache *key*. Use
`markdown_` and then `md5($articleContent)`:

[[[ code('2183f403bd') ]]]

Excellent! Different markdown content will have a different key. Now, when we call
`getItem()` this does *not* actually go and fetch that from the cache. Nope, it just
creates a `CacheItem` object in memory that can *help* us fetch and save to the cache.

For example, to check if this key is *not* already cached, use `if (!$item->isHit())`:

[[[ code('3e42b28fa3') ]]]

Inside we need to *put* the item into cache. That's a two-step process. Step 1:
`$item->set()` and then the value, which is `$markdown->transform($articleContent)`.
Step 2: `$cache->save($item)`:

[[[ code('ae214fee7f') ]]]

I know, I know - it smells a bit over-engineered... but it's *crazy* powerful and
*insanely* quick.

***TIP
In Symfony 4.1, you will be able to use the `Psr\SimpleCache\CacheInterface`
type-hint to get a "simpler" (but less powerful) cache object.
***

After all of this, add `$articleContent = $item->get()` to fetch the value from cache:

[[[ code('836dc61644') ]]]

## Debugging the Cache

Ok, let's do this! Find your browser and refresh! Check this out: remember that we
have a web debug toolbar icon for the cache! I'll click and open that in a new tab.

Hmm. There are a number of things called "pools". Pools are different cache systems
and most are used internally by Symfony. The one *we're* using is called `cache.app`.
And, cool! We had a cache "miss" and two calls: we wrote to the cache and then read
from it.

Refresh the page again... and re-open the cache profiler. This time we *hit* the
cache. Yes!

And just to make sure we did our job correctly, go back to the markdown content.
Let's emphasize "turkey" with two asterisks:

[[[ code('7dbe22a839') ]]]

Refresh again! Yes! The change *does* show up thanks to the new cache key. And
this time, in the profiler, we had another miss and write on `cache.app`.

Check you out! You just learned Symfony's cache service! Add that to your toolkit!

But this leaves some questions: it's great that Symfony *gives* us a cache service...
but where is it saving the cache files? And more importantly, what if I need to
*change* the cache service to save the cache somewhere else, like Redis? That's next!
