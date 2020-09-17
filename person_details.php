<?php
if (is_readable(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
} else {
    die('Konfigurationsdatei nicht gefunden');
}
session_start();
require __DIR__ . '/vendor/autoload.php';

$storage = new Database();
$configDB = new stdClass();
$configDB->host = $DB_HOST;
$configDB->user = $DB_USER;
$configDB->pass = $DB_PASS;
$configDB->name = $DB_NAME;
$storage->initialize($configDB);
$form = new Form($storage);
$view = new View($storage);

$view->personDetailsPage();
if (isset($_POST['remove_movie_from_actor'])) {
    $storage->removeMovieFromActor($_POST['entry_details_id'], $_POST['entry_id']);
}
if (isset($_POST['remove_series_from_actor'])) {
    $storage->removeSeriesFromActor($_POST['entry_details_id'], $_POST['entry_id']);
}
if (isset($_POST['remove_movie_from_director'])) {
    $storage->removeMovieFromDirector($_POST['entry_details_id'], $_POST['entry_id']);
}
if (isset($_POST['remove_series_from_director'])) {
    $storage->removeSeriesFromDirector($_POST['entry_details_id'], $_POST['entry_id']);
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