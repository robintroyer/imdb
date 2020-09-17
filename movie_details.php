<?php
if (is_readable(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
} else {
    die('Konfigurationsdatei nicht gefunden');
}
session_start();
require __DIR__ . '/vendor/autoload.php';

echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';

$storage = new Database();
$configDB = new stdClass();
$configDB->host = $DB_HOST;
$configDB->user = $DB_USER;
$configDB->pass = $DB_PASS;
$configDB->name = $DB_NAME;
$storage->initialize($configDB);
$form = new Form($storage);
$view = new View($storage);

$view->detailsPage();
if (isset($_POST['person_details_type'])) {
    if ($_POST['person_details_type'] == 'movie') {
        $storage->deleteActorOfMovie($_POST['person_details_id'], $_GET['id']);
    } elseif ($_POST['person_details_type'] == 'series') {
        $storage->deleteActorOfSeries($_POST['person_details_id'], $_GET['id']);
    }
}
if (isset($_POST['director_details_type'])) {
    if ($_POST['director_details_type'] == 'movie') {
        $storage->deleteDirectorOfMovie($_POST['director_details_id'], $_GET['id']);
    } elseif ($_POST['director_details_type'] == 'series') {
        $storage->deleteDirectorOfSeries($_POST['director_details_id'], $_GET['id']);
    }
}


?>

<!doctype html>
    <html>
        <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        </head>
        <body>
            <button onclick="history.go(-1)">Zurück</button>
        </body>
    </html>