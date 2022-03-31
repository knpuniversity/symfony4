# JavaScript y Activos Específicos de la Página

El tema de las API es... ah... un tema enorme y enormemente importante hoy en día. Vamos a profundizar en las API en un futuro tutorial. Pero... Creo que al menos tenemos que llegar a lo más básico ahora mismo.

Así que éste es el objetivo: ¿ves este icono de corazón? Quiero que el usuario pueda hacer clic en él para que le "guste" el artículo. Vamos a escribir algo de JavaScript que envíe una petición AJAX a una ruta de la API. Esa ruta devolverá el nuevo número de "me gusta" y actualizaremos la página. Bien, el número de "me gusta" es sólo un número falso por ahora, pero aún podemos tener todo este sistema configurado y funcionando.

## Creando el nuevo archivo JavaScript

Ah, y por cierto, si te fijas en la parte inferior de `base.html.twig`, nuestra página sí tiene jQuery, así que podemos usarlo:

[[[ code('0f67bed719') ]]]

En el directorio `public/`, crea un nuevo directorio `js/` y un archivo dentro llamado, qué tal, `article_show.js`. La idea es que lo incluyamos sólo en la página de presentación del artículo.

Empieza con un bloque jQuery `$(document).ready()`:

[[[ code('9a89d847ed') ]]]

Ahora, abre `show.html.twig` y, desplázate un poco hacia abajo. Ah! Aquí está el número codificado y el enlace del corazón:

[[[ code('25f0a86b61') ]]]

Sí, iniciaremos la petición AJAX cuando se haga clic en este enlace y actualizaremos el "5" con el nuevo número.

Para configurar esto, vamos a hacer algunos cambios. En el enlace, añade una nueva clase `js-like-article`. Y para apuntar al 5, añade un span alrededor con `js-like-article-count`:

[[[ code('66074f8c17') ]]]

Podemos utilizarlos para encontrar los elementos en JavaScript 

Copia la clase del enlace. Escribamos un JavaScript muy sencillo... pero aún así impresionante: encuentra ese elemento y, al hacer clic, llama a esta función. Empieza con el clásico `e.preventDefault()` para que el navegador no siga el enlace:

[[[ code('3c57856ea0') ]]]

A continuación, establece una variable `$link` como `$(e.currentTarget)`:

[[[ code('47d551cc3a') ]]]

Este es el enlace que se acaba de pulsar. Quiero alternar ese icono del corazón entre estar vacío y lleno: hazlo con `$link.toggleClass('fa-heart-o').toggleClass('fa-heart')`:

[[[ code('ca28181c58') ]]] 

Para actualizar el valor de la cuenta, ve a copiar la otra clase: `js-like-article-count`. Encuéntrala y establece su HTML, por ahora, en `TEST`:

[[[ code('3843aac1f8') ]]]

## Incluir el JavaScript específico de la página

¡Es bastante sencillo! Todo lo que tenemos que hacer ahora es incluir este archivo JS en nuestra página. Por supuesto, en `base.html.twig`, podríamos añadir la etiqueta script justo al final con las demás:

[[[ code('2aa8f813aa') ]]]

Pero... en realidad no queremos incluir este archivo JavaScript en todas las páginas, sólo lo necesitamos en la página de presentación del artículo.

¿Pero cómo podemos hacerlo? Si lo añadimos al bloque `body`, en la página final aparecerá demasiado pronto, ¡incluso antes de que se incluya jQuery!

Para añadir nuestro nuevo archivo en la parte inferior, podemos anular el bloque `javascripts`. En cualquier lugar de `show.html.twig`, añade `{% block javascripts %}` y `{% endblock %}`:

[[[ code('d937677f48') ]]]

Añade la etiqueta script con `src=""`, empieza a escribir `article_show`, ¡y autocompleta!

[[[ code('e4b338c175') ]]]

Todavía hay un problema con esto... y puede que ya lo veas. Actualiza la página. Haz clic y... ¡no funciona!

Comprueba la consola. ¡Woh!

> $ no está definido

¡Eso no es bueno! Comprueba el código HTML y desplázate hacia la parte inferior. Sí, literalmente sólo hay una etiqueta de script en la página. ¡Eso tiene sentido! ¡Cuando anulas un bloque, anulas completamente ese bloque! ¡Todas las etiquetas de script de `base.html.twig` han desaparecido!

¡Ups! Lo que realmente queremos hacer es añadir al bloque, no sustituirlo. ¿Cómo podemos hacerlo? Digamos `{{ parent() }}`:

[[[ code('7e5179e625') ]]]

Esto imprimirá primero el contenido del bloque de la plantilla padre, y luego añadiremos nuestras cosas. Por eso ponemos el CSS en un bloque `stylesheets` y el JavaScript en un bloque `javascripts`.

¡Pruébalo ahora! ¡Refresca! Y... ¡funciona! A continuación, vamos a crear nuestra ruta de la API y a conectar todo esto.
