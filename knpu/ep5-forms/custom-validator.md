# Custom Validator

Coming soon...

Unfortunately, you can't use the `@UniqueEntity()` validation constraint above a class
that's not an entity, it's just a limitation that's not currently possible, so this
means that there is no built in validation and strength that can help us out here and
when you have, when, when, when you get in a situation, when there's no built in
validation constraint, the next thing to try is the `@Assert\Callback`. We use this
in an `Article` class. You create a public function, put that annotation of other
function and during validation your function's called and you can do whatever you
want. The only limitation is that we don't have access to any services in this
situation because we're just innosight as simple entity class. So if you need access
to services like you need to make a database query, then you need to create a custom
validation constraint. Fortunately, it's super easy. Find your terminal and run 

```terminal
php bin\console make:validator
```

Let's create a new validator called `UniqueUser`.
And notice this creates two classes, `UniqueUser` and `UniqueUserValidator`. Go find
those in your new `Validator\` directory. So first `UniqueUser` is basically a
configuration object, and we're actually going to use this annotation `@UniqueUser`
validation itself is actually handled by the `UniqueUserValidator`.

It's going to be past the `Constraint` object which will actually be our unique string
object and we can read any data off of it in order to get our job done. So it's
reading the message in order to add the validation error. And we have a message as a
public property over here.

So the first thing you need to do with your annotation, first we need to do is
actually make sure your annotation class is configured. Now, annotation in general
can be added either above the class or above the property, and actually you can also
add them above methods. If you add your validation annotation above your class, then
during validation, the value that's passed to you is the entire object. If you add it
above a property, then the value of your past is just at that property. So if you
need access to multiple fields on a class for validation, then you want to create a
an annotation that can be used above the class. This situation, I'm going to delete
my `@UniqueEntity` and I'm going to put my new annotation above my `$email` field. So
`@UniqueUser` am I hit tab actually adds that as a new use statement. So basically
we're going to take whatever value is populated with this email and instead of our
validator we're going to make we're gonna. Use that value to query the database to
see if that's already a already found. But first when you do a little bit more work
inside of our annotation class, hit shift, shift, and open the `NotBlank` core
annotation. Notice it has an `@Target()` annotation on top of it. This is something
special to the annotation system. By adding at target, it tells, it tells the system
where your annotation is allowed to be used. So let's copy that and paste that above
ours. This says it's okay to be used above a property above a method or even inside
of an annotation. That's an edge case, but we'll leave that.

If you did want your annotations video to be used above in a class, I would follow
the, uh, I would check out the unique entity constraint to follow it. Notice it
hasn't at target of class. And then the other thing you need to do is override the
targets. This tells the validation system that it's okay if this is applied to a
class anyways. The last thing we need to change inside of our `UniqueUser` is let's
change this `$message` and we'll have it be the same thing that we have above our User
class. I think you already registered, paste that and that's it. If we need more
configuration options, we can actually pass great more public properties on our
`UniqueUser`. And you can override any of these when you use your annotation and we're
not doing it here, but I couldn't say message = and uh, we can put whatever we wanted
in order to customize that. Alright, so just to see if this is working, let's go over
to our set parameter. Let's go over to val, a unique user validator and remove this
set parameter part. That's a way of filling in a wildcard values. We don't need that
and let's just make this always fail.

Speak over an hour now and refresh to resubmit the form. Boom, we've got it. Look, I
think you've already registered, which doesn't even make sense because we don't have
anything filled in. All right, so to do that, we're going to need to make a query
instead of our validator. Fortunately these validator classes, our services and so we
can use normal dependency injection `__construct()` method on top will type end 
`UserRepository $userRepository`. I'll hit alt entered to create that property and set it
down here. We can say `$existingUser = $this->userRepository->findOneBy()`

then we'll search for email set to
whatever value is I don't remember because we have put this above our email property.
The email is the value that's going to be passed to us automatically. None very
simply, `if (!existingUser)` then we'll `return;`. Very simple. Now, if we were using
this on an edit form, we would also want to make sure that if we found an existing
user, it's, we wouldn't want to throw an error if that was the same as this user.
That would just mean that they haven't changed. In that case, you'd actually need the
`$value` to be the entire `User` object so you can look at the `ID` and so for that you
would need to make this `UniqueUser` something that you added to the class, not just
the property. Alright, that's it. Back over and refresh and got it. We fill in a new
user and I'll add the no validate so I can keep the other fields blank and hit
register. Yep. The error goes away if we try. Will riker and summit the summit. The
field for him? Yep. We get the air. So custom validation constraints, super powerful
tool.