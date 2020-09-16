<?php
class View
{
    private $storage;
    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    public function showButtons($movies, $series, $actors, $directors)
    {
        echo '<form method="post">';
        echo '<div class="btn-group" role="group" aria-label="Basic example">
                <input type="submit" name="button_movie" value="Filme" class="btn btn-secondary">
                <input type="submit" name="button_series" value="Serien" class="btn btn-secondary">
                <input type="submit" name="button_actors" value="Schauspieler" class="btn btn-secondary">
                <input type="submit" name="button_directors" value="Regisseure" class="btn btn-secondary">
              </div>';
        echo '</form>';

        if (isset($_POST['button_movie'])) {
            $this->showMovies($movies);
        } elseif (isset($_POST['button_series'])) {
            $this->showSeries($series);
        } elseif (isset($_POST['button_actors'])) {
            $this->showActors($actors);
        } elseif (isset($_POST['button_directors'])) {
            $this->showDirectors($directors);
        }
    }
    private function showActors($actors)
    {
        // print_r($actors);
        echo '<ul class="list-group">';
        foreach ($actors as $actor) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $actor->getName()
            . '<input value="Details" name="person_details" type="submit" style="float:right;">
            <input name="person_details_id" value="' . $actor->getID() . '" type="hidden" style="float:right;">
            <input name="person_details_name" value="' . $actor->getName() . '" type="hidden" style="float:right;">
            <input name="person_details_bio" value="' . $actor->getBio() . '" type="hidden" style="float:right;">
            <input value="Löschen" name="delete_actor" type="submit" style="float:right;"></li>';
            echo '</form>';
        }
        echo '</ul>';
        
    }
    private function showDirectors($directors)
    {
        // print_r($directors);
        echo '<ul class="list-group">';
        foreach ($directors as $director) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $director->getName()
            . '<input value="Details" name="director_details" type="submit" style="float:right;">
            <input name="director_details_id" value="' . $director->getID() . '" type="hidden" style="float:right;">
            <input name="director_details_name" value="' . $director->getName() . '" type="hidden" style="float:right;">
            <input name="director_details_bio" value="' . $director->getBio() . '" type="hidden" style="float:right;">
            <input value="Löschen" name="delete_director" type="submit" style="float:right;"></li>';
            echo '</form>';
        }
        echo '</ul>';
        
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
            <input type="hidden" name="type" value="series">
            <input value="Löschen" name="delete_series" type="submit" style="float:right;"></li>';
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
            <input type="hidden" name="type" value="movie">
            <input value="Löschen" name="delete_movie" type="submit" style="float:right;"></li>';
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
    public function showPersonDetails()
    {
        // header('location:/imdb/person_details.php');

        if (isset($_POST['person_details'])) {
            header('location:/imdb/person_details.php?id=' . $_POST['person_details_id'] . '&name=' . $_POST['person_details_name']);
        } elseif (isset($_POST['director_details'])) {
            header('location:/imdb/person_details.php?id=' . $_POST['director_details_id'] . '&name=' . $_POST['director    _details_name']);
        }


    }
    public function personDetailsPage()
    {
        $details = $this->storage->getSinglePerson($_GET['id']);
        $movies = $this->storage->getMoviesOfPerson($_GET['id']);
        $series = $this->storage->getSeriesOfPerson($_GET['id']);
        $directed_movies = $this->storage->getDirectedMoviesOfPerson($_GET['id']);
        $directed_series = $this->storage->getDirectedSeriesOfPerson($_GET['id']);

        print_r($details);
        print_r($movies);
        print_r($series);
        print_r($directed_movies);
        print_r($directed_series);

        echo '<h1>' . $details->getName() . '</h1>';
        echo '<h3>Biografie</h3>';
        echo '<p>' . $details->getBio() . '</p>';
        if (!empty($movies)) {
            echo '<h4>Filme</h4>';
            echo '<ul class="list-group">';
            foreach ($movies as $movie) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $movie->getTitle()
                . '<input value="Details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $movie->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $movie->getTitle() . '">
                <input type="hidden" name="entry_type" value="movie"></li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        if (!empty($series)) {
            echo '<h4>Serien</h4>';
            echo '<ul class="list-group">';
            foreach ($series as $s) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $s->getTitle()
                . '<input value="Details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $s->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $s->getTitle() . '">
                <input type="hidden" name="entry_type" value="series"></li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        if (!empty($directed_movies)) {
            echo '<h4>Regisseur in folgenden Filmen</h4>';
            echo '<ul class="list-group">';
            foreach ($directed_movies as $directed_movie) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $directed_movie->getTitle()
                . '<input value="Details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $directed_movie->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $directed_movie->getTitle() . '">
                <input type="hidden" name="entry_type" value="movie"></li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        if (!empty($directed_series)) {
            echo '<h4>Regisseur in folgenden Serien</h4>';
            echo '<ul class="list-group">';
            foreach ($directed_series as $directed_s) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $directed_s->getTitle()
                . '<input value="Details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $directed_s->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $directed_s->getTitle() . '">
                <input type="hidden" name="entry_type" value="series"></li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        if (isset($_POST['entry_details'])) {
            header('location:/imdb/movie_details.php/?id=' . $_POST['entry_details_id'] . '&title=' . $_POST['entry_details_title'] . '&type=' . $_POST['entry_type']);
        }
    }
    public function detailsPage()
    {
        if ($_GET['type'] == 'movie') {
            $details = $this->storage->getSingleMovie($_GET['id']);
            $actors = $this->storage->getActorsOfMovie($_GET['id']);
            $directors = $this->storage->getDirectorsOfMovie($_GET['id']);
        } elseif ($_GET['type'] == 'series') {
            $details = $this->storage->getSingleSeries($_GET['id']);
            $actors = $this->storage->getActorsOfSeries($_GET['id']);
            $directors = $this->storage->getDirectorsOfSeries($_GET['id']);
        }
        
        echo '<h1>' . $details->getTitle() . '</h1>';
        echo '<ul class="list-group">';
        foreach ($actors as $actor) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $actor->getName()
            . '<input value="Details" name="person_details" type="submit" style="float:right;">
            <input name="person_details_id" value="' . $actor->getID() . '" type="hidden" style="float:right;">
            <input name="person_details_name" value="' . $actor->getName() . '" type="hidden" style="float:right;">
            <input name="person_details_bio" value="' . $actor->getBio() . '" type="hidden" style="float:right;"></li>';
            echo '</form>';
        }
        echo '</ul>';
        echo '<br />';
        echo '<h3>Regisseur/Produzent</h3>';
        echo '<ul class="list-group">';
        foreach ($directors as $director) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $director->getName()
            . '<input value="Details" name="director_details" type="submit" style="float:right;">
            <input name="director_details_id" value="' . $director->getID() . '" type="hidden" style="float:right;">
            <input name="director_details_name" value="' . $director->getName() . '" type="hidden" style="float:right;">
            <input name="director_details_bio" value="' . $director->getBio() . '" type="hidden" style="float:right;"></li>';
            echo '</form>';
        }
        echo '</ul>';

        if (
            isset($_POST['person_details'])
            || isset($_POST['director_details'])
        ) {
            $this->showPersonDetails();
        }
    }
}