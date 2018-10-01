# Deny Access in the Controller

There are two main places where you can deny access. The first we just learned about:
`access_control` in `security.yaml`:

[[[ code('4a03ec6438') ]]]

It's simple - just a regular expression and a role. It's *the* best way to protect
entire *areas* of your site - like *everything* under `/admin` with `ROLE_ADMIN`.

I *do* use access controls for things like that. But, most of the time, I prefer to
control access at a more granular level. Open `CommentAdminController`. Most of
the time, I deny access *right* inside the controller.

To test this out - let's comment-out our access control:

[[[ code('e9712be78f') ]]]

Back in `CommentAdminController`, how can we deny access here? Simple:
`$this->denyAccessUnlessGranted()` and pass this a role: `ROLE_ADMIN`:

[[[ code('53f8794507') ]]]

That's it. Move over and refresh!

Nice! Try changing it to `ROLE_USER`:

[[[ code('7e21da9e77') ]]]

Access granted! I love it!

## IsGranted Annotation

But wait, there's more! As simple as this is, I like to use annotations. Check this
out: delete the `denyAccessUnlessGranted()` code. Instead, above the method,
add `@IsGranted()` to use an annotation that comes from SensioFrameworkExtraBundle:
a bundle that we installed a long time ago via `composer require annotations`. In
double quotes, pass `ROLE_ADMIN`:

[[[ code('e287113a9f') ]]]

Nice! Try it: refresh!

> Access Denied by controller annotation

Pretty sweet. I know not everyone will *love* using annotations for this. So, if
you don't love it, use the PHP version. No problem.

## Protecting an Entire Controller Class

Oh, but the annotation *does* have one superpower. In addition to putting
`@IsGranted` above a controller method, you can *also* put it above the controller
*class*. Above `CommmentAdminController`, add `@IsGranted("ROLE_ADMIN")`:

[[[ code('1372d148bc') ]]]

Now, *every* method inside of this controller... which is only one right now, will
require this role. When you refresh... yep! Same error. That is an awesome way to
deny access.

We know how to make sure a user has a role. But, how can we simply make sure a user
is logged in, regardless of roles? Let's find out next - *and* - create our first
admin users.
