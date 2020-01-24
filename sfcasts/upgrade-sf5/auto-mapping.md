# Auto Mapping

Coming soon...


head over to `/admin/article` login with `admin1@thespacebar.com` password `engage`. 
Okay. And then he
had to create a new article, one of my favorite new features in Symfony 4.4 actually
it comes from Symfony 4.3 but was improved in Symfony 4.4 is something called
validation auto mapping. It's a super smart feature. So over here I'm going to go
into `templates/article_admin/_form.html.twig`. This is the form that
renders this page. And to help us play with validation on the button, I'm going to
add it `formnovalidate`

over here. If I refresh that will let me submit the entire form blank so we can see
the validation errors. We already have several validation errors, uh, which are
coming from the annotations on our `Article`. For example, `@Assert\NotBlank` is on
`$title`. So no surprise if I took this `@Assert\NotBlank` deleted it and delete it is
I'll actually just put it below the property just so I have a copy of it. But if I
remove that constraint when I go over here and resubmit the form blank, you
validation error is gone from article title. All right, so here's the new feature, go
back into `Article` and on top of the class add `@Assert\EnableAutoMapping`.

as soon as I do that and go back and refresh the error is back "This value should not be null"
it has different wording, but it automatically added a `NotNull`
constraints to this field. How the heck did that work? It guesses the validation
constraints based off of the doctrine meta-data. So this is actually `nullable=false`
by defaults and also off the co how our source code looks itself. I'll show you an
example of that in a second. By the way, to get the most out of this feature, make
sure that you have `symfony/property-info` installed. 

```terminal-silent
composer show symfony/property-info
```

If that package doesn't come up,
install that because this is used to grab some of that metadata. All right? So, for
example, if we change this to `nullable=true`, which means that this is now optional in
the database and go over and refresh the error is gone. Well, it's actually even
cooler than that. So I'm going to undo that.

I'm actually going to take off the `@ORM\Column` entirely. So I'm going to pretend
like I'm not saving this the database. I also need to remove this `@Gedmo\Slug`
just to avoid an error. So what's going to happen now for if we refresh, my guess is
that we won't get a validation error because there's no doctrine metadata that says
whether this field is required or not. But when we refresh, we do get another `NotNull`
know. So now that there's no doctrine metadata here, instead Symfony looks on the
setter method. If there is one for `$title`. So if you search for `setTitle()` Symfony sees
that there's a `setTitle()` method here. It sees that it requires a `string`. And because
this is not nullable, it assumes that the `$title` is a required field.

Check this out. Add a little question Mark before a string to make it nullable.
Refresh in the error is gone. So let's put everything back to go back to where we
were in the beginning. So what I love about this feature is it's just smart. It works
really well. So even if I add back my `@Assert\NotBlank`, wow. And go back and
refresh, check this out. I don't get two errors, I don't get the `NotNull` error and my
custom, `NotBlank`. It's smart enough to realize that because we have a `NotBlank`
annotation constraint on this that it doesn't need to add the `NotNull` that would 
be duplicating.

In addition to the `NotNull` constraint. It's also gonna automatically put `Length`
constraints on here. So, uh, you know, because this is a `255` length, if I get,

so if I type a super creative title over and over and over again and just paste that
all a lot of times and it enter, now I'm gonna get this value is too long. It should
have 255 characters or less. Just nice. It just helps me with all that sanity
validation. Now occasionally this feature can cause a problem and most notably in the
`User` class it's can sometimes create a problem because if you add

`AutoMapping` to this class, it's actually going to make your `$password` fields
required, which we actually don't want because we want the F the registration form to
submit successfully without that being required. And then usually we would then
manually set that field to the encoded password. So if you have this problem, just be
aware of it. You can also on a field you can say at `@Assets\DisableAutoMapping`
and that would disabled just for that one field. 

All right, next let's talk about hashing passwords and making sure that you are hashed.
Passers in. The database are always using the strongest algorithm. Oh one more thing.
You can also control this feature a bit and `config/packages/validator.yaml`
by default, the feature is only enabled if you add that annotation` @Assert\EnableAutoMapping`
but you can also enable it for specific namespaces automatically. So if I
uncommon in this out, it would activate it for all of my entities. Even if I didn't
have that annotation, I prefer to opt into it, but other people like to have it be
automatic. It's up to you.