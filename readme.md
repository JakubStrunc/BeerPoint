# Semestrální práce z KIV/WEB

## Rozběhnutí projektu
1. nacházíme se v adresáři ```WEB_APP```
2. zkopírujeme soubor ```app/Config/env.inc.example.php``` do souboru ```app/Config/env.inc.php``` (jeho obsah neměníme)
   - oba soubory budou obsahovat **POUZE** konstanty pro připojení k databázi.
   - soubor ```app/Config/env.inc.php``` se "necommituje"
3. příkazem ```make up``` se spustí celý systém včetně composeru

## Dostupný technologický stack
- spouští se z adresáře ```WEB_APP``` (ne z kořenového adresáře)
- ```make up``` :
  -  start Apache a start aplikace (nastartuje docker kontejner) - běží na ```localhost:80```
  -  start MariaDB - běží na ```localhost:3306```
  -  start phpMyAdmin - běží na ```localhost:8081```
  -  start npm instalace - pokud existuje soubor ```package.json```
- ```make down``` - vypne docker kontejner
- ```make down-rmi``` - vypne docker kontejner a smaže images
- ```make restart``` - restartuje docker
- ```make install``` - spustí composer install
- ```make phpstan-min``` - spustí phpstan pro splnění minimálních kritérií
- ```make phpstan-dop``` - spustí phpstan pro splnění doporučených kritérií
- ```make phpstan-max``` - spustí phpstan s maximální úrovní pravidel (nehodnotí se, je zde pouze pro ukázku nástroje)
- ```make sh``` - spustí bash kontejneru s Php
- ```make node-install``` - spustí npm install
- ```make node-sh``` - spustí node container a v něm sh