#!/bin/bash
# el caracter '#' indica que es un comentario
# el caracter '!' indica que el archivo es un ejecutable. Análogo al .exe o .bat en Windows
# '/bin/bash': bash es un tipo de shell, hay otros con los que se puede trabajar en linux si no estas seguro
# de la version con la que trabajas puedes usar /bin/sh, sh es un alias al shell de tu sistema

echo -n $(date +"%Y.%m.%d - %H:%M:%S:%N")  >> /home/docker.init.log
echo "- Entrypoint a : starting ..."  >> /home/docker.init.log

# figlet $THIS_IMAGE
# neofetch

echo -n $(date +"%Y.%m.%d - %H:%M:%S:%N")  >> /home/docker.init.log
echo "- Entrypoint a : figlet + neofetch almost ready" >> /home/docker.init.log


sleep 2


# ambos comandos mantienen al contenedor corriendo
# tail -f /dev/null

regla="\e[0;34m---------------------------------------------------------------------------\e[0m"
echo -e $regla 

echo -e "\e[1;34mnodejs -v : \e[0m" 
nodejs -v
echo -e  "\e[1;34mnpm -v : \e[0m"
npm -v
echo -e -n "\e[1;34mwebpack -v : \e[0m"
webpack -v



echo -en "\ncambiando de directorio a: /workdir" 
cd /workdir
pwd | tr -d '\n'
ls -B | sed 's/^/\t/'
echo -e $regla 

# npm install grunt --save-dev
# npm install grunt-contrib-jshint --save-dev
# npm install

# npm update 
# npm audit fix --force


echo -n $(date +"%Y.%m.%d - %H:%M:%S:%N")  >> /home/docker.init.log
echo "- Entrypoint a : almost everything ready, only remains to be executed grunt " >> /home/docker.init.log


# grunt concat 
# grunt


npx webpack

tail -f /dev/null

# -----------------------------------------------------------------------------------------


# echo $(date +"%Y.%m.%d - %H:%M:%S:%N - Entrypoint : start")  >> /tmp/_start.log

# chown -R www-data:www-data /var/www/html/

# echo $(date +"%Y.%m.%d - %H:%M:%S:%N - Entrypoint : almost end")  >> /tmp/_start.log



# apache2-foreground

# esta ultima linea nunca se ejecuta
# echo $(date +"%Y.%m.%d - %H:%M:%S:%N - Entrypoint : despues de Apache2")  >> /tmp/_start.log


