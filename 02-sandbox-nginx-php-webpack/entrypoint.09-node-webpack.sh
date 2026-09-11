#!/bin/bash
# el caracter '#' indica que es un comentario
# el caracter '!' indica que el archivo es un ejecutable. Análogo al .exe o .bat en Windows
# '/bin/bash': bash es un tipo de shell, hay otros con los que se puede trabajar en linux si no estas seguro
# de la version con la que trabajas puedes usar /bin/sh, sh es un alias al shell de tu sistema

echo -n $(date +"%Y.%m.%d - %H:%M:%S:%N")  >> /home/docker.init.log
echo "- Entrypoint a : starting ..."  >> /home/docker.init.log

# Definición de colores ANSI
RED='\033[0;31m'
RED_BOLD='\033[1;31m'
GREEN='\033[0;32m'
GREEN_BOLD='\033[1;32m'
BLUE='\033[0;34m'
BLUE_BOLD='\033[1;34m'
NC='\033[0m' # No Color / Restablecer

regla_1="\e[0;34m---------------------------------------------------------------------------\e[0m"
regla_2="───────────────────────────────────────"

sleep 2
echo -e $regla_1 


# Imprime la variable con formato o resaltado
echo -e $regla_2
echo -e "Starting 09-node-webpack container"
echo -e "${BLUE}NODE_ENV: ${RED_BOLD}${NODE_ENV:-not set}${NC}"
echo -e $regla_2
echo -e " "

# figlet $THIS_IMAGE
# neofetch

# echo -n $(date +"%Y.%m.%d - %H:%M:%S:%N")  >> /home/docker.init.log
# echo "- Entrypoint a : figlet + neofetch almost ready" >> /home/docker.init.log



echo -e "${BLUE_BOLD}nodejs -v : ${NC}" 
nodejs -v
echo -e  "${BLUE_BOLD}npm -v : ${NC}"
npm -v
echo -e -n "${BLUE_BOLD}webpack -v : ${NC}"
webpack -v



echo -en "\ncambiando de directorio a: /workdir" 
cd /workdir
pwd | tr -d '\n'
ls -B | sed 's/^/\t/'
echo -e $regla_1 

# npm install grunt --save-dev
# npm install grunt-contrib-jshint --save-dev
# npm install

# npm update 
# npm audit fix --force



echo -n $(date +"%Y.%m.%d - %H:%M:%S:%N")  >> /home/docker.init.log
echo "- Entrypoint a : almost everything ready " >> /home/docker.init.log


# Luego quitar esto 
chmod 777 www/ 
chmod -R 777 www/*.css www/*.js 

npx webpack --watch


# tail -f /dev/null

# -----------------------------------------------------------------------------------------


# echo $(date +"%Y.%m.%d - %H:%M:%S:%N - Entrypoint : start")  >> /tmp/_start.log

# chown -R www-data:www-data /var/www/html/

# echo $(date +"%Y.%m.%d - %H:%M:%S:%N - Entrypoint : almost end")  >> /tmp/_start.log


