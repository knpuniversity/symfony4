# La receta Twig

¿Recuerdas la única regla para un controlador? ¡Debe devolver un objeto Symfony Response! Pero a Symfony no le importa cómo lo hagas: puedes renderizar una plantilla, hacer peticiones a la API o hacer consultas a la base de datos y construir una respuesta JSON.

***TIP
Técnicamente, un controlador puede devolver cualquier cosa. Con el tiempo, aprenderás cómo y por qué hacer esto.
***

En realidad, la mayor parte del aprendizaje de Symfony consiste en aprender a instalar y utilizar un montón de herramientas potentes, pero opcionales, que facilitan este trabajo. Si tu aplicación necesita devolver HTML, una de estas grandes herramientas se llama Twig.

## Instalar Twig

En primer lugar, asegúrate de confirmar todos tus cambios hasta el momento:

```terminal-silent
git status
```

Yo ya lo he hecho. Las recetas son mucho más divertidas cuando puedes ver lo que hacen! Ahora ejecuta:

```terminal
composer require twig
```

Por cierto, en futuros tutoriales, nuestra aplicación será una mezcla de una aplicación HTML tradicional y una API con un front-end JavaScript. Así que si quieres saber cómo construir una API en Symfony, ¡llegaremos hasta allí!

Esto instala TwigBundle, algunas otras librerías y... ¡configura una receta! ¿Qué hace esa receta? Vamos a averiguarlo:

```terminal
git status
```

¡Woh! ¡Muchas cosas buenas! El primer cambio es `config/bundles.php`:

[[[ code('b65bd10885') ]]]

Los bundles son el sistema de "plugins" de Symfony. Y cada vez que instalamos un bundle de terceros, Flex lo añade aquí para que se utilice automáticamente. ¡Gracias Flex!

La receta también ha creado algunas cosas, ¡como un directorio `templates/`! Sí, no hace falta adivinar dónde van las plantillas: ¡es bastante obvio! Incluso añadió un archivo de diseño base que usaremos pronto.

Twig también necesita alguna configuración, así que la receta la añadió en `config/packages/twig.yaml`:

[[[ code('f7deb4c365') ]]]

Pero aunque este archivo fue añadido por Flex, es tuyo para modificarlo: puedes hacer los cambios que quieras.

Ah, ¡y esto me encanta! ¿Por qué nuestras plantillas tienen que vivir en un directorio `templates/`? ¿Está codificado en lo más profundo de Symfony? No ¡Está aquí mismo!

[[[ code('22bf999239') ]]]

No te preocupes todavía por este porcentaje de sintaxis - lo aprenderás en un próximo episodio. Pero, probablemente puedas adivinar lo que ocurre: `%kernel.project_dir%` es una variable que apunta a la raíz del proyecto.

De todos modos, ¡mirar lo que hizo una receta es una gran manera de aprender! Pero la lección principal de Flex es ésta: instala una biblioteca y ella se encarga del resto.

Ahora, ¡vamos a utilizar Twig!
