#!/bin/bash

# Creamos o sobrescribimos el archivo /test.sh con los comandos y la variable evaluada
cat <<EOF > /test.sh
#!/bin/bash
figlet "${THIS_IMAGE:-Linux Container}"
neofetch
EOF

# Asignamos permisos de ejecución al archivo generado
chmod +x /test.sh

# Esta última línea ejecuta el comando recibido por CMD ("sleep infinity") como PID 1
exec "$@"