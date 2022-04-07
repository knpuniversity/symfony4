# Barra de herramientas de depuración web y el perfilador

Asegúrate de que has confirmado todos tus cambios - yo ya lo he hecho. ¡Porque estamos a punto de instalar algo súper divertido! ¡Como la diversión de flotar en el espacio! Ejecuta:

```terminal
composer require profiler --dev
```

El perfilador - también llamado "barra de herramientas de depuración web" es probablemente la cosa más impresionante de Symfony. Esto instala unos cuantos paquetes y... ¡una receta! Ejecuta:

```terminal
git status
```

¡Bien, genial! Ha añadido un par de archivos de configuración e incluso algunas rutas en el entorno `dev`sólo que ayudan al funcionamiento del perfilador. Entonces... ¿qué diablos es el perfilador? Vuelve a tu navegador, asegúrate de que estás en la página de presentación del artículo y actualiza! Voilà!

## ¡Hola barra de herramientas de depuración web!

¿Ves esa barra negra tan elegante en la parte inferior de la página? Esa es la barra de herramientas de depuración web! Ahora se inyecta automáticamente en la parte inferior de cualquier página HTML válida durante el desarrollo. Sí, este código JavaScript hace una llamada AJAX que la carga.

Ah, y está repleta de información, como qué ruta se ha encontrado, qué controlador se ha ejecutado, el tiempo de ejecución, los detalles de la caché e incluso información sobre las plantillas.

Y a medida que instalemos más bibliotecas, ¡tendremos aún más iconos! Pero lo realmente impresionante es que puedes hacer clic en cualquiera de estos iconos para entrar en... el perfilador.

## Hola perfilador: El poderoso compañero de la barra de herramientas

OoooOoo. Esto nos lleva a una página totalmente diferente. El perfilador es como la barra de herramientas de depuración web con un reactor de fusión pegado a ella. La pestaña Twig muestra exactamente qué plantillas se han renderizado. También podemos obtener información detallada sobre el almacenamiento en caché, el enrutamiento y los eventos, de los que hablaremos en un futuro tutorial. Ah, y mi favorito personal: ¡Rendimiento! Esto te muestra el tiempo que ha tardado cada parte de la petición, incluyendo el controlador. En otro tutorial, utilizaremos esto para profundizar en cómo funciona exactamente Symfony bajo el capó.

Cuando estés listo para volver a la página original, puedes hacer clic en el enlace de la parte superior.

## Magia con la función dump()

Pero espera, ¡hay más! El perfilador también ha instalado el componente `var-dumper` de Symfony. Busca `ArticleController` y ve a `showAction()`. Normalmente, para depurar, utilizaré`var_dump()` para imprimir algunos datos. Pero, ¡ya no! En su lugar, utiliza `dump()`: vuelca el`$slug` y también el propio objeto controlador:

[[[ code('b964f4a635') ]]]

Bien, ¡recupera! Bonita salida de colores. Además, puedes expandir los objetos para profundizar en ellos.

***TIP
Para expandir todos los nodos anidados sólo tienes que pulsar `Ctrl` y hacer clic en la flecha.
***

## Uso de dump() en Twig

La función `dump()` es aún más útil en Twig. Dentro del bloque `body`, añade`{{ dump() }}`:

[[[ code('670987dfd6') ]]]

***TIP
Si no tienes instalado Xdebug, esto podría fallar con un problema de memoria. Pero no te preocupes En el próximo capítulo, instalaremos una herramienta para que esto sea aún mejor.
***

En Twig, puedes utilizar `dump()` sin argumentos. Y eso es especialmente útil. ¿Por qué? Porque vuelca una matriz asociativa de todas las variables a las que tienes acceso. Ya sabíamos que teníamos las variables `title` y `comments`. Pero, al parecer, ¡también tenemos una variable `app`! En realidad, todas las plantillas obtienen esta variable `app` automáticamente. ¡Es bueno saberlo!

¡Pero! ¡Symfony tiene aún más herramientas de depuración! ¡Vamos a por ellas y a aprender sobre los "paquetes" a continuación!
