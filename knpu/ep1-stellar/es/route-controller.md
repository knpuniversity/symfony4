# Rutas, controladores y páginas, ¡oh, Dios mío!

¡Vamos a crear nuestra primera página! En realidad, éste es el principal trabajo de un framework: ofrecerte un sistema de rutas y controladores. Una ruta es la configuración que define la URL de una página y un controlador es una función que escribimos y que realmente construye el contenido de esa página.

Y ahora mismo... ¡nuestra aplicación es muy pequeña! En lugar de sobrecargar tu proyecto con todas las funciones posibles que puedas necesitar -después de todo, aún no estamos en gravedad cero-, una aplicación Symfony es básicamente un pequeño sistema de rutas y controladores. Más adelante, instalaremos más funciones cuando las necesitemos, ¡como una unidad warp! Eso siempre es útil. En realidad, añadir más funciones va a ser bastante asombroso. Más adelante hablaremos de ello.

## Primera ruta y controlador

Abre el archivo de ruta principal de tu aplicación: `config/routes.yaml`:

[[[ code('ca7e7b48e4') ]]]

¡Ya tenemos un ejemplo! Descomenta eso. Ignora la clave `index` por ahora: es el nombre interno de la ruta, pero no es importante todavía.

Esto dice que cuando alguien va a la página de inicio - `/` - Symfony debe ejecutar un método `index()` en una clase `DefaultController`. Cambia esto por `ArticleController`y el método por `homepage`:

[[[ code('f5c6ae0ed2') ]]]

Y... ¡sí! ¡Eso es una ruta! ¡Hola ruta! Define la URL y le dice a Symfony qué función del controlador debe ejecutar.

La clase del controlador aún no existe, así que vamos a crearla Haz clic con el botón derecho del ratón en el directorio`Controller` y ve a "Nuevo" o pulsa `Cmd`+`N` en un Mac. Elige "Clase PHP". ¡Y, sí! ¿Recuerdas la configuración de Composer que hicimos en Preferencias? Gracias a eso, ¡PhpStorm adivina correctamente el espacio de nombres! La fuerza es fuerte con esto... El espacio de nombres para cada clase en `src/` debe ser `App` más el subdirectorio en el que se encuentre.

Nombra esto `ArticleController`:

[[[ code('b80d9887fc') ]]]

Y dentro, añade `public function homepage()`:

[[[ code('87255115fa') ]]]

Esta función es el controlador... y es nuestro lugar para construir la página. Para mayor confusión, también se llama "acción", o "ghob" para sus amigos klingon.

En cualquier caso, aquí podemos hacer lo que queramos: hacer consultas a la base de datos, llamadas a la API, tomar muestras del suelo en busca de materiales orgánicos o representar una plantilla. Sólo hay una regla: un controlador debe devolver un objeto Symfony `Response`.

Así que digamos: `return new Response()`: queremos el de `HttpFoundation`. Dale un mensaje de calma: `OMG! My first page already! WOOO!`:

[[[ code('9b6091d1b8') ]]]

Ejem. Ah, y mira esto: cuando dejé que PhpStorm autocompletara la clase `Response` añadió esta declaración `use` al principio del archivo automáticamente:

[[[ code('8c7baf2d54') ]]]

Verás que lo hago a menudo. ¡Buen trabajo Storm!

¡Vamos a probar la página! Encuentra tu navegador. Oh, esta página de "Bienvenida" sólo se muestra si no tienes ninguna ruta configurada. ¡Refresca! ¡Sí! Esta es nuestra página. Nuestra primera de muchas.

## Rutas de anotación

Eso fue bastante fácil, ¡pero puede ser más fácil! En lugar de crear nuestras rutas en YAML, vamos a utilizar una función genial llamada anotaciones. Se trata de una función adicional, así que tenemos que instalarla. Busca tu terminal abierto y ejecútalo:

```terminal
composer require annotations
```

Interesante... este paquete `annotations` en realidad instaló `sensio/framework-extra-bundle`. Vamos a hablar de cómo funciona muy pronto.

Ahora, sobre estas rutas de anotación. Comenta la ruta YAML:

[[[ code('aa861906ab') ]]]

Luego, en `ArticleController`, encima del método del controlador, añade `/**`, pulsa enter, borra esto y pon `@Route()`. Puedes utilizar cualquiera de las dos clases, pero asegúrate de que PhpStorm añade la declaración `use` en la parte superior. Luego añade `"/"`:

[[[ code('e9f8d1dc4a') ]]]

SUGERENCIA Cuando autocompletes la anotación `@Route`, asegúrate de elegir la de `Symfony\Component\Routing` - la que elegimos ahora está obsoleta. Ambas funcionan igual

Ya está La ruta está definida justo encima del controlador, por eso me encantan las rutas de anotación: todo está en un solo lugar. Pero no te fíes de mí, busca tu navegador y actualiza. ¡Es un traaaaap! Es decir, ¡funciona!

CONSEJO ¿Qué son exactamente las anotaciones? Son comentarios de PHP que se leen como configuración

## Rutas con comodines

¿Qué más podemos hacer con las rutas? Crea otra función pública llamada `show()`. Quiero que esta página acabe mostrando un artículo completo. Dale una ruta:`@Route("/news/why-asteroids-taste-like-bacon")`:

[[[ code('cae709ac9c') ]]]

Al final, así es como queremos que se vean nuestras URLs. Esto se llama "slug", es una versión de la URL del título. Como siempre, devuelve un`new Response('Future page to show one space article!')`:

[[[ code('7ba5de7f42') ]]]

¡Perfecto! Copia esa URL y pruébala en tu navegador. Funciona... ¡pero esto es una mierda! No quiero construir una ruta y un controlador para cada artículo que vive en la base de datos. No, necesitamos una ruta que pueda coincidir con `/news/` cualquier cosa. ¿Cómo? Usando `{slug}`:

[[[ code('d572f8cdbc') ]]]

Esta ruta ahora coincide con `/news/` cualquier cosa: que `{slug}` es un comodín. Ah, y el nombre `slug` puede ser cualquier cosa. Pero lo que elijas ahora estará disponible como argumento de tu "ghob", es decir, de tu acción.

Así que vamos a refactorizar nuestro mensaje de éxito para que diga

> Página futura para mostrar el artículo

Y luego esa babosa:

[[[ code('f97fda18bb') ]]]

¡Inténtalo! Actualiza la misma URL. ¡Sí! Coincide con la ruta y el slug se imprime! Cámbialo por otra cosa: `/why-asteroids-taste-like-tacos`. Tan delicioso! Vuelve al bacon... porque... ya sabes... todo el mundo sabe que a eso es a lo que realmente saben los asteroides.

Y... ¡sí! Llevamos 3 capítulos y ya conoces la primera mitad de Symfony: el sistema de rutas y controladores. Claro que puedes hacer cosas más sofisticadas con las rutas, como hacer coincidir expresiones regulares, métodos HTTP o nombres de host, pero todo eso te resultará bastante fácil ahora.

Es hora de pasar a algo realmente importante: es hora de aprender sobre Symfony Flex y el sistema de recetas. ¡Qué bien!
