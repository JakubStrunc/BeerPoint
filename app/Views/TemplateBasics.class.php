<?php
namespace kivweb\Views;
use phpDocumentor\Plugin\Scrybe\Template\TemplateInterface;


/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 */
class TemplateBasics implements IView{
//class TemplateBasics{


    const string PAGE_HOME = "HomeTemplate.twig";

    const string PAGE_KOSIK = "KosikTemplate.twig";

    const string PAGE_OBJEDNAVKY = "ObjednavkyTemplate.twig";

    const string PAGE_SPRAVAOBJEDNAVEK = "SpravaObjednavekTemplate.twig";

    const string PAGE_SPRAVAUZIVATELU = "SpravaUzivateluTemplate.twig";

    const string PAGE_SPRAVAPRODUKTU = "SpravaProduktuTemplate.twig";



    /**
     *  vygeneruje stranku.
     *  @param array $templateData    data ke strance
     *  @param string $pageType    stranka template
     */
    public function printOutput(array $templateData, string $pageType = self::PAGE_HOME):void
    {


        //// vypis sablony obsahu
        // data pro sablonu nastavim globalni
        global $tplData;
        $tplData = $templateData;
        //var_dump($tplData);
        //// vypis hlavicky
        $this->getHTMLHeader($templateData['title']);
        // nactu sablonu
        //require_once($pageType);

        //// vypis pacicky
        $this->getHTMLFooter();

    }
    /**
     *  vrati vrsek stranky az po oblast, ve ktere se vypisuje obsah stranky
     *  @param string $pageTitle    Nazev stranky.
     */
    public function getHTMLHeader(string $pageTitle):void {
        global $tplData; ?>

        <!doctype html>
        <html lang="cs">
        <head>
            <meta charset="utf-8">
            <title><?php echo $pageTitle; ?></title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
                  rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        </head>

        <!-- Background image -->
        <style>
            main {
                background-image: url('/app/images/backgroundbeeropoint.jpg');

            }
        </style>
        <script src="../../node_modules/jquery/dist/jquery.min.js"></script>

        <!doctype html>
        <html lang="cs">

        <body>
        <?php
         //var_dump($tplData);

        //navbar stranky
         $this->NavBar($tplData['userData']);?>

        <main>

        <?php
         //modal na zmenu hesla
         $this->userChangePass();
         ?>


        <?php
    }


    /**
     *  vrati paticku stranky
     */
    public function getHTMLFooter(){
        ?>
        <footer class="bg-dark text-white text-center py-3" style="height: 10vh;">
            <div class="container">
                <div class="mt-3">
                    <span id="spolecne_pati_predmet">&copy; KIV/WEB</span>;
                    <span id="spolecne_pati_autor">Jakub Štrunc</span>;
                    <span id="spolecne_pati_rok">© 2024</span>
                </div>
            </div>
        </footer>

        <!-- JavaScript soubory -->
        <script src="/app/JS/HomeJS.js"></script>
        <script src="/app/JS/KosikJS.js"></script>
        <script src="/app/JS/AlertJS.js"></script>
        <script src="/app/JS/ZmenaHeslaJS.js"></script>
        <script src="/app/JS/SpravaObjednavekJS.js"></script>
        <script src="/app/JS/SpravaUzivateluJS.js"></script>
        <script src="/app/JS/SpravaProduktuJS.js"></script>

        <?php
    }

