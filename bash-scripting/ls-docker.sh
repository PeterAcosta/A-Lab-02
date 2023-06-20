#!/bin/bash
# Pedro Javier Acosta : peteracosta@gmail.com
# list states and properties of docker objects, containers, images, volumes and networks

# my color codes :
C0="\e[0m"       # default
C1a="\e[0;31m"   # red
C1b="\e[1;31m"   # bold red
C2a="\e[0;32m"   # green
C2b="\e[1;32m"   # bold green
C3a="\e[0;33m"   # yellow
C3b="\e[1;33m"   # bold yellow
C4a="\e[0;34m"   # blue
C4b="\e[1;34m"   # bold blue
C5a="\e[0;35m"   # purple
C5b="\e[1;35m"   # bold purple
C6a="\e[0;36m"   # cyan
C6b="\e[1;36m"   # bold cyan
C7a="\e[0;37m"   # light gray
C7b="\e[1;37m"   # bold light gray
C9a="\e[0;90m"   # dark gray
C9b="\e[1;90m"   # bold dark gray


LINE_1="$C4a-------------------------------------------------------------------------------------------------------------------------------------$C0"
LINE_2="$C4a- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -$C0"



DOCKER_PS_AQ=$(docker ps -aq)
DOCKER_PS_RUNNING=$(docker ps -q --filter status=running )
DOCKER_PS_EXITED=$(docker ps  -q --filter status=exited  )

clear

### MEMORY #################################################
echo -e $LINE_2
echo -e "${C4b}free -m -h$C0" 
free -m -h


### STATS  #################################################
echo -e $LINE_1
echo -e "${C4b}docker stats --no-stream  $C0"
docker stats --no-stream


### RUNNING  ################################################
echo -e $LINE_1
echo -e "${C4b}RUNNING CONTAINERS   ( status=running ) :$C0"
docker ps --filter status=running --format 'table {{ .ID }}\t{{ .Names }}\t{{.Image}}\t{{.Command}}\t{{.Ports}}\t{{.RunningFor}}\t{{.Status}}'


### EXITING  ###############################################
echo -e $LINE_2
echo -e "${C4b}STOPPED CONTAINERS   ( status=exited )  :$C0"
if [ "$DOCKER_PS_EXITED" ] ; then
    docker ps --filter status=exited --format 'table {{ .ID }}\t{{ .Names }}\t{{.Image}}\t{{.Command}}\t{{.Ports}}\t{{.RunningFor}}\t{{.Status}}'
else
    echo -e "${C9a}none $C0"
fi


### LIST ALL CONTAINERS  ####################################
echo -e $LINE_2
echo -e "${C4b}docker ps -a  $C9a"
docker ps -a
echo -e -n $C0


### IPs & RESTART  ##########################################
echo -e $LINE_1
if [ "$DOCKER_PS_AQ" ] ; then
    docker inspect -f '{{.ID}} %tab% {{.Name}} %tab% {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}} %tab% {{.RestartCount}}' $DOCKER_PS_AQ |  \
        sed 's#%tab%#\t#g' |  \
        awk '{print substr($1, 0, 12), $2, $3, $4}' |  \
        sed 's#/##g' |  \
        sort |  \
        column -t -o "   " -N "CONTAINER ID,NAMES,IP,RESTART" |  \
        awk 'NR==1 {print "\033[1;34m" $0 "\033[0m"} NR>1'
fi


### VOLUMES #################################################
echo -e $LINE_1
echo -e "${C4b}docker volume ls  $C0"
docker volume ls


### IMAGES  #################################################
echo -e $LINE_1
echo -e "${C4b}docker images  $C0"
docker images


### NETWORK #################################################
echo -e $LINE_1
echo -e "${C4b}docker network ls  $C0"
docker network ls


echo -e $LINE_1
