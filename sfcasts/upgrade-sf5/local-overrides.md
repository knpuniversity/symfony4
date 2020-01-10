# Overriding Secrets Locally & Test Secrets

Coming soon...

What if I need to override a secret value on my local machine? Miller is actually a
perfect example. In our development secrets vault, we configure a mailer DSN to use
the null transport, which means it doesn't actually send emails, but if I'm testing
emails and I do want to override this maybe to send things to mail trap, how do I do
that? Well of course I could have run over here and run and say Ben console secrets
set mailer DSN and I could actually change that value in the vault. The problem is
that that is going to modify my mailer DSM vault file in. Then I'm going to have to
be super careful not to commit that, so not a great solution. Fortunately there is a
system inside of the secrets vault to take care of this. It is run that same at
secrets on set mailer DSN but pass a dash dash local on it. It looks exactly the same
way

when I paste in a my mail chat value here and interesting. So this did not make any
changes to our development vault at all. When you use that dash dash local flag, it's
Bay. All it does is put the file into a dot N. dot. Dev dot local file. Now you
probably already know that you can create, add that in that local file in this file
is loaded, but it's ignore from get and in fact you can actually have a dot N. dot.
Dev dot. Local and a dot M. dot. Prod that local that are also not committed. And
this one's only loaded in the dev environment, so it's acts very closely to the same
as dot M. dot. Local. The point is this local vault thing is nothing more than a
fancy way of setting an environment variable in a local file.

And that makes sense. As I mentioned earlier, when you use the environment variable
system, Symfony first looks for mailer DSN as an environment variable. If it finds
it, it uses it. And now in the development environment on my machine, it will find it
only if it doesn't find an environment variable, does it then go look in the vault.
So when I go and refresh this, now I have successfully overwritten that. So you can
use this cool Ben console secrets colon set thing. But really all you need to
understand is that if you want to override an environment variable locally, if you
want to override a secret value locally, just set it as an environment variable. Um,
I could even, I don't love having this, that M, that dev dot local file. It seems
excessive. So if I wanted to, I could even delete that and just pop it into my dot M
that local file and it's going to work just as well. But I'm going to, no, that was
telling them that I'm going to remove it. So this fact, the fact that environment
variables override secrets unlocks three possibilities. The first is the, for what
you just saw, we can very easily override secrets locally just by creating an
environment variable. The second interesting thing is that on deploy,

we can dump all of our secrets from our Prague vault into a dot and that local file,
check this out. I'm going to run it. VIN console secrets, colon decrypt to local dash
dash force, which, uh, tells assessment to override any entries that are in there
already. Dash dash N = prod. Right now you don't see any output from the command.
There's a pull request open to add a little more friendly output for this did is this
created a dot N. dot. Prod dot local file. Basically it went through the entire
production vault here and dump them into this.M. dot. Product local file. So now in
the production environment, it reads from this file, it's not reading at all from the
vault. Why is that interesting? Well, it's interesting for two reasons. The first is
that during your deploy, you can somehow make this user deploy system to make this
prod decrypt, that private dot PHP file, uh, to create this, you can then run this
secrets decrypt to local command and then delete that private key file immediately.

That private key file then does not need to live on your production server at all. It
just needs to live in kind of your, during your build process. The second reason is
this also gives you a minor performance boost because you're not decrypting the
values. Now you might be thinking, isn't this less secure? Because now all of my
environment variables are in plain text in this file. And the answer is no, you're
secret files on production are never completely safe. It's all about knowing the
attack vector. If somebody can somehow get access to this file, then they can read
that. They can, they can read your sensitive value. But if you're using the private
key file on production, then if someone got access to that file, then they could
still decrypt your passwords. So it's no more or less secure than having the private
key up on production. The third interesting thing that this,

okay,

I believe that M that pride to local just to get it out of the way. The third
interesting thing that this,

uh,

that we can do now that we understand that environment variables over at secrets is
we can fix our test environment. Think about it in the test environment, there is not
going to be a mailer, DSN environment variable set right now, but we can leverage the
new thing we learned to fix that. So first I'm gonna run over and say PHP bin /PHP
unit to run our tests

and

you can ignore the deprecation warnings. Whoa, huge doctors. And if you look at this,
look at this environment variable, not found mailer_D S N by the way, new fund feeder
and Symfony. Um, 4.4 is that because these air pages are hard to read and the test
environment dumps the exception as a comment on the top of the uh, HTML. And it also,
if I remember correctly, yup, dumps it on the bottom. So actually didn't need to
scroll up so far. I could've seen that right here. Love that. So there is no mailer
DSN in a value. And then in the environment variable in the, in the test environment,
the way to fix this is very straight forward. Put it in your dot and dot test file,
which is committed. So I'm actually going to use my old Knoll value. I'll copy it
from my dot N here, other DSN,

but that into dot M. dot test. So now in the test environment, there will be a real
environment variable, so it doesn't need to live in the secrets vault. So really when
you add a new in secret to the vault, you need to add it in the dev environment, the
prod environment, and put it into your dot M. dot test file so that it's in every
single environment. Let's run our tests with one more time and they pass. Beautiful.
Okay. That's it for the new vault feature. Let's just do a little cleanup on our
project here. I'm gonna remove the mailer DSN, a bind that I created, and then go
into our article controller and take out the mailer DSN stuff.

Okay,

perfect. Now our project is back to it. Next, let's talk about a really cool new
feature called auto mapping. And validation are really a smart system that
automatically adds validation constraints for you based on your doctor metadata. And
also the way your actual PHP code is written inside of your class.
