<?php
interface StorageInterface
{
    public function initialize($config);
    public function savePerson($person);
    public function saveMovie($movie);
    public function getPersons();
    public function getMovies();
    public function getSinglePerson($id);
    public function getSingleMovie($id);
}