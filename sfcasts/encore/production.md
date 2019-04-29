# Production Build & Deployment

Ok team: just one more thing to talk about: how the heck can we deploy all of this
to production?

Well, *before* that, our files aren't event ready for production yet! Open the
`public/build/` directory. If you open any of these files, you'll notice that they
are *not* minified. And at the bottom, each has a bunch of extra stuff for "sourcemaps":
a bit of config that makes debugging our code easier in the browser.

## Building For Production

We get *all* of this because we've been creating a *development* build. *Now*, at
your terminal, run:

```terminal
yarn build
```

This is a shortcut for `yarn encore production`. When we installed Encore, we got
a pre-started `package.json` file with... this `scripts` section. So, the *real*
command to build for production is `encore production`, or, really:

```terminal-silent
./node_modules/.bin/encore production
```

Anyways, that's the key thing: Encore has two main modes: `dev` and `production`.

And... done! On a big project, this might take a bit longer - production builds
can be much slower than dev builds.

*Now* we have a *very* different `build/` directory. First, all of the names are
bit obfuscated. Before, we had names that included things like `app~vendor`, which
kind of exposed the internal structure of what entry points we had and how they're
sharing data. No *huge* deal, but that's gone: gone, replaced by these numbered
files.

Also, if you look inside any of these, they're now totally minified and won't have
the sourcemap at the bottom. You *will* still see these license headers - that's
there for legal reasons, though you *can* configure them to removed. Those are
the only comments that are left in these final files.

And *even* though all the filenames just changed, we instantly move over, refresh,
and... it works: the Twig helpers are rendering the new filenames.

## Free Versioning

In fact, you may have noticed something special about the new filenames: every
single one now has a *hash* in it. Inside our `webpack.config.js` file, this is
happening thanks to this line: `enableVersioning()`. And check it out, the first
argument - which is a boolean of whether or not we want versioning - is using a
helper called `Encore.isProduction()`. That disables versioning for our dev builds,
just cause we don't need it, but *enables* it for production.

The *really* awesome thing is that *every* time the *contents* of this
`article_show.css` file changes, it will automatically get a new hash: the hash
is built from the *contents* of the file. Of course, we don't need to change
anything in our code, because the Twig helpers will automatically render the new
filename in the `script` or `link` tag. Basically... we get free file versioning,
or browser cache busting.

This *also* means that you should *totally* take advantage of something called
long-term caching. This is where you configure your web server - like `Nginx` -
to set an `Expires` header on *every* file it serves from the `/build` directory
with some super-distant value, like 1 year from now. The result is that, once a
user has downloaded these files, they will *never* ask our server for them again:
they'll just use their browser cache. But, as soon as we *update* a file, it'll
have a new filename and the user's browser will ask for it again. It's just free
performance. And if you got a step further and put something like CloudFlare in
front of your site, your server will receive even *less* requests for your assets.

## Deployment

Now that we have these, optimized, versioned files, how can we deploy them up to
production? Well... it depends. It depends on how sophisticated your deployment
is.

If you have a really *simple* deployment, where you basically, run `git pull` on
production and then clear the Symfony cache, you're probably going to need to
install node on your production server, run `yarn install`, and then run
`yarn build` up on production, each time you deploy. That's not ideal, but if you
have a simple deployment system, that *keeps* it simple.

If you have a slightly more sophisticated system, you can do it better. The *key*
thing to understand is that, once you've run `yarn build`, the *only* thing that
needs to go to production is the `public/build` directory. So you could literally
run `yarn build` on a different server - or even locally - and then just make sure
that this `build/` directory gets copied to production.

That's it! You don't need to have `node` installed on production and you don't
need to run anything with `yarn`. If you followed our tutorial on Ansistrano, you
would run `yarn` wherever you're executing Ansistrano, then use the `copy` module
to copy the directory.

## More Features

Ok, that's it! Actually, there are *more* features inside Encore - many more, like
enabling TypeScript, React or Vue support. But getting those all going should be
easy for you now. Go try them, and report back.

And, like always, if you have any questions, find us in the comments section.

All right friends, seeya next time.
