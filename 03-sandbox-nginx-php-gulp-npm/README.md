<img src="docker-nginx-php-gulp.jpeg" alt="Contenedores Docker con Nginx, PHP y Grunt" />

# Docker sandbox: Nginx + PHP-FPM + Node/Gulp (npm)

Entorno de desarrollo local, reproducible y aislado para proyectos web PHP. Jugando con la la arquitectura **Servidor Web + Lenguaje Backend + Task Runner Frontend**. El stack está compuesto por tres contenedores Docker que trabajan sobre una red privada y comparten el código del sitio:

## Arquitectura

```text
Navegador
    │
    │ HTTPS :443 (HTTP :80 redirige a HTTPS)
    ▼
┌─────────────────────┐      
│ 01-nginx            │ 
│ Nginx + SSL         │                           
│ /var/www/test       │                           
└──────────┬──────────┘                           
           │ FastCGI :9000
		   │
┌──────────▼──────────┐
│ 02-php              │
│ PHP 8.2 + PHP-FPM   │
│ /var/www/test       │
└──────────▲──────────┘
           │ archivos generados en www/
           │ 
┌──────────┴──────────┐
│ 09-gulp             │
│ Node.js 20 + Gulp   │
│ sources/ → www/     │
└─────────────────────┘
```

### Contenedores

- **`01-nginx`**: basado en `nginx:1.25.2-bookworm`. Termina TLS, sirve
  archivos estáticos, aplica compresión Gzip y actúa como proxy FastCGI para
  PHP.
- **`02-php`**: basado en `php:8.2.9-fpm-bookworm`. Ejecuta los scripts PHP
  mediante PHP-FPM y tiene habilitado OPcache.
- **`09-gulp`**: basado en `node:20.5.1-bookworm`. Instala Gulp y sus plugins,
  procesa los archivos fuente y mantiene un watcher activo para regenerar los
  resultados al detectar cambios.

**Nota sobre `09-gulp`:** Este contenedor utiliza **NPM (Node Package Manager)**, 
el gestor de paquetes de Node.js, para instalar las dependencias necesarias para 
utilizar **Gulp** como task runner.
NPM no instala **Node.js** ; Node.js ya viene incluido en la imagen base `node:20.5.1-bookworm`.
Mediante **NPM** se instala **Gulp CLI** de forma global y **Gulp junto con sus plugins** 
como dependencias de desarrollo del proyecto dentro de `/workdir/node_modules`.
Posteriormente se creará otro contenedor equivalente utilizando **pnpm** como gestor de paquetes, 
con el objetivo de comparar ambas alternativas.



Los tres servicios se conectan a la red bridge `00-net-devel-01`. Nginx y
PHP-FPM montan el mismo directorio `www/`, mientras que Gulp monta `sources/`
como entrada y `www/` como salida.

## Pipeline de assets

Los archivos editables deben mantenerse en `sources/`. Al iniciar el
contenedor de Gulp se ejecuta la tarea predeterminada, que procesa inicialmente
los archivos y luego observa cambios:

| Entrada | Transformación | Salida |
| --- | --- | --- |
| `sources/CSS/*.css` | Concatenación, minificación y source map | `www/_main.min.css` |
| `sources/JS/*.js` | Concatenación, minificación con Uglify y source map | `www/_main.min.js` |
| `sources/HTML/*.html` | Eliminación de comentarios y espacios, minificación de CSS/JS embebidos | `www/*.html` |
| `sources/PHP-functions/*.php` | Limpieza de comentarios/espacios y concatenación | `www/_functions.php` |
| `sources/PHP-classes/*.php` | Limpieza de comentarios/espacios, conservando un archivo por clase | `www/classes/*.php` |

Los archivos PHP de `sources/PHP/` contienen el código PHP de la aplicación y
no pasan por una tarea de minificación de Gulp. El código disponible en
`www/` es el que ejecutan Nginx y PHP-FPM. Los source maps de CSS y JavaScript
se conservan para facilitar la depuración.

## Requisitos

- Docker Engine
- Docker Compose v2 (`docker compose`)
- GNU Make (opcional, para usar los comandos del `Makefile`)
- Una entrada local en `/etc/hosts`:

  ```text
  127.0.0.1 test.local
  ```

El proyecto incluye certificados autofirmados para `test.local`. El navegador
mostrará una advertencia de confianza al acceder por HTTPS; esto es esperado
en un entorno local.

## Puesta en marcha

Desde este directorio:

```bash
docker compose up -d --build
```

Después de que los contenedores estén listos, abrir:

- <https://test.local>

El puerto HTTP `80` redirige a HTTPS y el puerto `443` publica el sitio.
Para observar la generación de archivos o revisar el estado:

```bash
docker compose ps
docker compose logs -f 09-gulp
docker compose logs -f 01-nginx
docker compose logs -f 02-php
```

## Comandos habituales

El `Makefile` ofrece atajos para las operaciones más frecuentes:

```bash
make bake       # Construye y levanta los servicios en segundo plano
make gulp       # Ejecuta gulp dentro del contenedor 09-gulp
make rerun      # Detiene y vuelve a iniciar los contenedores
make rebuild    # Elimina imágenes y reconstruye el entorno
make help       # Muestra los comandos disponibles
```

Para abrir una shell en un contenedor:

```bash
docker exec -it 01-nginx bash
docker exec -it 02-php bash
docker exec -it 09-gulp bash
```


Para detener el entorno:

```bash
docker compose down
```

## Estructura principal

```text
.
├── docker-compose.yaml           # Servicios, red, puertos y volúmenes
├── Dockerfile.01-nginx           # Imagen del servidor web
├── Dockerfile.02-php             # Imagen de PHP-FPM
├── Dockerfile.09-gulp            # Imagen de Node.js y Gulp
├── gulp/gulpfile.js              # Tareas de transformación
├── sources/                      # Código fuente editable
├── www/                          # Document root y artefactos generados
├── vol-x-01-nginx-conf.d/        # Configuración de hosts virtuales Nginx
├── resources/                    # Configuración adicional y certificados
├── entrypoint.*.sh               # Inicialización de cada contenedor
└── Makefile                      # Atajos de operación
```

## Flujo de trabajo recomendado

1. Editar CSS, JavaScript, HTML o PHP dentro de `sources/`.
2. Mantener los contenedores levantados para que Gulp regenere `www/`
   automáticamente.
3. Verificar los cambios en <https://test.local>.
4. No editar manualmente los artefactos que genera Gulp (`_main.min.*`,
   `_functions.php`, los HTML procesados y `www/classes/`); volverán a
   generarse desde `sources/`.


## Nota sobre la red Docker
 Los tres contenedores (`01-nginx`, `02-php` y `09-node-grunt`) están conectados a la misma red Docker, `00-net-devel-01`. Esto permite que los contenedores se comuniquen entre sí dentro de la red privada `100.0.0.0/16`.


## Notas de seguridad

Este repositorio está pensado solo para **desarrollo local**. Antes de utilizarlo en
producción:

- Sustituir el certificado autofirmado por certificados gestionados
  correctamente.
- Revisar los secretos y archivos `.htpasswd` antes de publicarlos.
- Ajustar los niveles de log y la configuración de PHP/Nginx.
- No exponer directamente los puertos ni la red de desarrollo sin una capa de
  seguridad adicional.



## Autor :
**Pedro Javier Acosta**
- GitHub: 	[github.com/peteracosta](https://github.com/peteracosta)
- LinkedIn: [linkedin.com/in/acosta-peter](https://linkedin.com/in/acosta-peter)

