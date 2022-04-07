# Depuración y paquetes

Symfony tiene aún más herramientas de depuración. La forma más fácil de obtenerlas todas es buscar tu terminal y ejecutar:

```terminal
composer require debug --dev
```

Encuentra tu navegador, navega hasta [symfony.sh](https://symfony.sh) y busca "debug". Ah, así que el alias `debug` instalará en realidad un paquete llamado `symfony/debug-pack`. Entonces... ¿qué es un paquete?

Haz clic para ver los detalles del paquete, y luego ve a su repositorio de GitHub.

¡Vaya! Es un solo archivo: ¡ `composer.json`! Dentro, ¡requiere otras seis bibliotecas!

A veces, vas a querer instalar varios paquetes a la vez relacionados con una función. Para facilitarlo, Symfony tiene una serie de "paquetes", y su objetivo es ofrecerte un paquete sencillo que en realidad instala varias otras bibliotecas.

En este caso, `composer require debug` instalará Monolog -una biblioteca de registro-,`phpunit-bridge` -para pruebas- e incluso el `profiler-pack` que ya hemos instalado antes.

Si vuelves al terminal... ¡sí! Se han descargado todas esas librerías y se han configurado algunas recetas.

Y... ¡mira esto! ¡Refrescar! Oye, ¡nuestro Twig `dump()` se ha vuelto más bonito! El `debug-pack`lo integró todo aún mejor.

# ¡Desembalar el paquete!

Vuelve a tu plantilla Twig y elimina ese volcado. Luego, abre `composer.json`. Acabamos de instalar dos paquetes: el `debug-pack` y el `profiler-pack`:

[[[ code('72dcebf651') ]]]

Y ahora sabemos que el `debug-pack` es en realidad una colección de unas 6 bibliotecas.

Pero, los packs tienen una desventaja... un "lado oscuro". ¿Qué pasaría si quisieras controlar la versión de una sola de estas bibliotecas? O qué pasaría si quisieras la mayoría de estas bibliotecas, pero no quisieras, por ejemplo, la `phpunit-bridge`. Bueno... ahora mismo, no hay forma de hacerlo: todo lo que tenemos es esta línea `debug-pack`.

¡No te preocupes valiente viajero del espacio! Simplemente... ¡desembala el paquete! Sí, en tu terminal, ejecuta

```terminal
composer unpack debug
```

El comando `unpack` viene de Symfony flex. Y... ¡interesante! Todo lo que dice es "eliminar symfony/debug-pack". Pero si miras tu `composer.json`:

[[[ code('5f748553f1') ]]]

Ah! Sí que ha eliminado `symfony/debug-pack`, pero lo ha sustituido por las 6 librerías de ese pack! Ahora podemos controlar las versiones o incluso eliminar bibliotecas individuales si no las queremos.

¡Ese es el poder de los paquetes!