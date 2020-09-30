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
        $sql = "INSERT INTO movies (title)
        VALUES ('" . $movie->getTitle() . "')";
        $this->conn->query($sql);
        $movie_id = $this->conn->insert_id;
        if ($actors) {
            foreach ($actors as $actor) {
                if ($actor > 0) {
                    $sql = "INSERT INTO movies_cast
                    VALUES ('" . $actor . "', '$movie_id')";
                    $this->conn->query($sql);
                }
            }
        }
        if ($directors) {
            foreach ($directors as $director) {
                if ($director > 0) {
                    $sql = "INSERT INTO movies_directors
                    VALUES ('$director', '$movie_id')";
                    $this->conn->query($sql);
                }
            }
        }  
    }
    public function saveSeries($series, $actors, $directors)
    {
        $sql = "INSERT INTO series (title)
        VALUES ('" . $series->getTitle() . "')";
        $this->conn->query($sql);
        $series_id = $this->conn->insert_id;
        if ($actors) {
            foreach ($actors as $actor) {
                $sql = "INSERT INTO series_cast
                VALUES ('$actor', '$series_id')";
                $this->conn->query($sql);
            }
        }
        if ($directors) {
            foreach ($directors as $director) {
                $sql = "INSERT INTO series_directors
                VALUES ('$director', '$series_id')";
                $this->conn->query($sql);
            }
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
    public function getActors()
    {
        $sql = "SELECT persons.id, persons.name, persons.bio
        FROM persons INNER JOIN movies_cast ON persons.id = movies_cast.actor_id
        UNION
        SELECT persons.id, persons.name, persons.bio
        FROM persons INNER JOIN series_cast ON persons.id = series_cast.actor_id";

        $result = $this->conn->query($sql);
        $actors = [];
        if ($result->num_rows > 0) {
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
    public function getDirectors()
    {
        $sql = "SELECT persons.id, persons.name, persons.bio
        FROM persons INNER JOIN movies_directors ON persons.id = movies_directors.director_id
        UNION
        SELECT persons.id, persons.name, persons.bio
        FROM persons INNER JOIN series_directors ON persons.id = series_directors.director_id";
        $result = $this->conn->query($sql);
        $directors = [];
        if ($result->num_rows > 0) {
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
        if (isset($persons)) {
            return $persons;

        }
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
        if (isset($actors)) {
            return $actors;
        }
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
        if (isset($directors)) {
            return $directors;
        }
    }
    public function getDirectorsOfSeries($series)
    {
        $sql = "SELECT persons.id, persons.name, persons.bio
        FROM (persons INNER JOIN series_directors ON persons.id = series_directors.director_id)
        INNER JOIN series ON series_directors.series_id = series.id
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
        if (isset($directors)) {
            return $directors;
        }
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
    public function deletePerson($person)
    {
        $sql = "DELETE FROM persons WHERE id = '$person'";
        $this->conn->query($sql);
        $sql = "DELETE FROM movies_cast WHERE actor_id = '$person'";
        $this->conn->query($sql);
        $sql = "DELETE FROM movies_directors WHERE director_id = '$person'";
        $this->conn->query($sql);
        $sql = "DELETE FROM series_cast WHERE actor_id = '$person'";
        $this->conn->query($sql);
        $sql = "DELETE FROM series_directors WHERE director_id = '$person'";
        $this->conn->query($sql);
    }
    public function deleteMovie($movie)
    {
        $sql = "DELETE FROM movies WHERE id = '$movie'";
        $this->conn->query($sql);
        $sql = "DELETE FROM movies_cast WHERE movie_id = '$movie'";
        $this->conn->query($sql);
        $sql = "DELETE FROM movies_directors WHERE movie_id = '$movie'";
        $this->conn->query($sql);
    }
    public function deleteSeries($series)
    {
        $sql = "DELETE FROM series WHERE id = '$series'";
        $this->conn->query($sql);
        $sql = "DELETE FROM series_cast WHERE series_id = '$series'";
        $this->conn->query($sql);
        $sql = "DELETE FROM movies_directors WHERE series_id = '$series'";
        $this->conn->query($sql);
    }
    public function deleteActorOfMovie($actor, $movie)
    {
        $sql = "DELETE FROM movies_cast WHERE actor_id = '$actor' AND movie_id = '$movie'";
        $this->conn->query($sql);
    }
    public function deleteActorOfSeries($actor, $series)
    {
        $sql = "DELETE FROM series_cast WHERE actor_id = '$actor' AND series_id = '$series'";
        $this->conn->query($sql);
    }
    public function deleteDirectorOfMovie($director, $movie)
    {
        $sql = "DELETE FROM movies_directors WHERE director_id = '$director' AND movie_id = '$movie'";
        $this->conn->query($sql);
    }
    public function deleteDirectorOfSeries($director, $series)
    {
        $sql = "DELETE FROM series_directors WHERE director_id = '$director' AND series_id = '$series'";
        $this->conn->query($sql);
    }
    public function removeMovieFromActor($movie, $actor)
    {
        $sql = "DELETE FROM movies_cast WHERE movie_id = '$movie' AND actor_id = '$actor'";
        $this->conn->query($sql);
    }
    public function removeSeriesFromActor($series, $actor)
    {
        $sql = "DELETE FROM series_cast WHERE series_id = '$series' AND actor_id = '$actor'";
        $this->conn->query($sql);
    }
    public function removeMovieFromDirector($movie, $director)
    {
        $sql = "DELETE FROM movies_directors WHERE movie_id = '$movie' AND director_id = '$director'";
        $this->conn->query($sql);
    }
    public function removeSeriesFromDirector($series, $director)
    {
        $sql = "DELETE FROM series_directors WHERE series_id = '$series' AND director_id = '$director'";
        $this->conn->query($sql);
    }
    public function getIdOfActor($actor)
    {
        $sql = "SELECT id, `name` FROM persons WHERE `name` = '$actor'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                return $id;
            }
        }
    }
    public function getIdOfDirector($director)
    {
        $sql = "SELECT id, `name` FROM persons WHERE `name` = '$director'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                return $id;
            }
        }
    }
    public function getIdOfMovie($movie)
    {
        $sql = "SELECT id, title FROM movies WHERE title = '$movie'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                return $id;
            }
        }
    }
    public function getIdOfSeries($series)
    {
        $sql = "SELECT id, title FROM series WHERE title = '$series'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                return $id;
            }
        }
    }
    public function addActorToMovie($actor, $movie)
    {
        $sql = "INSERT INTO movies_cast (actor_id, movie_id)
        VALUES ('$actor', '$movie')";
        $this->conn->query($sql);
    }
    public function addDirectorToMovie($director, $movie)
    {
        $sql = "INSERT INTO movies_directors (director_id, movie_id)
        VALUES ('$director', '$movie')";
        $this->conn->query($sql);
    }
    public function addActorToSeries($actor, $series)
    {
        $sql = "INSERT INTO series_cast (actor_id, series_id)
        VALUES ('$actor', '$series')";
        $this->conn->query($sql);
    }
    public function addDirectorToSeries($director, $series)
    {
        $sql = "INSERT INTO series_directors (director_id, series_id)
        VALUES ('$director', '$series')";
        $this->conn->query($sql);
    }
    public function editPerson($person)
    {
        $sql = "UPDATE persons
        SET `name` = '" . $person->getName() . "', bio = '" . $person->getBio() . "'
        WHERE id = '" . $person->getID() . "'";
        $this->conn->query($sql);
    }
    public function editMovie($movie)
    {
        $sql = "UPDATE movies
        SET title = '" . $movie->getTitle() . "'
        WHERE id = '" . $movie->getID() . "'";
        $this->conn->query($sql);
    }
    public function editSeries($series)
    {
        $sql = "UPDATE series
        SET title = '" . $series->getTitle() . "'
        WHERE id = '" . $series->getID() . "'";
        $this->conn->query($sql);
    }

    // Bacon
    public function getSeriesOfActor($actor)
    {
        $sql = "SELECT actor_id, series_id, id, title
        FROM series_cast
        INNER JOIN series ON series.id = series_cast.series_id
        WHERE actor_id = '$actor'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $series = [];
            while ($row = $result->fetch_assoc()) {
                $s = new Series();
                $s->setID($row['series_id']);
                $s->setTitle($row['title']);
                $series[] = $s;
            }
        }
        if (!empty($series)) {
            return $series;
        }
    }
}