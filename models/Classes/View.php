<?php
class View
{
    private $storage;
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
    public function showMovies($movies)
    {
        // echo '<form method="post"><ul class="list-group">';
        echo '<ul class="list-group">';
        foreach ($movies as $movie) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $movie->getTitle()
            . '<input class="details" type="submit" name="details" value="Details">
            <input type="hidden" name="details_id" value="' . $movie->getID() . '">
            <input type="hidden" name="details_title" value="' . $movie->getTitle() . '"></li>';
            echo '</form>';
        }
        // echo '</ul></form>';
        echo '</ul>';
        if (isset($_POST['details'])) {
            $this->showDetails();
        }
    }

    private function showDetails()
    {
        // $id = $_POST['details_id'];
        // echo $id;

        header('location:/imdb/movie_details.php/?id=' . $_POST['details_id'] . '&title=' . $_POST['details_title']);
    }
}