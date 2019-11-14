# Sender Authentication: SPF & DKIM

Just configuring your app to use a cloud email sender - like SendGrid - isn't enough.
That would be too simple! In my Gmail inbox, the message *was* delivered... but I
think we got lucky. This email *smells* like spam. The reason is that we're *claiming*
that the email is coming *from* `alienmailer@example.com`. We can see that in
our `Mailer` class: every email is coming *from* this address.

In a real app, we would replace this with an email address from our *real*
domain - like `droid@thespacebar.com`. But that doesn't fix things. The question
still remains: how does Gmail know that SendGrid - or really, our *account* on
SendGrid - is *authorized* to send emails from this domain? How does it know that
we're not some random spammer or phisher that's trying to *trick* users into
thinking this email is legitimately from this domain?

To get our emails past spam blockers, we need to add extra config to our
domain's DNS that *proves* our SendGrid account *is* authorized to send emails from
`example.com`... or whatever *your* domain actually is.

This is both a simple thing to do... and maybe confusing? Fortunately, every email
provider will guide you through the process and... I'll do my best to... explain
what the heck is going on.

## The Domain Authentication Process

On the left, find Settings and click "Sender Authentication". We want "Domain
Authentication" - click to get started. Ultimately, *all* we will need to do
is add a few new records to our domain's DNS. To help make that easier, we can
select where we host our DNS settings so that SendGrid can give us instructions
customized to that service.

In reality, we haven't deployed our site yet - so we'll walk through this process...
for pretend. Let's pretend our DNS is hosted on CloudFlare - I *love* CloudFlare.
I'll skip the "link branding" thing - that's something else entirely. Click Next.

Now it wants to know which *domain* we'll send from. Right now, we're sending
from `@example.com`. Let's change that to `@thespacebar.com` and pretend that
*this* is our production domain. In the box, use `thespacebar.com` and hit
"Next".

*Here* is the important stuff! If you don't care about what's going on, you can
simply add these 3 DNS records and skip ahead to where we talk about DMARC.
These are enough to *prove* that our SendGrid account is allowed to send emails
on behalf of our domain.

But I think this stuff is neat! When it comes to this whole "domain authentication"
thing, there are *three* fancy acronyms that you'll hear: SPF, DKIM and DMARC.
Here's the 60 second explanation of the first two.

## The DNS Settings: SPF & DKIM

Both SFP and DKIM are security mechanisms where you can set specific DNS records
that will say exactly *who* is allowed to send emails from your domain. SPF works
by whitelisting IP addresses that are allowed to send emails. DKIM works by
using a public key to prove that the sender is authorized to send emails.
They do similar jobs, but you typically want to have *both*.

Here's what the SPF and DKIM records look like for SymfonyCasts.com:

```
TXT symfonycasts.com                    v=spf1 include:spf.mailjet.com include:helpscoutemail.com ?all
TXT mailjet._domainkey.symfonycasts.com k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBi....
```

The first is the SPF - the sender policy framework. Our framework allows emails
to be sent by Mailjet - that's what our site uses for emails - and Helpscout,
which is our ticketing system. The second is for DKIM: it lists a public key that
can be used to verify that the email *was* really sent by an authorized sender.
Your DNS records might looks a bit different, but this is the general idea.

But, wow - the DNS records that SendGrid is telling us to use are *way* different!
This is because of a nice "Automated Security" feature they have. The short story
is this: by setting these CNAME records, *it* will set up the SPF and DKIM settings
for you... which is nice... because they're kinda long, complex strings. If you
*do* need more control, on the previous screen we *could* have selected an option
to turn "automated security" off. In that case, this step would tell us a couple
of `TXT` records we need to set - very similar to the `TXT` records we use for
SymfonyCasts.com.

## So... DMARC?

The point is: set these DNS records and you're good. But, there is *one* more,
*newer* part of email security that is often *not* handled by your cloud email
system. It's called DMARC and it's *totally* optional. Here's what the DMARC DNS
record looks like for SymfonyCasts:

```
TXT _dmarc v=DMARC1; p=none; pct=100; rua=mailto:re+eymg4cd5p5c@dmarc.postmarkapp.com; sp=none; aspf=r;
```

In a nutshell, DMARC adds even a bit *more* confidence to your emails. This
crazy string tells email inboxes a few things. For example, it *specifically*
says *what* should happen if an email fails SPF or DKIM. Technically, *just*
because an email fails DKIM, for example, it *doesn't* mean that the email
will *definitely* go to spam: it's just *one* thing that counts against the
email's spam score. But, if you want, you could create a DMARC that clarifies
this: for example, instructing that all emails that fail SPF or DKIM should
be *rejected*.

It also has one other *fascinating* super power, and this is the part I *love*.
SPF and DKIM are scary... because what if you set them up wrong? Or you set them
up right today, but then you tweak some DNS settings and accidentally break them?
Many of your emails might start going to spam without you even realizing it.

DMARC can solve this, and this is how *we* use it. By setting the `rua` key
to an email, you can request that all major ISP's send you reports about how
many emails they are receiving from your domain and whether or not SPF and DKIM
are aligned. Yep, you'll get a report if something is suddenly misconfigured...
and you can even see *who* is trying to send fake emails from your domain!

But, instead of getting these low-level messages into your personal inbox, we
use a free service from PostMarkApp. The reports are sent to *them*, and we get a
neat, weekly update.

Unfortunately, SendGrid doesn't help you set up DMARC. But *fortunately*,
by going to https://dmarc.postmarkapp.com/, you can answer a few short questions
and get the exact DMARC record you need.

Phew! Enough email, authentication nerdiness! I'll leave you to update your own
DNS records and... I'll change the email `from` back to `@example.com`.

And hey! About this `from` address. Every email from our app will probably be
*from* the same address. Can we set this globally? Yes! Let's talk about that
and events next.
