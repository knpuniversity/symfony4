# Punto final de la API JSON

Cuando hacemos clic en el icono del corazón, necesitamos enviar una petición AJAX al servidor que, eventualmente, actualizará algo en una base de datos para mostrar que el nos ha gustado este artículo. Esa ruta de la API también necesita devolver el nuevo número de corazones para mostrar en la página... ya sabes... por si a otras 10 personas les ha gustado desde que abrimos la página.

En `ArticleController`, haz un nuevo `public function toggleArticleHeart()`:

[[[ code('773717b419') ]]]

Luego añade la ruta anterior: `@Route("/news/{slug}")` - para que coincida con la URL del programa - y luego`/heart`. Dale inmediatamente un nombre: `article_toggle_heart`:

[[[ code('66831ee765') ]]]

He incluido el comodín `{slug}` en la ruta para que sepamos qué artículo es el que nos gusta. También podríamos utilizar el comodín `{id}` cuando tengamos una base de datos.

Añade el argumento correspondiente `$slug`. Pero como aún no tenemos una base de datos, añadiré un TODO: "¡realmente gustar/descartar el artículo!":

[[[ code('24472bf8ea') ]]]

## Devolver JSON

Queremos que esta ruta de la API devuelva JSON... y recuerda: la única regla para un controlador Symfony es que debe devolver un objeto Symfony Response. Así que podríamos decir literalmente `return new Response(json_encode(['hearts' => 5]))`.

¡Pero eso es demasiado trabajo! En su lugar, di `return new JsonResponse(['hearts' => rand(5, 100)]`:

[[[ code('a7ecdbda6f') ]]]

***TIP ¡O usa el atajo del controlador!

```php
return $this->json(['hearts' => rand(5, 100)]);
```

Ten en cuenta que desde PHP 7.0, en lugar de `rand()`, puedes utilizar `random_int()`, que genera enteros pseudoaleatorios criptográficamente seguros. Es preferible utilizarlo a menos que tengas problemas de rendimiento, pero con sólo varias llamadas no se nota. ***

No hay nada especial aquí: `JsonResponse` es una subclase de `Response`. Llama a`json_encode()` por ti, y también establece la cabecera `Content-Type` en `application/json`, lo que ayuda a tu JavaScript a entender las cosas.

Probemos primero esto en el navegador. Vuelve y añade `/heart` a la URL. ¡Sí! ¡Nuestro primer punto final de la API!

SUGERENCIA ¡Mi JSON tiene un bonito aspecto gracias a la extensión [JSONView][json_view] para Chrome!

## Hacer que la ruta sea sólo POST

En algún momento, esta ruta modificará algo en el servidor: le "gustará" el artículo. Así que, como mejor práctica, no deberíamos poder hacerle una petición GET. Hagamos que esta ruta sólo coincida cuando se haga una petición POST. ¿Cómo? Añadiendo otra opción a la ruta: `methods={"POST"}`:

[[[ code('fafe7bd425') ]]]

En cuanto lo hagamos, ya no podremos hacer una petición GET en el navegador: ¡ya no coincide con la ruta! Corre:

```terminal
./bin/console debug:router
```

Y verás que la nueva ruta sólo responde a peticiones POST. Bastante bien. Por cierto, Symfony tiene muchas más herramientas para crear rutas de la API: esto es sólo el principio. ¡En futuros tutoriales, iremos más allá!

## Conectando el JavaScript y la API

¡Nuestro punto final de la API está listo! Copia el nombre de la ruta y vuelve a `article_show.js`. Pero espera... si queremos hacer una petición AJAX a la nueva ruta... ¿cómo podemos generar la URL? Este es un archivo JS puro... ¡así que no podemos usar la función Twig `path()`!

En realidad, hay un paquete muy bueno llamado [FOSJsRoutingBundle][fos_js_routing_bundle] que sí permite generar rutas en JavaScript. Pero, voy a mostrarte otra forma sencilla.

De vuelta a la plantilla, busca la sección del corazón. Vamos a... ¡rellenar el `href` del enlace! Añade `path()`, pega el nombre de la ruta, y pasa el comodín `slug` a una variable `slug`:

[[[ code('52801fef03') ]]]

En realidad... todavía no hay una variable `slug` en esta plantilla. Si te fijas en `ArticleController`, sólo estamos pasando dos variables. Añade una tercera: `slug`
establecer en `$slug`:

[[[ code('e350bcc68b') ]]]

Eso debería al menos establecer la URL del enlace. Vuelve a la página del programa en tu navegador y actualiza. ¡Sí! El enlace del corazón está conectado.

¿Por qué hemos hecho esto? Porque ahora podemos obtener esa URL muy fácilmente en JavaScript. Añade `$.ajax({})` y pasa `method: 'POST'` y `url` a `$link.attr('href')`:

[[[ code('c0268387f2') ]]]

¡Eso es todo! Al final, añade `.done()` con una devolución de llamada que tenga un argumento `data`:

[[[ code('7a1700529c') ]]]

El `data` será lo que devuelva nuestra ruta de la API. Eso significa que podemos mover la línea HTML de recuento de artículos a esto, y establecerla en `data.hearts`:

[[[ code('aba3f5c2bb') ]]]

Ah, y si no estás familiarizado con la función `.done()` o con las promesas, te recomiendo encarecidamente que eches un vistazo a nuestra [Pista de JavaScript][javascript_track]. No es material para principiantes: está pensado para llevar tu JS al siguiente nivel.

De todos modos... ¡probemos ya! ¡Refresca! Y... ¡clic! ¡Funciona!

Y... ¡Tengo una sorpresa! ¿Ves este pequeño icono en forma de flecha en la barra de herramientas de depuración de la web? Apareció en cuanto hicimos la primera petición AJAX. En realidad, cada vez que hacemos una petición AJAX, ¡se añade al principio de esta lista! Esto es genial porque -¿recuerdas el perfilador? - puedes hacer clic para ver el perfil de cualquier petición AJAX. Sí, ahora tienes todas las herramientas de rendimiento y depuración a tu alcance... incluso para las llamadas AJAX.

Ah, y si hubiera un error, lo verías en toda su hermosa y estilizada gloria en la pestaña Excepción. Poder cargar el perfilador para una llamada AJAX es una especie de huevo de pascua: no todo el mundo lo conoce. Pero debería hacerlo.

Creo que es hora de hablar de la parte más importante de Symfony: Fabien. Es decir, los servicios.

[json_view]: https://chrome.google.com/webstore/detail/jsonview/chklaanhfefbnpoihckbnefhakgolnmc?hl=en [fos_js_routing_bundle]: https://github.com/FriendsOfSymfony/FOSJsRoutingBundle [javascript_track]: https://knpuniversity.com/tracks/javascript#modern-javascript
