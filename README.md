# A-Lab-02

Colección de laboratorios y utilidades para practicar administración de sistemas
Linux, scripting con Bash y creación de entornos Docker reproducibles. El
repositorio no es una aplicación única: reúne experimentos independientes para
estudiar herramientas de consola, imágenes base y operación de contenedores.

## Qué problema resuelve

Centraliza ejemplos prácticos para:

- consultar y formatear información de usuarios, grupos, disco y servicios;
- inspeccionar contenedores, imágenes, volúmenes, redes y estadísticas de Docker;
- levantar varias distribuciones Linux en contenedores aislados pero conectados a
  una red común;
- comparar gestores de paquetes y configuraciones de imágenes basadas en Alpine,
  Debian, Ubuntu, CentOS, Red Hat UBI, Fedora y Amazon Linux.

## Tecnologías y herramientas

- **Bash** y utilidades GNU/Unix (`awk`, `sed`, `grep`, `sort`, `du`, `df`,
  `free`, `id`, `column`).
- **Docker Engine**, **Docker Compose** y **Dockerfiles**.
- **Make** para comandos operativos del laboratorio Docker.
- Gestores de paquetes de las imágenes: `apk`, `apt-get`, `yum` y `dnf`.
- Herramientas instaladas en las imágenes: `neofetch`, `figlet` y `mc`.
- **VS Code** mediante una configuración opcional de iconos y reglas de
  columnas.

No hay `package.json`, `requirements.txt`, código de aplicación ni base de
datos. `status.sh` puede consultar Apache, PHP, MySQL, Node.js y npm del equipo
anfitrión, pero esos componentes no están instalados ni gestionados por este
repositorio.

## Arquitectura

El proyecto se divide en dos áreas sin una capa de aplicación compartida:

1. **Utilidades Bash en el anfitrión**: scripts ejecutables que leen información
   del sistema local o del daemon de Docker y presentan resultados en consola.
2. **Laboratorio Docker**: cada Dockerfile parte de una distribución Linux,
   instala herramientas de consola, copia un entrypoint y deja el contenedor
   activo mediante `sleep infinity`. `docker-compose.yaml` construye los ocho
   servicios y los conecta a la red `os-linux-hub`.

Los Dockerfiles usan `ARG IMAGE_NAME`, por lo que la imagen base puede
reemplazarse al construir desde línea de comandos. Las imágenes y los
contenedores se identifican con nombres y etiquetas definidos en Compose.

## Funcionalidades principales

### Scripting Linux

- `ls-users.sh`: menú interactivo para listar usuarios y grupos a partir de
  `/etc/passwd` y `/etc/group`, ordenados por UID/GID o nombre, incluyendo los
  grupos asociados a cada usuario.
- `disk-space.sh`: muestra los diez directorios que más espacio ocupan en el
  directorio actual.
- `status.sh`: presenta el estado de Apache y MySQL y las versiones de PHP,
  Node.js y npm disponibles en el anfitrión.
- `ls-colors.sh`: demuestra códigos ANSI de color para Bash.
- `ls-docker.sh`: resume uptime, disco, memoria, estadísticas, contenedores,
  imágenes, IPs, reinicios, volúmenes y redes Docker.
- `ls-volumes.sh`: lista volúmenes Docker, proyecto, contenedores asociados,
  punto de montaje y tamaño aproximado en MB.
- `ssh timeout.txt`: fragmento de configuración SSH con
  `ServerAliveInterval 60`; no es un script ejecutable.

### Entornos Docker

Compose define estos servicios:

| Servicio | Imagen base |
| --- | --- |
| `os-1a-alpine` | Alpine 3.17 |
| `os-2a-debian-10-buster` | Debian Buster slim |
| `os-2b-debian-11-bullseye` | Debian Bullseye |
| `os-2c-ubuntu-23-04` | Ubuntu Lunar 23.04 |
| `os-3a-centos-7-9-2009` | CentOS 7 |
| `os-4a-redhat-ubi8` | Red Hat UBI 8.7 |
| `os-4b-fedora-39` | Fedora 39 |
| `os-5c-amazon-linux-2023` | Amazon Linux 2023 |

Los entrypoints muestran información de la distribución (`neofetch` y, según la
imagen, `figlet`) y mantienen el proceso en ejecución para permitir acceso
interactivo.

## Requisitos

- Linux, macOS o Windows con un entorno compatible con Docker.
- Docker Engine y Docker Compose v2 (`docker compose`).
- Bash para ejecutar los scripts.
- `sudo` para `ls-volumes.sh`.
- Un daemon Docker accesible para los scripts que inspeccionan Docker.

