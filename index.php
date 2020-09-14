<?php
// echo '<script src="script.js"></script>';
$storage = new Database();
$configDB = new stdClass();
$configDB->host = $DB_HOST;
$configDB->user = $DB_USER;
$configDB->pass = $DB_PASS;
$configDB->name = $DB_NAME;
$storage->initialize($configDB);
$form = new Form($storage);
$view = new View($storage);

$form->newPerson();
$form->newMovie();
// print_r($storage->getMovies());
$view->showMovies($storage->getMovies());

