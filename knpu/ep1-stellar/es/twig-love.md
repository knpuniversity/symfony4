# Twig ❤️

¡De vuelta al trabajo! Abre `ArticleController`. En cuanto quieras renderizar una plantilla, tienes que extender una clase base: `AbstractController`:

[[[ code('21312d03bb') ]]]

Obviamente, tu controlador no necesita extender esto. Pero normalmente lo harán... ¡porque esta clase te da métodos de acceso directo! El que queremos es `return $this->render()`. Pásale un nombre de archivo de plantilla: qué tal `article/show.html.twig` para ser coherente con el nombre del controlador. El segundo argumento es una matriz de variables que quieres pasar a tu plantilla:

[[[ code('30d53c8e17') ]]]

Al final, vamos a cargar artículos de la base de datos. Pero... ¡espera! Todavía no estamos preparados. ¡Así que vamos a fingir hasta que lo consigamos! Pasa una variable`title` ajustada a una versión del título del slug:

[[[ code('920ec9980f') ]]]

¡Genial! ¡Vamos a añadir esa plantilla! Dentro de `templates/`, crea un directorio `article` y luego el archivo: `show.html.twig`.

Añade un `h1`, y luego imprime esa variable `title`: `{{ title }}`:

[[[ code('0b61ed35ed') ]]]

## Conceptos básicos de Twig

Si eres nuevo en Twig, ¡bienvenido! ¡Te va a encantar! Twig sólo tiene 2 sintaxis. La primera es `{{ }}`. Yo la llamo la etiqueta "decir algo", porque imprime. Y al igual que en PHP, puedes imprimir cualquier cosa: una variable, una cadena o una expresión compleja.

La segunda sintaxis es `{% %}`. Yo la llamo la etiqueta "hacer algo". Se utiliza siempre que necesitas hacer algo, en lugar de imprimir, como una declaración `if` o un bucle `for`. Veremos la lista completa de etiquetas "hacer algo" en un minuto.

Y... sí, ¡eso es todo! Bueno, vale, he mentido totalmente. Hay una tercera sintaxis:`{# #}`: ¡comentarios! 

Al final de esta página, pegaré algo de contenido extra codificado para animar las cosas

[[[ code('fded556337') ]]]

¡Vamos a probarlo! ¡Busca tu navegador y actualízalo! ¡Bum! ¡Tenemos contenido!

Pero fíjate: si ves la fuente de la página... es sólo este contenido: aún no tenemos ningún diseño ni estructura HTML. ¡Pero pronto lo tendremos!

## Bucle con for

Vuelve a tu controlador. Con el tiempo, los usuarios tendrán que poder comentar los artículos, para que puedan debatir respetuosamente las conclusiones del artículo basadas en un análisis e investigación objetivos. Ya sabes... no es diferente de cualquier otra sección de comentarios de noticias. Ejem.

Voy a pegar 3 comentarios falsos. Añade una segunda variable llamada `comments` para pasarlos a la plantilla:

[[[ code('f8434f96ff') ]]]

Esta vez, no podemos limitarnos a imprimir esa matriz: tenemos que hacer un bucle sobre ella. En la parte inferior, y un `h2` que diga "Comentarios" y luego añade un `ul`:

[[[ code('b81ee7fde9') ]]]

¡Para hacer el bucle, necesitamos nuestra primera etiqueta de hacer algo! ¡Woo! Utiliza `{% for comment in comments %}`. La mayoría de las etiquetas "hacer" algo también tienen una etiqueta de cierre: `{% endfor %}`:

[[[ code('25bd457831') ]]]

Dentro del bucle, `comment` representa el comentario individual. Así que, simplemente imprímelo:`{{ comment }}`:

[[[ code('75dd75142f') ]]]

¡Pruébalo! ¡Genial! Es realmente feo... uf. Pero lo arreglaremos más tarde.

## La increíble referencia de Twig

Ve a [twig.symfony.com][twig_docs] y haz clic en el enlace Documentación. Desplázate un poco hacia abajo hasta que veas un conjunto de columnas: la [Referencia Twig][twig_ref].

