# Lets Generate a PDF!

Let's transform this Twig template into a PDF.

Back in `AuthorWeeklyReportSendCommand`, right before we create the `Email`,
*this* is where we'll generate the PDF, so we can attach it. To do that, our command
needs *two* new services: `Environment $twig` - yes, it looks weird, but the type-hint
to get Twig directly is called `Environment` - and `Pdf $pdf`. That *second* service
comes from SnappyBundle.

As a reminder, if you don't know what type-hint to use, you can always spin over
to your terminal and run:

```terminal
php bin/console debug:autowiring pdf
```

There it is!

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
it at that file. As long as `wkhtmltopdf` is set up correctly... and our HTML
generates a nice-looking page, it should work!

If *all* has gone well, the `$pdf` variable will now be a string containing the
actual PDF content... which we could do anything with, like save to a file *or*
attach to an email. Why, what a wonderful idea!

## Adding an Attachment

Adding an attachment to an email... probably looks exactly like you would expect:
`->attach()`. The first argument is the file *contents* - so `$pdf`. If you
need to attach something *big*, you can also use a file *resource* here - like
use `fopen` on a file and pass the file handle so you don't need to read the
whole thing into memory. The second argument will be the filename for the
attachment. Let's uses `weekly-report-%s.pdf` and pass today's date for the
wildcard: `date('Y-m-d')`.

Love it! We're ready to try this thing. Find your terminal and run:

```terminal
php bin/console app:author-weekly-report:send
```

As a reminder, even though this *looks* like it's sending to six authors, it's
a lie! It's *really* looping over 6 *possible* authors, but only sending emails
to those that have written an article within the past 7 days. Because the database
fixtures for this project have a bunch of randomness, this might send to 5 users,
2 users... or 0 users. If it doesn't send *any* emails, try reloading your fixtures
by running:

```terminal
php bin/console doctrine:fixtures:load
```

If you are *so* lucky that it's sending *more* than 2 emails, you'll get an error
from Mailtrap, because it limits sending 2 emails per 10 seconds on the free plan.
You can ignore the error or reload the fixtures.

In my case, in Mailtrap... yea! This sent 2 emails. If I click on the first one...
it looks good... and it has an attachment! Let's open it up!

Oh... ok... I guess it *technically* worked... but it looks *terrible*. This
definitely did *not* have Bootstrap CSS applied to it. The question is: why not?

Next, let's put on our debugging hats, get to the bottom of this mystery, and
*crush* this bug.
