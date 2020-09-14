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
    public function saveMovie($movie, $actors)
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
    public function getSinglePerson($id)
    {
        
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
}