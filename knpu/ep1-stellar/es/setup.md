# Symfony 4: ¡Lanzamiento!

¡Hola chicos! ¡Sí! ¡Es la hora de Symfony 4! Estoy muy emocionado. ¿Por qué? Porque nada me hace más feliz que sentarme a trabajar dentro de un framework en el que codificar es realmente divertido, y en el que puedo construir características rápidamente, pero sin sacrificar la calidad. Bueno, tal vez sería aún más feliz haciendo todo eso en una playa... con, tal vez, una bebida fría?

En cualquier caso, Symfony 4 ha reimaginado por completo la experiencia del desarrollador: vas a crear mejores funciones, más rápido que nunca. Además, Symfony tiene un nuevo y único superpoder: comienza como un microframework, y luego escala automáticamente en tamaño a medida que tu proyecto crece. ¿Cómo? Permanece atento...

Ah, ¿y he mencionado que Symfony 4 es la versión más rápida de la historia? ¿Y el framework PHP más rápido? Sinceramente, todos los frameworks son lo suficientemente rápidos de todos modos, pero la cuestión es ésta: estás construyendo sobre una base realmente impresionante.

***TIP
Consulta http://www.phpbenchmarks.com para ver las estadísticas de los benchmarks de terceros
***

## Preparación: Descarga y actualiza Composer

Bien, ¡empecemos ya! Abre un nuevo terminal y entra en el directorio que quieras. Asegúrate de que ya tienes [Composer](https://getcomposer.org/) instalado globalmente para que puedas decir simplemente `composer`. Si tienes alguna duda, ¡pregúntanos en los comentarios!

Y también asegúrate de que tienes la última versión:

```terminal-silent
composer self-update
```

Esto es importante: Composer ha tenido una reciente corrección de errores para ayudar a Symfony.

## ¡Instala Symfony!

Para descargar tu nuevo proyecto Symfony, ejecuta `composer create-project symfony/skeleton`y ponlo en un nuevo directorio llamado `the_spacebar`.

```terminal-silent
composer create-project symfony/skeleton the_spacebar '4.4.*'
```

¡Ese es el nombre de nuestro proyecto! "La Barra Espacial" será el lugar para que la gente de toda la galaxia se comunique, comparta noticias y discuta sobre los famosos y el BitCoin. ¡Va a ser increíble!

Este comando clona el proyecto `symfony/skeleton` y luego ejecuta `composer install`para descargar sus dependencias.

Más abajo, hay algo especial: algo sobre "recetas". OooOOO. Las recetas son un concepto nuevo y muy importante. Hablaremos de ellas en unos minutos.

## Iniciar el servidor web

Y al final, ¡genial! Symfony nos da instrucciones claras sobre qué hacer a continuación. Desplazarnos al nuevo directorio:

```terminal-silent
cd the_spacebar
```

Aparentemente, podemos ejecutar nuestra aplicación inmediatamente ejecutando:

```terminal
php -S 127.0.0.1:8000 -t public
```

Esto inicia el servidor web PHP incorporado, que es genial para el desarrollo. `public/`
es la raíz del documento del proyecto - ¡pero pronto hablaremos de ello!

***TIP
Si quieres usar Nginx o Apache para el desarrollo local, ¡puedes hacerlo! Consulta http://bit.ly/symfony-web-servers.
***

¡Es hora de despegar! Pasa a tu navegador y ve a `http://localhost:8000`. ¡Saluda a tu nueva aplicación Symfony!

## Nuestro Pequeño Proyecto

***TIP
Symfony ya no crea un repositorio Git automáticamente para ti. Pero, ¡no hay problema! Sólo tienes que escribir `git init` una vez para inicializar tu repositorio.
***

De vuelta al terminal, crearé una nueva pestaña de terminal. Symfony ya ha inicializado un nuevo repositorio git por nosotros y nos ha dado un archivo `.gitignore` perfecto. ¡Gracias Symfony!

***TIP
Si estás usando PhpStorm, querrás ignorar el directorio `.idea` de git. Yo ya lo tengo ignorado en mi archivo global .gitignore: https://help.github.com/articles/ignoring-files/
***

Eso significa que podemos crear nuestro primer commit simplemente diciendo

```terminal
git init
git add .
git commit
```

Crea un mensaje de commit tranquilo y bien pensado.

```terminal-silent
# Woohoo! OMG WE ARE USING SYMFONY4
```

¡Woh! Mira esto: todo el proyecto -incluyendo las cosas de Composer y `.gitignore`- ¡sólo tiene 16 archivos! ¡Nuestra aplicación es pequeñita!

Ahora vamos a aprender más sobre nuestro proyecto y a configurar nuestro editor para que el desarrollo de Symfony sea increíble
