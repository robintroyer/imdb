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
    public function getActors();
    public function getDirectors();
    public function getSinglePerson($id);
    public function getSingleMovie($id);
    public function getSingleSeries($id);
    public function getActorsOfMovie($movie);
    public function getActorsOfSeries($series);
    public function getDirectorsOfMovie($movie);
    public function getDirectorsOfSeries($series);
    public function getMoviesOfPerson($id);
    public function getSeriesOfPerson($id);
    public function getDirectedMoviesOfPerson($id);
    public function getDirectedSeriesOfPerson($id);
    public function deletePerson($person);
    public function deleteMovie($movie);
    public function deleteSeries($series);
    public function deleteActorOfMovie($actor, $movie);
    public function deleteActorOfSeries($actor, $series);
    public function deleteDirectorOfMovie($director, $movie);
    public function deleteDirectorOfSeries($director, $series);
}