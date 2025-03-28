<?php

namespace kivweb\Models;


//namespace kivweb\Services;
use kivweb\Services\Logovani;
use Monolog\Logger;
use AllowDynamicProperties;
use PDO;
use PDOException;

require_once ("app/Services/Logovani.php");
require_once ("vendor/autoload.php");

require_once("app/Config/env.inc.php");

define("TABLE_UZIVATEL","UZIVATEL");
define("TABLE_PRAVO","PRAVA");
define("TABLE_OBJEDNAVKA","OBJEDNAVKA");
define("TABLE_OBSAHUJE","OBSAHUJE");
define("TABLE_PRODUKT","PRODUKT");



/**
 * Trida spravujici databazi.
 */
class MyDatabase {

    /** @var PDO $pdo */
    private PDO $pdo;


    /** @var MySession $mySession */
    protected MySession $mySession;


    /** @var string $userSessionKey */
    private string $userSessionKey = "current_user_id";


    /** @var Logger $logger */
    public Logger $logger;
    /** @var Logger $loggerUdalosti */
    public Logger $loggerUdalosti;



    /**
     * inicializace db
     */
    public function __construct(){

        // nastaveni Loggeru
        if (isset($_SESSION["logovani"]) == false) {

            $log = Logovani::getLogovani();
            $_SESSION["logovani"] = $log;
        }
        $logovani = $_SESSION["logovani"];
        $this->logger = $logovani->logProvoz;
        $this->loggerUdalosti = $logovani->logUdalost;


        $this->pdo = new \PDO("mysql:host=" . DB__SERVER . ";dbname=" . DB__JMENO_DB, DB__UZIVATEL, DB__HESLO);
        $this->pdo->exec("set names utf8");

        //vytvoreni db
        if (!$this->tableExists(TABLE_UZIVATEL)) {
            $this->createTables();
        }
        //$this->createTables();
        require_once ("MySessions.class.php");
        $this->mySession = new MySession();


    }

    /**
     * vytvoreni db
     * @param string $tableName jmeno tabulky
     */
    private function tableExists(string $tableName): bool
    {
        try {
            $result = $this->pdo->query("SHOW TABLES LIKE '$tableName'");
            return $result && $result->rowCount() > 0;
        } catch (PDOException $e) {
            $this->logger->error("Error checking table existence: " . $e->getMessage());
            return false;
        }
    }


    /**
     * provedeni sql
     * @param string $dotaz sql code na provedeni
     */
    private function executequery(string $dotaz): void
    {
        $res = $this->pdo->query($dotaz);

        if($res){
        }
        else{
            $error = $this->pdo->errorInfo();
            echo $error[2];
        }
    }


