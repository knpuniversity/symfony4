# Lets Generate a PDF!

How can we make the email we're sending from the console command *cooler*? By adding
an attachment! Wait, hmm. That's probably *too* easy - Mailer makes attachments
simple. Ok, then... how about this: in addition to having the table inside the
email that summarizes what the author has written during the past week, let's
generate a PDF with a similar table and attach *it* to the email.

So that's the first challenge: how can we generate a PDF... and hopefully enjoy
the process!

## Installing Snappy & wkhtmltopdf

My favorite tool for creating PDFs is called Snappy. Fly over to your terminal
and install it with:

```terminal
composer require knplabs/knp-snappy-bundle
```

Snappy is a wrapper around a command-line utility called `wkhtmltopdf`. It has
some quirks, but is a *super* powerful too: you create some HTML that's styled
with CSS, give it to `wkhtmltopdf`, it *renders* that like a browser would, and
gives you back a PDF version. Snappy makes working with `wkhtmltopdf` pretty easy,
but you'll need to make sure it's installed on your system. I installed it on my
Mac via `brew`.

```terminal-silent
wkhtmltopdf --version
```

Also check where it's installed with `which` or `whereis`:

```terminal-silent
which wkhtmltopdf
```

Mine is installed at `/usr/local/bin/wkhtmltopdf`. If your binary live somewhere
else, then you'll need to tweak some config. When we installed that bundle, that
bundle's recipe added a new section to the bottom of our `.env` file with two
new environment variables. These are both used by a new `knp_snappy.yaml` file
that was *also* added by the bundle.

The  `WKHTMLTOPDF_PATH` variable already equals what I have on my machine. So
if *your* path is different, copy this, paste it in your `.env.local`, and customize
it. Oh, and don't worry about `wkhtmltoimage`: we won't use that utility.

## Creating the PDF Templates

Ultimately, to create the PDF, we're going to render a template with Twig and
pass it to Snappy so it can do its work. Open up
`templates/email/author-weekly-report.html.twig`.

Hmm. In theory, we *could* just render *this* template and use its HTML. But...
that won't work because it relies on the special `email` variable. And more importantly,
we probably won't want the PDF to look *exactly* like the email - we don't really
want the logo on top, for example.

No problem: let's do some organizing! Copy the table code. Then, in the
`templates/email` directory, I'll create a new file called `_report-table.html.twig`
and paste! Let's make this fancier by adding `class="table table-striped"`.

Those CSS classes come from Bootstrap CSS, which our *site* uses, but our emails
do *not*. So when we render this table in the email, these won't do anything.
But my *hope* is that when we generate the PDF, we will *include* Bootstrap CSS
so that the table styles nicely.

Back in `author-weekly-report.html.twig`, take out that able and just say
`{{ include('email/_report-table.html.twig') }}`

*Now* we can create a template that we'll render to get the HTML for the PDF.
Well, we *could* just render this `_report-table.html.twig` template... but
because it doesn't have an HTML body or CSS, it would look... terrible.

Instead, in `templates/email/`, create a new file: `author-weekly-report-pdf.html.twig`.
To add some basic HTML, I'll use a PhpStorm that I *just* learned! Add an
exclamation point then hit "tab". Boom!

Because we're going to add Bootstrap CSS to this template, let's add a little
Bootstrap structure: a `<div class="container">`, `<div class="row">` and
`<div class="col-sm-12">`. Inside, how about an `<h1>` with "Weekly Report" and
today's date, which we can get with `{{ 'now'|date('Y-m-d') }}`. Bring in the
table with `{{ include('email/_report-table.html.twig') }}`.

## Adding CSS to the Template

If we *just* rendered this and passed the HTML to Snappy, it *would* work, but
we contain *no* CSS styling... so it would look like it was from the 90's. If
you look in our `base.html.twig`, this project uses Webpack Encore. The
`encore_entry_link_tags()` function basically adds the base CSS, which includes
Bootstrap.

Copy this line, close that template, and add this to the PDF template. Even if
you're not using Encore, the point is that an *easy* way to style your PDF is
by bring in the same CSS that your site uses. Oh, and because our site has a
gray background... but I want my PDF to *not* share *that* specific styling, I'll
hack in a `background-color: #fff`.

By the way, if our app needed to generate *multiple* PDF files, to avoid duplication,
I would *absolutely* create a PDF "base template" - like `pdfBase.html.twig` -
so that every PDF can share the same look and feel. Also, I'm *not* bringing in
any JavaScript tags, but you *could* if your JavaScript is responsible for helping
render how your page looks.

## Using Snappy

Ok, let's do this! Back in `AuthorWeeklyReportSendCommand`, right before we create
the `Email`, we need to generate a PDF we can attach it. To do that, our command
needs *two* new services: `Environment $twig` - yes, it looks weird, but the type-hint
to get Twig directly is called `Environment` - and `Pdf $pdf`. That *second* service
comes from SnappyBundle.

As a reminder, if you don't know what type-hint to use, you can always spin over
to your terminal and run:

```terminal
php bin/console debug:autowiring pdf
```

There it is.

Ok, step 1 is to use Twig to render the template and get the HTML:
`$html = $this->twig->render()`. Oh... PhpStorm doesn't like that... because I
forgot to add the properties! I'll put my cursor on the new arguments, hit Alt+Enter,
and select "Initialize Fields" to create those 2 properties and set them.

*Now*, back to work: `$this->twig->render()` and pass this the template name -
`email/author-weekly-report-pdf.html.twig` - and an array of the variables it
needs... which I think is just `articles`. Pass `'articles' => $articles`.

To turn that HTML into PDF content, we can say
`$pdf = $this->pdf->getOutputFromHtml($html)`.

Cool, right! Behind the scenes, this simple method does a lot: it takes the HTML
content, saves it to a temporary file, then executes `wkhtmltopdf` and *points*
it at this file. As long as `wkhtmltopdf` is set up correctly and our HTML
generates a nice-looking page, it should work!

Next, let's attach the PDF to our email, send it and... see what it looks like!
Spoiler-alert: it's going to look terrible! Even though we included some CSS in
our template, it's *not* going to work immediately.