    /**
     *  vrati navbar stranky
     *  @param array $user    uzivatel
     */
    public function NavBar(array $user):void {
        ?>

        <nav class="navbar navbar-dark bg-dark fixed-top">


            <div class="container-fluid">
                <!-- jmeno a log -->
                <a class="navbar-brand" id="nadpis" href="#">
                    <img src="../../app/images/Logo.png"
                         class="bd-placeholder-img"
                         style="width: auto; height: 50px; object-fit: cover;"
                         alt="logo">
                     BeerPoint
                </a>

                <!-- itemy navbaru -->
                <div class="d-none d-lg-flex">
                <?php
                    if($user['id_prava'] == 1){
                        $this->getAdminNavBarItems($user['uzivatelske_jmeno']);
                    }
                    elseif ($user['id_prava'] == 3){
                        $this->getUserNavBarItems($user['uzivatelske_jmeno']);
                    }
                    elseif ($user['id_prava'] == 2){
                        $this->getSellerNavBarItems($user['uzivatelske_jmeno']);
                    }
                    else{
                        $this->getUnregisteredNavBarItems();
                    }

                ?>
                </div>

                <!-- navbary pro mensi okno -->
                <div class="d-none d-sm-flex d-lg-none text-bg-dark">
                    <!-- navbar pro neprihlasenyho -->
                    <?php if(empty($user['uzivatelske_jmeno'])) {
                    $this->getUnregisteredNavBarItems();
                    }else{ ?>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- navbar itemy -->
                    <div class="offcanvas offcanvas-end text-bg-dark" style="width: 400px;" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title fs-4" id="offcanvasDarkNavbarLabel"><?php echo $user['uzivatelske_jmeno'] ?></h5> <!-- Increased text size -->
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <?php
                        if ($user['id_prava'] == 1) { // Admin Role
                            ?>
                            <li class="nav-item">
                                <a href="/index.php?page=Home" id="home" class="nav-link px-2 text-white fs-5">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="/index.php?page=spravaProduktu" id="sprava_produktu" class="nav-link px-2 text-white fs-5">Správa produktů</a>
                            </li>
                            <li class="nav-item">
                                <a href="/index.php?page=spravaObjednavek" id="sprava_objednavek" class="nav-link px-2 text-white fs-5">Správa Objednávek</a>
                            </li>
                            <?php
                        } elseif ($user['id_prava'] == 2) { // Seller Role
                            ?>
                            <li class="nav-item">
                                <a href="/index.php?page=Home" id="home" class="nav-link px-2 text-white fs-5">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="/index.php?page=spravaProduktu" id="sprava_produktu" class="nav-link px-2 text-white fs-5">Správa produktů</a>
                            </li>
                            <li class="nav-item">
                                <a href="/index.php?page=spravaObjednavek" id="sprava_objednavek" class="nav-link px-2 text-white fs-5">Správa Objednávek</a>
                            </li>
                            <?php
                        } elseif ($user['id_prava'] == 3) { // User Role
                            ?>
                            <li class="nav-item">
                                <a href="/index.php?page=Home" id="home" class="nav-link px-2 text-white fs-5">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="/index.php?page=kosik" id="kosik" class="nav-link px-2 text-white fs-5">Košík</a>
                            </li>
                            <li class="nav-item">
                                <a href="/index.php?page=objednavky" id="objednavky" class="nav-link px-2 text-white fs-5">Objednávky</a>
                            </li>
                            <?php } ?>
                                <hr class="my-4">
                                <li class="nav-item">
                                    <input type="button" data-bs-toggle="modal" class="dropdown-item mb-4 fs-5" value="Změnit Heslo" data-bs-target="#changePassModal"> <!-- Larger text and margin -->
                                </li>
                                <li class="nav-item">
                                    <form method="POST">
                                        <input type="submit" id="odhlasit_se" name="odhlasit" class="dropdown-item text-danger fs-5" value="Odhlásit se"> <!-- Larger text -->
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
                </div>


            </div>
        </nav>


        <?php
    }

    /**
     *  vrati navbar itemy pro neprihlasenyho
     */
    private function getUnregisteredNavBarItems() {
        ?>
        <div class="text_end">
            <button type="button" class="btn btn-light mx-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                Login
            </button>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#registerModal">
                Create account
            </button>

        </div>




        <?php
    }
    /**
     *  vrati navbar itemy pro user
     * @param string $user
     */
    private function getUserNavBarItems(string $user):void {
        ?>
        <div class="d-flex ms-auto">
            <ul class="nav">
                <li><a href="/index.php?page=Home" id="home" class="nav-link px-2 text-white">Home</a></li>
                <li><a href="/index.php?page=kosik" id="kosik" class="nav-link px-2 text-white">Košík</a></li>
                <li><a href="/index.php?page=objednavky" id="objednavky" class="nav-link px-2 text-white">Objednávky</a></li>
            </ul>

            <?php $this->userDropDown($user) ?>
        </div>

        <?php
    }

    /**
     *  vrati navbar itemy pro prodejce
     * @param string $user
     */
    private function getSellerNavBarItems(string $user):void {
        ?>

        <div class="d-flex ms-auto">
            <ul class="nav">
                <li><a href="/index.php?page=Home" id="home" class="nav-link px-2 text-white">Home</a></li>
                <li><a href="/index.php?page=spravaProduktu" id="sprava_produktu" class="nav-link px-2 text-white">Správa produktů</a></li>
                <li><a href="/index.php?page=spravaObjednavek" id="sprava_objednavek" class="nav-link px-2 text-white">Správa Objednávek</a></li>
            </ul>

            <?php $this->userDropDown($user) ?>
        </div>

        <?php
    }
    /**
     *  vrati navbar itemy pro admina
     * @param string $user
     */
    private function getAdminNavBarItems(string $user):void {
        ?>
        <div class="d-flex ms-auto">
            <ul class="nav">
                <li><a href="/index.php?page=Home" id="home" class="nav-link px-2 text-white">Home</a></li>
                <li><a href="/index.php?page=spravaProduktu" id="sprava_produktu" class="nav-link px-2 text-white">Správa produktů</a></li>
                <li><a href="/index.php?page=spravaObjednavek" id="sprava_objednavek" class="nav-link px-2 text-white">Správa Objednávek</a></li>
                <li><a href="/index.php?page=spravaUzivatelu" id="sprava_uzivatelu" class="nav-link px-2 text-white">Správa Uživatelů</a></li>
            </ul>

            <?php $this->userDropDown($user) ?>
        </div>

        <?php
    }
    /**
     *  vrati itemy pro drpdown menu
     * @param string $user // informace o uzivetely
     */
    private function userDropDown(string $user):void {
        ?>

        <div class="dropdown ms-3">
            <button class="navbar-toggler dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                </svg>

                <?php echo $user ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                <li><input type="button" data-bs-toggle="modal" class="dropdown-item" value="Změnit Heslo" data-bs-target="#changePassModal"></li>
                <li><form method="POST"><input type="submit" id="odhlasit_se" name="odhlasit" class="dropdown-item text-danger" value="Odhlásit se"></form></li>


            </ul>
        </div>

        <?php
    }

    /**
     *  modal okno pro zmenu hesla
     */
    public function userChangePass() {?>
        <div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Změna hesla</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation mb-6" method="POST" novalidate="" action="">
                        <div class="container">
                            <!-- Heslo txt pole -->
                            <div class="mb-3">
                                <label for="formPassword" class="form-label">Staré heslo</label>
                                <input type="password" class="form-control" id="formPassword" name="heslo">
                            </div>
                            <!-- Potvrzeni hesla txt pole -->
                            <div class="mb-3">
                                <label for="formConfirmPassword" class="form-label">Potvrzení starého hesla</label>
                                <input type="password" class="form-control" id="formConfirmPassword" name="heslo2">
                            </div>
                            <!-- Nove heslo txt pole -->
                            <div class="mb-3">
                                <label for="formNewPassword" class="form-label">Nové heslo</label>
                                <input type="password" class="form-control" id="formNewPassword" name="noveheslo">
                            </div>
                            <!-- Potvrzeni noveho hesla txt pole -->
                            <div class="mb-3">
                                <label for="formConfirmNewPassword" class="form-label">Potvrzení nového hesla</label>
                                <input type="password" class="form-control" id="formConfirmNewPassword" name="noveheslo2">
                            </div>
                        </div>
                        <!-- Footer btns -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" id="btn_ulozit" class="btn btn-warning" value="Změnit Heslo">

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
    }

}

?>