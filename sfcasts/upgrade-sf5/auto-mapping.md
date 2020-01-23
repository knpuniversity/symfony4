# Auto Mapping

Coming soon...

[inaudible]

head over to slash. Admin.

Yeah.

/article login with admin one if space dot dotcom password engage. Okay. And then he
had to create a new article, one of my favorite new features in Symfony 4.4 actually
it comes from 74.3 but was improved in Symfony 0.4. Point four is something called
validation auto mapping. It's a super smart feature. So over here I'm going to go
into templates. Article admin_formed at age two months wig. This is the form that
renders this page. And to help us play with validation on the button, I'm going to
add it form and no validate

over here. If I refresh that will let me submit the entire form blank so we can see
the validation errors. We already have several validation errors, uh, which are
coming from the annotations on our article. For example, at assert /not blank is on
title. So no surprise if I took this at assert /not blank deleted it and delete it is
I'll actually just put it below the property just so I have a copy of it. But if I
remove that constraint when I go over here and resubmit the form blank, you
validation error is gone from article title. All right, so here's the new feature, go
back into article and on top of the class add at enable auto mapping.

Okay,

as soon as I do that and go back and refresh the error is back this value, it should
not be, no, it has different wording, but it automatically added a knot. No
constraints to this field. How the heck did that work? It guesses the validation
constraints based off of the doctrine meta-data. So this is actually nullable = false
by defaults and also off the co how our source code looks itself. I'll show you an
example of that in a second. By the way, to get the most out of this feature, make
sure that you have Symfony /property info installed. If that package doesn't come up,
install that because this is used to grab some of that metadata. All right? So, for
example, if we change this to Nobel = true, which means that this is now optional in
the database and go over and refresh the error is gone. Well, it's actually even
cooler than that. So I'm going to undo that.

Okay,

I'm actually going to take off the ad RM column entirely. So I'm going to pretend
like I'm not saving this the database. I also need to remove this ATG Edmodo /slog
just to avoid an air. So what's going to happen now for if we refresh, my guess is
that we won't get a validation error because there's no doctrine metadata that says
whether this field is required or not. But when we refresh, we do get a nother not
know. So now that there's no doctrine metadata here, instead Symfony looks on the
setter method. If there is one for title. So if you search for set title Symfony sees
that there's a set title method here. It sees that it requires a string. And because
this is not nullable, it assumes that the title is a required field.

Okay?

Check this out. Add a little question Mark before a string to make it nullable.
Refresh in the error is gone. So let's put everything back to go back to where we
were in the beginning. So what I love about this feature is it's just smart. It works
really well. So even if I add back my ad assert /not blank, wow. And go back and
refresh, check this out. I don't get to airs, I don't get the not no air and my
custom, not blank. It's smart enough to realize that because we have a not blank
annotation constraint on this

that

it doesn't need to add the not no, that would be duplicating.

Okay.

In addition to the knots, no constraint. It's also gonna automatically put life
constraints on here. So, uh, you know, because this is a two 55 length, if I get,

okay,

so if I type a super creative title over and over and over again and just paste that
all a lot of times and it enter, now I'm gonna get this value is too long. It should
have 205 characters or less. Just nice. It just helps me with all that sanity
validation. Now occasionally this feature can cause a problem and most notably in the
user class it's can sometimes create a problem because if you add

Mmm

auto mapping to this class, it's actually going to make your password fields
required, which we actually don't want because we want the F the registration form to
submit successfully without that being required. And then usually we would then
manually set that field to the encoded password. So if you have this problem, just be
aware of it. You can also on a field you can say at [inaudible]

Oh

at RM disable auto mapping and that would disabled just for that one field. All
right, next let's talk about hashing passwords and making sure that you are hashed.
Passers in. The database are always using the strongest algorithm. Oh one more thing.
You can also control this feature a bit and config packages validated at yell a by
default, the feature is only enabled if you add that annotation at a certain /enable
automatic, but you can also enable it for specific namespaces automatically. So if I
uncommon in this out, it would activate it for all of my entities. Even if I didn't
have that annotation, I prefer to opt into it, but other people like to have it be
automatic. It's up to you.