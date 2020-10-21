<?php
class View
{
    private $storage;
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
    public function reloadPage($page)
    {
        if ($page == 'movie_details') {
            header('location: ' . $_SERVER['PHP_SELF'] . '?id=' . $_GET['id'] . '&title=' . $_GET['title'] . '&type=' . $_GET['type']);
            die;        
        } elseif ($page == 'person_details') {
            header('location: ' . $_SERVER['PHP_SELF'] . '?id=' . $_GET['id'] . '&name=' . $_GET['name']);
            die;        
        }
    }
    public function showButtons($movies, $series, $actors, $directors, $persons)
    {
        echo '<div class="button_nav">';
        echo '<form method="post">';
        echo '<div id="button_grid" class="disabled">
                <input type="submit" name="button_movie" value="Filme">
                <input type="submit" name="button_series" value="Serien">
                <input type="submit" name="button_actors" value="Schauspieler">
                <input type="submit" name="button_directors" value="Regisseure">
                <input type="submit" name="button_persons" value="Personen">
                <input type="submit" name="button_bacon" value="Bacon Number">
                <input type="submit" name="button_moviedb" value="The Movie DB">
              </div>';
        echo '</form>';
        echo '<button onclick="addClass()"><img src="./src/images/2x/baseline_menu_black_18dp.png"></button>';
        echo '</div>';
        if (isset($_POST['button_movie'])) {
            $this->showMovies($movies);
        } elseif (isset($_POST['button_series'])) {
            $this->showSeries($series);
        } elseif (isset($_POST['button_actors'])) {
            $this->showActors($actors);
        } elseif (isset($_POST['button_directors'])) {
            $this->showDirectors($directors);
        } elseif (isset($_POST['button_persons'])) {
            $this->showPersons($persons);
        } elseif (isset($_POST['button_bacon'])) {
            $bacon = new Bacon($this->storage);
            $bacon->showForm();
        } elseif (isset($_POST['button_moviedb'])) {
            // echo __DIR__ . '/tmdb.php';
            header('location:tmdb.php');
            // $bacon = new Bacon($this->storage);
            // $bacon->theMovieDBForm();
        }
    }
    public function showPersons($persons)
    {
        echo '<ul>';
        foreach ($persons as $person) {
            echo '<form method="post">';
            echo '<li class="list_grid"><span class="name">' . $person->getName()
            . '</span><input value="Löschen" class="button_delete" name="delete_person" type="submit" style="float:right;">
            <input value="Details" class="button_details" name="person_details" type="submit" style="float:right;">
            <input name="person_details_id" value="' . $person->getID() . '" type="hidden" style="float:right;">
            <input name="person_details_name" value="' . $person->getName() . '" type="hidden" style="float:right;>
            <input name="person_details_bio" value="' . $person->getBio() . '" type="hidden" style="float:right;">
            </li>';
            echo '</form>';
        }
        echo '</ul>';
    }
    private function showActors($actors)
    {
        echo '<ul>';
        foreach ($actors as $actor) {
            echo '<form method="post">';
            echo '<li class="list_grid"><span class="name">' . $actor->getName()
            . '</span><input value="Löschen" class="button_delete" name="delete_actor" type="submit" style="float:right;">
            <input value="Details" class="button_details" name="person_details" type="submit" style="float:right;">
            <input name="person_details_id" value="' . $actor->getID() . '" type="hidden" style="float:right;">
            <input name="person_details_name" value="' . $actor->getName() . '" type="hidden" style="float:right;">
            <input name="person_details_bio" value="' . $actor->getBio() . '" type="hidden" style="float:right;">
            </li>';
            echo '</form>';
        }
        echo '</ul>';
    }
    private function showDirectors($directors)
    {
        echo '<ul>';
        foreach ($directors as $director) {
            echo '<form method="post">';
            echo '<li class="list_grid"><span class="name">' . $director->getName()
            . '</span><input value="Löschen" class="button_delete" name="delete_director" type="submit" style="float:right;">
            <input value="Details" class="button_details" name="director_details" type="submit" style="float:right;">
            <input name="director_details_id" value="' . $director->getID() . '" type="hidden" style="float:right;">
            <input name="director_details_name" value="' . $director->getName() . '" type="hidden" style="float:right;">
            <input name="director_details_bio" value="' . $director->getBio() . '" type="hidden" style="float:right;">
            </li>';
            echo '</form>';
        }
        echo '</ul>';
    }
    private function showSeries($series)
    {
        echo '<ul>';
        foreach ($series as $s) {
            echo '<form method="post">';
            echo '<li class="list_grid"><span class="name">' . $s->getTitle()
            . '</span><input value="Löschen" class="button_delete" name="delete_series" type="submit" style="float:right;">
            <input class="button_details" type="submit" name="details" value="Details">
            <input type="hidden" name="details_id" value="' . $s->getID() . '">
            <input type="hidden" name="details_title" value="' . $s->getTitle() . '">
            <input type="hidden" name="type" value="series">
            </li>';
            echo '</form>';
        }
        echo '</ul>';
        if (isset($_POST['details'])) {
            $this->showDetails();
        }
    }
    private function showMovies($movies)
    {
        echo '<ul>';
        foreach ($movies as $movie) {
            echo '<form method="post">';
            echo '<li class="list_grid"><span class="name">' . $movie->getTitle()
            . '</span><input value="Löschen" class="button_delete" name="delete_movie" type="submit" style="float:right;">
            <input class="button_details" type="submit" name="details" value="Details">
            <input type="hidden" name="details_id" value="' . $movie->getID() . '">
            <input type="hidden" name="details_title" value="' . $movie->getTitle() . '">
            <input type="hidden" name="type" value="movie">
            </li>';
            echo '</form>';
        }
        echo '</ul>';
    }
    public function showDetails()
    {
        header('location:/imdb/movie_details.php/?id=' . $_POST['details_id'] . '&title=' . $_POST['details_title'] . '&type=' . $_POST['type']);
    }
    public function showPersonDetails()
    {
        if (isset($_POST['person_details'])) {
            header('location:/imdb/person_details.php?id=' . $_POST['person_details_id'] . '&name=' . $_POST['person_details_name']);
        } elseif (isset($_POST['director_details'])) {
            header('location:/imdb/person_details.php?id=' . $_POST['director_details_id'] . '&name=' . $_POST['director_details_name']);
        }
    }
    public function personDetailsPage()
    {
        $details = $this->storage->getSinglePerson($_GET['id']);
        $movies = $this->storage->getMoviesOfPerson($_GET['id']);
        $series = $this->storage->getSeriesOfPerson($_GET['id']);
        $directed_movies = $this->storage->getDirectedMoviesOfPerson($_GET['id']);
        $directed_series = $this->storage->getDirectedSeriesOfPerson($_GET['id']);
        echo '<h1 id="person_name">' . $details->getName() . '</h1>';
        echo '<h3>Biografie</h3>';
        echo '<p id="person_bio">' . $details->getBio() . '</p>';
        echo '<button id="edit_person"><img alt="Bearbeiten" src="./src/images/1x/baseline_create_black_18dp.png"></button>';
        echo '<h4>Filme</h4>';
        if (isset($movies)) {
            echo '<ul class="list-group">';
            foreach ($movies as $movie) {
                echo '<form method="post">';
                echo '<li class="list_grid"><span class="name">' . $movie->getTitle()
                . '</span><input value="Film entfernen" class="button_delete" name="remove_movie_from_actor" type="submit" style="float:right;">
                <input value="Details" class="button_details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $movie->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $movie->getTitle() . '">
                <input type="hidden" name="entry_type" value="movie">
                <input type="hidden" name="entry_id" value="' . $details->getID() . '">
                </li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        echo '<button id="new_movie" class="btn btn-success">Film hinzufügen</button>';
        echo '<div id="new_movie_buttons"></div>';
        ob_start();
        if (isset($_POST['new_movie_submit'])) {
            $movie = new Movie();
            $movie->setTitle($_POST['new_movie_title']);
            $this->storage->saveMovie($movie, 0, 0);
            $this->storage->addActorToMovie(
                $_GET['id'], $this->storage->getIdOfMovie($_POST['new_movie_title'])
            );
            $this->reloadPage('person_details');
        }
        if (isset($_POST['add_movie'])) {
            $this->storage->addActorToMovie(
                $_GET['id'], $this->storage->getIdOfMovie($_POST['movie'])
            );
            $this->reloadPage('person_details');
        }
        ob_start();
        echo '<h4 id="series_heading">Serien</h4>';
        if (isset($series)) {
            echo '<ul class="list-group">';
            foreach ($series as $s) {
                echo '<form method="post">';
                echo '<li class="list_grid"><span class="name">' . $s->getTitle()
                . '</span><input value="Serie entfernen" class="button_delete" name="remove_series_from_actor" type="submit" style="float:right;">
                <input value="Details" class="button_details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $s->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $s->getTitle() . '">
                <input type="hidden" name="entry_type" value="series">
                <input type="hidden" name="entry_id" value="' . $details->getID() . '">
                </li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        echo '<button id="new_series" class="btn btn-success">Serie hinzufügen</button>';
        echo '<div id="new_series_buttons"></div>';
        if (isset($_POST['new_series_submit'])) {
            $series = new Series();
            $series->setTitle($_POST['new_series_title']);
            $this->storage->saveSeries($series, 0, 0);
            $this->storage->addActorToSeries(
                $_GET['id'], $this->storage->getIdOfSeries($_POST['new_series_title'])
            );
        }
        if (isset($_POST['add_series'])) {
            $this->storage->addActorToSeries(
                $_GET['id'], $this->storage->getIdOfSeries($_POST['series'])
            );
            $this->reloadPage('person_details');
        }
        echo '<h4 id="director_movies_heading">Regisseur in folgenden Filmen</h4>';
        if (isset($directed_movies)) {
            echo '<ul class="list-group">';
            foreach ($directed_movies as $directed_movie) {
                echo '<form method="post">';
                echo '<li class="list_grid"><span class="name">' . $directed_movie->getTitle()
                . '</span><input value="Film entfernen" class="button_delete" name="remove_movie_from_director" type="submit" style="float:right;">
                <input value="Details" class="button_details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $directed_movie->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $directed_movie->getTitle() . '">
                <input type="hidden" name="entry_type" value="movie">
                <input type="hidden" name="entry_id" value="' . $details->getID() . '">
                </li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        echo '<button id="new_directed_movie" class="btn btn-success">Film hinzufügen</button>';
        echo '<div id="new_directed_movie_buttons"></div>';
        if (isset($_POST['new_directed_movie_submit'])) {
            $movie = new Movie();
            $movie->setTitle($_POST['new_movie_title']);
            $this->storage->saveMovie($movie, 0, 0);
            $this->storage->addDirectorToMovie(
                $_GET['id'], $this->storage->getIdOfMovie($_POST['new_movie_title'])
            );
            $this->reloadPage('person_details');
        }
        if (isset($_POST['add_directed_movie'])) {
            $this->storage->addDirectorToMovie(
                $_GET['id'], $this->storage->getIdOfMovie($_POST['movie'])
            );
            $this->reloadPage('person_details');
        }
        echo '<h4 id="directed_series_heading">Regisseur in folgenden Serien</h4>';
        if (isset($directed_series)) {
            echo '<ul class="list-group">';
            foreach ($directed_series as $directed_s) {
                echo '<form method="post">';
                echo '<li class="list_grid"><span class="name">' . $directed_s->getTitle()
                . '</span><input value="Serie entfernen" class="button_delete" name="remove_series_from_director" type="submit" style="float:right;">
                <input value="Details" class="button_details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $directed_s->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $directed_s->getTitle() . '">
                <input type="hidden" name="entry_type" value="series">
                <input type="hidden" name="entry_id" value="' . $details->getID() . '">
                </li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        echo '<button id="new_directed_series" class="btn btn-success">Serie hinzufügen</button>';
        echo '<div id="new_directed_series_buttons"></div>';
        echo '<div id="after_directed_series"></div>';
        if (isset($_POST['new_directed_series_submit'])) {
            $series = new Series();
            $series->setTitle($_POST['new_series_title']);
            $this->storage->saveSeries($series, 0, 0);
            $this->storage->addDirectorToSeries(
                $_GET['id'], $this->storage->getIdOfSeries($_POST['new_series_title'])
            );
            $this->reloadPage('person_details');
        }
        if (isset($_POST['add_directed_series'])) {
            $this->storage->addDirectorToSeries(
                $_GET['id'], $this->storage->getIdOfSeries($_POST['series'])
            );
            $this->reloadPage('person_details');
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
            $type = 'movie';
        } elseif ($_GET['type'] == 'series') {
            $details = $this->storage->getSingleSeries($_GET['id']);
            $actors = $this->storage->getActorsOfSeries($_GET['id']);
            $directors = $this->storage->getDirectorsOfSeries($_GET['id']);
            $type = 'series';
        }
        if ($type == 'series') {
            echo '<h1 id="title_heading"><span id="title_series">' . $details->getTitle()
            . '</span>&nbsp<button id="edit_series"><img alt="Bearbeiten" src="../src/images/1x/baseline_create_black_18dp.png"></button></h1>';
        } elseif ($type == 'movie') {
            echo '<h1 id="title_heading"><span id="title_movie">' . $details->getTitle()
            . '</span>&nbsp<button id="edit_movie"><img alt="Bearbeiten" src="../src/images/1x/baseline_create_black_18dp.png"></button></h1>';
        }
        echo '<ul class="list-group">';
        ob_start();
        if (isset($actors)) {
            foreach ($actors as $actor) {
                echo '<form method="post">';
                echo '<li class="list_grid"><span class="name">' . $actor->getName()
                . '</span><input value="Löschen" class="button_delete" name="remove_actor" type="submit" style="float:right;">
                <input value="Details" class="button_details" name="person_details" type="submit" style="float:right;">
                <input name="person_details_id" value="' . $actor->getID() . '" type="hidden" style="float:right;">
                <input name="person_details_name" value="' . $actor->getName() . '" type="hidden" style="float:right;">
                <input name="person_details_bio" value="' . $actor->getBio() . '" type="hidden" style="float:right;">
                <input name="person_details_type" value="' . $type . '" type="hidden">
                </li>';
                echo '</form>';
            }
        }
        echo '</ul>';
        echo '<button id="new_actor" type="button" class="btn btn-success">Schauspieler hinzufügen</button>';
        echo '<div id="new_actor_form"></div>';
        if (isset($_POST['new_person_submit'])) {
            if ($type == 'movie') {
                $person = new Person();
                $person->setName($_POST['new_person_name']);
                $person->setBio($_POST['new_person_bio']);
                $this->storage->savePerson($person);
                $this->storage->addActorToMovie(
                    $this->storage->getIdOfActor($_POST['new_person_name']), $_GET['id']
                );
                $this->reloadPage('movie_details');
            } elseif ($type == 'series') {
                $person = new Person();
                $person->setName($_POST['new_person_name']);
                $person->setBio($_POST['new_person_bio']);
                $this->storage->savePerson($person);
                $this->storage->addActorToSeries(
                    $this->storage->getIdOfActor($_POST['new_person_name']), $_GET['id']
                );
                $this->reloadPage('movie_details');
            }
        }
        if (isset($_POST['add_actor'])) {
            if ($type == 'movie') {
                $this->storage->addActorToMovie(
                    $this->storage->getIdOfActor($_POST['person']), $_GET['id']
                );
                $this->reloadPage('movie_details');
            } elseif ($type = 'series') {
                $this->storage->addActorToSeries(
                    $this->storage->getIdOfActor($_POST['person']), $_GET['id']
                );
                $this->reloadPage('movie_details');
            }
        }
        ob_start();
        echo '<br />';
        echo '<h3>Regisseur/Produzent</h3>';
        echo '<ul class="list-group">';
        if (isset($directors)) {
            foreach ($directors as $director) {
                echo '<form method="post">';
                echo '<li class="list_grid"><span class="name">' . $director->getName()
                . '</span><input value="Löschen" class="button_delete" name="remove_director" type="submit" style="float:right;">
                <input value="Details" class="button_details" name="director_details" type="submit" style="float:right;">
                <input name="director_details_id" value="' . $director->getID() . '" type="hidden" style="float:right;">
                <input name="director_details_name" value="' . $director->getName() . '" type="hidden" style="float:right;">
                <input name="director_details_bio" value="' . $director->getBio() . '" type="hidden" style="float:right;">
                <input name="director_details_type" value="' . $type . '" type="hidden">
                </li>';
                echo '</form>';
            }
        }
        echo '</ul>';
        echo '<button id="new_director" type="button" class="btn btn-success">Regisseur hinzufügen</button>';
        echo '<div id="new_director_form"></div>';
        if (isset($_POST['new_person_submit_director'])) {
            if ($type == 'movie') {
                $person = new Person();
                $person->setName($_POST['new_person_name']);
                $person->setBio($_POST['new_person_bio']);
                $this->storage->savePerson($person);
                $this->storage->addDirectorToMovie(
                    $this->storage->getIdOfDirector($_POST['new_person_name']), $_GET['id']
                );
                $this->reloadPage('movie_details');
            } elseif ($type == 'series') {
                $person = new Person();
                $person->setName($_POST['new_person_name']);
                $person->setBio($_POST['new_person_bio']);
                $this->storage->savePerson($person);
                $this->storage->addDirectorToSeries(
                    $this->storage->getIdOfDirector($_POST['new_person_name']), $_GET['id']
                );
                $this->reloadPage('movie_details');
            }
        }
        if (isset($_POST['add_director'])) {
            if ($type == 'movie') {
                $this->storage->addDirectorToMovie(
                    $this->storage->getIdOfDirector($_POST['person']), $_GET['id']
                );
                $this->reloadPage('movie_details');
            } elseif ($type == 'series') {
                $this->storage->addDirectorToSeries(
                    $this->storage->getIdOfDirector($_POST['person']), $_GET['id']
                );
                $this->reloadPage('movie_details');
            }
        }
        if (
            isset($_POST['person_details'])
            || isset($_POST['director_details'])
        ) {
            $this->showPersonDetails();
        }
    }
}