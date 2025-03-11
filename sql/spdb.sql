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
INSERT INTO `UZIVATEL` (`id_uzivatel`, `email`, `uzivatelske_jmeno`, `heslo`, `id_prava`) VALUES (1, 'admin@example.com', 'pepaadmin', 'hashed_password_1', 1);
INSERT INTO `UZIVATEL` (`id_uzivatel`, `email`, `uzivatelske_jmeno`, `heslo`, `id_prava`) VALUES (2, 'user@example.com', 'kubaprodejce', 'hashed_password_2', 2);
INSERT INTO `UZIVATEL` (`id_uzivatel`, `email`, `uzivatelske_jmeno`, `heslo`, `id_prava`) VALUES (3, 'guest@example.com', 'januser', 'hashed_password_3', 3);
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
COMMIT;


-- -----------------------------------------------------
-- Data for table `OBSAHUJE`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `OBSAHUJE` (`pocet`, `id_objednavka`, `id_produkt`) VALUES (6, 1, 1);
INSERT INTO `OBSAHUJE` (`pocet`, `id_objednavka`, `id_produkt`) VALUES (4, 1, 2);
INSERT INTO `OBSAHUJE` (`pocet`, `id_objednavka`, `id_produkt`) VALUES (10, 2, 3);
COMMIT;
