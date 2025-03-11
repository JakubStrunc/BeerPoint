<?php

namespace kivweb\Controllers;

use kivweb\Models\MyDatabase;


class KosikController implements IController
{

    /** @var MyDatabase $db  Sprava databaze. */
    private MyDatabase $db;


    /** @var Basics */
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
     * @return array vraci data pro sablonu
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


        $tplData['title'] = "Kosik";



        // logovani
        if($tplData['userData']['id_prava'] == 0){
            $this->db->logger->info("Neprihlaseny->" . $tplData['title']);
        }else{
            $role = $this->db->getRoleById($tplData['userData']['id_prava']);
            $this->db->logger->info($role . "->" . $tplData['title']);
        }

        //sprava na zmenu hesla
        $this->Basics->changePassManager($tplData['userData']);

        //ziskani kosiku
        $tplData['kosik'] = $this->db->getCard($tplData['userData']['id_uzivatel']);
        if ($tplData['kosik'] == null) {
            $this->db->loggerUdalosti->alert("kosik je prazdny");
            $this->db->createCard($tplData['userData']['id_uzivatel']);
            $tplData['kosik'] = $this->db->getCard($tplData['userData']['id_uzivatel']);
        }

        //hodnota k total price
        $tplData['totalPrice'] = 0;

        // produkty v kosiku
        $tplData['kosikProducts'] = $this->db->getProductsByOrderId($tplData['kosik']['id_objednavka']);

        // vypocitej total cenu kosiku
        if ($tplData['kosikProducts']) {
            foreach ($tplData['kosikProducts'] as $product) {
                $tplData['totalPrice'] += $product['cena'] * $product['pocet'];
            }
        }

        // odeslani objednavky
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (isset($data['action']) && $data['action'] == 'send-order') {
                //var_dump($_POST['primeni']);

                // validace udaju
                if (
                    isset($data['jmeno']) && isset($data['primeni']) && isset($data['ulice']) &&
                    isset($data['cislo_popisne']) && isset($data['orientacni_cislo']) &&
                    isset($data['psc']) && isset($data['mesto']) && isset($data['telefonni_cislo']) &&
                    $data['jmeno'] != "" && $data['primeni'] != "" && $data['ulice'] != "" &&
                    $data['cislo_popisne'] != "" && $data['orientacni_cislo'] != "" &&
                    $data['psc'] != "" && $data['mesto'] != "" && $data['telefonni_cislo'] != ""
                ) {

                    //updatuj objednavku ke zpracovani
                    if(!($this->db->updateOrderDetails($data["kosik_id"], $data['jmeno'], $data['primeni'], $data['ulice'],
                        $data['cislo_popisne'], $data['orientacni_cislo'], $data['psc'], $data['mesto'], $data['telefonni_cislo']))) {
                        echo json_encode(['success' => true]);
                    } else {
                        echo json_encode(['success' => false]);
                    }
                    exit;

                }
                else{
                    $this->db->loggerUdalosti->warning("Vsechna data nejsou vyplnena");
                }
            }
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            //vymazani produktu z kosiku
            if(isset($data['action']) && $data['action'] == 'delete-product') {

                // smaz produkt
                if (!($this->db->deleteFromTable(TABLE_OBSAHUJE, "id_produkt = :kid_produkt", ["kid_produkt" => $data['id']]))) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
                exit;
            }
        }

        // update mnozstvi
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);


            if (isset($data['action']) && $data['action'] === 'update-quantity') {
                $quantity = $data['quantity'];

                // validuj inputy
                if (!is_numeric($quantity) || $quantity < 0) {
                    echo json_encode(['success' => false, 'error' => 'Neplatné množství.']);
                    exit();
                }

                // dostan produkt
                $this->db->getCard($tplData['userData']['id_uzivatel']);
                $product = $this->db->getProductById($data['id_produkt']);


                // vypictej novou cenu pro produkt
                $productTotal = $product['cena'] * $data['quantity'];

                // updatuj mnozstvi v db
                $this->db->updateCardQuantity($data['id_produkt'], $data['quantity']);

                // vypocitej znovu total price
                $kosikProducts = $this->db->getProductsByOrderId($tplData['kosik']['id_objednavka']);
                //var_dump($kosikProducts);
                $orderTotal = 0;
                foreach ($kosikProducts as $kosikProduct) {
                    $orderTotal += $kosikProduct['cena'] * $kosikProduct['pocet'];
                }


                // vrat data
                echo json_encode([
                    'success' => true,
                    'productTotal' => $productTotal,
                    'orderTotal' => $orderTotal
                ]);
                exit();
            }
        }

        return $tplData;
    }
}