¡Esto es increíble! ¿Ves las etiquetas de la izquierda? Esa es toda la lista de posibles etiquetas de "hacer algo". Sí, siempre será `{%` y luego una de estas: `for`, `if`,`extends`, `tractorbeam`. Y sinceramente, sólo vas a utilizar unas 5 de ellas la mayoría de las veces.

Twig también tiene funciones... que funcionan como cualquier otro lenguaje - y una cosa genial llamada "tests". Son un poco singulares, pero no demasiado difíciles, y te permiten decir cosas como `if foo is defined` o... `if space is empty`.

La parte más útil de esta referencia es la sección de filtros. Los filtros son como las funciones, pero con una sintaxis diferente, mucho más moderna. Vamos a probar nuestro filtro `|length`.

Vuelve a nuestra plantilla. Quiero imprimir el número total de comentarios. Añade un conjunto de paréntesis y di `{{ comments|length }}`:

[[[ code('235089f531') ]]]

Esto es un filtro: el valor `comments` pasa de izquierda a derecha, como una tubería Unix. El filtro `length` cuenta lo que se le haya pasado, e imprimimos el resultado. ¡Incluso puedes utilizar varios filtros!

¡CONSEJO Para confundir innecesariamente a tus compañeros de equipo, prueba a utilizar los filtros `upper` y `lower` una y otra vez: `{{ name|upper|lower|upper|lower|upper }}`!

## Herencia de plantillas

Twig tiene una última función genial: su sistema de herencia de plantillas. Porque, ¡recuerda! Todavía no tenemos una página HTML real: sólo el contenido de la plantilla.

Para solucionar esto, en la parte superior de la plantilla, añade `{% extends 'base.html.twig' %}`:

[[[ code('2d2ff7b0f0') ]]]

Esto hace referencia al archivo `base.html.twig` que fue añadido por la receta:

[[[ code('e8a33ec214') ]]]

Ahora es sencillo, pero este es nuestro archivo de diseño y lo personalizaremos con el tiempo. Al ampliarlo, deberíamos obtener al menos esta estructura HTML básica.

Pero cuando actualizamos... ¡sorpresa! ¡Un error! ¡Y probablemente uno que verás en algún momento!

> Una plantilla que extiende otra no puede incluir contenido fuera de los bloques Twig

Eh. Vuelve a mirar la plantilla base: es básicamente un diseño HTML más un montón de bloques... la mayoría de los cuales están vacíos. Cuando extiendes una plantilla, le estás diciendo a Twig que quieres poner tu contenido dentro de esa plantilla. Los bloques, son los "agujeros" en los que nuestra plantilla hija puede poner contenido. Por ejemplo, hay un bloque llamado `body`, y ahí es donde queremos poner nuestro contenido:

[[[ code('4cb4a54ec9') ]]]

Para ello, tenemos que anular ese bloque. En la parte superior del contenido, añade`{% block body %}`, y en la parte inferior, `{% endblock %}`:

[[[ code('d73afdb44a') ]]]

Ahora nuestro contenido debería ir dentro de ese bloque en `base.html.twig`. Pruébalo! ¡Refuerza! ¡Sí! No parece diferente, pero tenemos un cuerpo HTML adecuado.

## Más sobre los bloques

Eres completamente libre de personalizar esta plantilla todo lo que quieras: cambiar el nombre de los bloques, añadir más bloques y, con suerte, ¡hacer que el sitio parezca menos feo!

Ah, y la mayoría de las veces, los bloques están vacíos. Pero puedes dar al bloque algún contenido por defecto, como con `title`:

[[[ code('c7206760e1') ]]]

Sí, el título de la pestaña del navegador es `Welcome`.

¡Anulemos eso! En la parte superior... o realmente, en cualquier lugar, añade `{% block title %}`. Luego di `Read `, imprime la variable `title`, y `{% endblock %}`:

[[[ code('e5912946a5') ]]]

¡Pruébalo! ¡Sí! El título de la página cambia. Y... ¡voilà! Eso es Twig. Te va a encantar.

SEEALSO Echa un vistazo a otro [screencast][twig_screencast] nuestro para saber más sobre Twig

A continuación, vamos a ver una de las características más asesinas de Symfony: el perfilador.

[twig_docs]: https://twig.symfony.com/ [twig_ref]: https://twig.symfony.com/doc/2.x/#reference [twig_screencast]: https://knpuniversity.com/screencast/twig
