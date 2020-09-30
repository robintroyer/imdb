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
// $bacon = new Bacon($storage);
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
if (isset($_POST['submit_bacon'])) {
    $bacon = new Bacon($storage);
    // $bacon->getGenerations($_POST['first']);
    $bacon_number = $bacon->createArray($_POST['first']);
    if ($bacon_number !== false) {
        echo 'Bacon Nummer: ' . $bacon_number;
    } else {
        echo 'Bacon Nummer existiert nicht.';
    }
    // $b = $bacon->getRelation($_POST['first'], $_POST['second']);
    // if (is_array($b)) {
    //     $c = $bacon->changeArray();
    // }
}
if (isset($_POST['actor_submit'])) {
    $bacon = new Bacon($storage);
    $bacon->theMovieDB($_POST['actor_name']);
}