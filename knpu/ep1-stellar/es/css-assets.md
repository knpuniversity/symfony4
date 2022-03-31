# Activos: CSS y JavaScript

Incluso los astronautas -que generalmente se pasan el tiempo mirando al negro abismo- exigen un sitio menos feo que éste ¡Vamos a arreglarlo!

Si descargas el código del curso desde la página en la que estás viendo este vídeo ahora mismo, dentro del archivo zip, encontrarás un directorio `start/`. Y dentro de él, verás el mismo directorio `tutorial/` que tengo aquí. Y dentro de él... he creado un nuevo `base.html.twig`. Cópialo y sobrescribe nuestra versión en `templates/`:

[[[ code('2633464924') ]]]

A nivel técnico, es básicamente lo mismo que antes: tiene los mismos bloques:`title` `stylesheets` , `body` y `javascripts` en la parte inferior. Pero ahora, tenemos un bonito diseño HTML que está estilizado con Bootstrap 

Si refrescas, debería verse mejor. ¡Woh! ¡No hay cambios! ¡Qué raro! En realidad... esto es más raro de lo que crees. Busca tu terminal y elimina el directorio `var/cache/dev`:

```terminal-silent
rm -rf var/cache/dev/*
```

¿Qué diablos es esto? Internamente, Symfony almacena cosas en este directorio. Y... normalmente no necesitas pensar en esto en absoluto: Symfony es lo suficientemente inteligente durante el desarrollo como para reconstruir automáticamente esta caché siempre que sea necesario. Entonces... ¿por qué la borro manualmente? Bueno... porque hemos copiado mi archivo... y como su fecha de "última modificación" es anterior a la de nuestro `base.html.twig` original, Twig se confunde y piensa que la plantilla no se ha actualizado. En serio, esto no es algo que deba preocupar en ninguna otra situación.

## Referenciando archivos CSS

Y cuando actualizamos... ¡ahí está! Vale, sigue siendo bastante feo. ¡Eso es porque nos faltan algunos archivos CSS!

En el directorio `tutorial/`, también he preparado algunos `css/`, `fonts/` y `images/`. Todos estos archivos necesitan ser accedidos por el navegador del usuario, y eso significa que deben vivir dentro de `public/`. Abre ese directorio y pégalos allí.

Por cierto, Symfony tiene una herramienta impresionante llamada [Webpack Encore][webpack_encore] que ayuda a procesar, combinar, minificar y, en general, hacer cosas increíbles con tus archivos CSS y JS. Vamos a hablar de Webpack Encore... pero en otro tutorial. Por ahora, vamos a configurar las cosas con archivos normales y estáticos.

Los dos archivos CSS que queremos incluir son `font-awesome.css` y `styles.css`. ¡Y no necesitamos hacer nada complejo ni especial! En `base.html.twig`, busca el bloque`stylesheets` y añade una etiqueta `link`.

Pero espera, ¿por qué añadimos exactamente la etiqueta `link` dentro del bloque `stylesheets`? ¿Es importante? Bueno, técnicamente... no importa: una etiqueta `link` puede vivir en cualquier lugar de `head`. Pero más adelante, puede que queramos añadir archivos CSS adicionales en páginas específicas. Al poner las etiquetas `link` dentro de este bloque, tendremos más flexibilidad para hacerlo. No te preocupes: pronto veremos un ejemplo de esto con un archivo JavaScript.

Entonces... ¿qué ruta debemos utilizar? Como `public/` es la raíz del documento, debería ser simplemente `/css/font-awesome.css`:

[[[ code('10454bcffb') ]]]

Haz lo mismo con el otro archivo: `/css/styles.css`:

[[[ code('021f90b1af') ]]]

¡Es así de sencillo! ¡Refresca! Todavía no es perfecto, pero es mucho mejor

## La función de activos no tan místicos

Y ahora voy a complicar ligeramente las cosas. Vuelve a las Preferencias de PhpStorm, busca "Symfony" y encuentra el plugin "Symfony". Cambia el directorio "web" por `public` - se llamaba `web` en Symfony 3.

Esto no es necesario, pero nos dará más autocompletado cuando trabajemos con activos. Elimina la ruta "font-awesome", vuelve a escribirla y pulsa el tabulador para autocompletar:

[[[ code('cc39fcfd80') ]]]

¡Woh! ¡Envolvió la ruta en una función Twig `asset()`! Haz lo mismo para `styles.css`:

[[[ code('bcac8db177') ]]]

Esto es lo que pasa: siempre que enlaces a un activo estático -CSS, JS o imágenes- debes envolver la ruta en esta función `asset()`. Pero... no es realmente tan importante. De hecho, ahora mismo, no hace nada: imprimirá la misma ruta que antes. Pero, en el futuro, la función `asset()` nos dará más flexibilidad para versionar nuestros activos o almacenarlos en un CDN.

En otras palabras: ¡no te preocupes demasiado por ella, pero recuerda usarla!

## Instalación del componente de activos

En realidad, la función `asset()` hace algo inmediatamente: ¡rompe nuestro sitio! ¡Refrescar! ¡Ah!

La función `asset()` proviene de una parte de Symfony que aún no tenemos instalada. Arréglalo ejecutando

```terminal
composer require asset
```

Esto instala el componente `symfony/asset`. Y en cuanto Composer termine... podemos refrescar, ¡y funciona! Para comprobar que la función `asset()` no está haciendo nada mágico, puedes mirar la etiqueta `link` en el código fuente HTML: es la misma aburrida `/css/styles.css`.

Hay otro punto en el que necesitamos utilizar `asset()`. En el diseño, busca`img`. Ah, ¡una etiqueta `img`! Elimina el `src` y vuelve a escribir `astronaut-profile`:

[[[ code('64f7697c89') ]]]

¡Perfecto! Refresca y disfruta de nuestro nuevo avatar en el menú de usuario. Hay muchos datos codificados, pero lo haremos dinámico con el tiempo.

## Estilizar la página del artículo

¡El diseño se ve muy bien! Pero el interior de la página... sí... sigue siendo bastante terrible. De vuelta al directorio `tutorial/`, también hay un archivo `article.html.twig`. No copies este archivo entero, sólo copia su contenido. Ciérralo y abre`show.html.twig`. Pega el nuevo código en la parte superior del bloque `body`:

[[[ code('15f947f1bf') ]]]

Compruébalo en tu navegador. ¡Sí! Parece genial... pero toda esta información está codificada. Es decir, el nombre del artículo es sólo texto estático.

Tomemos el código dinámico que tenemos en la parte inferior y trabajémoslo en el nuevo HTML. Para el título, utiliza `{{ title }}`:

[[[ code('f468e82073') ]]]

Abajo, imprime el número de comentarios. Sustitúyelo por `{{ comments|length }}`:

[[[ code('143f61892e') ]]]

Ah, y en la parte inferior, hay una caja de comentarios y un comentario real. Busquemos esto y... ¡añadamos un bucle! Para `comment in comments` en la parte superior, y `endfor` en la parte inferior. Para el comentario real, utiliza `{{ comment }}`:

[[[ code('39391247f8') ]]]

Borra el código antiguo de la parte inferior... oh, pero no borres el `endblock`:

[[[ code('322192c580') ]]]

Vamos a probarlo - ¡recupera! ¡Se ve muy bien! Todavía hay un montón de cosas codificadas, pero esto es mucho mejor.

Es hora de hacer que nuestra página web sea menos fea y de aprender sobre el segundo trabajo del enrutamiento: la generación de rutas para enlazar.

[webpack_encore]: https://github.com/symfony/webpack-encore
