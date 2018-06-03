# Updating an Entity with New Fields

It's time to get back to work on the article page... because... some of this stuff
is still hardcoded! Lame! Like, the author, number of hearts, and this image. There
are a few possible images in our project that our dummy articles can point to.

Our mission is clear: create three new fields in `Article` and use those to make
all of this finally dynamic! Let's go!

Open your `Article` entity. The simplest way to add new fields is just to... add
them by hand! It's easy enough to copy an existing property, paste, rename, and
configure it. Of course, if you want a getter and setter method, you'll also need
to create those.

## Generating New Fields into the Entity

Because of that, *my* favorite way to *add* fields is to, once again, be lazy, and
generate them! Find your terminal and run the *same* command as before:

```terminal
php bin/console make:entity
```

If you pass this the name of an *existing* entity, it can actually *update* that
class and add new fields. Magic! First, add `author`, use `string` as the type. And
yea, in the future when we have a "user" system, this field might be a database relation
to that table. But for now, use a string. Say no to nullable. Reminder: when you
say *no* to nullable, it means that this field *must* be set in the database. If
you try to save an entity *without* any data on it, you'll get a huge database
exception.

Next, add `heartCount`, as an integer, and say not null: this should always have
a value, even if it's zero. Then, finally, the image. In the database, we'll store
only the image *filename*. And, full disclosure, uploading files is a whole different
topic that we'll cover in a *different* tutorial. In this example, we're going to
use a few existing images in the `public/` directory. But, both in this situation
and in a real-file upload situation, the field on your entity looks the same:
`imageFilename` as a string and nullable yes, because maybe the image is optional
when you first start writing an article.

Ok, hit enter and, done! Let's go check out the entity! Great: three new properties
on top:

[[[ code('ba672e8bfb') ]]]

And of course, at the bottom, here are their getter and setter methods:

[[[ code('d3101581c8') ]]]

Now that we have the new fields, don't forget! We need a migration:

```terminal
php bin/console make:migration
```

When that finishes, go look at the new file to make sure it doesn't have
any surprises: `ALTER TABLE article`, and then it adds `author`, `heart_count`
and `image_filename`:

[[[ code('99253d6ac2') ]]]

I *love* it!

Close that, run back to your terminal, and migrate!

```terminal
php bin/console doctrine:migrations:migrate
```

## Field Default Value

Next, we need to make sure these new fields are populated on our dummy articles.
Open `ArticleAdminController`.

Oh, but first, remember that, in the `Article` entity, `heartCount` is *required*
in the database:

[[[ code('8f273ea5e7') ]]]

Actually, to be more clear: `nullable=true` means that it *is* allowed to be null
in the database. If you *don't* see `nullable`, it uses the *default* value,
which is false.

Anyways, this means that `heartCount` *needs* a value! But here's a cool idea:
once our admin area is fully finished, when an author creates a new article, they
shouldn't need to *set* the `heartCount` manually. I mean, it's not like we expect
the form to have a "heart count" input field on it. Nope, we expect it to automatically
default to zero for new articles.

So... how can we give a property a *default* value in the database? By giving it
a default value in PHP: `$heartCount = 0`:

[[[ code('9e117683b1') ]]]

## Using the new Fields

Ok, back to `ArticleAdminController`! Add `$article->setAuthor()` and use the same
data we had on the original, hardcoded articles:

[[[ code('86253b53c0') ]]]

Then, `->setHeartCount()` and give this a random number between, how about, 5 and 100:

[[[ code('b3cf60247a') ]]]

And finally, `->setImageFilename()`. The file we've been using is called `asteroid.jpeg`.
Keep using that:

[[[ code('81617e992e') ]]]

Excelente! Because we already have a bunch of records in the database where these
fields are *blank*, just to keep things simple, let's delete the table entirely
and start fresh. Do that with:

```terminal
php bin/console doctrine:query:sql "TRUNCATE TABLE article"
```

If you check out the page now and refresh... cool, it's empty. Now, go to
`/admin/article/new` and... refresh a few times. Awesome! Check out the homepage!

We *have* articles... but actually... this author is still hardcoded in the template.
Easy fix!

## Updating the Templates

Open up `homepage.html.twig`. Let's first change the... where is it... ah, yes!
The author's name: use `{{ article.author }}`:

[[[ code('3bb1f7ddb2') ]]]

Then, in `show.html.twig`, change the article's heart count - here it is - to
`{{ article.heartCount }}`. And also update the author, just like before:

[[[ code('84aa59a244') ]]]

If you try the homepage now, ok, it looks *exactly* the same, but *we* know that
these author names are now dynamic. If you click into an article.. yea! We have
88 hearts - that's definitely coming from the database.

## Updating the Image Path

The *last* piece that's still hardcoded is this image. Go back to `homepage.html.twig`.
The image path uses `asset('images/asteroid.jpeg')`:

[[[ code('7f1e00bcb0') ]]]

So... this is a *little* bit tricky, because only part of this - the  `asteroid.jpeg`
part - is stored in the database. One solution would be to use Twig's concatenation
operator, which is `~`, then `article.imageFilename`:

```twig
{# templates/article/homepage.html.twig #}

{# ... #}
    <img class="article-img" src="{{ asset('images/'~article.imageFilename) }}">
{# ... #}
```

You don't see the `~` much in Twig, but it works like a `.` in PHP.

That's fine, but a *nicer* way would be to create a new method that does this for
us. Open `Article` and, at the bottom, create a new `public function getImagePath()`:

[[[ code('ef0ef86320') ]]]

Inside, return `images/` and then `$this->getImageFilename()`:

[[[ code('2a61808d43') ]]]

Thanks to this, in the template, we only need to say `article.imagePath`:

[[[ code('bb5da6bbd8') ]]]

And yea, `imagePath` is totally *not* a real property on `Article`! But thanks to
the kung fu powers of Twig, this works fine.

Oh, and side note: notice that there is *not* an opening slash on these paths:

[[[ code('690218bf13') ]]]

As a reminder, you do *not* need to include the opening `/` when using the `asset()`
function: Symfony will add it there automatically.

Ok, try it out - refresh! It still works! And now that we've centralized that method,
in `show.html.twig`, it's *super* easy to make the same change: `article.imagePath`:

[[[ code('2b7198b6b6') ]]]

Awesome. And when you click on the show page, it works too.

Next! Now that the heart count is stored in the database, let's make our AJAX
endpoint that "likes" an article *actually* work correctly. Right now, it does
nothing, and returns a random number. We can do better.
