<?php
class Database implements StorageInterface
{
    private $conn;
    public function initialize($config)
    {
        $this->conn = new mysqli($config->host, $config->user, $config->pass, $config->name);
        if ($this->conn->connect_error) {
            die('Connection failed: ') . $this->conn->connect_error;
        }

        
        
    }
    public function savePerson($person)
    {
        $sql = "INSERT INTO persons (`name`, bio)
        VALUES ('" . $person->getName() . "', '" . $person->getBio() . "')";
        if ($this->conn->query($sql)) {
            echo 'Record added successfully';
        }
    }
    public function saveMovie($movie, $actors, $directors)
    {
        $sql = "INSERT INTO movies (title, director)
        VALUES ('" . $movie->getTitle() . "', '" . $movie->getDirector() . "')";
        $this->conn->query($sql);
        print_r($movie);
        print_r($actors);
        $movie_id = $this->conn->insert_id;
        foreach ($actors as $actor) {
            $sql = "INSERT INTO movies_cast
            VALUES ('" . $actor . "', '$movie_id')";
            $this->conn->query($sql);
        }
        foreach ($directors as $director) {
            $sql = "INSERT INTO movies_directors
            VALUES ('$director', '$movie_id')";
            $this->conn->query($sql);
        }
    }
    public function saveSeries($series, $actors, $directors)
    {
        print_r($series);
        print_r($actors);
        print_r($directors);
        $sql = "INSERT INTO series (title)
        VALUES ('" . $series->getTitle() . "')";
        $this->conn->query($sql);
        $series_id = $this->conn->insert_id;
        foreach ($actors as $actor) {
            $sql = "INSERT INTO series_cast
            VALUES ('$actor', '$series_id')";
            $this->conn->query($sql);
        }
        foreach ($directors as $director) {
            $sql = "INSERT INTO series_directors
            VALUES ('$director', '$series_id')";
            $this->conn->query($sql);
        }
    }
    public function getPersons()
    {
        $sql = "SELECT id, `name`, bio
        FROM persons";
        $persons = [];
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $person = new Person();
                $person->setID($row['id']);
                $person->setName($row['name']);
                $person->setBio($row['bio']);
                $persons[] = $person;
            }
        }
        return $persons;
    }
    public function getMovies()
    {
        $sql = "SELECT *
        FROM movies";

        $movies = [];
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $movie = new Movie();
                $movie->setID($row['id']);
                $movie->setTitle($row['title']);
                $movie->setDirector($row['director']);
                $movies[] = $movie;
            }
        }
        return $movies;
    }
    public function getSeries()
    {
        $sql = "SELECT *
        FROM series";

        $series_array = [];
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $series = new Series();
                $series->setID($row['id']);
                $series->setTitle($row['title']);
                $series_array[] = $series;
            }
        }
        return $series_array;
    }
    public function getSinglePerson($id)
    {
        $sql = "SELECT id, `name`, bio
        FROM persons
        WHERE id = '$id'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $actor = new Person();
                $actor->setID($row['id']);
                $actor->setName($row['name']);
                $actor->setBio($row['bio']);
            }
        }
        return $actor;
    }
    public function getSingleMovie($id)
    {
        $sql = "SELECT *
        FROM movies
        WHERE id = '$id'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $movie = new Movie();
                $movie->setID($row['id']);
                $movie->setTitle($row['title']);
                $movie->setDirector($row['director']);
            }
        }
        return $movie;

    }
    public function getSingleSeries($id)
    {
        $sql = "SELECT *
        FROM series
        WHERE id = '$id'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $series = new Series();
                $series->setID($row['id']);
                $series->setTitle($row['title']);
            }
        }
        return $series;
    }
    public function getActorsOfMovie($movie)
    {
        $sql = "SELECT persons.id, persons.name, persons.bio
        FROM (persons INNER JOIN movies_cast ON persons.id = movies_cast.actor_id) INNER JOIN movies ON movies_cast.movie_id = movies.id
        WHERE (((movies.id)='$movie'));
        ";
        $result = $this->conn->query($sql);
        // print_r($result);
        if ($result->num_rows > 0) {
            $persons = [];
            while ($row = $result->fetch_assoc()) {
                $person = new Person();
                $person->setID($row['id']);
                $person->setName($row['name']);
                $person->setBio($row['bio']);
                $persons[] = $person;
            }
        }
        return $persons;
    }
    public function getActorsOfSeries($series)
    {
        $sql = "SELECT persons.id, persons.name, persons.bio
        FROM (persons INNER JOIN series_cast ON persons.id = series_cast.actor_id) INNER JOIN series ON series_cast.series_id = series.id
        WHERE (((series.id)='$series'))";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $actors = [];
            while ($row = $result->fetch_assoc()) {
                $actor = new Person();
                $actor->setID($row['id']);
                $actor->setName($row['name']);
                $actor->setBio($row['bio']);
                $actors[] = $actor;
            }
        }
        return $actors;
    }
    public function getDirectorsOfMovie($movie)
    {
        $sql = "SELECT persons.id, persons.name, persons.bio
        FROM (persons INNER JOIN movies_directors ON persons.id = movies_directors.director_id)
        INNER JOIN movies ON movies_directors.movie_id = movies.id
        WHERE (((movies.id)='$movie'))";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $directors = [];
            while ($row = $result->fetch_assoc()) {
                $director = new Person();
                $director->setID($row['id']);
                $director->setName($row['name']);
                $director->setBio($row['bio']);
                $directors[] = $director;
            }
        }
        return $directors;
    }
    public function getDirectorsOfSeries($series)
    {
        $sql = "SELECT persons.id, persons.name, persons.bio
        FROM (persons INNER JOIN series_directors ON persons.id = series_directors.director_id)
        INNER JOIN series ON series_directors.series_id = series_id
        WHERE (((series.id)='$series'))";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $directors = [];
            while ($row = $result->fetch_assoc()) {
                $director = new Person();
                $director->setID($row['id']);
                $director->setName($row['name']);
                $director->setBio($row['bio']);
                $directors[] = $director;
            }
        }
        return $directors;
    }
    public function getMoviesOfPerson($id)
    {
        $sql = "SELECT movies.id, movies.title
        FROM (movies INNER JOIN movies_cast ON movies.id = movies_cast.movie_id)
        INNER JOIN persons ON movies_cast.actor_id = persons.id
        WHERE (((persons.id)='$id'))";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $movies = [];
            while ($row = $result->fetch_assoc()) {
                $movie = new Movie();
                $movie->setID($row['id']);
                $movie->setTitle($row['title']);
                $movies[] = $movie;
            }
        }
        if (isset($movies)) {
            return $movies;
        }
    }
    public function getSeriesOfPerson($id)
    {
        $sql = "SELECT series.id, series.title
        FROM (series INNER JOIN series_cast ON series.id = series_cast.series_id)
        INNER JOIN persons ON series_cast.actor_id = persons.id
        WHERE (((persons.id)='$id'))";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $series_array = [];
            while ($row = $result->fetch_assoc()) {
                $series = new Series();
                $series->setID($row['id']);
                $series->setTitle($row['title']);
                $series_array[] = $series;
            }
        }
        if (isset($series_array)) {
            return $series_array;
        }
    }
    public function getDirectedMoviesOfPerson($id)
    {
        $sql = "SELECT movies.id, movies.title
        FROM (movies INNER JOIN movies_directors ON movies.id = movies_directors.movie_id)
        INNER JOIN persons ON movies_directors.director_id = persons.id
        WHERE (((persons.id)='$id'))";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $movies = [];
            while ($row = $result->fetch_assoc()) {
                $movie = new Movie();
                $movie->setID($row['id']);
                $movie->setTitle($row['title']);
                $movies[] = $movie;
            }
        }
        if (isset($movies)) {
            return $movies;
        }
    }
    public function getDirectedSeriesOfPerson($id)
    {
        $sql = "SELECT series.id, series.title
        FROM (series INNER JOIN series_directors ON series.id = series_directors.series_id)
        INNER JOIN persons ON series_directors.director_id = persons.id
        WHERE (((persons.id)='$id'))";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $series_array = [];
            while ($row = $result->fetch_assoc()) {
                $series = new Series();
                $series->setID($row['id']);
                $series->setTitle($row['title']);
                $series_array[] = $series;
            }
        }
        if (isset($series_array)) {
            return $series_array;
        }
    }
}