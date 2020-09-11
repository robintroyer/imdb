<?php
class Database implements StorageInterface
{
    private $conn;
    public function initialize($config)
    {
        $this->conn = new mysqli($config->host, $config->user, $config->pass);
        if ($this->conn->connect_error) {
            die('Connection failed: ') . $this->conn->connect_error;
        }
    }
    public function savePerson($person)
    {
        
    }
    public function saveMovie($movie)
    {
        
    }
    public function getPersons()
    {
        
    }
    public function getMovies()
    {
        
    }
    public function getSinglePerson($id)
    {
        
    }
    public function getSingleMovie($id)
    {
        
    }
}