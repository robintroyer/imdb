<?php
class View
{
    private $storage;
    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    public function showButtons($movies, $series)
    {
        echo '<form method="post">';
        echo '<div class="btn-group" role="group" aria-label="Basic example">
                <input type="submit" name="button_movie" value="Filme" class="btn btn-secondary">
                <input type="submit" name="button_series" value="Serien" class="btn btn-secondary">
              </div>';
        echo '</form>';

        if (isset($_POST['button_movie'])) {
            $this->showMovies($movies);
        } elseif (isset($_POST['button_series'])) {
            $this->showSeries($series);
        }
    }
    private function showSeries($series)
    {
        // print_r($series);
        echo '<ul class="list-group">';
        foreach ($series as $s) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $s->getTitle()
            . '<input class="details" type="submit" name="details" value="Details">
            <input type="hidden" name="details_id" value="' . $s->getID() . '">
            <input type="hidden" name="details_title" value="' . $s->getTitle() . '">
            <input type="hidden" name="type" value="series"></li>';
            echo '</form>';
        }
        echo '</ul>';
        if (isset($_POST['details'])) {
            $this->showDetails();
        }
    }
    private function showMovies($movies)
    {
        // echo '<form method="post"><ul class="list-group">';
        echo '<ul class="list-group">';
        foreach ($movies as $movie) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $movie->getTitle()
            . '<input class="details" type="submit" name="details" value="Details">
            <input type="hidden" name="details_id" value="' . $movie->getID() . '">
            <input type="hidden" name="details_title" value="' . $movie->getTitle() . '">
            <input type="hidden" name="type" value="movie"></li>';
            echo '</form>';
        }
        // echo '</ul></form>';
        echo '</ul>';
        // if (isset($_POST['details'])) {
            // $this->showDetails();
        // }
    }

    public function showDetails()
    {
        // $id = $_POST['details_id'];
        // echo $id;
        header('location:/imdb/movie_details.php/?id=' . $_POST['details_id'] . '&title=' . $_POST['details_title'] . '&type=' . $_POST['type']);
    }
}