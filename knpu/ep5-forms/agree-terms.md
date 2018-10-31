# Agree Terms

Coming soon...

One of the things we've lost on this new registration form versus our original one,
if you look in the template is the all important agree to the terms checkbox, which I
know we all love with the depth of our heart. This is of course actually legally an
important part that we need to have in our registration form, so let's code this up
correctly now, a few years ago, we may have coded this box, this checkbox just by
adding maybe in unmapped field to our form just to make sure that they check the
checkbox, but then we wouldn't actually save anything to the database because after
all, it's kind of a pointless field to say that the user check the checkbox when it's
something you force with validation, but nowadays for legal purposes, we want to know
the exact dates that the person actually agreed to your terms. So we're actually
going to add a new field to our user entity that records that exact date. So find a
terminal and run, 

```terminal
php bin/console make:entity
```

We'll update our `User` class and we
will do agreed Megan Newfield called `agreedTermsAt` that. Should be a `datetime` field
and it cannot be `nullable` in the database because we need this to be set on all
cases. Then I'll hit enter to finish that. Now ultimately, before I worry about the
migration, what we want on our forum is a checkbox,

so at the bottom of my form I'm actually going to add a new checkbox. I'm going to
call it `agreeTerms`. Now notice I'm not there. There is a slight problem. Now I call
this `agreeTerms`. Our new field is actually called `agreedTermsAt`, so this is
not going to work out of the box. We're going to need to do a little bit more work
here and even more than that. If you google for a Symfony form types

and click for the form type reference, if you look down here on the `Checkbox` field,
you `CheckboxType`, it says that this should always be used for fuel that has a
boolean value. If the box is checked, the field will be set to `true`. If the box is
unchecked, the value will be set to `false`. That makes perfect sense. That's the whole
point of a checkbox, so we do have a small problem now in that we want a checkbox on
our form, so I'll use `CheckboxType::class`, which is `true` or `false`, but ultimately
in our user class we need this to be set to a day, so that's going to be something
that we're gonna need to fix, but first let's run our migration. So 

```terminal-silent
php bin\console make:migration
```

 We'll go and open up our migrations directory, open up that
file, and Yep, looks good. Just adding that one field. We'll run 

```terminal
php bin\console doctrine:migrations:migrate
```

but Oh, things are not happy. Remember, we have existing users in our database table,
so when we suddenly create a new field that is not `null`, my sql has a hard time
figuring out what value to put into that field, so we actually need to be a little
bit smarter with our migration. Now, fortunately, there's only one statement in this
migration, this altar table, and since it failed, it means this entire migration
failed and this `agreed_terms_at` was not set. When migrations fail, things can be a
little more more complicated when you have multiple statements and one migration file
and maybe some of them executed but not others. In newer versions of my sql, those
would all be wrapped in a transaction. If you're using my sql five, then it's
possible for individual statements to run and not the not fail altogether anyways. We
can actually safely execute this changes migration than execute again, and what we're
going to do is we're gonna change this to from not `DATETIME NOTNULL` to 
`DATETIME DEFAULT NULL`, just temporarily. And then we can say 
`$this->addSql('UPDATE user SET agreed_terms_at = NOW()');`,

so first let's go back and run that one migration.

```terminal-silent
php bin\console doctrine:migrations:migrate
``` 
 
It works this time and then to actually finish things, I'll run it, 

```terminal-silent
php bin\console make:migration
```

one more time and this
should just give us some migration. Yep. That changes this now to not know which will
be allowed now that we've given all of those users, existing users a real value. Now
for legal purposes, you probably wouldn't want to do this in production because you
need those users to actually agree to their terms, but from a database migration
standpoint, this should work just fine. Yep, and it does. All right, so let's get
back to work.

There are two ways to get the form class, this field in the form class to play nice
with the property on our `User`. The first one is we could do something clever where we
create a `setAgreedTerms()` method on our `User` class and when that's called, we
actually set the `agreedTermsAt` the property to the current date because remember,
when we have an agreed terms field on our form, it doesn't mean we have to have that
property and our user. It just means that we have to have a good agreed terms method
and he set agreed terms method so we could actually make this work by making that
getter and setter and using the other property to return a `true` or `false` value. The
only problem with that is it's a little bit. It's a little bit weird. It makes your
code a little bit hard to write. It's a little bit clever, so instead I'm going to
make this another `mapped` field `false`, which simply means that we're going to handle
this logic in our controller. Most going to add some constraints to this in order to
make this a validate that this is definitely checked. We can say `new IsTrue()`

and I'm past that. The normal message option says, I know, I know it's silly, but you
must agree to our terms. Excellent. All right, so now this is `mapped = false`, which
should be able to go over, refresh the page and yes, it's actually impossible to see
because of some styling problems right now their site, but there is a checkbox away
over here actually off my screen, which we are definitely going to need effects.
We'll worry about that in a second because we have a set map map to false. It means
that this logic is no longer being a. it's no longer being set in our entity. It
means in our security controller, we need to handle it manually just like we do with
set the password field, which is no problem.

We can say `if (true === $form['agreedTerms']->getData())`.
Now that might seem a little redundant to you since we are forcing this to be `true`
via validation and you're right, I'm just being extra careful us for legal purposes
to make sure I actually checked to make sure that that was actually checked in utero.
We basically want to now say `$user->setAgreedToTermsAt()` and set that to stay state,
but instead of calling that method, we can do this a slightly cleaner way and these
are class. I'll look for my `setAgreedTermsAt()` and instead of having this method,
I'm going to rename it to `agreeTerms()` not have any argument and instead say
`$this->agreedTermsAt = new \DateTime()`. So basically it's just making
it easier. I can just call that directly. And `SecurityController` will say 
`$user->agreeTerms()`. All right, let's try that. Let's refresh our page, and
annoyingly, I can't actually see my field yet, so let's hack in a little bit of
padding on my div here. Okay, there we go.

We're registered as Jordy three at the enterprise that come password engage, hit
enter, and yes we got it. And we know that got checked it. We know that got set in
the database because it's required in the database. So we would have seen a huge air
if we didn't. So there's a really nice way of using it, mapped unmapped fields on
your entity, do you have the exact fields you want, and then just a little bit of
glue code and your controller to update your `User` class. Sometimes with the user. Uh,
sometimes this is a better solution when you're working with some of these forms
system. Then a more clever solution like creating a set agreed terms method because
it's just easier to read and it's easier for people. It's easier to learn and know
that you can do this.

Oh, but there's just one more small problem. Try to reload the fixtures:

php bin/console doctrine:fixtures:load

It... explodes! We've made the new `agreedTermsAt` field *required* in the
database. So, we need to update our fixtures. No problem. Open `UserFixture`.
In the first block, add `$user->agreeTerms()`. Copy that, and do the same for
the admin users.

Cool! Let's make sure that works - reload again.

And.... all better.