<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

//// Pripojeni k databazi ////

// define("DB__SERVER","OKS_WEB_mariadb");
// define("DB__JMENO_DB","oks-web");
// define("DB__UZIVATEL","root");
// define("DB__HESLO","super_tajne_root_heslo_123");
//const DB__SERVER = "OKS_WEB_mariadb";
//const DB__JMENO_DB = "oks-web";
//const DB__UZIVATEL = "root";
//const DB__HESLO = "super_tajne_root_heslo_123";

// definice konkretnich nazvu tabulek
// define("TABLE_UZIVATEL","UZIVATEL");
// define("TABLE_PRAVO","PRAVA");
// define("TABLE_OBJEDNAVKA","OBJEDNAVKA");
// define("TABLE_OBSAHUJE","OBSAHUJE");
// define("TABLE_PRODUKT","PRODUKT");


//// Dostupne stranky webu ////

/** Adresar kontroleru. */
//const DIRECTORY_CONTROLLERS = "Controllers";
/** Adresar modelu. */
//const DIRECTORY_MODELS = "Models";
/** Adresar sablon */
//const DIRECTORY_VIEWS = "Views";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "Home";

/** Dostupne webove stranky. */
const WEB_PAGES = array(//// Uvodni stranka ////
    "Home" => array(
        "title" => "Home",

        //// kontroler
        //"file_name" => "IntroductionController.class.php",
        "controller_class_name" => \kivweb\Controllers\HomeController::class, // poskytne nazev tridy vcetne namespace

        // ClassBased sablona
        "view_class_name" => \kivweb\Views\TemplateBasics::class,

        // TemplateBased sablona
        //"view_class_name" => \kivweb\Views\TemplateBased\TemplateBasics::class,
        "template_type" => \kivweb\Views\TemplateBasics::PAGE_HOME,
    ),

    "objednavky" => array(
        "title" => "objednavky",

        //// kontroler
        //"file_name" => "IntroductionController.class.php",
        "controller_class_name" => \kivweb\Controllers\ObjednavkyController::class, // poskytne nazev tridy vcetne namespace

        // ClassBased sablona
        "view_class_name" => \kivweb\Views\TemplateBasics::class,

        // TemplateBased sablona
        //"view_class_name" => \kivweb\Views\TemplateBased\TemplateBasics::class,
        "template_type" => \kivweb\Views\TemplateBasics::PAGE_OBJEDNAVKY,
    ),

    "kosik" => array(
        "title" => "kosik",

        //// kontroler
        //"file_name" => "IntroductionController.class.php",
        "controller_class_name" => \kivweb\Controllers\KosikController::class, // poskytne nazev tridy vcetne namespace

        // ClassBased sablona
        "view_class_name" => \kivweb\Views\TemplateBasics::class,

        // TemplateBased sablona
        //"view_class_name" => \kivweb\Views\TemplateBased\TemplateBasics::class,
        "template_type" => \kivweb\Views\TemplateBasics::PAGE_KOSIK,
    ),

    "spravaObjednavek" => array(
        "title" => "spravaObjednavek",

        //// kontroler
        //"file_name" => "IntroductionController.class.php",
        "controller_class_name" => \kivweb\Controllers\SpravaObjednavekController::class, // poskytne nazev tridy vcetne namespace

        // ClassBased sablona
        "view_class_name" => \kivweb\Views\TemplateBasics::class,

        // TemplateBased sablona
        //"view_class_name" => \kivweb\Views\TemplateBased\TemplateBasics::class,
        "template_type" => \kivweb\Views\TemplateBasics::PAGE_SPRAVAOBJEDNAVEK,
    ),

    "spravaProduktu" => array(
        "title" => "spravaProduktu",

        //// kontroler
        //"file_name" => "IntroductionController.class.php",
        "controller_class_name" => \kivweb\Controllers\SpravaProduktuController::class, // poskytne nazev tridy vcetne namespace

        // ClassBased sablona
        "view_class_name" => \kivweb\Views\TemplateBasics::class,

        // TemplateBased sablona
        //"view_class_name" => \kivweb\Views\TemplateBased\TemplateBasics::class,
        "template_type" => \kivweb\Views\TemplateBasics::PAGE_SPRAVAPRODUKTU,
    ),

    "spravaUzivatelu" => array(
        "title" => "spravaUzivatelu",

        //// kontroler
        //"file_name" => "IntroductionController.class.php",
        "controller_class_name" => \kivweb\Controllers\SpravaUzivateluController::class, // poskytne nazev tridy vcetne namespace

        // ClassBased sablona
        "view_class_name" => \kivweb\Views\TemplateBasics::class,

        // TemplateBased sablona
        //"view_class_name" => \kivweb\Views\TemplateBased\TemplateBasics::class,
        "template_type" => \kivweb\Views\TemplateBasics::PAGE_SPRAVAUZIVATELU,
    ),





);


