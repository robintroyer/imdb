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


// $this->storage->getSingleMovie($)
// echo $_GET['title'];

if ($_GET['type'] == 'movie') {
    $details = $storage->getSingleMovie($_GET['id']);
    $actors = $storage->getActorsOfMovie($_GET['id']);
    $directors = $storage->getDirectorsOfMovie($_GET['id']);
} elseif ($_GET['type'] == 'series') {
    $details = $storage->getSingleSeries($_GET['id']);
    $actors = $storage->getActorsOfSeries($_GET['id']);
    $directors = $storage->getDirectorsOfSeries($_GET['id']);
}


echo '<h1>' . $details->getTitle() . '</h1>';
echo '<ul class="list-group">';
foreach ($actors as $actor) {
    echo '<li class="list-group-item">' . $actor->getName() . '</li>';
}
echo '</ul>';
echo '<br />';
echo '<h3>Regisseur/Produzent</h3>';
echo '<ul class="list-group">';
foreach ($directors as $director) {
    echo '<li class="list-group-item">' . $director->getName() . '</li>';
}
echo '</ul>';

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