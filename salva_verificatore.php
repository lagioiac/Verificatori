<?php
include 'config.php';
include 'include/check_user.php';
include 'db/mysql.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db = new DbConnect();
$db->open() or die($db->error());

require 'class/PersonaleUOTClass.php';

$personaleUOTClass = new PersonaleUOTClass();

session_start();
$isAdmin = isset($_SESSION['flgAdmin']) && $_SESSION['flgAdmin'] == 1;

if (!$isAdmin) {
    die("Accesso non autorizzato.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post = array(
        'IDXF' => $_POST['IDXF'],
        'cognome' => $_POST['cognome'],
        'nome' => $_POST['nome'],
        'iduot' => $_POST['iduot'],
        'idqualifica' => $_POST['idqualifica'],
        'cell' => $_POST['cell'],
        'telfisso' => $_POST['telfisso'],
        'email' => $_POST['email'],
        'flgP' => isset($_POST['flgP']) ? 1 : 0,
        'flgS' => isset($_POST['flgS']) ? 1 : 0,
        'flgR' => isset($_POST['flgR']) ? 1 : 0,
        'flgT' => isset($_POST['flgT']) ? 1 : 0,
        'flgAltreUot' => isset($_POST['flgAltreUot']) ? 1 : 0,
        'disponibile' => $_POST['disponibile'],
        'note' => $_POST['note'],
        'idruolo' => $_POST['idruolo']
    );

    $result = $personaleUOTClass->updatePersonaleUOT($db, $post);

    if ($result) {
        header("Location: verificatori.php?succes");
        exit();
    } else {
        echo "Errore nel salvataggio del verificatore.";
    }
} else {
    echo "Richiesta non valida.";
}
?>

