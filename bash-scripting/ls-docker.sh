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

clear

echo $LINE_1
echo "${C4b}docker stats --no-stream  $C0"
docker stats --no-stream

echo $LINE_2
echo "${C4b}free -m -h$C0" 
free -m -h

echo $LINE_1
echo "${C4b}RUNNING CONTAINERS   ( status=running ) :$C0"
docker ps --filter status=running --format 'table {{ .ID }}\t{{ .Names }}\t{{.Image}}\t{{.Command}}\t{{.Ports}}\t{{.RunningFor}}\t{{.Status}}'
	
echo $LINE_2
echo "${C4b}STOPPED CONTAINERS   ( status=exited )  :$C0"
docker ps --filter status=exited --format 'table {{ .ID }}\t{{ .Names }}\t{{.Image}}\t{{.Command}}\t{{.Ports}}\t{{.RunningFor}}\t{{.Status}}'

echo $LINE_2
echo "${C4b}docker ps -a  $C0"
docker ps -a

echo $LINE_1
echo "${C4b}IPs              NAMES  $C0"
if [ "$DOCKER_PS_AQ" ] ; then docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}} %tab% {{.Name}}' $DOCKER_PS_AQ | sed 's#%tab%#\t#g' | sed 's#/##g' | sort -t . -k 1,1n -k 2,2n -k 3,3n -k 4,4n; fi

echo $LINE_1
echo "${C4b}docker volume ls  $C0"
docker volume ls

echo $LINE_1
echo "${C4b}docker images  $C0"
docker images

echo $LINE_1
echo "${C4b}docker network ls  $C0"
docker network ls

echo $LINE_1
