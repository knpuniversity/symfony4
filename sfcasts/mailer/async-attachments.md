# Attachments with Async Messenger Emails

Our registration email is being sent asynchronously via Messenger. And actually,
*every* email our app sends will now be async. Let's double-check that the
weekly report emails are still working.

Hit Ctrl+C to stop the worker process and, just to make sure our database if full
of *fresh* data, reload the fixtures:

```terminal
php bin/console doctrine:fixtures:load
```

Now run:

```terminal
php bin/console app:author-weekly-report:send
```

## Problems with Binary Attachments

Ah! Explosion! Incorrect string value? Wow. Okay. What we're seeing is a real-world
limitation of the doctrine transport: it can't handle binary data. This *may* change
in Symfony 4.4 - there's a pull request for it - but it may not be merged in time.

Why does our email contain binary data? Remember: the method that creates the
author weekly report email *also* generates a PDF and attaches it. That PDF is
binary... so when Messenger tries to put it into a column that doesn't support
binary data... boom! Weird explosion.

If this is a problem for you, there are two fixes. First, instead of Doctrine, use
another transport - like AMQP. Second, if you need to use doctrine and you *do*
send binary attachments, instead of saying `->attach()` you can say
`->attachFromPath()` and pass this a *path* on the filesystem to the file. By doing
this, the *path* to the file is what is stored in the queue. The only caveat is
that the worker needs to have access to the file at that path.

## Messenger and Tests

There's one other thing I want to show with messenger. Run the tests!

```terminal
php bin/phpunit
```

Awesome! There are a *bunch* of deprecation notices, but the tests *do* pass.
However, run that Doctrine query again to see the queue:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM messenger_messages'
```

Uh oh... the email - the one from our functional test to the registration page -
was added to the queue! Why is that a problem? Well, it's not a *huge* problem...
but if we run the `messenger:consume` command...

```terminal-silent
php bin/console messenger:consume -vv
```

That would actually send that email! Again, that's not the end of the world...
it's just a little odd - the test environment doesn't need to send real emails.

If you've configured your `test` environment to use a different database than
normal, you're good: your test database queue table *will* fill up with messages,
but you'll never run the `messenger:consume` command from that environment anyways.

## Overriding the Transport in the test Environment

But there's also a way to solve this directly in Messenger. In `.env`, copy
`MESSENGER_TRANSPORT_DSN` and open up `.env.test`. Paste this but replace `doctrine`
with `in-memory`. So: `in-memory://`

[[[ code('854ee71758') ]]]

This transport... is useless! And I *love* it. When Messenger sends something to
an "in-memory" transport, the message... actually goes nowhere - it's just discarded.

Run the tests again:

```terminal-silent
php bin/phpunit
```

And... check the database:

```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM messenger_messages'
```

No messages! Next, lets finish our grand journey through Mailer by integrating
our Email styling with Webpack Encore.
