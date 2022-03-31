# Generación de URLs

La mayoría de estos enlaces no van a ninguna parte. ¡Lo que sea! ¡No hay problema! Los iremos rellenando a medida que vayamos avanzando. Además, la mayoría de nuestros usuarios estarán en hipersueño durante al menos unas cuantas décadas más.

Pero podemos conectar algunos de ellos -como el texto del logotipo de la "Barra Espacial"- que deberían ir a la página de inicio.

Abre `templates/base.html.twig` y busca "La Barra Espacial":

[[[ code('27f2d01739') ]]]

Vale - apuntemos este enlace a la página de inicio. Y sí, podríamos decir simplemente `href="/"`.

Pero... hay una forma mejor. En lugar de eso, vamos a generar una URL a la ruta. Sí, vamos a pedirle a Symfony que nos dé la URL de la ruta que está encima de nuestra acción de la página de inicio:

[[[ code('460d7431e9') ]]]

¿Por qué? Porque si alguna vez decidimos cambiar la URL de esta ruta -por ejemplo, a `/news` -, si generamos la URL en lugar de codificarla, todos los enlaces se actualizarán automáticamente. ¡Mágico!

## El famoso debug:router

¿Cómo podemos hacer esto? Primero, busca tu terminal y ejecútalo:

```terminal
./bin/console debug:router
```

Esta es una pequeña e impresionante herramienta que te muestra una lista de todas las rutas de tu aplicación. Puedes ver nuestras dos rutas y un montón de rutas que ayudan al perfilador y a la barra de herramientas de depuración web.

Hay una cosa sobre las rutas de la que todavía no hemos hablado: cada ruta tiene un nombre interno. Éste nunca se muestra al usuario, sólo existe para que podamos referirnos a esa ruta en nuestro código. Para las rutas de anotación, por defecto, ese nombre se crea por nosotros.

## Generación de URLs con path()

Es decir, para generar una URL a la página de inicio, copia el nombre de la ruta, vuelve a`base.html.twig`, añade `{{ path() }}` y pega el nombre de la ruta:

[[[ code('73c504620b') ]]]

¡Ya está!

¡Refresca! ¡Haz clic en él! ¡Sí! Hemos vuelto a la página de inicio.

Pero... en realidad no me gusta confiar en los nombres de ruta autocreados porque podrían cambiar si cambiamos el nombre de ciertas partes de nuestro código. En su lugar, en cuanto quiero generar una URL a una ruta, añado una opción de nombre: `name="app_homepage"`:

[[[ code('90101ad865') ]]]

Vuelve a ejecutar `debug:router`:

```terminal-silent
./bin/console debug:router
```

Lo único que ha cambiado es el nombre de la ruta. Ahora vuelve a `base.html.twig`y utiliza aquí el nuevo nombre de la ruta:

[[[ code('096760bd65') ]]]

Sigue funcionando exactamente igual que antes, pero tenemos el control total del nombre de la ruta.

## Hacer bonita la página de inicio

Ahora tenemos un enlace a nuestra página de inicio... pero no sé por qué querrías ir aquí: ¡es súper feo! Así que vamos a renderizar una plantilla. En `ArticleController`, en lugar de devolver un `Response`, devuelve `$this->render()` con `article/homepage.html.twig`:

[[[ code('7d7a22a74f') ]]]

Por ahora, no pases ninguna variable a la plantilla.

Esta plantilla aún no existe. Pero si vuelves a mirar en el directorio `tutorial/` de la descarga de código, he creado una plantilla de página de inicio para nosotros. ¡Genial! Cópiala y pégala en `templates/article`:

[[[ code('d5ce83eaa1') ]]]

No es nada especial: sólo un montón de información codificada y artículos espaciales fascinantes. Sin embargo, es una página de inicio muy atractiva. Y sí, haremos todo esto dinámico cuando tengamos una base de datos.

## Generar una URL con una {wildcard}

Uno de los artículos codificados es con el que hemos estado jugando: ¡Por qué los asteroides saben a tocino! El enlace aún no va a ninguna parte, así que vamos a arreglarlo generando una URL a nuestra página de presentación del artículo

Paso 1: ahora que queremos enlazar con esta ruta, dale un nombre: `article_show`:

[[[ code('9629fe3a0c') ]]]

Paso 2: dentro de `homepage.html.twig`, busca el artículo... y... para el `href`, utiliza `{{ path('article_show') }}`:

[[[ code('6b9d58a24a') ]]]

Eso debería funcionar... ¿no? ¡Refresca! ¡No! ¡Es un error enorme y horrible!

> Faltan algunos parámetros obligatorios - `{slug}` - para generar una URL para `article_show`.

¡Eso tiene mucho sentido! Esta ruta tiene un comodín... así que no podemos simplemente generar una URL hacia ella. No, tenemos que decirle también a Symfony qué valor debe utilizar para la parte`{slug}`.

¿Cómo? Añadiendo un segundo argumento a `path()`: `{}`. Esa es la sintaxis para un array asociativo cuando estás dentro de Twig - es similar a JavaScript. Dale a esto una clave `slug` establecida en `why-asteroids-taste-like-bacon`:

[[[ code('fd28f8b776') ]]]

Pruébalo - ¡actualiza! ¡El error ha desaparecido! Y mira esto: el enlace va a nuestra página de presentación.

A continuación, vamos a añadir algo de JavaScript y una ruta de la API para dar vida a este pequeño icono de corazón
