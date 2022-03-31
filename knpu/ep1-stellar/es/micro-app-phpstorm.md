# Nuestra Micro-App y la configuración de PhpStorm

Nuestra misión: ir con audacia donde nadie ha ido antes... ¡consultando nuestra app! Ya he abierto el nuevo directorio en PhpStorm, así que enciende tu tricorder y ¡exploremos!

## El directorio public/

Sólo hay tres directorios en los que debes pensar. En primer lugar, `public/` es la raíz del documento: por lo que contendrá todos los archivos de acceso público. Y... ¡sólo hay uno en este momento! `index.php`. Este es el "controlador frontal": una palabra elegante que inventaron los programadores y que significa que es el archivo que se ejecuta cuando vas a cualquier URL.

Pero, en realidad, casi nunca tendrás que preocuparte por ello. De hecho, ahora que hemos hablado de este directorio, ¡deja de pensar en él!

## src/ y config/

Sí, ¡he mentido! Realmente sólo hay dos directorios en los que debes pensar:`config/` y `src/`. `config/` contiene... um... ya sabes... archivos de configuración y `src/`es donde pondrás todo tu código PHP. Es así de sencillo.

¿Dónde está Symfony? Como siempre, cuando creamos el proyecto, Composer leyó nuestro archivo `composer.json`y descargó todas las librerías de terceros -incluyendo partes de Symfony- en el directorio `vendor/`.

## Instalar el servidor

Vuelve a tu terminal y busca la pestaña original. Fíjate en esto: en la parte inferior, dice que podemos obtener un servidor web mejor ejecutando `composer require server`. ¡Me gustan las cosas mejores! ¡Así que vamos a probarlo! Pulsa `Ctrl`+`C` para detener el servidor existente, y luego ejecútalo:

```terminal
composer require server
```

Si estás familiarizado con Composer... ¡el nombre del paquete debe parecer gracioso! Realmente, ¡está mal! Normalmente, todo nombre de paquete es "algo" barra "algo", como`symfony/console`. Así que... ¡ `server` no debería funcionar! ¡Pero lo hace! Esto forma parte de un nuevo y genial sistema llamado Flex. Pronto hablaremos de ello

Cuando esto termine, ya puedes ejecutar:

```terminal
php ./bin/console server:run
```

Esto hace básicamente lo mismo que antes... pero el comando es más corto. Y cuando refrescamos, ¡sigue funcionando!

Por cierto, este comando `bin/console` va a ser nuestro nuevo compañero robot. Pero no es magia: nuestro proyecto tiene un directorio `bin/` con un archivo `console` dentro. Los usuarios de Windows deberían decir `php bin/console`... porque es sólo un archivo PHP.

Entonces, ¿qué cosas sorprendentes puede hacer este robot `bin/console`? Busca tu pestaña de terminal abierta y ejecútala:

```terminal
php ./bin/console
```

¡Sí! Esta es una lista de todos los comandos de `bin/console`. Algunos de ellos son oro para la depuración. ¡Hablaremos de ellos a lo largo del camino!

## Configuración de PhpStorm

Bien, ¡ya casi estamos listos para empezar a codificar! Pero tenemos que hablar de nuestra nave espacial, quiero decir, ¡del editor! Mira, puedes usar lo que quieras... pero... ¡recomiendo encarecidamente PhpStorm! En serio, ¡hace que desarrollar en Symfony sea un sueño! Y no, esos buenos chicos y chicas de PhpStorm no me están pagando por decir esto... ¡pero pueden hacerlo si quieren!

Ejem, si lo usas... lo cual sería genial para ti... hay 2 secretos que necesitas saber para engañar a tu nave espacial, ah, ¡editor! Está claro que he estado demasiado tiempo en hiper-sueño.

Ve a Preferencias, Plugins, y luego haz clic en "Examinar Repositorios". Hay 3 plugins imprescindibles. Busca "Symfony". Primero: el "Plugin Symfony". Tiene más de 2 millones de descargas por una razón: te dará toneladas de autocompletado ridículo. También deberías descargar "PHP Annotations" y "PHP Toolbox". Yo ya los tengo instalados. Si no los tienes, verás un botón de "Instalar" en la parte superior de la descripción. Instálalos y reinicia PHPStorm.

Luego, vuelve a Preferencias, busca "symfony" y encuentra la nueva sección "Symfony". Haz clic en la casilla "Habilitar Plugin": tienes que habilitar el plugin de Symfony para cada proyecto. Dice que tienes que reiniciar... pero creo que es mentira. ¿Qué podría salir mal?

Así que ese es el truco nº 1 de PhpStorm. Para el segundo, busca "Composer" y haz clic en la sección "Composer". Haz clic para buscar la "Ruta a composer.json" y selecciona la de nuestro proyecto. No estoy seguro de por qué esto no es automático... ¡pero da igual! Gracias a esto, PhpStorm facilitará la creación de clases en `src/`. Lo verás muy pronto.

¡Muy bien! Nuestro proyecto está configurado y ya funciona. Empecemos a construir algunas páginas y a descubrir más cosas chulas de la nueva aplicación.
