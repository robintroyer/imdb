<?php
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
$view->showButtons($storage->getMovies(), $storage->getSeries(), $storage->getActors(), $storage->getDirectors(), $storage->getPersons());
if (isset($_POST['details'])) {
    $view->showDetails();
}
if (
    isset($_POST['person_details'])
    || isset($_POST['director_details'])
) {
    $view->showPersonDetails();
}
if (isset($_POST['delete_actor'])) {
    $storage->deletePerson($_POST['person_details_id']);
}
if (isset($_POST['delete_director'])) {
    $storage->deletePerson($_POST['director_details_id']);
}
if (isset($_POST['delete_movie'])) {
    $storage->deleteMovie($_POST['details_id']);
}
if (isset($_POST['delete_series'])) {
    $storage->deleteSeries($_POST['details_id']);
}
if (isset($_POST['delete_person'])) {
    $storage->deletePerson($_POST['person_details_id']);
}