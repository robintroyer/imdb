<?php
class Person
{
    protected $id;
    protected $name;
    protected $bio;
    public function setID($id)
    {
        $this->id = $id;
    }
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setBio($bio)
    {
        $this->bio = $bio;
    }
    public function getID()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getBio()
    {
        return $this->bio;
    }
}