<?php

namespace kivweb\Controllers;

use kivweb\Models\MyDatabase;


class SpravaProduktuController implements IController
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
     * @return array Vrací data pro šablonu
     */
    public function show(string $pageTitle):array
    {

        // ziskame data prihlaseneho uzivatele
        if($this->db->isUserLogged()){
            $tplData['userData'] = $this->Basics->loginManager();
            $this->Basics->logoutManager();
        }else{
            $tplData['userData'] = $this->db->getUser(1);
        }

        $tplData['title'] = "SpravaProduktu";



        // logovani
        if($tplData['userData']['id_prava'] == 0){
            $this->db->logger->info("Neprihlaseny->" . $tplData['title']);
        }else{
            $role = $this->db->getRoleById($tplData['userData']['id_prava']);
            $this->db->logger->info($role . "->" . $tplData['title']);
        }

        //sprava na zmenu hesla
        $this->Basics->changePassManager($tplData['userData']);

        //ziskani produktu
        $tplData['products'] = $this->db->getAllProducts();
        $tplData['undoneproducts'] = $this->db->getAllUndoneProducts();

        // edit produktu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $errors = [];
            $file_name = "";
            // zpracovani formulare
            if (isset($_POST['action']) && $_POST['action'] === 'update-product') {

                // validuce jmena
                if (empty($_POST['nazev'])) {
                    $errors['nazev'] = "Jméno produktu je povinné.";
                }

                // Vvalidace popisu
                if (empty($_POST['popis'])) {
                    $errors['popis'] = "Popis produktu je povinný.";
                } else if (strlen($_POST['popis']) > 50) {
                    $errors['popis'] = "Popis produktu nesmí obsahovat více než 50 slov.";
                }

                // validace ceny
                if (empty($_POST['cena'])) {
                    $errors['cena'] = "Cena produktu je povinná.";
                } elseif (!is_numeric($_POST['cena']) || $_POST['cena'] <= 0) {
                    $errors['cena'] = "Cena produktu musí být platné číslo.";
                }

                // validace mnozstvi
                if (empty($_POST['mnozstvi'])) {
                    $errors['mnozstvi'] = "Množství produktu je povinné.";
                } elseif (!is_numeric($_POST['mnozstvi']) || $_POST['mnozstvi'] < 0) {
                    $errors['mnozstvi'] = "Množství produktu musí být platné číslo.";
                }

                // validace fotky
                if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] !== UPLOAD_ERR_OK) {
                    $errors['fileToUpload'] = "Fotka produktu je povinná.";
                } else {
                    $target_dir = "app/images/";
                    $file_name = basename($_FILES['fileToUpload']['name']);
                    $target_file = $target_dir . $file_name;
                    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);
                }

                // vrat chyby
                if (!empty($errors)) {
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    exit();
                }

                // uloz produkt do db
                $this->db->updateProduct($_POST['product_id'], $_POST['nazev'], $_POST['cena'], $_POST['mnozstvi'], $_POST['popis'], $file_name);

                // vrat data
                echo json_encode(['success' => true]);
                exit();
            }
        }

        //pridani produktu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $errors = [];
            $file_name = "";

            // ypracovani formulare
            if (isset($_POST['action']) && $_POST['action'] === 'add-product') {

                // validace jmena
                if (empty($_POST['nazev'])) {
                    $errors['nazev'] = "Jméno produktu je povinné.";
                }

                // validace pipisu
                if (empty($_POST['popis'])) {
                    $errors['popis'] = "Popis produktu je povinný.";
                } else if (str_word_count($_POST['popis']) > 50) {
                    $errors['popis'] = "Popis produktu nesmí obsahovat více než 50 slov.";
                }

                // Vvalidace ceny
                if (empty($_POST['cena'])) {
                    $errors['cena'] = "Cena produktu je povinná.";
                } elseif (!is_numeric($_POST['cena']) || $_POST['cena'] <= 0) {
                    $errors['cena'] = "Cena produktu musí být platné číslo.";
                }

                // validace mnozstvi
                if (empty($_POST['mnozstvi'])) {
                    $errors['mnozstvi'] = "Množství produktu je povinné.";
                } elseif (!is_numeric($_POST['mnozstvi']) || $_POST['mnozstvi'] < 0) {
                    $errors['mnozstvi'] = "Množství produktu musí být platné číslo.";
                }

                // validace fotky
                if (!isset($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'] !== UPLOAD_ERR_OK) {
                    $errors['fileToUpload'] = "Fotka produktu je povinná.";
                } else {
                    $target_dir = "app/images/";
                    $file_name = basename($_FILES['fileToUpload']['name']);
                    $target_file = $target_dir . $file_name;
                    move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file);
                }

                // vrat chyby
                if (!empty($errors)) {
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    exit();
                }

                // uloz produkt do db
                $this->db->addProduct($_POST['nazev'], $_POST['cena'], $_POST['mnozstvi'], $_POST['popis'], $file_name);

                // vrat data
                echo json_encode(['success' => true]);
                exit();
            }
        }

        //smazani produktu
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (isset($data['action']) && $data['action'] === 'delete-product') {
                //vymaz produkt
                $this->db->deleteFromTable(TABLE_PRODUKT, "id_produkt = :kid_produkt", ["kid_produkt" => $data['id']]);
                // vrat data
                echo json_encode(['success' => true]);
                exit();
            }
        }

        //dostan data o produktu z db
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);


            if (isset($data['action']) && $data['action'] === 'get-product') {
                if (!isset($data['id_produkt'])) {
                    echo json_encode(['success' => false, 'message' => 'ID produktu není zadáno.']);
                    exit();
                }

                // nacteni produktu z db
                $productId = intval($data['id_produkt']);
                $product = $this->db->getProductById($productId); // Metoda pro načtení produktu

                //vrat data
                if ($product) {
                    echo json_encode(['success' => true, 'data' => $product]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Produkt nebyl nalezen.']);
                }
                exit();
            }
        }

        return $tplData;
    }
}