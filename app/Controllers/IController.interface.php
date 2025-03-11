<?php

namespace kivweb\Controllers;
/**
 * Rozhrani pro vsechny ovladace (kontrolery).
 */
interface IController {

    /**
     * Zajisti vypsani prislusne stranky.
     *
     * @param string $pageTitle     Nazev stanky.
     * @return array           HTML prislusne stranky.
     */
    public function show(string $pageTitle):array;

}

?>