<?php

namespace kivweb\Controllers;

use kivweb\Models\MyDatabase;


class ObjednavkyController implements IController
{

    /** @var MyDatabase $db  Sprava databaze. */
    private MyDatabase $db;
    /**
     * @var Basics
     */
    private Basics $Basics;

    /**
     * inicializace db
     */
    public function __construct() {

        $this->db = new MyDatabase();
        $this->Basics = new Basics();
    }

    /**
     * funkce pro ziskavani dat z db
     * @param string $pageTitle nazev stranky
     * @return array Vrací data pro šablonu
     */
    public function show(string $pageTitle):array
    {
        // ziskame data prihlaseneho uzivatele
        if($this->db->isUserLogged()){
            $tplData['userData'] = $this->Basics->loginManager();
            $this->Basics->logoutManager();
        }else{
            $tplData['userData'] = $this->db->getUser(3);
        }


        $tplData['title'] = "Home";

        // logovani
        if($tplData['userData']['id_prava'] == 0){
            $this->db->logger->info("Neprihlaseny->" . $tplData['title']);
        }else{
            $role = $this->db->getRoleById($tplData['userData']['id_prava']);
            $this->db->logger->info($role . "->" . $tplData['title']);
        }

        //sprava na zmenu hesla
        $this->Basics->changePassManager($tplData['userData']);

        //ziskani objednavek uzivatele
        $orders = $this->db->getOrdersById($tplData['userData']['id_uzivatel']);
        if ($orders) {
            // ziskani produktu objednavky
            foreach ($orders as &$order) {
                $order['products'] = $this->db->getProductsByOrderId($order['id_objednavka']);

                // vypocitej total price
                $order['totalPrice'] = 0;
                foreach ($order['products'] as $product) {
                    $order['totalPrice'] += $product['cena'] * $product['pocet'];
                }
            }
        }

        //uloz data
        $tplData['orders'] = $orders;


        return $tplData;
    }
}