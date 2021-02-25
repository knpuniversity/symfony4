# PHP 7.4 preload

There are two last small - but cool - features I want to talk about.

## Huh? Preload?

For the first, search for "Symfony preload" to find a blog post talking about
it: "New in Symfony 4.4: Preloading Symfony Applications in PHP 7.4".

Here's the deal: in PHP 7.4 a new feature was added called "preloading". Basically,
in your `php.ini` file, you can point an `opcache.preload` setting at a file that
contains a list of all the PHP files that your application uses.

***TIP
You may also need to set an `opcache.preload_user` setting set to your web server
user (e.g. `www-data`).
***

By doing this,
when PHP starts, it will "preload" those files into OPcache. You're effectively
giving your web-server a "head" start: telling it to load the source code it will
need into memory *now* so that it's ready when you start serving traffic.

What's the catch? Well, first, you need to create this "list of files", which
we'll talk about in a minute. Second, each time these files change - so on each
deploy - you need to restart your web server. And third, until PHP 7.4.2, this
feature was a little buggy. It *should* be fine now, but there still could be some
bugs left. Proceed with caution.

## The Generated Preload File

So how does Symfony fit into this? Symfony knows a lot about your app, like
which classes your app uses. And so, it can build that "preload" file
automatically.

Check it out, at your terminal, clear the prod cache:

```terminal
php bin/console cache:clear --env=prod
```

Now, in PhpStorm, check out the `var/cache/prod/` directory... here it is:
`App_KernelProdContainer.preload.php`. *This* file - which basically includes a
bunch of classes - is a PHP 7.4 preload file. All *you* need to do is update the
`opcache.preload` setting in `php.ini` to point to this file, restart your web server
any time you deploy and, voilà! Instant performance boost!

How much of a boost? I'm not sure. It's such a new feature that benchmarks are
only *starting* to be released. The blog post says 30 to 50%, I've seen other
places saying more like 10 or 15%. Either way, if you can get your system set up
to use it, free performance!

Next, let's talk about one last feature: a command you can run to make sure
*all* your service wiring and type-hints are playing together nicely. Because in
our app, there *is* a problem.
