# Adding Remember Me

Go back to the HTML form: it has *one* other field that we haven't talked about yet:
the "remember me" checkbox:

[[[ code('2fbda9110f') ]]]

You could check & uncheck this to your heart's delight: that works great. But...
checking it does... nothing. No worries: making this *actually* work is super
easy - just two steps.

First, make sure that your checkbox has no value and that its name is `_remember_me`:

[[[ code('bb77f5b697') ]]]

That's the magic name that Symfony will look for. Second, in `security.yaml`, under
your firewall, add a new `remember_me` section. Add two *other* keys below this.
The first is required: `secret` set to `%kernel.secret%`:

[[[ code('5ebe8cba24') ]]]

Second, `lifetime` set to 2592000, which is 30 days in seconds:

[[[ code('e78025891c') ]]]

This option is... optional - it defaults to one year.

## More about Parameters

As *soon* as you add this key, *if* the user checks a checkbox whose name is
`_remember_me`, then a "remember me" cookie will be instantly set and used to log in
the user if their session expires. This `secret` option is a cryptographic secret
that's used to *sign* the data in that cookie. If you ever need a cryptographic
secret, Symfony has a parameter called `kernel.secret`. Remember: anything surrounded
by percent signs is a *parameter*. We never created this parameter directly: this
is one of those built-in parameters that Symfony always makes available.

To see a list of *all* of the parameters, don't forget this handy command:

```terminal
php bin/console debug:container --parameters
```

The most important ones start with `kernel`. Check out `kernel.secret`. Interesting,
it's set to `%env(APP_SECRET)%`. This means that it's set to the *environment*
variable `APP_SECRET`. That's one of the variables that's configured in our
`.env` file.

## Watch the Cookie Save Login!

Anyways, let's try this out! I'll re-open my inspector and refresh the login page.
Go to Application, Cookies. Right now, there is only one: `PHPSESSID`.

This time, check the "remember me" box and log in. *Now* we *also* have a `REMEMBERME`
cookie! And, check this out: I'm logged in as `spacebar1@example.com`. Delete the
`PHPSESSID` - it currently starts with `q3` - and refresh. Yes! We are *still*
logged in!

A totally *new* session was created - with a new id. But even though this new session
is empty, the remember me cookie causes us to stay logged in. You can even see
that there's a new Token class called `RememberMeToken`. That's a low-level detail,
but, it's a nice way to prove that this just worked.

Next - we've happily existed so far *without* storing or checking user passwords.
Time to change that!
