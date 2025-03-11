############################################################
##################### OKS-WEB Makefile #####################
############################################################

DIR_DOCKER=docker
PHP_CONTAINER_NAME=OKS_WEB_apache
NODE_CONTAINER_NAME=node

# start aplikace (nastartuje docker kontejner)
up:
	cd "${DIR_DOCKER}" && docker-compose up

# vypne docker kontejner
down:
	cd "${DIR_DOCKER}" && docker-compose down

# vypne docker kontejner a smaze images
down-rmi:
	cd "${DIR_DOCKER}" && docker-compose down --rmi all

# restartuje docker
restart r:
	cd "${DIR_DOCKER}" && docker-compose restart

# spusti composer install
install:
	cd "${DIR_DOCKER}" && docker exec -it "${PHP_CONTAINER_NAME}" sh -c "composer install"

# spusti phpstan pro splneni minimalnich kriterii
phpstan-min:
	cd "${DIR_DOCKER}" && docker exec -it "${PHP_CONTAINER_NAME}" sh -c "composer phpstan-min"

# spusti phpstan pro splneni doporucenych kriterii
phpstan-dop:
	cd "${DIR_DOCKER}" && docker exec -it "${PHP_CONTAINER_NAME}" sh -c "composer phpstan-dop"

# spusti phpstan s maximalni urovni pravidel. (Nehodnoti se, je zde pouze pro ukazku nastroje)
phpstan-max:
	cd "${DIR_DOCKER}" && docker exec -it "${PHP_CONTAINER_NAME}" sh -c "composer phpstan-max"

# spusti bash kontejneru s php
sh:
	cd "${DIR_DOCKER}" && docker exec -it "${PHP_CONTAINER_NAME}" sh -c "umask 000 && sh"

# spusti npm install
node-install:
	cd "${DIR_DOCKER}" && docker-compose run --rm "${NODE_CONTAINER_NAME}" npm install
# spusti node container a v něm sh
node-sh:
	cd "${DIR_DOCKER}" && docker-compose run --rm "${NODE_CONTAINER_NAME}" sh


