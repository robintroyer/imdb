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
$bacon = new Bacon($storage);
$bacon->theMovieDBForm();
if (isset($_GET['actor_name'])) {
    $bacon->theMovieDB($_GET['actor_name']);
}
if (isset($_GET['movie'])) {
    $bacon->showMovie($_GET['movie']);
}
if (isset($_GET['series'])) {
    $bacon->showSeries($_GET['series']);
}
if (isset($_GET['actor_id'])) {
    $bacon->theMovieDB($bacon->getActorName($_GET['actor_id']));
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