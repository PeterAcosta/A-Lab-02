# Proporciona ayuda
.PHONY: help
help: ## Comando de ayuda
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

show_me_all: ## me muestra las imagenes y los contenedores:
	clear
	@echo "=============================================================================================================================="
	docker stats --no-stream
	@echo "\n=============================================================================================================================="
	docker images
	@echo "\n==============================================================================================================================\nContenedores activos:"
#   @echo "\n-------------------------------------------------------------------------------------\nContenedores activos:"
	docker ps --filter status=running
#   @echo "\n- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\nContenedores inactivos:"
#	@echo "\n.................................................................................................................\nContenedores inactivos:"
	@echo "\n------------------------------------------------------------------------------------------------------------------------------\nContenedores inactivos:"
	docker ps --filter status=exited
	@echo "\n=============================================================================================================================="
	docker volume ls
	@echo "\n=============================================================================================================================="
	docker network ls
	@echo "\n=============================================================================================================================="



delete_all: ## Borra las imagenes y los contenedore
	clear
	@echo "------------------------------------------------------------------------------------------------------------------------------"
	@echo "Borro contenedores:"
	docker rm -f 1a-alpine-3.17 2a-debian-10-buster 2b-debian-11-bullseye 2c-ubuntu-23.04 3a-centos-7.9.2009 4a-redhat-ubi8 4b-fedora-39 5c-amazon-linux-2023
	@echo "\n------------------------------------------------------------------------------------------------------------------------------"
	@echo "Borro imagenes:"
	docker rmi -f image-1a-alpine-3.17:1a image-2a-debian-10-buster:2a image-2b-debian-11-bullseye:2b image-2c-ubuntu-23.04:2c image-3a-centos-7.9.2009:3a image-4a-redhat-ubi8:4a image-4b-fedora-39:4b image-5c-amazon-linux-2023:5c
	@echo ""