Las imágenes base se descargan desde registros externos durante la construcción.
Algunas versiones del laboratorio son antiguas; si una etiqueta deja de estar
disponible, será necesario actualizar el `ARG IMAGE_NAME` del Dockerfile
correspondiente.

## Instalación y ejecución

```bash
git clone <URL-del-repositorio>
cd A-Lab-02
```

### Levantar el laboratorio Docker

```bash
cd docker-labs
docker compose up -d --build
docker compose ps
```

Para abrir una shell en un contenedor:

```bash
docker exec -it 2b-debian-11-bullseye bash
docker exec -it 1a-alpine-3.17 sh
```

Alpine se accede con `sh`; las demás imágenes están configuradas con
`/bin/bash` como comando predeterminado, siempre que esa shell exista en la
imagen.

Para detener y eliminar los contenedores creados por Compose:

```bash
docker compose down
```

El comando anterior no elimina las imágenes. Desde `docker-labs/`, también se
pueden consultar recursos con `make show_me_all`. `make delete_all` elimina de
forma forzada los contenedores e imágenes con los nombres definidos por el
proyecto; úsalo únicamente si se desea esa limpieza.

### Ejecutar las utilidades Bash

Desde la raíz del repositorio:

```bash
bash bash-scripting/disk-space.sh
bash bash-scripting/ls-colors.sh
bash bash-scripting/ls-users.sh
bash bash-scripting/ls-docker.sh
sudo bash bash-scripting/ls-volumes.sh
bash bash-scripting/status.sh
```

`ls-users.sh` solicita una opción por teclado. `ls-docker.sh` y
`ls-volumes.sh` requieren que Docker esté instalado y accesible. `status.sh`
invoca `service apache2`, `php`, `service mysql`, `node` y `npm`; si alguno no
está instalado o configurado como servicio, su salida reflejará esa situación.

El fragmento SSH puede incorporarse manualmente a la configuración global
`/etc/ssh/ssh_config`, revisando antes el impacto de aplicar
`ServerAliveInterval 60` a todas las conexiones del equipo.

## Estructura relevante

```text
.
├── bash-scripting/
│   ├── disk-space.sh
│   ├── ls-colors.sh
│   ├── ls-docker.sh
│   ├── ls-users.sh
│   ├── ls-volumes.sh
│   ├── status.sh
│   └── ssh timeout.txt
├── docker-labs/
│   ├── Dockerfile.*
│   ├── docker-compose.yaml
│   ├── entrypoint.*.sh
│   └── Makefile
└── .vscode/settings.json
```

## Decisiones técnicas relevantes

- Se mantienen Dockerfiles separados para hacer visible la diferencia entre
  distribuciones y sus gestores de paquetes.
- Los `entrypoint` son deliberadamente simples y mantienen los contenedores
  vivos para facilitar prácticas con `docker exec`.
- Compose concentra la construcción y asigna una red común, en lugar de
  configurar cada contenedor manualmente.
- Los scripts formatean la salida con colores ANSI y tablas para facilitar la
  inspección desde una terminal.
- No se incorporan dependencias de una aplicación ni automatización de
  despliegue: el objetivo es didáctico y operativo.

## Conocimientos que demuestra

Este proyecto evidencia experiencia práctica en Bash, parsing de archivos
separados por `:`, pipelines Unix, permisos y servicios Linux, diagnóstico de
recursos, Dockerfiles, imágenes base, entrypoints, redes Compose, volúmenes,
Make y operación de contenedores desde CLI.

## Posibles mejoras futuras

- Añadir validaciones de dependencias y mensajes de error consistentes antes de
  ejecutar cada script.
- Incorporar `set -euo pipefail`, quoting más estricto y pruebas automatizadas
  para los scripts Bash.
- Sustituir etiquetas de imágenes obsoletas por versiones soportadas y fijar
  versiones de forma verificable.
- Reducir capas y limpiar cachés de paquetes en todos los Dockerfiles.
- Unificar la configuración repetida de colores y aliases en helpers reutilizables.
- Añadir documentación de contribución y una integración continua que valide
  sintaxis Bash y la configuración de Compose.

## Estado del proyecto

Repositorio de prácticas y experimentación. No se encontraron tests
automatizados ni un pipeline de CI configurado; la validación actual se realiza
ejecutando los scripts y los comandos Docker descritos arriba.
