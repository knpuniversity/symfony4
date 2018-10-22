# Update Form

Coming soon...

Okay,

we know all about what it looks like to create a new `Article` form, create the form
process. The form request saved the article, but what does it look like to make an
edit form answer delightfully is almost identical. In fact, let's copy our. All of
our code from our `new()` action will go down to our `edit()` action where the only thing
we're doing so far is allowing Symfony to query for our article. Excellent. Now I'll
type in the missing arguments. We need the `Request` and also we need the 
`EntityManagerInterface $em`. Okay, so now this is now exactly the same controller that we
had before. So how do you make this an edit form? Very simple. Just pass `$article` is
the second argument to `->createForm()` them. That's it. When you pass `$article`, the second
argument and when Symfony renders the form, it will call the getter methods on that
article in order to fill in the form. In fact, we can already try this. It's using
the new template, but that should be fine. So when `/article/1/edit`, I don't know
if you don't have an id with article that [inaudible] let's go over here and run 

```terminal silent
php bin\console doctrine:query:sql 'Select * FROM article'
```

and perfect will use an ID 26
there. It is a completely filled in form. The other cool thing is when you submit it
calls the setter methods on that same `Article` object. So we can still say 
`$article = $form->getData()` for one, but these two `Article` objects are exactly the same. So we
actually don't even need this. That's it. This is now a functional edit page. We'll
update the existing article in doctrine will know to update that record in the
database. I'll just update my flash messages. Article updated in accuracy is squashed
and then instead of redirecting to the list page, let's give this article this route
in `name="admin_article_edit"` and we will redirect there, but of course we need to pass
the ID. That's in the wildcard. So `$article->getId()` the controller is now done. Now
we don't actually want to reuse the same twig template because it has things like
launch a new article and the word create, so instead of going to change this to 
`edit.html.twig` and then down in the `templates\` directory, I'm going to copy the
entire new template and change that to edit because the only thing is we need to
change aren't really those two things, edits the article, and then down here instead
of great. I will say update.

So now when we refresh.

Yep, it looks perfect in here. Let's change the content here a little bit. Hit update
and we are back. This has been saved to the database just like that. Of course, I
don't love having all this duplicated form logic, especially if we start customizing
some more stuff. So when I'm going to do is actually create a new template
called `_form.html.twig`. I'm prefixing this by_just to help me remember that this is
not going to be a full page. This is going to be a little template, partial values,
so I'm going to copy the entire form paste it inside of here. Then the one part that
needs to change, if we can just turn this into a new variable, we'll call it 
`{{ button_text }}`. Then from edit we can use the include function to include the article you_form
to html twig, and we can pass additional variables as the second argument. So I'll
say button text set to update. We'll copy this and go to new, remove all of that
duplicated stuff and say create. Perfect. So just to double check, this works. Yep,
this still works fine. If we'd go to `/article/new`. Nice. It works as well. And just
to make it easier to edit articles back on the list page, let's make a link to that
new edit page. So let's open list that age spots and we'll add one more column here
at the end.

If a path to `Article` `admin_article_edit`, passing an ID of article, that ID number,
the link itself. We'll just use an icon `fa fa-pencil`. Alright, let's try that
out. Go back to that last page. Yeah, there we go.

I mean click any of those. Go directly to that page and we've got it. So that's one
of the great things about the `Form` component is the edit in new pages are no
different. In fact, the `Form` component can't even tell the difference. All it knows
is that if we don't pass it in the `$article`, it will create one and then of course
when we save that new one, doctrine will do an insert query and when we do pass it
in, `$article` form just says, okay, I'll just update that existing `Article` and when we
save that one, doctrine is smart enough to know that this exists in the database and
to make an update.