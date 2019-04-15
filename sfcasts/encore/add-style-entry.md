# Add Style Entry

Coming soon...

There are now only two files left inside of our public directory. I can kill the js
directory entirely. Uh, there's two, both just two CSS files. These are included on
the, let's see, the account index .html.twig. It has its own page specific CSS and
insecurity log in that each month, like it has its own page that CSS and

okay.

And uh, we also include login over here on register. So this is a kind of a tricky
case because what job, what Webpack wants you to do is it always wants you to have an
entry file, which is always a JavaScript file and then it will output the JavaScript
file and the final CSS file if you have one. But if you have a case where you just
simply do not have a JavaScript file, it gets a little bit awkward. So what we could
do is create an account dot js and a login dot js and all those files would do was
import these CSS files that would work. We would then have an extra empty account dot
js and the login dot js. But that's not that big of a deal. But we do support this
while we, you know, encore and we do really recommend that you think of re of doing
it the proper way. Sometimes we realize that you just have random CSS files so we can
handle this very easily. So first thing I'm gonna do is I'm going to move both of the
CSS files up into our CSS directory and just because we can, I'll make both of these
scss files.

Yeah.

And then inside of Webpack Dot config that jazz, so we can add a special thing called
add that style intrigued. We'll have one called account or point it at assets /CSS /a
comp that SPSS and the other one called log in that points to log in the scss.

Okay.

It's now if you move over to find your build control c and run yarn watch.

Okay.

And you can see the entry point account and log in, just dumped those CSS files,

which is perfect. And now means that we can go into our index .html.twig. Your place
was with encore link tags for account. A copy of that and I'll do the same thing
inside of logging that h wait for the login entry and then registered at each one
played for the login entry there. And just to make sure this looks okay, we'll check
the account page profile. Yup. Everything seems to look just fine. So I just want you
to know that this is available is actually kind of a hack internally. The way encore
does this is, this is the same as add entry, except that a Webpack, we'll still
output a JavaScript file, an empty account dot js. So because we're using ad style
entry, a encore just deletes that for you so you don't have to deal with the extra
contact js file, which would have been empty anyways.