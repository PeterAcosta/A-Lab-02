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
echo -e "\e[1;34mnpx --version : \e[0m"
npx --version
echo -e "\e[1;34mcorepack --version : \e[0m"
corepack --version
echo -e "\e[1;34mpnpm --version : \e[0m"
pnpm --version


echo -en "\ncambiando de directorio a: /workdir" 
cd /workdir
pwd | tr -d '\n'
ls -B | sed 's/^/\t/'
echo -e $regla 

echo -e -n "\n\e[1;34mgulp --version : \e[0m"
gulp --version



echo -n $(date +"%Y.%m.%d - %H:%M:%S:%N")  >> /home/docker.init.log
echo "- Entrypoint a : almost everything ready, only remains to be executed gulp " >> /home/docker.init.log




echo -e -n "\n\e[1;34mIniciando gulp:"
gulp


# tail -f /dev/null

# -----------------------------------------------------------------------------------------
# esta ultima linea nunca se ejecuta
echo $(date +"%Y.%m.%d - %H:%M:%S:%N - Entrypoint : despues de gulp")  >> /home/docker.init.log


