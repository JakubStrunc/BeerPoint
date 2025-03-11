<?php

namespace app\Config;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\BrowserConsoleHandler;
use Monolog\Processor\IntrospectionProcessor;

class LogConfiguration
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
    public const LOG_FILE__LADENI = self::DIR_LOGY."ladeni.log";

    /**
     * LOG soubor pro provoz
     */
    public const LOG_FILE__PROVOZ = self::DIR_LOGY."provoz.log";

    /**
     * LOG soubor pro problemy
     */
    public const LOG_FILE__PROBLEMY = self::DIR_LOGY."problemy.log";

    // ----------------------- KONEC -----------------------
    
    // zmenit, pokud chcete využívat logování při ladění
    public const LOG_LADENI = true;

    public static function setup(Logger $logProvoz, string $dateFormat): void
    {
        $handlerProvoz = new StreamHandler(self::LOG_FILE__PROVOZ, Logger::INFO);
        $zaznamProvoz = "%datetime% %level_name% %message%\n";
        $formatter = new LineFormatter($zaznamProvoz, $dateFormat);
        $handlerProvoz->setFormatter($formatter);

        $handlerProblemy = new StreamHandler(self::LOG_FILE__PROBLEMY, Logger::WARNING);
        $zaznamProblemy = "%datetime% %level_name% %message%\n\t%extra%\n";
        $formatter = new LineFormatter($zaznamProblemy, $dateFormat);
        $handlerProblemy->setFormatter($formatter);
        $handlerProblemy->setBubble(true);

        $logProvoz->pushProcessor(new IntrospectionProcessor());
        $logProvoz->pushHandler($handlerProvoz);
        $logProvoz->pushHandler($handlerProblemy);
    }

    public static function setupDebugLoggers(Logger $logUdalost, Logger $logDb, string $dateFormat): void
    {
        if (self::LOG_LADENI) {
            $handlerLadeni = new RotatingFileHandler(self::LOG_FILE__LADENI, 1, Logger::DEBUG);
            $zaznamLadeni = "%datetime% %channel%.%level_name% %message%\n";
            $formatter = new LineFormatter($zaznamLadeni, $dateFormat);
            $handlerLadeni->setFormatter($formatter);

            $logUdalost->pushHandler($handlerLadeni);
            $logDb->pushHandler($handlerLadeni);
        }
    }

    public static function setupConsoleHandler(Logger $logProvoz): void
    {
        $handlerKonzole = new BrowserConsoleHandler(Logger::DEBUG);
        $zaznamKonzole = "%channel%.%level_name% %message%";
        $formatter = new LineFormatter($zaznamKonzole);
        $handlerKonzole->setFormatter($formatter);

        $logProvoz->pushHandler($handlerKonzole);
    }


}