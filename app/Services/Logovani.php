<?php

namespace kivweb\Services;

use app\Config\LogConfiguration;
use Monolog\Logger;


class Logovani
{
    /** @var Logovani $logovani */
    private static $logovani = null;

    /** @var Logger $logProvoz */
    public $logProvoz;

    /** @var Logger $logUdalost */
    public $logUdalost;

    /** @var Logger $logDb */
    public $logDb;

    public function __construct()
    {
        $this->nastavLogovani();
    }

    public static function getLogovani() : Logovani
    {
        if (self::$logovani == null) {
            self::$logovani = new Logovani();
        }
        return self::$logovani;
    }

    public function nastavLogovani() : void
    {
        $dateFormat = "Y-m-d H:i:s";

        $this->logProvoz = new Logger('provoz');


        $this->logUdalost = new Logger('udalosti');
        $this->logDb = new Logger('databaze');

// ---------------------------------------------------------------------------------------------------------------
//---------------------------Odkomentovat níže po vytvoření LogConfiguration--------------------------------------
//----------------------------------------------------------------------------------------------------------------
       LogConfiguration::setup($this->logProvoz, $dateFormat);
       LogConfiguration::setupDebugLoggers($this->logUdalost, $this->logDb, $dateFormat);
       LogConfiguration::setupConsoleHandler($this->logProvoz);
    }
}