# Docker sandbox: Nginx + PHP-FPM + Node/Webpack

Entorno de desarrollo local para experimentar con una arquitectura web
separada en tres contenedores Docker: servidor web, runtime de backend y
herramientas de procesamiento frontend. El código fuente se mantiene en
`sources/` y los artefactos que publica el sitio se generan en `www/`.

## Arquitectura

```text
Navegador
    │
    │ http://test.local (80) → https://test.local (443)
    ▼
┌──────────────────────────┐
│ 01-nginx                 │
│ Nginx + SSL              │
│ /var/www/test            │
└────────────┬─────────────┘
             │ FastCGI :9000
             ▼
┌──────────────────────────┐
│ 02-php                   │
│ PHP 8.2.9 + PHP-FPM      │
│ /var/www/test            │
└──────────────────────────┘
             ▲
             │ archivos procesados en www/
┌────────────┴─────────────┐
│ 09-node-webpack          │
│ Node.js 20.5.1 + Webpack │
│ sources/ → www/          │
└──────────────────────────┘
```

- **`01-nginx`** usa `nginx:1.25.2-bookworm`, sirve archivos estáticos,
  termina TLS y reenvía las peticiones PHP a PHP-FPM mediante FastCGI.
- **`02-php`** usa `php:8.2.9-fpm-bookworm`, ejecuta el backend PHP y habilita
  OPcache.
- **`09-node-webpack`** usa `node:20.5.1-bookworm`. Instala Webpack y sus
  plugins, procesa los archivos fuente y mantiene un watcher activo.

Los servicios comparten la red Docker `00-net-devel-01`, configurada con la
subred `100.0.0.0/16`. Nginx y PHP montan `www/` como document root, mientras
que el contenedor de Node monta `sources/`, `webpack/` y `www/`.

## Pipeline con Webpack

El archivo [`webpack/webpack.config.js`](./webpack/webpack.config.js) busca los
archivos CSS y JavaScript en orden alfabético, compila en modo `production` y
activa `watch`. El entrypoint del contenedor ejecuta:

```bash
npx webpack --watch
```

El pipeline realiza estas transformaciones:

| Entrada | Proceso | Salida en `www/` |
| --- | --- | --- |
| `sources/JS/*.js` | Concatenación y optimización mediante Webpack | `_main.js` |
| `sources/CSS/*.css` | Extracción y minificación con `css-loader`, `mini-css-extract-plugin` y `css-minimizer-webpack-plugin` | `_main.css` |
| `sources/HTML/*.html` | Minificación de espacios, comentarios, CSS y JavaScript embebidos | `HTML/*.html` |
| `sources/PHP-classes/*.php` | Limpieza con `php_strip_whitespace` | `classes/*.php` |
| `sources/PHP-scripts/*.php` | Limpieza con `php_strip_whitespace` | `scripts/*.php` |
| `sources/PHP-functions/*.php` | Limpieza, eliminación de etiquetas externas y agrupación mediante plugin local | `_functions.php` |

El plugin [`php-functions-bundle.js`](./webpack/plugins/php-functions-bundle.js)
conserva el orden alfabético de los fragmentos y registra sus archivos como
dependencias para que Webpack los vuelva a procesar al modificarlos. Los
archivos de `www/` son artefactos generados; deben editarse los originales en
`sources/`.

> La configuración actual solo incorpora archivos que coincidan con
> `sources/JS/*.js` y `sources/CSS/*.css`. El archivo `sources/JS/__entrypoint.txt`
> sirve como referencia, pero no es una entrada que Webpack cargue.

## Requisitos

- Docker Engine.
- Docker Compose v2 (`docker compose`).
- GNU Make, opcional, para utilizar los atajos del `Makefile`.
- Una entrada local en `/etc/hosts`:

  ```text
  127.0.0.1 test.local www.test.local
  ```

El proyecto incluye un certificado autofirmado para `test.local`, por lo que el
navegador mostrará una advertencia de confianza al acceder por HTTPS.

## Puesta en marcha

Desde este directorio:

```bash
docker compose up -d --build
```

Después de iniciar los servicios, abrir <https://test.local>. El puerto `80`
redirige al puerto HTTPS `443`.

Para revisar el estado y los logs:

```bash
docker compose ps
docker compose logs -f 09-node-webpack
docker compose logs -f 01-nginx
docker compose logs -f 02-php
```

Para detener el entorno:

```bash
docker compose down
```

## Comandos habituales

El `Makefile` incluye atajos para el ciclo de vida del stack:

```bash
make bake       # Construye y levanta con Compose Bake
make rerun      # Detiene y vuelve a iniciar los contenedores
make rebuild    # Elimina imágenes y reconstruye el entorno
make help       # Muestra los comandos disponibles
```

También se puede abrir una shell directamente:

```bash
docker exec -it 01-nginx bash
docker exec -it 02-php bash
docker exec -it 09-node-webpack bash
```

## Estructura principal

```text
.
├── docker-compose.yaml              # Servicios, red, puertos y volúmenes
├── Dockerfile.01-nginx              # Imagen de Nginx
├── Dockerfile.02-php                # Imagen de PHP-FPM
├── Dockerfile.09-node-webpack       # Imagen de Node.js y Webpack
├── entrypoint.*.sh                  # Inicialización de cada contenedor
├── webpack/
│   ├── webpack.config.js            # Pipeline de transformación
│   ├── plugins/                     # Plugin local para agrupar funciones PHP
│   ├── package.json                 # Scripts y dependencias del proyecto
│   └── package-lock.json            # Versiones bloqueadas de NPM
├── sources/                         # Código fuente editable
├── www/                             # Document root y artefactos generados
├── vol-x-01-nginx-conf.d/           # Virtual host de Nginx
├── resources/                       # Certificados y configuraciones
├── vol-01-var-log-nginx/            # Logs de Nginx
├── vol-02-var-log-php/              # Logs de PHP
├── Makefile                         # Atajos para Docker Compose
└── go-container.sh                  # Menú para acceder a contenedores
```

## Flujo de trabajo recomendado

1. Editar HTML, CSS, JavaScript o PHP dentro de `sources/`.
2. Mantener levantado `09-node-webpack` para que Webpack observe los cambios.
3. Verificar el resultado en <https://test.local>.
4. No editar manualmente los archivos generados en `www/`, ya que se
   sobrescriben durante la siguiente compilación.

## Seguridad y alcance

Este sandbox está destinado al desarrollo local y al aprendizaje. Antes de
usarlo en producción se deben reemplazar los certificados autofirmados,
revisar secretos y permisos, ajustar la configuración de Nginx y PHP, y limitar
la exposición de puertos y de la red Docker.

## Autor

**Pedro Javier Acosta**

- GitHub: [github.com/peteracosta](https://github.com/peteracosta)
- LinkedIn: [linkedin.com/in/acosta-peter](https://linkedin.com/in/acosta-peter)