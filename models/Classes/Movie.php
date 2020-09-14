<?php
class Movie
{
    protected $id;
    protected $title;
    protected $director;
    // protected $actors;

    public function setID($id)
    {
        $this->id = $id;
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function setDirector($director)
    {
        $this->director = $director;
    }
    // public function setActors($actors)
    // {
    //     $this->actors = $actors;
    // }
    public function getID()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getDirector()
    {
        return $this->director;
    }
    // public function getActors()
    // {
    //     return $this->actors;
    // }
}