    /**
     * sql kod
     */
    private function createTables(): void
    {
          $q = "
    -- MySQL Script generated by MySQL Workbench
    -- Model: New Model    Version: 1.0

    SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
    SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
    SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

    -- -----------------------------------------------------
    -- Schema mydb
    -- -----------------------------------------------------

    -- -----------------------------------------------------
    -- Table `PRAVA`
    -- -----------------------------------------------------
    DROP TABLE IF EXISTS `PRAVA`;

    CREATE TABLE IF NOT EXISTS `PRAVA` (
                                           `id_prava` SMALLINT NOT NULL,
                                           `nazev` VARCHAR(45) NOT NULL,
        PRIMARY KEY (`id_prava`)
        ) ENGINE = InnoDB;


    -- -----------------------------------------------------
    -- Table `UZIVATEL`
    -- -----------------------------------------------------
    DROP TABLE IF EXISTS `UZIVATEL`;

    CREATE TABLE IF NOT EXISTS `UZIVATEL` (
                                              `id_uzivatel` INT NOT NULL AUTO_INCREMENT,
                                              `email` VARCHAR(45) NOT NULL,
        `uzivatelske_jmeno` VARCHAR(45) NOT NULL,
        `heslo` VARCHAR(255) NOT NULL,
        `id_prava` SMALLINT NOT NULL,
        PRIMARY KEY (`id_uzivatel`),
        INDEX `fk_uzivatel_prava_id_prava_idx` (`id_prava` ASC),
        CONSTRAINT `fk_uzivatel_prava_id_prava`
        FOREIGN KEY (`id_prava`)
        REFERENCES `PRAVA` (`id_prava`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        ) ENGINE = InnoDB;


    -- -----------------------------------------------------
    -- Table `OBJEDNAVKA`
    -- -----------------------------------------------------
    DROP TABLE IF EXISTS `OBJEDNAVKA`;

    CREATE TABLE IF NOT EXISTS `OBJEDNAVKA` (
                                                `id_objednavka` INT NOT NULL AUTO_INCREMENT,
                                                `jmeno` VARCHAR(45) NOT NULL,
        `prijmeni` VARCHAR(45) NOT NULL,
        `ulice` VARCHAR(45) NOT NULL,
        `cislo_popisne` INT NOT NULL,
        `orientacni_cislo` INT NOT NULL,
        `psc` INT NOT NULL,
        `mesto` VARCHAR(45) NOT NULL,
        `telefonni_cislo` VARCHAR(15) NOT NULL,
        `dokoncena` TINYINT NOT NULL,
        `id_uzivatel` INT NOT NULL,
        `stav` VARCHAR(45) NOT NULL,
        PRIMARY KEY (`id_objednavka`),
        INDEX `fk_uzivatele_objednavka_id_uzivatel_idx` (`id_uzivatel` ASC),
        CONSTRAINT `fk_uzivatele_objednavka_id_uzivatel`
        FOREIGN KEY (`id_uzivatel`)
        REFERENCES `UZIVATEL` (`id_uzivatel`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        ) ENGINE = InnoDB;


    -- -----------------------------------------------------
    -- Table `PRODUKT`
    -- -----------------------------------------------------
    DROP TABLE IF EXISTS `PRODUKT`;

    CREATE TABLE IF NOT EXISTS `PRODUKT` (
                                             `id_produkt` INT NOT NULL AUTO_INCREMENT,
                                             `nazev` VARCHAR(45) NOT NULL,
        `cena` INT NOT NULL,
        `mnozstvi` INT NOT NULL,
        `popis` TEXT NULL,
        `photo` VARCHAR(45) NULL,
        PRIMARY KEY (`id_produkt`)
        ) ENGINE = InnoDB;


    -- -----------------------------------------------------
    -- Table `OBSAHUJE`
    -- -----------------------------------------------------
    DROP TABLE IF EXISTS `OBSAHUJE`;

    CREATE TABLE IF NOT EXISTS `OBSAHUJE` (
                                              `pocet` INT NOT NULL,
                                              `id_objednavka` INT NOT NULL,
                                              `id_produkt` INT NOT NULL,
                                              PRIMARY KEY (`id_objednavka`, `id_produkt`),
        INDEX `fk_produkt_obsahuje_id_produkt_idx` (`id_produkt` ASC),
        CONSTRAINT `fk_objednavka_obsahuje_id_objednavka`
        FOREIGN KEY (`id_objednavka`)
        REFERENCES `OBJEDNAVKA` (`id_objednavka`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
        CONSTRAINT `fk_produkt_obsahuje_id_produkt`
        FOREIGN KEY (`id_produkt`)
        REFERENCES `PRODUKT` (`id_produkt`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
        ) ENGINE = InnoDB;


    SET SQL_MODE=@OLD_SQL_MODE;
    SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
    SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

    -- -----------------------------------------------------
    -- Data for table `PRAVA`
    -- -----------------------------------------------------
    START TRANSACTION;
    INSERT INTO `PRAVA` (`id_prava`, `nazev`) VALUES (1, 'Admin');
    INSERT INTO `PRAVA` (`id_prava`, `nazev`) VALUES (2, 'Prodejce');
    INSERT INTO `PRAVA` (`id_prava`, `nazev`) VALUES (3, 'Uzivatel');
    COMMIT;


    -- -----------------------------------------------------
    -- Data for table `UZIVATEL`
    -- -----------------------------------------------------
    START TRANSACTION;
    INSERT INTO `UZIVATEL` (`id_uzivatel`, `email`, `uzivatelske_jmeno`, `heslo`, `id_prava`) VALUES (1, 'admin@example.com', 'pepaadmin', '". password_hash("admin", PASSWORD_DEFAULT). "', 1);
    INSERT INTO `UZIVATEL` (`id_uzivatel`, `email`, `uzivatelske_jmeno`, `heslo`, `id_prava`) VALUES (2, 'seller@example.com', 'kubaprodejce', '". password_hash("seller", PASSWORD_DEFAULT). "', 2);
    INSERT INTO `UZIVATEL` (`id_uzivatel`, `email`, `uzivatelske_jmeno`, `heslo`, `id_prava`) VALUES (3, 'user@example.com', 'januser', '". password_hash("user", PASSWORD_DEFAULT). "', 3);
    COMMIT;


    -- -----------------------------------------------------
    -- Data for table `OBJEDNAVKA`
    -- -----------------------------------------------------
    START TRANSACTION;
    INSERT INTO `OBJEDNAVKA` (`id_objednavka`, `jmeno`, `prijmeni`, `ulice`, `cislo_popisne`, `orientacni_cislo`, `psc`, `mesto`, `telefonni_cislo`, `dokoncena`, `id_uzivatel`, `stav`) VALUES (1, 'Jan', 'Strunc', 'Karlova', 12, 1, 11000, 'Praha', '602123456', 1, 3, 'odeslana');
    INSERT INTO `OBJEDNAVKA` (`id_objednavka`, `jmeno`, `prijmeni`, `ulice`, `cislo_popisne`, `orientacni_cislo`, `psc`, `mesto`, `telefonni_cislo`, `dokoncena`, `id_uzivatel`, `stav`) VALUES (2, 'Jan', 'Strunc', 'Karlova', 12, 1, 11000, 'Praha', '602123456', 0, 3, 'kosik');
    COMMIT;


    -- -----------------------------------------------------
    -- Data for table `PRODUKT`
    -- -----------------------------------------------------
    START TRANSACTION;
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (1, 'Pilsner Urquell', 30, 100, 'Klasické české pivo Pilsner Urquell', 'pilsner.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (2, 'Gambrinus', 25, 150, 'Oblíbené světlé pivo Gambrinus', 'gambrinus.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (3, 'Exelent', 28, 200, 'Světově proslulé pivo Exelent', 'exelent.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (4, 'Gambrinus 11', 28, 120, 'Světlé pivo Gambrinus 11°', 'gambrinus_11.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (5, 'Radegast 12 Sud', 35, 50, 'Radegast 12° v sudech', 'radegast_12_sud.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (6, 'Birell Pomelo & Grep Sud', 20, 70, 'Nealkoholické pivo Birell Pomelo & Grep v sudech', 'birell_pomelo&grep_sud.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (7, 'Gambrinus 12 přepravka', 360, 25, 'Gambrinus 12° balený v přepravkách', 'gambrinus_12_prepravka.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (8, 'Gambrinus 10 přepravka', 350, 30, 'Gambrinus 10° balený v přepravkách', 'gambrinus_10_prepravka.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (9, 'Pilsner přepravka', 395, 20, 'Pilsner Urquell balený v přepravkách', 'pilsner_prepravka.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (10, 'Kozel přepravka', 375, 18, 'Kozel balený v přepravkách', 'kozel_prepravka.jpg');
    INSERT INTO `PRODUKT` (`id_produkt`, `nazev`, `cena`, `mnozstvi`, `popis`, `photo`) VALUES (11, 'Kozel sud', 260, 40, 'Kozel balený v sudech', 'kozel_sud.jpg');
    COMMIT;


    -- -----------------------------------------------------
    -- Data for table `OBSAHUJE`
    -- -----------------------------------------------------
    START TRANSACTION;
    INSERT INTO `OBSAHUJE` (`pocet`, `id_objednavka`, `id_produkt`) VALUES (6, 1, 1);
    INSERT INTO `OBSAHUJE` (`pocet`, `id_objednavka`, `id_produkt`) VALUES (4, 1, 2);
    INSERT INTO `OBSAHUJE` (`pocet`, `id_objednavka`, `id_produkt`) VALUES (10, 2, 3);
    COMMIT;
    ";
    $this->executequery($q);

    //logger
    $this->loggerUdalosti->notice("Nove vytvoreni tabulek");
    }

    /**
     * ziskani dat z tabulky
     * @param string $tableName jmeno tabulky
     * @param array $data
     * @param string $whereStatement
     * @param string $orderStatement serazeni
     * @return false|array|null
     */
    public function selectFromTable(string $tableName, array $data, string $whereStatement = "", string $orderStatement = ""): false|array|null
    {
        //sql kod
        $q = "SELECT * FROM " . $tableName
            .(($whereStatement == "") ? "" : " WHERE ".$whereStatement)
            .(($orderStatement == "") ? "" : " ORDER BY ".$orderStatement);

        $this->loggerUdalosti->debug($q);
        $vystup = $this->pdo->prepare($q);
        //$obj = $this->executequery($q);
        if($vystup->execute($data)) {
            return $vystup->fetchAll();
        } else {
            // dotaz skoncil chybou
            return null;
        }

    }

    /**
     * vamzani dat z tabulky
     * @param string $tableName jmeno tabulky
     * @param string $whereStatement kde co
     * @param array $data
     */
    public function deleteFromTable(string $tableName, string $whereStatement, array $data): bool
    {
        $q = "DELETE FROM $tableName WHERE $whereStatement";
        $this->loggerUdalosti->debug($q);
        $vystup = $this->pdo->prepare($q);
        //$obj = $this->executequery($q);
        if($vystup->execute($data)) {
            return false;
        }
        return true;
    }

    /**
     * vloz to tabulky
     * @param string $tableName jmeno tabulky
     * @param string $insertStatement // kam
     * @param string $insertValues // co
     * @param array $data
     * @return bool
     */
    public function insertIntoTable(string $tableName, string $insertStatement, string $insertValues, array $data): bool
    {
        $q = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        $this->loggerUdalosti->debug($q);
        $vystup = $this->pdo->prepare($q);
        //$obj = $this->executequery($q);
        if($vystup->execute($data)) {
            return false;
        }
        return true;
    }

    /**
     * udate tabulky
     * @param string $tableName jmeno tabulky
     * @param string $updateStatement
     * @param string $whereStatement
     * @param array $data
     * @return bool
     */
    public function updateInTable(string $tableName, string $updateStatement, string $whereStatement, array $data): bool
    {
        $q = "Update $tableName SET $updateStatement WHERE $whereStatement";
        //var_dump($q);
        $this->loggerUdalosti->debug($q);
        $vystup = $this->pdo->prepare($q);
        //$obj = $this->executequery($q);
        if($vystup->execute($data)) {
            return false;
        }
        return true;
    }


    /**
     * dostan vsechny uzivatele
     */
    function getAllUsers(): false|array|null
    {

        $users = $this->selectFromTable(TABLE_UZIVATEL, array(), "id_uzivatel", "");
        $this->loggerUdalosti->info("Nacitani uzivatelu");
        return $users;
    }

    /**
     * pridej noveho uzivatele
     * @param string $email
     * @param string $uzivatelske_jmeno
     * @param string $heslo
     * @param int $idPravo
     * @return bool
     */
    public function addNewUser(string $email, string $uzivatelske_jmeno, string $heslo, int $idPravo = 3): bool
    {

        $xssemail = htmlspecialchars($email);
        $xssuzivatelske_jmeno = htmlspecialchars($uzivatelske_jmeno);
        $xssheslo = htmlspecialchars($heslo);
        $xssidPravo = htmlspecialchars($idPravo);
        $insertStatement = "email, uzivatelske_jmeno, heslo, id_prava";
        $insertValues = ":kemail, :kuzivatelske_jmeno, :kheslo, :kidprava";
        $data = array("kemail" => $xssemail, "kuzivatelske_jmeno" => $xssuzivatelske_jmeno, "kheslo" => $xssheslo, "kidprava" => $xssidPravo);
        $this->loggerUdalosti->info("Registrace noveho uzivatele $uzivatelske_jmeno");
        return $this->insertIntoTable(TABLE_UZIVATEL, $insertStatement, $insertValues, $data);
    }

    /**
     * updatuj uzevatele heslo
     * @param int $id_uzivatel
     * @param string $heslo
     * @return bool
     */
    public function updateUserPassword(int $id_uzivatel, string $heslo): bool
    {

        $updateStatement = "heslo = :kheslo";
        $whereStatement = "id_uzivatel = :kid_uzivatel";
        $data = array("kheslo" => $heslo, "kid_uzivatel" => $id_uzivatel);

        //logovani
        $this->loggerUdalosti->info("Změna hesla uživatele s id: $id_uzivatel");

        return $this->updateInTable(TABLE_UZIVATEL, $updateStatement, $whereStatement, $data);
    }

    /**
     * ziskej uzivatele podle id
     * @param int $id_uzivatel
     * @return mixed
     */
    public function getUser(int $id_uzivatel): mixed
    {
        // Define the selection statement and data
        $whereStatement = "id_uzivatel = :kid_uzivatel";
        $data = array("kid_uzivatel" => $id_uzivatel);

        // Fetch user data
        $userData = $this->selectFromTable(TABLE_UZIVATEL, $data, $whereStatement);

        // Return the first record from the result
        return $userData[0];
    }


    /**
     * destan uzivatelovu roli podle id
     * @param int $idRole
     * @return mixed|null
     */
    public function getRoleById(int $idRole): mixed
    {

        $whereStatement = "id_prava = :kid_prava";
        $data = array("kid_prava" => $idRole);

        $role = $this->selectFromTable(TABLE_PRAVO, $data, $whereStatement);

        return $role[0]['nazev'] ?? null;
        }


    /**
     * ziskej vsechny produkty
     * @return array|false|null
     */
    function getAllProducts(): false|array|null
    {
        $this->loggerUdalosti->info("Nacteni Produktu k prodeji");
        return $this->selectFromTable(TABLE_PRODUKT, array(), "mnozstvi != 0", "id_produkt");
    }

    /**
     * ziskej nedodelane produkty
     * @return array|false|null
     */
    function getAllUndoneProducts(): false|array|null
    {
        $this->loggerUdalosti->info("Nacteni Produktu k dodelani");
        return $this->selectFromTable(TABLE_PRODUKT, array(), "mnozstvi = 0", "id_produkt");
    }

    /**
     * ziskej produkt podle id
     * @param $id_produkt
     * @return mixed
     */
    public function getProductById($id_produkt): mixed
    {
        $whereStatement = "id_produkt = :kid_produkt";
        $data = array("kid_produkt" => $id_produkt);

        $this->loggerUdalosti->info("Načtení produktu s ID: $id_produkt");

        $products = $this->selectFromTable(TABLE_PRODUKT, $data, $whereStatement);

        return $products[0];
    }

    /**
     * pridej produkt
     * @param string $nazev
     * @param int $cena
     * @param int $mnozstvi
     * @param string $popis
     * @param string $photo
     * @return bool
     */
    public function addProduct(string $nazev, int $cena, int $mnozstvi, string $popis, string $photo): bool
    {

        $xssnazev = htmlspecialchars($nazev);
        $xsscena = htmlspecialchars($cena);
        $xssmnozstvi = htmlspecialchars($mnozstvi);
        $xsspopis = htmlspecialchars($popis);
        // Define the insert statement and placeholders
        $insertStatement = "nazev, cena, mnozstvi, popis, photo";
        $insertValues = ":knazev, :kcena, :kmnozstvi, :kpopis, :kphoto";
        $data = array(
            "knazev" => $xssnazev,
            "kcena" => $xsscena,
            "kmnozstvi" => $xssmnozstvi,
            "kpopis" => $xsspopis,
            "kphoto" => $photo
        );

        // logovani
        $this->loggerUdalosti->info("Přidání nového produktu: $nazev");

        return $this->insertIntoTable(TABLE_PRODUKT, $insertStatement, $insertValues, $data);
    }

    /**
     * updatuj produkt
     * @param int $id_produkt
     * @param string $nazev
     * @param float $cena
     * @param int $mnozstvi
     * @param string $popis
     * @param string $photo
     * @return bool
     */
    public function updateProduct(int $id_produkt, string $nazev, float $cena, int $mnozstvi, string $popis, string $photo = ""): bool
    {
        $xssnazev = htmlspecialchars($nazev);
        $xsscena = htmlspecialchars($cena);
        $xssmnozstvi = htmlspecialchars($mnozstvi);
        $xsspopis = htmlspecialchars($popis);
        $updateStatement = "nazev = :knazev, cena = :kcena, mnozstvi = :kmnozstvi, popis = :kpopis, photo = :kphoto";
        $whereStatement = "id_produkt = :kid_produkt";
        $data = array(
            "knazev" => $xssnazev,
            "kcena" => $xsscena,
            "kmnozstvi" => $xssmnozstvi,
            "kpopis" => $xsspopis,
            "kphoto" => $photo,
            "kid_produkt" => $id_produkt
        );

        // logovani
        $this->loggerUdalosti->info("Úprava produktu: $nazev");

        return $this->updateInTable(TABLE_PRODUKT, $updateStatement, $whereStatement, $data);
    }

    /**
     * zmen roli uzivateli
     * @param int $userId
     * @param int $roleId
     * @return bool
     */
    public function updateUserRole(int $userId, int $roleId): bool
    {
        // Define the update statement and bind data
        $updateStatement = "id_prava = :kid_prava";
        $whereStatement = "id_uzivatel = :kid_uzivatel";
        $data = array(
            "kid_prava" => $roleId,
            "kid_uzivatel" => $userId
        );

        // logovani
        $this->loggerUdalosti->info("Úprava role uživatele s ID: $userId na roli $roleId");

        return $this->updateInTable(TABLE_UZIVATEL, $updateStatement, $whereStatement, $data);
    }

    /**
     * ziskej vsechny objednavky
     * @return false|array|null
     */
    public function getAllOrders(): false|array|null
    {
        $whereStatement = "dokoncena = :dokoncena";
        $orderStatement = "id_objednavka";
        $data = array("dokoncena" => 1);

        // logovani
        $this->loggerUdalosti->info("Načtení všech objednávek uživatelů");

        return $this->selectFromTable(TABLE_OBJEDNAVKA, $data, $whereStatement, $orderStatement);
    }

    /**
     * aktualizuj stav objednavky
     * @param int $id_objednavka
     * @param string $stav
     * @return bool
     */
    public function updateOrder(int $id_objednavka, string $stav): bool
    {

        $updateStatement = "stav = :kstav";
        $whereStatement = "id_objednavka = :kid_objednavka";
        $data = array(
            "kstav" => $stav,
            "kid_objednavka" => $id_objednavka
        );

        // logovani
        $this->loggerUdalosti->info("Úprava stavu objednávky číslo: $id_objednavka na $stav");

        return $this->updateInTable(TABLE_OBJEDNAVKA, $updateStatement, $whereStatement, $data);
    }

    /**
     * dorucovatelske udaje
     * @param int $id_objednavka
     * @param string $jmeno
     * @param string $prijmeni
     * @param string $ulice
     * @param string $cislo_popisne
     * @param string $orientacni_cislo
     * @param string $psc
     * @param string $mesto
     * @param string $telefonni_cislo
     * @return bool
     */
    public function updateOrderDetails(int $id_objednavka, string $jmeno, string $prijmeni, string $ulice, string $cislo_popisne, string $orientacni_cislo, string $psc, string $mesto, string $telefonni_cislo): bool
    {

        $xssjmeno = htmlspecialchars($jmeno);
        $xssprijmeni = htmlspecialchars($prijmeni);
        $xssulice = htmlspecialchars($ulice);
        $xsscislo_popisne = htmlspecialchars($cislo_popisne);
        $xssorientacni_cislo = htmlspecialchars($orientacni_cislo);
        $xsspsc = htmlspecialchars($psc);
        $xssmesto = htmlspecialchars($mesto);
        $xsstelefonni_cislo = htmlspecialchars($telefonni_cislo);
        $updateStatement = "
        jmeno = :kjmeno, 
        prijmeni = :kprijmeni, 
        ulice = :kulice, 
        cislo_popisne = :kcislo_popisne, 
        orientacni_cislo = :korientacni_cislo, 
        psc = :kpsc, 
        mesto = :kmesto, 
        telefonni_cislo = :ktelefonni_cislo, 
        dokoncena = :kdokoncena";

        $whereStatement = "id_objednavka = :kid_objednavka";

        $data = array(
            "kjmeno" => $xssjmeno,
            "kprijmeni" => $xssprijmeni,
            "kulice" => $xssulice,
            "kcislo_popisne" => $xsscislo_popisne,
            "korientacni_cislo" => $xssorientacni_cislo,
            "kpsc" => $xsspsc,
            "kmesto" => $xssmesto,
            "ktelefonni_cislo" => $xsstelefonni_cislo,
            "kdokoncena" => 1,
            "kid_objednavka" => $id_objednavka
        );

        // logovani
        $this->loggerUdalosti->info(
            "Objednávka odeslána na adresu $ulice $cislo_popisne/$orientacni_cislo, $psc, $mesto na jméno $jmeno $prijmeni"
        );

        return $this->updateInTable(TABLE_OBJEDNAVKA, $updateStatement, $whereStatement, $data);
    }

    /**
     * ziskej produkt podle id
     * @param int $id_objednavka
     * @return false|array
     */
    public function getProductsByOrderId(int $id_objednavka): false|array
    {
        $q = "
        SELECT 
            p.id_produkt, 
            p.nazev, 
            p.cena, 
            p.photo, 
            o.pocet 
        FROM " . TABLE_OBSAHUJE . " o
        JOIN " . TABLE_PRODUKT . " p ON o.id_produkt = p.id_produkt
        WHERE o.id_objednavka = :kid_objednavka
    ";

        $data = array("kid_objednavka" => $id_objednavka);

        // logovani
        $this->loggerUdalosti->info("Načtení detailů produktu pro objednávku číslo: $id_objednavka");
        $this->loggerUdalosti->debug($q);

        // proved
        $stmt = $this->pdo->prepare($q);
        if ($stmt->execute($data)) {
            return $stmt->fetchAll();
        } else {
            $this->loggerUdalosti->error("Chyba při načítání detailů produktu pro objednávku číslo: $id_objednavka");
            return [];
        }
    }

    /**
     * ziskej objednavku podle id
     * @param int $idUzivatel
     * @return array|null
     */
    public function getOrdersById(int $idUzivatel): ?array
    {
        $whereStatement = "id_uzivatel = :kid_uzivatel AND dokoncena = :dokoncena";
        $data = array(
            "kid_uzivatel" => $idUzivatel,
            "dokoncena" => 1
        );

        // logovani
        $this->loggerUdalosti->info("Načtení všech objednávek uživatele s ID: $idUzivatel");

        $orders = $this->selectFromTable(TABLE_OBJEDNAVKA, $data, $whereStatement);

        return !empty($orders) ? $orders : null;
    }

    /**
     * vytvor kosik
     * @param int $idUzivatel
     * @return bool
     */
    public function createCard(int $idUzivatel): bool
    {
        $insertStatement = "
        jmeno, 
        prijmeni, 
        ulice, 
        cislo_popisne, 
        orientacni_cislo, 
        psc, 
        mesto, 
        telefonni_cislo, 
        dokoncena, 
        id_uzivatel, 
        stav";
        $insertValues = "
        :kjmeno, 
        :kprijmeni, 
        :kulice, 
        :kcislo_popisne, 
        :korientacni_cislo, 
        :kpsc, 
        :kmesto, 
        :ktelefonni_cislo, 
        :kdokoncena, 
        :kid_uzivatel, 
        :kstav";
        $data = array(
            "kjmeno" => "blank",
            "kprijmeni" => "blank",
            "kulice" => "blank",
            "kcislo_popisne" => "0",
            "korientacni_cislo" => "0",
            "kpsc" => "0",
            "kmesto" => "blank",
            "ktelefonni_cislo" => "0",
            "kdokoncena" => 0,
            "kid_uzivatel" => $idUzivatel,
            "kstav" => "zpracovává se"
        );

        // logovani
        $this->loggerUdalosti->info("Košík vytvořen uživateli s ID: $idUzivatel");

        return $this->insertIntoTable(TABLE_OBJEDNAVKA, $insertStatement, $insertValues, $data);
    }

    /**
     * pridej do kosiku
     * @param int $id_objednavka
     * @param int $id_produkt
     * @return bool
     */
    public function addToCard(int $id_objednavka, int $id_produkt): bool
    {
        $insertStatement = "pocet, id_objednavka, id_produkt";
        $insertValues = ":kpocet, :kid_objednavka, :kid_produkt";
        $data = array(
            "kpocet" => 1,
            "kid_objednavka" => $id_objednavka,
            "kid_produkt" => $id_produkt
        );

        $this->loggerUdalosti->info("Přidání produktu $id_produkt do košíku číslo $id_objednavka");

        return $this->insertIntoTable(TABLE_OBSAHUJE, $insertStatement, $insertValues, $data);
    }


    /**
     * ziskej kosik
     * @param int $idUzivatel
     * @return mixed|null
     */
    public function getCard(int $idUzivatel): mixed
    {
        $whereStatement = "id_uzivatel = :kid_uzivatel AND dokoncena = :dokoncena";
        $data = array(
            "kid_uzivatel" => $idUzivatel,
            "dokoncena" => 0
        );

        // logovani
        $this->loggerUdalosti->info("Získání košíku uživatele s ID: $idUzivatel");

        $orders = $this->selectFromTable(TABLE_OBJEDNAVKA, $data, $whereStatement);

        if($orders) {
            return $orders[0];
        }
        return null;
    }

    /**
     * zjisti zda je produkt v kosiku
     * @param int $idObjednavka
     * @param int $idProduct
     * @return bool
     */
    public function isProductInCard(int $idObjednavka, int $idProduct): bool
    {

        $whereStatement = "id_objednavka = :kid_objednavka AND id_produkt = :kid_produkt";
        $data = array(
            "kid_objednavka" => $idObjednavka,
            "kid_produkt" => $idProduct
        );

        // logovani
        $this->loggerUdalosti->info("Ověřování, jestli je produkt v košíku");

        $obsahuje = $this->selectFromTable(TABLE_OBSAHUJE, $data, $whereStatement);

        return !empty($obsahuje);
    }

    /**
     * update mnozstvi produktu v kosiku
     * @param int $id_produkt
     * @param int $quantity
     * @return bool
     */
    public function updateCardQuantity(int $id_produkt, int $quantity): bool
    {

        $updateStatement = "pocet = :kpocet";
        $whereStatement = "id_produkt = :kid_produkt";
        $data = array(
            "kpocet" => $quantity,
            "kid_produkt" => $id_produkt
        );

        // logovani
        $this->loggerUdalosti->info("Úprava množství produktu: $id_produkt na $quantity");

        return $this->updateInTable(TABLE_OBSAHUJE, $updateStatement, $whereStatement, $data);
    }

    /**
     * uzivatel s emailem
     * @param string $email
     * @return array|false|null
     */
    public function userLoginEmail(string $email): false|array|null
    {
        $whereStatement = "email = :kemail";
        $data = array("kemail" => $email);

        // logovani
        $this->loggerUdalosti->info("Přihlašování uživatele s emailem: $email");

        return $this->selectFromTable(TABLE_UZIVATEL, $data, $whereStatement);
    }

    /**
     * zkontroluj heslo
     * @param string $heslo
     * @param array $user
     * @return bool
     */
    public function userLoginPass(string $heslo, array $user): bool
    {

        if(password_verify($heslo, $user[0]['heslo'])){

            $_SESSION[$this->userSessionKey] = $user[0]["id_uzivatel"];
            return true;
        }else{
            return false;
        }
    }

    /**
     * odhalseni uzivatele
     * @return void
     */
    public function userLogout(): void
    {
        $this->logger->notice("Uspesne odhlaseni");
        unset($_SESSION[$this->userSessionKey]);
    }


    /**
     * je uzivatel prihlasen?
     * @return bool
     */
    public function isUserLogged(): bool
    {
        return isset($_SESSION[$this->userSessionKey]);
    }


    /**
     * ziskek prihlaseneho uzivatele udaje
     * @return mixed|null
     */
    public function getLoggedUserData(): mixed
    {
        if ($this->isUserLogged()) {
            $userId = $_SESSION[$this->userSessionKey];

            if ($userId == null) {
                echo "Server Error: Data nebyla nalezena, uživatel odhlášen.";
                $this->userLogout();
                return null;
            }

            $whereStatement = "id_uzivatel = :kid_uzivatel";
            $data = array("kid_uzivatel" => $userId);

            $userData = $this->selectFromTable(TABLE_UZIVATEL, $data, $whereStatement);

            if (empty($userData)) {
                echo "Data se nenalezla, uživatel odhlášen.";
                $this->userLogout();
                return null;
            }

            return $userData[0];
        }

        return null;
    }

}



