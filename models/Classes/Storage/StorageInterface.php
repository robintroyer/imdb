<?php
interface StorageInterface
{
    public function initialize($config);
    public function savePerson($person);
    public function saveMovie($movie, $actors, $directors);
    public function saveSeries($series, $actors, $directors);
    public function getPersons();
    public function getMovies();
    public function getSeries();
    public function getSinglePerson($id);
    public function getSingleMovie($id);
    public function getSingleSeries($id);
    public function getActorsOfMovie($movie);
    public function getActorsOfSeries($series);
    public function getDirectorsOfMovie($movie);
    public function getDirectorsOfSeries($series);
}