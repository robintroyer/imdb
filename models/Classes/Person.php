<?php
class Person
{
    protected $name;
    protected $bio;
    public function setName($name)
    {
        $this->name = $name;
    }
    public function setBio($bio)
    {
        $this->bio = $bio;
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