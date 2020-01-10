# Production Secrets

Coming soon...

Whenever you add a new secret, you need to make sure that you add it to the dev
environment and the prod environment. That's because these, this vault of secrets is
only going to be loaded in the dev environment, so if so, unless we also added to the
prod vault. Right now our product environment is going to be broken. How do we add
this key to a prod environment? It's with the same command because before Ben
consults secrets colon set, but with a dash dash N = prod and we'll set mailer_D, S N
here, I'll paste in my production. Send grid value. You can't see it there just
because it's being hidden, hidden, hidden for security. Now once again, because this
is the first time we've added a key to the prod vault it behind, it also added the
new prod decrypt and encrypt keys for us in a distant to actually encrypting the
value.

Now once again, this encrypted public key is something you should commit to your
repository. It only gives people access to add new keys to your vault, which you'll
probably want any developer on your team to be able to add new keys. But this prod
decrypt private that PHP is something that you do not want to commit. This is going
to allow someone to read your production secrets. This is somewhere you should keep
very safe with your deploy system, but it should not be committed to your repository
and actually for to get status and get ad config and then get status. You can see
that by default it is not um, committing that uh, private decrypt key. That's because
in our dot get ignore file. We are ignoring the private key. We got this when we
upgraded the Symfony framework bundle. So that is awesome because we, and just like
in the dev vault, we can look at the secrets by saying secrets on list dash dash N =
prod. And because we do locally right now have the decrypt key, we can say dash dash
reveal to see what it looks like.

So now if we commit this adding mailer DSN to prod vault, this is awesome because we
actually have our secrets safely committed to the repository. The only, which means
we only have to keep track of one kind of secret sensitive value and it's this
private. When we deploy to production, our only job is that we need to somehow create
this file which has this long value inside of it. So right now if we refresh our
page, it still shows us the no nothing because we are still in the dev environment.
So let's go to our [inaudible] and file and just temporarily I'm going to change our
environment to prod, then move over and run bin console, cache, colon clear. I don't
need to have a dash dash and prod here because I just changed that in my debt and
file. Perfect.

Now a new mover, it works. Our prod value comes out from the prod vault. Now just to
show you how this all works, I'm going to actually right click on this and go to
right click on this and go to refactor rename. And I'm just going to click a fool in
front of it right now. Basically that's me. I'm effectively doing that. So that
Symfony won't see that file anymore that I'm pretending as if we deleted that file.
Yeah, actually not. Let's just delete it. So right now, just so we can see how this
works, I'm going to open my prod kit file. I'm going to copy this and I am then going
to delete it as soon as you do this. If we refresh now we get a 500 air and let's
see, let's go over here and tail VAR, log prod that log.

And we can see the problem environment variable not found mailer underscored DSN. So
if you don't have that private key there, it's not gonna be able to decrypt it. And
that's the area that you're going to get. So you go back here now and actually I can
do command Z to undo that deleted file and now and refresh. We've got it back and
let's switch back to the dev environment just to make our life easier. So that's it.
You have a dev vault, you have a prod vault, you can commit your secret keys now to,
uh, your repository and you only have one secret thing you need to keep track of on
deployed. But there is one piece left this, which uncovers a couple of interesting
things. And the question is, what if I need to override a value in my dev vault on my
local machine? Like for example, in my dev vault right now I'm using the Knoll
transport, but what if I temporarily want to change that to male traps so I can
actually see emails being sent locally? How can we do that? Well, they handle that.
Symfony has what's sort of called a local vault set up, which is a beautifully simple
concept. Let's talk about it next.