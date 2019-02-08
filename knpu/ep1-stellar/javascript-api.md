# JSON API Endpoint

When we click the heart icon, we need to send an AJAX request to the server that will,
eventually, update something in a database to show that the we liked this article.
That API endpoint also needs to return the new number of hearts to show on the page...
ya know... in case 10 other people liked it since we opened the page.

In `ArticleController`, make a new `public function toggleArticleHeart()`:

[[[ code('773717b419') ]]]

Then add the route above: `@Route("/news/{slug}")` - to match the show URL - then
`/heart`. Give it a name immediately: `article_toggle_heart`:

[[[ code('66831ee765') ]]]

I included the `{slug}` wildcard in the route so that we know *which* article is
being liked. We could also use an `{id}` wildcard once we have a database.

Add the corresponding `$slug` argument. But since we *don't* have a database yet,
I'll add a TODO: "actually heart/unheart the article!":

[[[ code('24472bf8ea') ]]]

## Returning JSON

We want this API endpoint to return JSON... and remember: the *only* rule for
a Symfony controller is that it must return a Symfony Response object. So we could
literally say `return new Response(json_encode(['hearts' => 5]))`.

But that's too much work! Instead say `return new JsonResponse(['hearts' => rand(5, 100)]`:

[[[ code('a7ecdbda6f') ]]]

***TIP
Or use the controller shortcut!

```php
return $this->json(['hearts' => rand(5, 100)]);
```

Note that since PHP 7.0 instead of `rand()` you may want to use `random_int()` that
generates cryptographically secure pseudo-random integers. It's more preferable to use
unless you hit performance issue, but with just several calls it's not even noticeable.
***

There's nothing special here: `JsonResponse` is a *sub-class* of `Response`. It calls
`json_encode()` *for* you, and also sets the `Content-Type` header to `application/json`,
which helps your JavaScript understand things.

Let's try this in the browser first. Go back and add `/heart` to the URL. Yes! Our
*first* API endpoint!

***TIP
My JSON looks pretty thanks to the [JSONView][json_view] extension for Chrome!
***

## Making the Route POST-Only

Eventually, this endpoint will *modify* something on the server - it will "like"
the article. So as a best-practice, we should *not* be able to make a GET request
to it. Let's make this route *only* match when a POST request is made. How? Add
another option to the route: `methods={"POST"}`:

[[[ code('fafe7bd425') ]]]

As *soon* as we do that, we can no longer make a GET request in the browser: it
does *not* match the route anymore! Run:

```terminal
./bin/console debug:router
```

And you'll see that the new route only responds to POST requests. Pretty cool. By
the way, Symfony has a *lot* more tools for creating API endpoints - this is *just*
the beginning. In future tutorials, we'll go further!

## Hooking up the JavaScript & API

Our API endpoint is ready! Copy the route name and go back to `article_show.js`.
But wait... if we want to make an AJAX request to the new route... how can we generate
the URL? This is a pure JS file... so we can't use the Twig `path()` function!

Actually, there *is* a really cool bundle called [FOSJsRoutingBundle][fos_js_routing_bundle]
that *does* allow you to generate routes in JavaScript. But, I'm going to show you
another, simple way.

Back in the template, find the heart section. Let's just... fill in the `href` on
the link! Add `path()`, paste the route name, and pass the `slug` wildcard set to
a `slug` variable:

[[[ code('52801fef03') ]]]

Actually... there is *not* a `slug` variable in this template yet. If you look
at `ArticleController`, we're only passing two variables. Add a third: `slug`
set to `$slug`:

[[[ code('e350bcc68b') ]]]

That *should* at least set the URL on the link. Go back to the show page in your
browser and refresh. Yep! The heart link *is* hooked up.

Why did we do this? Because now we can get that URL *really* easily in JavaScript.
Add `$.ajax({})` and pass `method: 'POST'` and `url` set to `$link.attr('href')`:

[[[ code('c0268387f2') ]]]

That's it! At the end, add `.done()` with a callback that has a `data` argument:

[[[ code('7a1700529c') ]]]

The `data` will be whatever our API endpoint sends back. That means that we can move
the article count HTML line into this, and set it to `data.hearts`:

[[[ code('aba3f5c2bb') ]]]

Oh, and if you're not familiar with the `.done()` function or Promises, I'd highly
recommend checking out our [JavaScript Track][javascript_track]. It's not beginner
stuff: it's meant to take your JS up to the next level.

Anyways... let's try it already! Refresh! And... click! It works!

*And*... I have a surprise! See this little arrow icon in the web debug toolbar?
This showed up as soon as we made the first AJAX request. Actually, every time we
make an AJAX request, it's added to the top of this list! That's awesome because -
remember the profiler? - you can click to view the profiler for *any* AJAX request.
Yep, you now have all the performance and debugging tools at your fingertips... even
for AJAX calls.

Oh, and if there *were* an error, you would see it in all its beautiful, styled glory
on the Exception tab. Being able to load the profiler for an AJAX call is kind of
an easter egg: not everyone knows about it. But you *should*.

I think it's time to talk about the most important part of Symfony: Fabien. I mean,
services.


[json_view]: https://chrome.google.com/webstore/detail/jsonview/chklaanhfefbnpoihckbnefhakgolnmc?hl=en
[fos_js_routing_bundle]: https://github.com/FriendsOfSymfony/FOSJsRoutingBundle
[javascript_track]: https://knpuniversity.com/tracks/javascript#modern-javascript
