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
$movie = $storage->getSingleMovie($_GET['id']);
// print_r($movie);
echo '<h1>' . $movie->getTitle() . '</h1>';
$actors = $storage->getActorsOfMovie($_GET['id']);
// print_r($actors);
echo '<ul class="list-group">';
foreach ($actors as $actor) {
    // echo '<li class="list-group-item>' . $actor->getName() . '</li>';
    echo '<li class="list-group-item">' . $actor->getName() . '</li>';
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