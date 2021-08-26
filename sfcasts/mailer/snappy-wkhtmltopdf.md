# PDF: Snappy, wkhtmltopdf & Template Setup

How can we make the email we're sending from the console command *cooler*? By adding
an attachment! Wait, hmm. That's probably *too* easy - Mailer makes attachments
simple. Ok, then... how about this: in addition to having the table inside the
email that summarizes what the author wrote during the past week, let's generate
a PDF with a similar table and attach *it* to the email.

So that's the first challenge: generating a styled PDF... and hopefully enjoying
the process!

## Installing Snappy & wkhtmltopdf

My favorite tool for creating PDFs is called Snappy. Fly over to your terminal
and install it with:

```terminal
composer require "knplabs/knp-snappy-bundle:^1.6"
```

Snappy is a wrapper around a command-line utility called `wkhtmltopdf`. It has
some quirks, but is a *super* powerful tool: you create some HTML that's styled
with CSS, give it to `wkhtmltopdf`, it *renders* it like a browser would, and
gives you back a PDF version. Snappy makes working with `wkhtmltopdf` pretty easy,
but you'll need to make sure it's installed on your system. I installed it on my
Mac via `brew`.

```terminal-silent
wkhtmltopdf --version
```

Also, check *where* it's installed with `which` or `whereis`:

```terminal-silent
which wkhtmltopdf
```

Mine is installed at `/usr/local/bin/wkhtmltopdf`. If your binary live somewhere
else, you'll need to tweak some config. When we installed the bundle, the
bundle's recipe added a new section to the bottom of our `.env` file with two
new environment variables. 

[[[ code('15dbd16f7c') ]]]

These are both used inside a new `knp_snappy.yaml` file that was *also* added by the bundle.

[[[ code('588aa1f85d') ]]]

The  `WKHTMLTOPDF_PATH` variable already equals what I have on my machine. So
if *your* path is different, copy this, paste it to your `.env.local` file, and
customize it. Oh, and don't worry about `wkhtmltoimage`: we won't use that utility.

## Creating the PDF Templates

Ultimately, to create the PDF, we're going to render a template with Twig and
pass the HTML from that to Snappy so it can do its work. Open up
`templates/email/author-weekly-report.html.twig`.

[[[ code('59c1ebc0c2') ]]]

Hmm. In theory, we *could* just render *this* template and use its HTML. But...
that won't work because it relies on the special `email` variable. And more
importantly, we probably don't want the PDF to look *exactly* like the email - we
don't want the logo on top, for example.

No problem: let's do some organizing! Copy the table code. Then, in the
`templates/email` directory, I'll create a new file called `_report-table.html.twig`
and paste! 

[[[ code('b7764dd176') ]]]

Let's make this fancier by adding `class="table table-striped"`. Oo, fancy!

[[[ code('90f6b94aaf') ]]]

Those CSS classes come from Bootstrap CSS, which our *site* uses, but our emails
do *not*. So when we render this table in the email, these won't do anything.
But my *hope* is that when we generate the PDF, we will *include* Bootstrap CSS
and our table will look pretty.

Back in `author-weekly-report.html.twig`, take out that table and just say
`{{ include('email/_report-table.html.twig') }}`

[[[ code('27182b97c2') ]]]

*Now* we can create a template that we will render to get the HTML for the PDF.
Well, we *could* just render this `_report-table.html.twig` template... but
because it doesn't have an HTML body or CSS, it would look... simply awful.

Instead, in `templates/email/`, create a new file:
`author-weekly-report-pdf.html.twig`. To add some basic HTML, I'll use a PhpStorm
shortcut that I *just* learned! Add an exclamation point then hit "tab". Boom!
Thanks Victor!

[[[ code('c8573d74dd') ]]]

Because we're going to add Bootstrap CSS to this template, let's add a little
Bootstrap structure: `<div class="container">`, `<div class="row">` and
`<div class="col-sm-12">`. 

[[[ code('df146c0798') ]]]

Inside, how about an `<h1>` with "Weekly Report" and today's date, which we can get 
with `{{ 'now'|date('Y-m-d') }}`. 

[[[ code('eff902eaff') ]]]

Bring in the table with `{{ include('email/_report-table.html.twig') }}`.

[[[ code('9ffe02fdd1') ]]]

## Adding CSS to the Template

If we *just* rendered this and passed the HTML to Snappy, it *would* work, but
would contain *no* CSS styling... so it would look like it was designed in the
90's. If you look in `base.html.twig`, this project uses Webpack Encore. The
`encore_entry_link_tags()` function basically adds the base CSS, which includes
Bootstrap.

Copy this line, close that template, and add this to the PDF template. 

[[[ code('6d7dbfefd8') ]]]

Even if you're not using Encore, the point is that an *easy* way to style your PDF is
by bringing in the same CSS that your site uses. Oh, and because our site has a
gray background... but I want my PDF to *not* share *that* specific styling, I'll
hack in a `background-color: #fff`.

By the way, if our app needed to generate *multiple* PDF files, I would
*absolutely* create a PDF "base template" - like `pdfBase.html.twig` - so that
every PDF could share the same look and feel. Also, I'm *not* bringing in
any JavaScript tags, but you *could* if your JavaScript is responsible for helping
render how your page looks.

Ok, we're ready! Next, let's use Snappy to create the PDF, attach it to the email
and high-five ourselves. Because celebrating victories is important!
