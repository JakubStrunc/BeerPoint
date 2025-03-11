<?php

// zakladni nazev namespace, ktery bude pri registraci nahrazen za vychozi adresar aplikace
// pozn.: lze presunout do settings (zde ponechano pro nazornost)
/** @var string BASE_NAMESPACE_NAME  Zakladni namespace. */
const BASE_NAMESPACE_NAME = "kivweb";
/** @var string BASE_APP_DIR_NAME  Vychozi adresar aplikace. */
const BASE_APP_DIR_NAME = "app";

/** @var array FILE_EXTENSIONS  Dostupne pripony souboru, ktere budou testovany pri nacitani souboru pozadovanych trid. */
const FILE_EXTENSIONS = [".class.php", ".interface.php"];


//// automaticka registrace pozadovanych trid
// ukazana slozitejsi varianta,
// protoze namespaces zacinaji vlastnim nazvem (namisto nazvu vychoziho adresare)
// a soubory nemaji jednotnou priponu (ale maji pripony .class.php nebo .interface.php)
spl_autoload_register(function ($className) {
    if (strncmp(BASE_NAMESPACE_NAME, $className, strlen(BASE_NAMESPACE_NAME)) !== 0) {
        return;
    }

    $relativeClass = substr($className, strlen(BASE_NAMESPACE_NAME));
    $relativeClass = str_replace("\\", DIRECTORY_SEPARATOR, $relativeClass);
    $fileBase = __DIR__ . DIRECTORY_SEPARATOR . BASE_APP_DIR_NAME . $relativeClass;

    foreach (FILE_EXTENSIONS as $extension) {
        $file = $fileBase . $extension;
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }

});



?>
