<?php

namespace kivweb\Controllers;

use kivweb\Models\MyDatabase;



class Basics
{
    /** @var MyDatabase $db */
    private MyDatabase $db;

    /**
     * inicializace db
     */
    public function __construct() {

        $this->db = new MyDatabase();
    }

    /**
     * funkce pro spravu prihlaseni
     *
     */
    public function loginManager():array
    {


        // je uzivatel prihlasen
        if ($this->db->isUserLogged()) {
            //dostan data
            $tplData = $this->db->getLoggedUserData();
        } else {
            $tplData['id_prava'] = 0;
        }

        return $tplData;
    }

    /**
     * funkce pro spravu odhlaseni uzivatele
     */
    public function logoutManager(): void
    {
        if (isset($_POST['odhlasit'])) {
            //odhlasime uzivatele
            $this->db->userLogOut();
            header("Location: index.php?page=Home");
            exit();

        }
    }

    /**
     * @param array $user // uzivatel
     */
    public function changePassManager( array $user): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //pripraveni odpovedi na json
            header('Content-Type: application/json');

            //nacteme data pres post
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData, true);

            if (isset($data['action']) && $data['action'] === 'change-password') {
                // Inicializace chybovych zprav
                $errors = [
                    'oldPassword' => null,
                    'confirmOldPassword' => null,
                    'newPassword' => null,
                    'confirmNewPassword' => null
                ];
                // validace stareho hesla
                if (empty($data['oldPassword'])) {
                    $errors['oldPassword'] = "Zadejte staré heslo.";
                }
                // kontrola hesel z db
                if (!(password_verify($data["oldPassword"], $user['heslo']))) {
                    //var_dump($userData);
                    $errors['oldPassword'] = "Špatné heslo.";
                }

                // validace a potvrzeni stareho hesla
                if (empty($data['confirmOldPassword'])) {
                    $errors['confirmOldPassword'] = "Potvrďte staré heslo.";
                } elseif ($data['confirmOldPassword'] !== $data['oldPassword']) {
                    $errors['confirmOldPassword'] = "Staré heslo a potvrzení se neshodují.";
                }


                // validace noveho hesla
                if (empty($data['newPassword'])) {
                    $errors['newPassword'] = "Zadejte nové heslo.";
                }

                // validace a potvrzeni noveho hesla
                if (empty($data['confirmNewPassword'])) {
                    $errors['confirmNewPassword'] = "Potvrďte nové heslo.";
                } elseif ($data['confirmNewPassword'] !== $data['newPassword']) {
                    $errors['confirmNewPassword'] = "Nové heslo a potvrzení se neshodují.";
                }

                // chybova message
                if (!empty(array_filter($errors))) {
                    echo json_encode(['success' => false, 'errors' => $errors]);
                    exit();
                }


                // aktualizace hesla
                $newPassword = password_hash($data['newPassword'], PASSWORD_DEFAULT);
                $this->db->updateUserPassword($user['id_uzivatel'], $newPassword);
                echo json_encode(['success' => true, 'message' => "Heslo bylo úspěšně změněno."]);
                exit();
            }
        }
    }
}