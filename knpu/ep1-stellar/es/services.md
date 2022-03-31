# Servicios

Es hora de hablar de la parte más fundamental de Symfony: ¡los servicios!

Sinceramente, Symfony no es más que un montón de objetos útiles que funcionan juntos. Por ejemplo, hay un objeto router que hace coincidir rutas y genera URLs. Hay un objeto Twig que renderiza plantillas. Y hay un objeto Logger que Symfony ya utiliza internamente para almacenar cosas en un archivo `var/log/dev.log`.

En realidad, todo en Symfony -quiero decir todo- lo hace uno de estos objetos útiles. Y estos objetos útiles tienen un nombre especial: servicios.

## ¿Qué es un servicio?

Pero no te emociones demasiado con esa palabra: servicio. Es una palabra especial para una idea realmente sencilla: un servicio es cualquier objeto que hace un trabajo, como generar URLs, enviar correos electrónicos o guardar cosas en una base de datos.

Symfony viene con un gran número de servicios, y quiero que pienses en los servicios como tus herramientas.

Por ejemplo, si te diera el servicio logger, u objeto, podrías utilizarlo para registrar mensajes. Si te diera el servicio mailer, ¡podrías enviar correos electrónicos! ¡Herramientas!

Toda la segunda mitad de Symfony consiste en aprender dónde encontrar estos servicios y cómo utilizarlos. Cada vez que aprendes sobre un nuevo servicio, obtienes una nueva herramienta, ¡y te vuelves un poco más peligroso!

## Uso del servicio de registro

Vamos a comprobar el sistema de registro. Busca tu terminal y ejecuta:

```terminal
tail -f var/log/dev.log
```

Borrar la pantalla. Ahora, actualiza la página, y vuelve a moverte. ¡Impresionante! Esto demuestra que Symfony tiene algún tipo de sistema de registro. Y como todo lo hace un servicio, debe haber un objeto logger. Así que esta es la pregunta: ¿cómo podemos obtener el servicio logger para poder registrar nuestros propios mensajes?

Aquí está la respuesta: dentro del controlador, en el método, añade un argumento adicional. Dale una pista de tipo `LoggerInterface` - pulsa el tabulador para autocompletarlo y llámalo como quieras, qué tal `$logger`:

[[[ code('3f726459ce') ]]]

Recuerda: cuando autocompletas, PhpStorm añade la declaración `use` en la parte superior por ti.

Ahora, podemos utilizar uno de sus métodos: `$logger->info('Article is being hearted')`:

[[[ code('b922641119') ]]]

Antes de hablar de esto, ¡probemos! Busca en tu navegador y haz clic en el corazón, que golpea la ruta AJAX. Vuelve al terminal. ¡Sí! Ahí está en la parte inferior. Pulsa `Ctrl`+`C` para salir `tail`.

## Servicio de autoconexión

Vale, ¡genial! Pero... ¿cómo diablos ha funcionado eso? Esto es lo que pasa: antes de que Symfony ejecute nuestro controlador, mira cada argumento. Para argumentos simples como `$slug`, nos pasa el valor comodín del router:

[[[ code('4afae713e5') ]]]

Pero para `$logger`, mira el tipo-indicación y se da cuenta de que queremos que Symfony nos pase el objeto logger. Ah, y el orden de los argumentos no importa.

Esta es una idea muy poderosa llamada autoconexión: si necesitas un objeto de servicio, ¡sólo tienes que saber el tipo de pista correcto que debes utilizar! Así que... ¿cómo diablos he sabido utilizar `LoggerInterface`? Bueno, por supuesto, si miras la documentación oficial de Symfony sobre el registrador, te lo dirá. Pero, hay una forma más fresca.

Ve a tu terminal y ejecuta:

```terminal
./bin/console debug:autowiring
```

¡Boom! Esta es una lista completa de todas las sugerencias de tipo que puedes utilizar para obtener un servicio. Fíjate en que la mayoría de ellos dicen que son un alias de algo. No te preocupes demasiado por eso: al igual que las rutas, cada servicio tiene un nombre interno que puedes utilizar para referenciarlo. Aprenderemos más sobre eso más adelante. Ah, y cada vez que instales un nuevo paquete, tendrás más y más servicios en esta lista. ¡Más herramientas!

## Usando Twig directamente

¡Y mira esto! Si quieres obtener el servicio Twig, puedes utilizar cualquiera de estas dos sugerencias de tipo.

¿Y recuerdas que dije que todo en Symfony lo hace un servicio? Pues bien, cuando llamamos a `$this->render()` en un controlador, eso es sólo un atajo para obtener el servicio Twig y llamar a un método en él:

[[[ code('ac75dbaf9b') ]]]

De hecho, imaginemos que el atajo `$this->render()` no existe. ¿Cómo podríamos representar una plantilla? No hay problema: sólo necesitamos el servicio Twig. Añade un segundo argumento con una pista de tipo `Environment`, porque ese es el nombre de la clase que vimos en `debug:autowiring`. Llama al arg `$twigEnvironment`:

[[[ code('bd092ca645') ]]]

A continuación, cambia la declaración `return` por `$html = $twigEnvironment->render()`:

[[[ code('e9dd2a8868') ]]]

El método que queremos llamar en el objeto Twig es casualmente el mismo que el acceso directo del controlador.

Luego, al final, devuelve `new Response()` y pasa `$html`:

[[[ code('ba7bb2196c') ]]]

Vale, esto es mucho más trabajo que antes... y no lo haría en un proyecto real. Pero, quería demostrar un punto: cuando usas el método de acceso directo `$this->render()` en el controlador, todo lo que hace realmente es llamar a `render()` en el servicio Twig y luego envolverlo dentro de un objeto `Response` para ti.

¡Pruébalo! Vuelve y actualiza la página. ¡Funciona exactamente igual que antes! Por supuesto, utilizaremos métodos abreviados, porque nos hacen la vida mucho más increíble. Volveré a cambiar mi código para que se vea como antes. Pero la cuestión es ésta: todo lo hace un servicio. Si aprendes a dominar los servicios, podrás hacer cualquier cosa desde cualquier lugar en Symfony.

Hay mucho más que decir sobre el tema de los servicios, y sobre muchas otras partes de Symfony: la configuración, Doctrine y la base de datos, los formularios, la seguridad y las API, por nombrar sólo algunas. ¡La Barra Espacial está lejos de ser la fuente de información galáctica que sabemos que será!

Pero, ¡felicidades! Acabas de dedicar una hora a conseguir una base impresionante en Symfony. No te arrepentirás de tu duro trabajo: estás en camino de construir grandes cosas y, como siempre, de convertirte en un desarrollador cada vez mejor.

Muy bien chicos, ¡hasta la próxima!
