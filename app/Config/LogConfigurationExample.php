<?php

namespace app\Config;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Processor\IntrospectionProcessor;

/**
 * Pouze ukázková třída, která reprezentuje konfiguraci logování
 * Pro skutečné vytvoření konfigurace logování vytvořte třídu LogConfiguration v namespace app\config
 */
class LogConfigurationExample
{

    // Nasledujici blok nemente.
    // ----------------------- START -----------------------
    /**
     * Podadresar, kam se zapisuji LOG soubory
     */
    public const DIR_LOGY = ".".DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR;

    /**
     * LOG soubor pro ladeni
     */
    public const LOG_FILE__LADENI = DIR_LOGY."ladeni.log";

    /**
     * LOG soubor pro provoz
     */
    public const LOG_FILE__PROVOZ = DIR_LOGY."provoz.log";

    /**
     * LOG soubor pro problemy
     */
    public const LOG_FILE__PROBLEMY = DIR_LOGY."problemy.log";

    // ----------------------- KONEC -----------------------
    
    // zmenit, pokud chcete využívat logování při ladění
    public const LOG_LADENI = true;

    public static function setup(Logger $logProvoz, string $dateFormat): void
    {
        //TODO Doplňte konfigutaci logování pro provoz
    }

    public static function setupDebugLoggers(Logger $logUdalost, Logger $logDb, string $dateFormat): void
    {
        if (self::LOG_LADENI) {
            //TODO Doplňte konfigutaci logování pro debug
        }
    }

    public static function setupConsoleHandler(Logger $logProvoz): void
    {
            //TODO Doplňte konfigutaci logování pro konzoli
    }
}