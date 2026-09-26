#!/bin/bash

# ---------------------------------------------------------------------
# entrypoint.04-postgresql.sh
#
# PostgreSQL 18.6
# Debian 13 (Trixie)
#
# Este script es el ENTRYPOINT personalizado del contenedor.
# ---------------------------------------------------------------------

set -e

echo
echo "=============================================================="
echo "  04 - PostgreSQL"
echo "  Image : ${THIS_IMAGE}"
echo "  Tag   : ${THIS_IMAGE_TAG}"
echo "  TZ    : ${TZ}"
echo "=============================================================="
echo

# ---------------------------------------------------------------------
# Información del sistema
# ---------------------------------------------------------------------

echo "### Sistema:"
cat /etc/debian_version
echo

echo "### PostgreSQL:"
postgres --version
echo

# ---------------------------------------------------------------------
# Ejecutar el ENTRYPOINT oficial de PostgreSQL
# ---------------------------------------------------------------------
#
# La imagen oficial de PostgreSQL ya trae su propio entrypoint:
#
# /usr/local/bin/docker-entrypoint.sh
#
# No debemos reemplazar su funcionamiento.
# Nuestro script simplemente hace las personalizaciones anteriores
# y luego le entrega el control al entrypoint oficial.
# ---------------------------------------------------------------------

exec /usr/local/bin/docker-entrypoint.sh "$@"
