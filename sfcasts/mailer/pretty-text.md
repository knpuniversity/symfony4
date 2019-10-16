# Pretty Text Emails

When we send an HTML email, we know that Mailer automatically generates a *text*
version for us. Thanks Mailer! And, other than this extra style stuff on top...
which we don't really want, it does a pretty good job! But... we can make it even
*better* - *and* remove those weird extra styles - with one simple command.
Find your terminal and run:

```terminal
composer require league/html-to-markdown
```

This is a library that's good at taking HTML and transforming it into Markdown...
which, I know, seems like an odd thing to do... but it's super handy! As *soon*
as you install it, Mailer will automatically use it to transform the HTML email
into text. Well... it will transform the HTML to *markdown*... but it turns out
that Markdown is a very attractive text format.

Check it out: on the site, go back, bump the email again, submit and... there's
our new email. The HTML looks the same, but check out the text. Yea! *First*
of all, the html-to-markdown library was smart enough to get rid of the styles.
It also embedded the logo image on top... which may or may not be useful, but
it *does* correctly represent the image & link.

The *most* important thing is that it turned the HTML into a nice structure: the
header is obvious, bold content is inside asterisks and the line breaks are correct.
Basically, we can now stop worrying about the text emails *entirely*: our emails
will have them *and* they will look great.

Next, there are *two* ways to add an image to an email: linking to them or
*embedding* them. Let's learn how to embed an image and *when* that's the
best option.
