<?php

namespace kivweb\Controllers;

use kivweb\Models\MyDatabase;

require_once ("vendor/autoload.php");

//use kivweb\Services\Logovani;
//require_once ("app/Services/Logovani.php");
//require_once ("vendor/autoload.php");


class HomeController implements IController
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

//        $logovani = $_SESSION['logovani'];
//        $this->logger = $logovani->logProvoz;

    }

    /**
     * funkce pro ziskavani dat z db
     * @param string $pageTitle nazev stranky
     * @return array vraci data pro sablonu
     */
    public function show(string $pageTitle):array
    {

        // ziskame data prihlaseneho uzivatele
        $tplData['userData'] = $this->Basics->loginManager();
        $this->Basics->logoutManager();


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

        //ziskani katalogu
        $tplData['products'] = $this->db->getAllProducts();
        if(empty($tplData['products'])){
            $this->db->loggerUdalosti->error("Zadne prodkty nejsou na shopu");
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            header('Content-Type: application/json');

            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            //pozadavek pridani do kosiku
            if(isset($data['action']) && $data['action'] == 'add-product') {
                //var_dump($_POST["id_produkt"]);

                //kosik uzivatele
                $kosik = $this->db->getCard($tplData['userData']['id_uzivatel']);
                //var_dump($kosik);

                //vytvoreni kosiku pokud neexisuje
                if ($kosik == null) {
                    $this->db->loggerUdalosti->alert("kosik je prazdny");
                    $this->db->createCard($tplData['userData']['id_uzivatel']);
                    $kosik = $this->db->getCard($tplData['userData']['id_uzivatel']);
                }

                //existuje produkt v kosiku
                if(!($this->db->isProductInCard($kosik['id_objednavka'],$data['id']))) {
                    $this->db->loggerUdalosti->alert("Produkt je v kosiku uz");
                    $this->db->addToCard($kosik['id_objednavka'], $data['id']);
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            $errors = []; // pole chyb


            if (isset($data['action']) && $data['action'] == 'register') {
                // validace uzivatelskeho jmena
                if (empty($data['uzivatelske_jmeno'])) {
                    $errors['uzivatelske_jmeno'] = "Uživatelské jméno je povinné.";
                }

                // validace emailu
                if (empty($data['email'])) {
                    $errors['email'] = "Email je povinný.";
                } else  {
                    foreach ($this->db->getAllUsers() as $user) {
                        //var_dump($user);
                        if($user['email'] == $data['email']) {
                            //var_dump($user['email']);
                            $errors['email'] = "Emailová adresa je už registrována.";
                            break;
                        }
                    }
                }


                // validace hesla
                if (empty($data['heslo'])) {
                    $errors['heslo'] = "Heslo je povinné.";
                }

                // validace potvrzeni hesla
                if (empty($data['heslo2'])) {
                    $errors['heslo2'] = "Potvrzení hesla je povinné.";
                } elseif ($data['heslo'] != $data['heslo2']) {
                    $errors['heslo2'] = "Hesla se neshodují.";
                }

                // vraceni chyb
                if (!empty($errors)) {
                    echo json_encode(['success' => false, 'error' => $errors]);
                    exit();
                }

                //ulozeni uzivatele do db
                $hashed_pass = password_hash($data['heslo'], PASSWORD_DEFAULT);
                $this->db->addNewUser($data['email'],$data['uzivatelske_jmeno'], $hashed_pass );
                $user = $this->db->userLoginEmail($data["email"]);
                $this->db->userLoginPass($data['heslo'], $user);

                echo json_encode(['success' => true]);
                exit();
            }
        }


        // zpracovani pozadavku pro prihlaseni
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);


            if(isset($data['action']) && $data['action'] == 'login') {

                //chybovnik
                $errors = ['email' => null, 'heslo' => null];

                // validace emailu
                if (empty($data["email"])) {
                    $errors['email'] = "E-mail je povinný.";
                }

                // validace hesla
                if (empty($data['heslo'])) {
                    $errors['heslo'] = "Heslo je povinné.";
                }

                // vratime chyby
                if (!empty(array_filter($errors))) {
                    echo json_encode(['success' => false, 'error' => $errors]);
                    exit();
                }

                //prihlas uzivatele
                $user = $this->db->userLoginEmail($data["email"]);
                if($user){
                    //var_dump($user);
                    if($this->db->userLoginPass($data['heslo'], $user)) {
                        $this->db->logger->notice("Neprihlaseni->Registrace->Uspesne prihlaseni");
                        //var_dump($userData);
                        echo json_encode(['success' => true]);
                    }else{
                        //var_dump($user);
                        echo json_encode(['success' => false, 'error' => ['email' => null, 'heslo' => "Špatné heslo."]]);
                    }
                }else{
                    echo json_encode(['success' => false, 'error' => ['email' => "Uživatel s tímto e-mailem neexistuje.", 'heslo' => null]]);
                }
                exit();
            }
        }

        return $tplData;
    }
}