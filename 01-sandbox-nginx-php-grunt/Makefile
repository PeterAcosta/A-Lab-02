# Makefile for docker

## my color codes :
## Colores ANSI - sin espacios extra
C0=\033[0m#			default
C1a=\033[0;31m#		red
C1b=\033[1;31m#		red bold
C2a=\033[0;32m#		green
C2b=\033[1;32m#		green bold
C3a=\033[0;33m#		yellow
C3b=\033[1;33m#		yellow bold
C4a=\033[0;34m#		blue
C4b=\033[1;34m#		blue bold 
C5a=\033[0;35m#		purple
C5b=\033[1;35m#		purple bold
C6a=\033[0;36m#		cyan
C6b=\033[1;36m#		cyan bold
C7a=\033[0;37m#		gray light
C7b=\033[1;37m#		gray light bold  
C9a=\033[0;90m#		gray dark 
C9b=\033[1;90m#		gray dark bold 

BG_LIGHT_GRAY=\033[47m#		Background gray light
BG_DARK_GRAY=\033[100m#		Background gray dark
BG_CUSTOM_DARK_GRAY=\033[48;2;64;64;64m# RGB(64,64,64) - Gris muy oscuro
BG_CUSTOM_DARKER_GRAY='\033[48;2;32;32;32m'# RGB(32,32,32) - Gris aún más oscuro

## text styles
RESET=\e[0m
BOLD=\e[1m
DIM=\e[2m
ITALIC=\e[3m
UNDERLINE=\e[4m
BLINK=\e[5m
INVERT=\e[7m
HIDDEN=\e[8m
STRIKE=\e[9m

RULE-1="123456789.123456789.123456789.123456789.123456789.123456789.123456789.123456789."
LINE-1="--------------------------------------------------------------------------------"
# LINE-2="\n\033[0;34m-------------------------------------------------------------------------------------------------------------------------------------\033[0m"
# LINE-3="\n\033[0;34m-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -   \033[0m"

# LINE-1="\033[0;34m==================================================================================================================================\033[0m"
# LINE-2="\n\033[0;34m==================================================================================================================================\033[0m"
# LINE-3="\n\033[0;34m---------------------------------------------------------------------------------------------------------------------------------\033[0m"

# LINE-1="\033[0;34m-------------------------------------------------------------------------------------------------------------------------------------\033[0m"
# LINE-2="\n\033[0;34m-------------------------------------------------------------------------------------------------------------------------------------\033[0m"
# LINE-3="\n\033[0;34m-  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -  -   \033[0m"


# DOCKER-PS-AQ=$(shell docker ps -aq)


# Proporciona ayuda
.PHONY: help
help: ## 			ayuda
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//' | sort



bake: ##                  Usa docker compose BAKE para construir y levantar los contenedores
	COMPOSE_BAKE=true docker compose up -d


wipe: ## 			Arrasa con todo, cierra todo, borras las imagenes y borra los directorios
	@sudo -v
	docker compose down 
	docker rmi  $$(docker images -aq )
#	docker volume rm $$(docker volume ls -q)
	sudo rm -R vol-01-var-log-nginx
	sudo rm -R vol-02-var-log-php


rebuild:  ##              Detiene los contenedores, borra las imagens y recontruye todo
	@echo "\n$(C2b)$(LINE-1)"
	@echo "$(C2a)Deteniendo contenedores:$(C0)"
	docker compose down
	@echo  "$(C2a)Borrando imagenes:$(C0)"
	docker rmi  $$(docker images -aq )	
	@echo  "$(C2a)Reconstruyendo imagenes y iniciando contenedores:$(C0)"
	docker compose up -d
	@echo "$(C2b)$(LINE-1)\n"

rerun: ##                 Detiene los contenedores y los vuelve a ejecutar
	@echo "\n$(C2b)$(LINE-1)"
	@echo "$(C2b)Deteniendo contenedores:$(C0)"
	docker compose down
	@echo "\n$(C2b)Re-iniciando contenedores:$(C0)"
	docker compose up -d
	@echo "$(C2b)$(LINE-1)\n"


delete_all_images: ## 	Borra todas TODAS  las imagenes 
	docker rmi  $$(docker images -aq )


delete_all_volumes: ## 	Borra todos los volumenes
	docker volume rm $$(docker volume ls -q)


#delete_all_directories: ##Borra todos los directorios
#	sudo rm -R v-03-mariadb-logs/
#	sudo rm -R v-03-mariadb-backups/    


pull_all_images: ## 	Hace el pull de todas las imagenes base necesarias
	docker pull nginx:1.25.2-bookworm
	docker pull php:8.2.9-fpm-bookworm
#	docker pull node:alpine3.17


delete_images: ## 	Borra las 3 imagenes de este stack
	docker rmi  01-image-php-8.2.6  
	docker rmi  02-image-node-3.17
	docker rmi  03-image-mariadb
