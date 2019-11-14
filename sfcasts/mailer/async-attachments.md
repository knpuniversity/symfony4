# Attachments with Async Messenger Emails

message sitting here in all its glory. I needed to double check, but I think in order
for that to work, you needed to have the route context stuff set, which we do. Okay.
So let's check out our other email from the console command bet should work just the
same. So I'm gonna hit control C to exit out of the worker and just to make sure my
data's fresh, I'm gonna reload the fixtures

```terminal-silent
php bin/console doctrine:fixtures:load
```

and then run

```terminal
php bin/console app:author-weekly-report:send
```

and Whoa, it explodes. Incorrect string value. Wow. Okay.
So I wanted to show you this because in the real world you might hit this. One of the
limitations of the doctrine transport is that you can't send it binary data. And
because our console command creates,

okay, should've done that.

And because our console commands sends the author of your prequel send author weekly
report message, it creates a PDF and attach that PDF messenger tries to put that
binary and PDF into the queue. The talk can transfer, doesn't support that. And this
may be something that's fixed in Symfony 4.4 automatically, or you may be able to opt
into fixing it with a configuration option. I'm not sure yet. So there are two
options to fix this. The first is instead of doctrine, you can use another transport
like AMQP. The second thing, if you absolutely need to use doctrine and you
absolutely need to send a PDF attachments, there's another option here. Instead of
saying attached, you can say attach from path and here you can pass it the path to a
PDF file. Ultimately what gets stored in the, um, in the queue is just that string
and then the file is loaded when you actually send the message. The only caveat is
that that file needs to exist. You need to put that file on the filesystem and then
it needs to exist when the worker actually, um, uh, uh, works on it. Um, but the
attachment path is a way to get around that.

There's one other thing that I want to show with a messenger. Oh, I want, you're
using messenger. Run your test

```terminal
php bin/phpunit
```

Awesome.

There are a whole bunch of deprecation notices, but they pats, but check this out.
Rerun our `doctrine:query:sql`.

```terminal
php bin/console doctrine:query:sql 'SELECT * FROM messenger_messages'
```

It depends on definitely how you have your database
credentials set up and that invoice and that local. But there's a pretty good chance
that when you run select star for messages, you're going to see a new message in
there. This is actually the message that was sent during our functional test test
controller security test. Inside of here, we made a test that actually went to
register and registered and of course that sent an email. Well, if your dev and test
environments share the same database, then you're actually gonna end up with a new
entry inside of there from the test. Which is kind of a bummer because it means that
if we

tried to do messenger:consume,

```terminal
php bin/console messenger:consume -vv
```

that would actually send that message from the test
environment, which technically isn't a problem because it's just going to go to
Mailtrap. But it is a little bit weird. So because of that in the test environment
only instead of using the doctrine transport, I like to use something called the
in-memory transport. So I'm gonna copy `MESSENGER_TRANSPORT_DSN`, then opened up the
`.env.test` environment, paste this here and replace doctrine with `in-memory`
investment memory. So `in-memory://`and that's it. The number
of transport is basically a fake transport. It says if something is sent there, don't
actually do anything with it. It just kind of holds onto it. And then at the end of
the test it just goes away forever. So with this, it just cleans things up. Cause if
we run our tests

```terminal-silent
php bin/phpunit
```

after we run our tests,

```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM messenger_messages'
```

the database table is empty. So we're good there. Next, let's talk about Encore.
