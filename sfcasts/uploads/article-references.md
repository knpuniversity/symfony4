# Article References

Coming soon...

So we've gotten really great stuff handled with this uploaded article I image but now
let's make things more complicated. One of the things that authors have articles need
to be able to do is they need to be able to upload multiple supporting files. I'll
call them references like maybe some pdf information that they found somewhere, other
text files, so every article needs to have many of these article references. That's
where things get a little bit trickier.

Let's start by creating a new entity for this because it's going to be a relationship
where `Article` has many of this new entity. It's all run

```terminal
php bin/console make:entity
```

Let's call our new entity `ArticleReference` and if the properties, first thing
is we'll have the relationship property back to `Article`. That's going to be a for not
sure what type of relationship that should be. You can type in `relation` there. It's
going to be relationship back to `Article` and in this case is going to be a `ManyToOne`. 
Each `Article` can have many `ArticleReferences`. So `ManyToOne` and while lot
say not `null` the database, basically every `ArticleReference` must be related to an
`Article`. And yes, we will map the other side of the relationship. It's kind of
convenient to be able to say `$article->getArticleReferences()`. I will say `no` to
orphan removal that we won't because we won't, uh, make use of that. All right, next
we of course will need every `ArticleReference` going to need the `filename` that was
stored. Won't that be strange? But if five and now I'm also going to create a couple
of additional things cause the only thing we really need right is the file name and
the relation back to article. But I'm going to store the original file name.

Oh my bad.

Say No to nullable. I'm going to store the `originalFileName`. This is actually going to
allow us to, um, have unique file names and the filesystem, but keep the original
file name when the user downloads it and then we can store it. Anything else we want.
So I don't actually need it, but let's,

okay.

Also stored the `mimeType`, which might be useful for showing what type of file it is
or some sort of icon and that's it. Perfect. Then we'll run 

```terminal
php bin/console make:migration
```

Yeah.

And we'll switch over here and I'll go into source migrations just to make sure that
migration looks right. Yup. Great table `article_reference` and a foreign key back to
the `article` table. Run that with 

```terminal
php bin/console doctrine:migrations:migrate
```

Perfect.

And before we get to work, I want you to go to the `Entity/Article.php` and steep
and this did create the `$articleReferences` property over here and the convenience
property and that allows us to be able to, if we have an `Article` we can say 
`getArticleReferences` and it will list all the references. Last can be really
convenient. Um, and also had an `addArticleReference()` and `removeArticleReference()`
or convenience methods. I'm actually going to delete those just because I'm not going
to need them. I'm going to read article references from the side, but I'm not going
to edit them.

All right. So let's think about how we want this to work. Every article is going to
have many article references. I need to be able to upload, um, multiple things to
this, uh, to this article. Now a lot of you are probably expecting me to use
Symfony's `CollectionType` as a form form special form field type that allows you to
embed many subtypes. And I'm not going to do that because then there's way too hard.
The `CollectionType` is hard enough on its own without adding, adding and delete
functionality and file uploads to it. We're going to do something different and
actually it's going to be a much better user experience anyways, we're going to keep
this main form all by itself and then we're going to build the article reference
upload as a kind of a separate widget next to it.

And we're going to do this on the edits template. So I'm going to go into `templates/`,
uh, `article_admin/edit.html.twig` and everything we're going to do is going to
be inside of the edit template, not inside the new template. And the reason for that
is this functionality is only going to work with the, when you are editing an
article, it turns out trying to upload files to an entity before it's been saved. The
database actually causes a lot of complexity and I'll say if you can, it's something
that I would just recommend you avoid. Save Your entity somehow before you actually
start uploading. All right, so let's do a, I'll add a little `<hr>` here and then we're
just going to give a little bit of markup. So we'll now have a detailed section
that's actually going to hold the form and then we'll also have another dev next to
that and this will hold the references. So for fresh does it gives us a little bit of
separation here.

Yeah,

and then over here when I literally want is the ability to upload a reference one of
the time, yes. Later we're going to talk about uploading multiple things, but I just
want to make it very simple. All we need is just a form tag with one field in it. So
I'm not even gonna use Symfony's form system for this because it's just so simple.
Leave `action=""` empty for a moment. We'll fill that in in a second. Same 
`method="POST"`. No. For Your `enctype="multipart/form-data"`

and inside we'll say

`<input type="file" name=` how about `reference` and then we just need that 
`<button type="submit">`. I'll give some classes so it's not too ugly. Say Upload.

Cool.

There we go. So simple upload fit form. Now we're going to have this submit to a uh,
a different endpoint. What I mean is we're not gonna have this submit right back to
the same edit end point. This is going to submit to a different end point whose whole
job is to handle these article reference uploads and to keep things organized.
Instead of continuing to put more things into our `ArticleAdminController`, I'm going
to create a new controller called `ArticleReferenceAdminController`. We're
eventually going to have multiple end points for deleting article references and
editing some data on them. So I want to keep things organized and its own spot. I'm
extending `BaseController`. This is just a controller we have in our project. It
doesn't really contain anything. It just extends abstract controller. So nothing
special happening there. Then I'll create a 
`public function uploadArticleReference()`

Okay.

And we'll put the `@Route` above this.

Okay.

Make sure you get the one from `Symfony/Component`. And how about `/admin/article/{id}`.
That will be the `Article` id that we want to attach the reference to `/references`.
We'll give it a `name="admin_article_add_reference"`.

And

I'm also going to add `methods={"POST"}`. This is only going to uh, uh,

okay.

Respond to a post method and you don't need to have that. Eventually we're going to
um, you know, if we want to create other API end points we could so cause we have the
article ID here, I'll use the little trick where I can type into the `Article`. And
then I also want to do the security. We want to make sure that actually have access
to edit this article. So if you remember, the way that we do that typically is with
this `@IsGranted("MANAGE", subject="article")`. It's a voter we created in our Symfony
series. It makes sure that you are the author of this article or a super admin and
yeah, that's it.

Yeah.

And then let's go ahead and and get the ad, the `Request` argument and the one from
`HttpFoundation`. And then I'm going to `dd($request->files->get())`, and then the name we
had on our file upload field, which is `reference`, then a copy of the route name and
back in our template. And we're just going to link to that. So `action=` `{{ path() }}`, the
name of that. And I'll use multiple lines here at, and then we need to pass id and
then we need to pass the articles id. Uh, but actually we don't have the `article`
variable inside of this template. We do have the, um, uh, `form` variable and we could
get it and we could actually get the article off of that. Um, but just to keep things
a little bit cleaner, I'm actually going to go into my `edit()`. Uh, controller, which
renders this template and I pass it in the `article` variables. Now we can say
`article.id`. Alright. Refresh that and we'll inspect element here and make sure
everything looks good. Yup, that's the URL we want. Multipart perfect. So let's
choose a file. How about our Symfony best practices and upload. Nice. Our beloved
uploaded file, um, with the original name and we are set to go. So next, let's talk
about next. We're going to move this on to our filesystem, but the thing about
references is that we want them to be private so we are not going to move them into
the `public/` directory. Okay.