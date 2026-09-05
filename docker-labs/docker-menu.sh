#!/bin/bash

# ==============================================================================
# Script de Gestión de Contenedores Docker por Sistema Operativo
# ==============================================================================

# Definición de colores para la interfaz en terminal
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
NC='\033[0m' # Sin color

show_header() {
    clear
    echo -e "${CYAN}====================================================================${NC}"
    echo -e "${YELLOW}         Container runner / manager script - MULTI OS LAB               ${NC}"
    echo -e "${CYAN}===================================================================="
    echo -e "		      Peter's trials and tests "
    echo -e "${CYAN}--------------------------------------------------------------------${NC}"
}

launch_container() {
    local service_name=$1
    local container_name=$2
    local os_label=$3

    echo -e "\n${NC}[+] Iniciando proceso para: ${YELLOW}${os_label}${NC}"
    
    # 1. Compilar imagen si no está construida
    echo -e "${BLUE}[i] Compilando/Verificando imagen para ${service_name}...${NC}"
    docker compose build $service_name

    if [ $? -ne 0 ]; then
        echo -e "${RED}[!] Error en el build de ${service_name}.${NC}"
        read -p "Presione Enter para continuar..."
        return
    fi

    # 2. Levantar el contenedor en segundo plano (detached)
    echo -e "${BLUE}[i] Levantando contenedor ${container_name}...${NC}"
    docker compose up -d $service_name

    if [ $? -eq 0 ]; then
        echo -e "\n${YELLOW}[✓] Contenedor ${container_name} iniciado correctamente.${NC}"
        read -p "¿Desea ingresar a la consola del contenedor ahora? (s/n): " ingresar
        if [[ "$ingresar" =~ ^[Ss]$ ]]; then
            echo -e "${NC}[*] Conectando a la shell de ${YELLOW}${container_name}...${NC}"
			echo -e "${CYAN}    (run: cat /etc/os-release )${NC}"
			echo -e "${CYAN}    (run: neofetch )${NC}"
			echo -e "${CYAN}    (run: ./usr/local/bin/test.sh )${NC}\n"
            if [ "$service_name" == "os-1a-alpine" ]; then
                # docker exec -it $container_name /bin/sh || docker exec -it $container_name /bin/bash
				docker exec -it $container_name /bin/bash
            else
                docker exec -it $container_name /bin/bash
            fi
        fi
    else
        echo -e "${RED}[!] Error al levantar el contenedor ${service_name}.${NC}"
    fi

	echo -e "\n"
    read -p "Presione Enter para volver al menú principal..."
}

while true; do
    show_header
    echo -e " ${YELLOW}1)${NC} Para contenedor con Alpine 3.17           (os-1a-alpine)"
    echo -e " ${YELLOW}2)${NC} Para contenedor con Debian 10 Buster      (os-2a-debian-10-buster)"
    echo -e " ${YELLOW}3)${NC} Para contenedor con Debian 11 Bullseye    (os-2b-debian-11-bullseye)"
    echo -e " ${YELLOW}4)${NC} Para contenedor con Ubuntu 23.04          (os-2c-ubuntu-23-04)"
    echo -e " ${YELLOW}5)${NC} Para contenedor con CentOS 7.9            (os-3a-centos-7-9-2009)"
    echo -e " ${YELLOW}6)${NC} Para contenedor con RedHat UBI 8.7        (os-4a-redhat-ubi8)"
    echo -e " ${YELLOW}7)${NC} Para contenedor con Fedora 39             (os-4b-fedora-39)"
    echo -e " ${YELLOW}8)${NC} Para contenedor con Amazon Linux 2023     (os-5c-amazon-linux-2023)"
    echo -e "${CYAN}--------------------------------------------------------------------${NC}"
    echo -e " ${GREEN}a)${NC} Levantar TODOS los contenedores juntos"
    echo -e " ${GREEN}b)${NC} Estado de los contenedores (docker compose ps)"
    echo -e " ${RED}c)${NC} Detener y eliminar TODOS los contenedores"
	echo -e " ${RED}d)${NC} Eliminar TODOS las imagenes"
    echo -e " ${GREEN}0)${NC} Salir del programa"
    echo -e "${CYAN}====================================================================${NC}"
    
    read -p "Seleccione una opción [0-8 / a-b-c]: " opcion

    case $opcion in
        1) launch_container "os-1a-alpine" "1a-alpine-3.17" "Alpine Linux 3.17" ;;
        2) launch_container "os-2a-debian-10-buster" "2a-debian-10-buster" "Debian 10 Buster" ;;
        3) launch_container "os-2b-debian-11-bullseye" "2b-debian-11-bullseye" "Debian 11 Bullseye" ;;
        4) launch_container "os-2c-ubuntu-23-04" "2c-ubuntu-23.04" "Ubuntu 23.04" ;;
        5) launch_container "os-3a-centos-7-9-2009" "3a-centos-7.9.2009" "CentOS 7.9" ;;
        6) launch_container "os-4a-redhat-ubi8" "4a-redhat-ubi8" "RedHat UBI 8.7" ;;
        7) launch_container "os-4b-fedora-39" "4b-fedora-39" "Fedora 39" ;;
        8) launch_container "os-5c-amazon-linux-2023" "5c-amazon-linux-2023" "Amazon Linux 2023" ;;
        a)
            echo -e "\n${GREEN}[+] Compilando y levantando TODOS los servicios...${NC}"
            docker compose up -d --build
            echo -e "\n\nPresione Enter para continuar..."
			read -r
            ;;
        b)
            echo -e "\n${GREEN}[i] Estado actual del entorno:${NC}\n"
            docker compose ps
            echo -e "\n\nPresione Enter para continuar..."
			read -r
            ;;
			
        c)
            echo -e "\n${RED}[-] Deteniendo y eliminando contenedores...${NC}"
            docker compose down
            echo -e "\n\nPresione Enter para continuar..."
			read -r
            ;;

        d)
            echo -e "\n${RED}[-] Eliminando todas las imágenes...${NC}"
            docker rmi $(docker images -q)	
            echo -e "\n\nPresione Enter para continuar..."
			read -r
            ;;

        0)
            echo -e "\n${YELLOW}¡Hasta luego!${NC}\n"
            exit 0
            ;;
        *)
            echo -e "\n${RED}[!] Opción inválida. Intente de nuevo.${NC}"
            sleep 1.5
            ;;
    esac
done