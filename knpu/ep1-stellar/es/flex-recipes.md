# Symfony Flex y Aliases

Es hora de desmitificar algo increíble: los rayos tractores. Bueno, en realidad, aún no los hemos descubierto... así que vamos a desmitificar otra cosa, algo que ya está ocurriendo entre bastidores. Primero confirma todo, con un bonito mensaje:

***TIP
¡Espera! Ejecuta primero `git init` antes de `git add .`: Symfony ya no crea un repositorio Git automáticamente por ti :)
***

```terminal-silent
git init
git add .
git commit -m "making so much good progress"
```

## Instalar el comprobador de seguridad

Vamos a instalar una nueva función llamada Verificador de Seguridad de Symfony. Esta es una gran herramienta.... pero... revelación completa: lo estamos instalando principalmente para mostrar el sistema de recetas. Ooooo. Ejecutar:

```terminal
git status
```

Vale, no hay cambios. Ahora ejecuta:

```terminal
composer require sec-checker
```

***TIP
Este paquete sólo se utilizará durante el desarrollo. Por lo tanto, sería mejor ejecutar `composer require sec-checker --dev`.
***

## Hola Symfony Flex

Una vez más, ¡ `sec-checker` no debería ser un nombre de paquete válido! ¿Qué está pasando? Muévete y abre `composer.json`:

[[[ code('5cb39f8b41') ]]]

Nuestro proyecto comenzó con unas pocas dependencias. Una de ellas era `symfony/flex`: esto es súper importante. Flex es un plugin de Composer con dos superpoderes.

## Alias de Flex

El primer superpoder es el sistema de alias. Busca tu navegador y ve a [symfony.sh][symfony_sh].

Este es el servidor de "recetas" de Symfony: hablaremos de lo que significa a continuación. Busca "seguridad". Ah, aquí hay un paquete llamado `sensiolabs/security-checker`. Y más abajo, tiene alias: `sec-check`, `sec-checker`, `security-check` y más.

Gracias a Flex, podemos decir `composer require sec-checker`, o cualquiera de estos alias, y lo traducirá al nombre real del paquete. Sí, es sólo un sistema de atajos. Pero el resultado es realmente genial. ¿Necesitas un registrador? `composer require logger`. ¿Necesitas enviar correos electrónicos? `composer require mailer`. ¿Necesitas un rayo tractor? `composer require`. Espera, no, no podemos ayudarte con eso.

De vuelta a `composer.json`, ¡sí! Composer añadió en realidad `sensiolabs/security-checker`:

[[[ code('883ddc1e9c') ]]]

Ese es el primer superpoder de Flex.

## Recetas de Flex

El segundo superpoder es aún mejor: las recetas. Mmmm. Vuelve a tu terminal y... ¡sí! Se instaló y, mira esto "Operaciones Symfony: 1 receta". Luego, "Configuración de `sensiolabs/security-checker`".

¿Qué significa eso? Que se ejecute:

```terminal
git status
```

¡Woh! Esperábamos que se actualizaran `composer.json` y `composer.lock`. Pero también hay cambios en el archivo `symfony.lock` y, de repente, ¡tenemos un archivo de configuración totalmente nuevo!

En primer lugar, `symfony.lock`: este archivo lo gestiona Flex. Lleva la cuenta de las recetas que se han instalado. Básicamente... confírmalo en git, pero no te preocupes por él.

El segundo archivo es `config/packages/dev/security_checker.yaml`:

[[[ code('3a567d7003') ]]]

Este fue añadido por la receta y, ¡genial! Añade un nuevo comando `bin/console` a nuestra aplicación! No te preocupes por el código en sí: ¡pronto entenderás y escribirás código como éste!

La cuestión es la siguiente: gracias a este archivo, ahora podemos ejecutar:

```terminal
php bin/console security:check
```

¡Genial! ¡Este es el sistema de recetas en acción! Siempre que instales un paquete, Flex ejecutará la receta de ese paquete, si es que existe. Las recetas pueden añadir archivos de configuración, crear directorios o incluso modificar archivos como `.gitignore` para que la biblioteca funcione al instante sin ninguna configuración adicional. Me encanta Flex.

Por cierto, el propósito del comprobador de seguridad es que compruebe si hay alguna vulnerabilidad conocida para los paquetes utilizados en nuestro proyecto. En este momento, ¡estamos bien!

Pero la receta ha hecho otro cambio. Ejecutar:

```terminal
git diff composer.json
```

Por supuesto, `composer require` añadió el paquete. ¡Pero la receta añadió un nuevo script!

[[[ code('b4ef3d7af5') ]]]

Gracias a eso, cada vez que ejecutamos

```terminal
composer install
```

cuando termina, ejecuta el comprobador de seguridad automáticamente. ¡Así que genial!

Ah, y no lo mostraré ahora, pero Flex es incluso lo suficientemente inteligente como para desinstalar las recetas cuando eliminas un paquete. Eso hace que probar nuevos paquetes sea rápido y fácil.

## El repositorio de recetas

Así que te estarás preguntando... ¿dónde viven estas recetas? ¡Buena pregunta! Viven... en la nube. Es decir, viven en GitHub. En [symfony.sh][symfony_sh], haz clic en "Receta" junto al comprobador de seguridad. Ah, nos lleva al repositorio `symfony/recipes`. Aquí puedes ver qué archivos se añadirán y algunos otros cambios descritos en`manifest.json`.

Todas las recetas viven en este repositorio, o en otro llamado `symfony/recipes-contrib`. No hay ninguna diferencia importante entre ambos repositorios: pero las recetas oficiales se vigilan más de cerca en cuanto a su calidad.

A continuación Pongamos en marcha el sistema de recetas instalando Twig para poder crear plantillas adecuadas.

[symfony_sh]: https://symfony.sh
