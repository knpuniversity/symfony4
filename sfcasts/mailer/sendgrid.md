# Production Settings with SendGrid

If we're going to send emails with SendGrid... we... probably need an account!
Head to `sendgrid.com` and click to register. I'll create a shiny new
`symfonycasts` username, a thought-provoking password, my email and I am hopefully
*not* a robot... and if I am... I'm *at least* a self-aware robot. Does that count?
And... create account! Oh man! Registration step 2! Let's fill these out and... done!

SendGrid *just* sent us an email to verify my account. I've already got my
inbox open and ready. There it is! I'll click to confirm my email and... we're
good!

## Creating the SendGrid API Key

Back on the SendGrid "guide" page, on a high-level, we need some sort of API key
or username & password that we can use to send through our new account. Click "Start"
and then "Choose" the SMTP Relay option.

Yea, I know, I know: SendGrid says that the Web API method is recommended. Most
Cloud providers give you these two options: send through the traditional SMTP relay
*or* use some custom API endpoints that they expose. They recommend the API way
because, if you're creating all of your emails by hand, it's probably easier: just
POST your subject, to, from, body, etc to an API endpoint and it takes care of
creating the email behind-the-scenes. The API probably also has a few extra,
SendGrid-specific features *if* you need to do something really custom.

But because Mailer - and really the Mime component - are handling all of the complexity
of creating the email *for* us, it's much easier to use the SMTP relay.

*Finally*, it's time to create an API key that will authenticate us over SMTP.
Give the key a name - just so you can recognize what it's for 1 year from now when
we've *completely* forgotten. And hit "Create Key".

Check out our beautiful new SendGrid API key. Hmm, actually, down here, it's
called a "Password". In reality, this *is* a SendGrid API key - you *could* use
it to send emails through their RESTful API. But because SMTP authentication works
via a username and password, SendGrid tells us to use `apikey` as the username
and this as the password. It also tells us exactly what server and port to use.
This is *everything* we need. Copy the password.

## Configuring the SMTP Way vs the SendGrid Transport Way

In `.env.local`, we could use all that info to fill in the normal
`smtp://username:password@server:port` format. That would *totally* work.

*Or*, we could use the SendGrid transport to make life easier: just
`smtp://` - the long API key - then `@sendgrid`.

```
MAILER_DSN=smtp://API_KEY@sendgrid
```

The `sendgrid` transport is just a small wrapper around the SMTP transport to
make life easier: because it *knows* that the `username` is always `apikey`... and
that the server is always `smtp.sendgrid.net`, we don't need to fill those in.

In Symfony 4.4, the new syntax will look like this:

```
sendgrid://KEY@default
```

By the way, the SendGrid transport can use SMTP behind the scenes *or* make
API requests to the SendGrid API. In fact, most transports are like this.
Symfony chooses the *best* one by default - usually smtp - but you could force
it to use the API by saying `sendgrid+api://`.

## Sending an Email!

Ok team - let's try this! Back in the browser, tell SendGrid that we *have*
updated our settings and click "Next".

At this point, unless we've made a mistake, it *should* work: SendGrid is waiting
for us to try it. So... let's do that! Back on our site, hit enter on the
registration page. This time, because we're going to send a *real* email - yay! -
I'll register with a *real* address: `ryan@symfonycasts.com`. Type in a fun password,
agree to terms and... go!

No errors!? Ho, ho! Because it *probably* worked. Tell SendGrid to
"Verify Integration" - that makes it *look* for the email we just sent.

## Our Message is Spammy

While we're waiting... ah! I see a new message in my inbox! And it looks *perfect*.
If you don't see anything, double-check your spam folder. Because... the email
we sent is actually *super* spammy. Why? See how we're sending from
`alienmailer@example.com`? Do we *own* the `example.com` domain? No! And even if
we did, we have not *proven* that our SendGrid account is *allowed* to send emails
on behalf of that domain. This is *the* biggest mistake you can make when sending
emails and we'll talk more about how to fix it in a few minutes.

But first, back on SendGrid... hmm. It didn't see my email? It *definitely* sent.
Hit to verify again - sometimes this works quickly... but I've also had to hit
this button 3-4 times before. So... keep trying.

Finally, it works. Next, our great new email system... will probably result in
pretty much *every* email we send going straight to Spam. Wah, wah. We need to
*prove* that we are *allowed* to send from whatever domain our "from" address is
set to. Let's tackle "Sender authentication".
