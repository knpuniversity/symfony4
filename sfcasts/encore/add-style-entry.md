# Add Style Entry

Coming soon...

There are now only two files left inside of our public directory. I can kill the `js/`
directory entirely. Uh, there's two, both just two CSS files. These are included on
the, let's see, the `account/index.html.twig`. It has its own page specific CSS and
in `security/login.html.twig`, like it has its own page that CSS and

okay.

And uh, we also include `login.css` over here on `register.html.twig`. So this is a kind of a tricky
case because what job, what Webpack wants you to do is it always wants you to have an
entry file, which is always a JavaScript file and then it will output the JavaScript
file and the final CSS file if you have one. But if you have a case where you just
simply do not have a JavaScript file, it gets a little bit awkward. So what we could
do is create an `account.js` and a `login.js` and all those files would do was
import these CSS files that would work. We would then have an extra empty `account.js`
and the `login.js`. But that's not that big of a deal. But we do support this
while we, you know, Encore and we do really recommend that you think of re of doing
it the proper way. Sometimes we realize that you just have random CSS files so we can
handle this very easily. So first thing I'm gonna do is I'm going to move both of the
CSS files up into our `css/` directory and just because we can, I'll make both of these
`scss` files.

Yeah.

And then inside of `webpack.config.js` so we can add a special thing called
`addStyleEntry()`. We'll have one called `account` or `./assets/css/account.scss`
and the other one called `login` that points to `login.scss`.

Okay.

It's now if you move over to find your build `control + C` and run

```terminal
yarn watch
```

Okay.

And you can see the entry point `account` and `login`, just dumped those CSS files,

which is perfect. And now means that we can go into our `index.html.twig`. Your place
was with `{{ encore_entry_link_tags('account') }}`. A copy of that and I'll do the same thing
inside of `login.html.twig` for the `login` entry and then `register.html.twig`
for the `login` entry there. And just to make sure this looks okay, we'll check
the `/account` page profile. Yup. Everything seems to look just fine. So I just want you
to know that this is available is actually kind of a hack internally. The way encore
does this is, this is the same as `addEntry()`, except that a Webpack, will still
output a JavaScript file, an empty `account.js`. So because we're using `addStyleEntry()`
a Encore just deletes that for you so you don't have to deal with the extra
`account.js` file, which would have been empty anyways.