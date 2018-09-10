# Authentication Errors

but the point is your user objects stored at the
session, but Symfony makes sure that it's always fresh at the beginning of every
single request. That is the main job of your user provider. All right, so let's go
back to the login page because honestly what happens when we fail login, which is
only possible right now by using a fake email address and oh, cannot redirect to an
empty url. And if you look down here, this is actually coming from our abstract form
login authenticator. If you looked into it, the problem is that the authenticator is
trying to call or get log in url. That's because if we fail, if we fail log in, of
course what we want to do is redirect back to the login page, but we need to tell the
authenticator where this is so return this->router error, generate

APP_login. All right, try it again, refresh and perfect, and you even see that we get
the air up here, username and not be found.

We get that exact error because the step that fails is the get user message in a
little bit. I'll show you how to customize that message to say, email cannot be
found. For example, if we failed to get credentials method, instead we'll log in with
a legitimate user. You'll see that it's automatically an invalid credentials message,
but more than that, I'm going to show you how this is working because if you go into
security controller, remember we're getting the air just by calling some
authentication utils, get last authentication error, and then we're passing that into
our template and we are rendering a message key property on that. So the whole
handling of the authentication error is happening a bit magically and I want to
demystify that a little bit. If you scroll to the top of your authentic cater and
hold command or control to click and click into abstract form, log and authentic
later. In reality, when authentication fails, this on authentication failure message
is called and see. What it does is it takes the air message which is stored on the
exception variable and it stores it in the session. Then in our controller, when we
say get last authentication error, I'm going to hold command again.

No, not not going to do that. That's actually a shortcut to go read that key off of
the session, so are authentic stores the air and the session, and then we read the
air off our controller and we render it. Then it calls get logged in. You were out
and it redirects us there, so no magic going on. Now you'll notice if you go back in
and fail authentication again with a fake email address, when you come back to the
email address, field is empty, which is not ideal. We should prefill that with the
email that they just put in, but if you look in your country, if you look in your
controller, we're using that same authentication utils to also get something called
get last username, which is the value that we put into that field. We're passing this
into our template, but I forgot to render this as a value on my email field. It's no
problem. Let's say value =

curly curly, last username. However, unlike the error messages, the last user name is
not automatically stored in the session. This is something that we need to do inside
of our login form authenticator. Super easy to do and get credentials. Instead of
returning, say prudential's equals. At this point we know exactly what email is being
set, so let's set this in the session by saying request Arrow, get session,->set, and
when you use a special constant here called security, get the one from the security
components last user name and set that to credentials email that will store this
value in the session, which is where getting in the get last year's and a method
reads that key off of the session. Then at the bottom we'll return the same
credentials, so the last user name and the air are both set to the session and we
read those. Now if we go back, log in with that same email address again, we get the
air in. This time the email stays awesome. So let's learn how to customize these
error messages and we need to add a log out link so that we can actually start
logging out. Let's do that next.
