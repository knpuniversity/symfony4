# Author Relation

Coming soon...

If you look in the homepage, you can see that every `Article` has an author, but when
we originally set up the system, if you look inside `Article`,

the `author` is just a string field and that's because we didn't know how to handle
the database relationships correctly. What I want to do now is replaced this `author`
string property with a proper relation to our `User` entity, so every `Article` will be
created by a specific user. The reason we're doing this is it's going to lead us down
to some very interesting access control problems with being able to control who is
allowed to edit each individual `Article`, but first let's just worry about the
relationship stuff, so to remove first, let's remove the `author` property entirely.
Also find the getter and setter methods and we will remove those. How about a
```terminal
php bin/console make:migration
```
If this were on production, you might need to be a little bit more careful to make
sure you didn't lose that data. If you needed it in the migrations directory, I'll 
open up the new migration and yep, `ALTER TABLE `Article` DROP author`. 
Great. Next, let's go back and let's run
```terminal
php bin/console make:entity
```
Let's read ad that `article` property, the other property, but
as a relationship, so let's update the `Article` on Steve. We're going to add a new
property called `author` will make it a relationship. This is going to be a
relationship to our `User` entity. Now this is going to be another `ManyToOne`
relationship because each `Article` has one `User` in each `User` can have many articles,
so `ManyToOne`.

The other author property should not be knowable and this should be required in the
database and we'll say "yes" to mapping the other side of the relationship and I'll
say "no" to orphan removal, but that's not important right now. Then I'll enter to
finish.
Then once again, run
```terminal
php bin/console make:migration
```
Oh,

and once again, look over and oh, of course you probably saw I made a mistake there.
You can say it's adding `author_id`, but dropping `author`. That was my mistake.
Delete that migration file because I free after I generated the initial migration file, 
I forgot to migrate. 
```terminal-silent
php bin/console doctrine:migrations:migrate
```
Well, migrate that will drop that `author`, original `author`
property. Now we'll clear and ron
```terminal
php bin/console make:migration
```
again.

Gosh darn it.

Move over and okay, this looks better. Just adding the `author_id` and the foreign
key constraints on that. I'll close out those migrations and once again, run
```terminal
php bin/console doctrine:migrations:migrate
```
Whoa. When you can see this actually explodes, this
isn't one of those tricky migrations where because we made the new column required,
it actually fails to add the foreign key in the migration. If this were, if our
application we're already deployed to production, well, we'd actually need to do is
actually first make the property `nullable=true`, generate the migration, so that's
allowed to be `null`. Then run some script or a query to set up the author for all the
existing articles. Then changes this dental legal spouse and generate another
migration. So three different steps to do that because this isn't on production yet.

Yeah,

we know when we first deployed, our database is going to be empty and it's not going
to have this problem, so we're not going to do is actually run
```terminal
php bin/console doctrine:schema:drop --full-database --force
```
That's a fancy way of actually dropping every table in the database. Then I'm going 
to rerun all of my aggressions to make sure that they are working. Okay, cool. And you 
can see it works that time because there weren't any articles in the database, so 
now we do need to go into our `ArticleFixtures` class though.

Okay.

It makes sure we update the code in here. Set `$author`. His strength thing is not going
to work anymore. Well, we need to do is actually relate this to one of the users
that's created in `UserFixture`. Remember we have two groups, these main users and
these admin users. I'm going to allow normal people to create a `Article`, so I'm going
to relate it to one of these main users. To do that, we can save
`$this->getRandomReference('main_users')` that will randomly give us one
of those elements and then on the top of the class I can remove this old static
property. All right, let's try that. Move over. Run
```terminal
php bin/console doctrine:fixtures:load
```
and it works, but only luckily. Notice that `UserFixture` happened to run
before `ArticleFixtures`.

We need that to happen now that these are dependent on each other but down and get
dependencies. I actually want you to add `UserFixture::class`, that will
make sure that the `UserFixture` always run before `ArticleFixtures`. Whereas right
now there wasn't guaranteed yet. Do you want to? You can try it again and you shouldn't
see it will all load in that same order, but now it's a guaranteed. 

All right, so our articles now proper user relationships, but we haven't updated 
anything else in our code. And if refers to the home page, now you get a big explosion 
and exception has been thrown. Rendering the template `Catchable Fatal Error: Object of Class
Proxies\__CG__\App\Entity\User cannot be converted to string`. So two important
things here, whenever you see this proxies thing, this isn't internal object as wrapping 
your entity, ignore it. I want you to pretend like this just says that our `User` 
could not be converted to `string`. This part of the `Article` makes sense because 
in our template we're just friending `{{ article.author}}` that used to be a `string`, 
but that's now a `User` object.
So we could go change this to `Article` that author dot first name or we can go into
our `User` class and add a two string method `public function __toString()` and 
`return $this->getFirstName()` Soon as we do that. Nice. It works perfectly. 

Alright, so what I really want to talk about is I want to start talking
about, uh, adding an admin section where you can edit articles. So we already have an
`ArticleAdminController` though. There's only one end point and it just has a di
statement in it right now. Hello, this, let's create another `public function edit()`. I love this. I'm going to put the normal route and we'll have the you where
I'll be `/admin/article/{id}/edit`. I won't give it any name yet.
Down here we can actually say `Article $article`.

Yeah,

and Cynthia will use the `id`, the query for that specific `Article` and inside just
to see if this is working. Let's do a `dd($article)`. Now. You'll remember that this
entire controller protected by `ROLE_ADMIN_ARTICLE` right now, so only avid users can
see it. To get a valid id. I'm actually going to go over my database run
```terminal-silent
php bin/console doctrine:query:sql 'SELECT * FROM article'
```
and it looks like right now I can use the ID 20. Perfect, so let's go to 
`/admin/article/20/edit` to the login page. If you've got
an to access, I just go log into somebody else and we'll say
`admin2@thespacebar.com`, password `engage`

and her. Perfect. We are bumped back to that page. Now here's where things get
interesting. I do want admin users to be able to access this page, but I also want
the author of this `Article` to be able to edit it too. The problem is if the author is
just a normal user, they're not going to have `ROLE_ADMIN_ARTICLE` and I don't want
to give them `ROLE_ADMIN_ARTICLE` because I don't want them to be able to create new.
Maybe I don't want them to do other stuff like create new articles or delete
articles, so for the first time our access control rules are more complex. We need to
be able to allow admin users to edit this `Article` or the owner of the `Article`. The
permissions are different for each `Article`. We're going to solve that next with a
great system called voters.