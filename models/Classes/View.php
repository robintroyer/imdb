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
        echo '<ul class="list-group">';
        foreach ($actors as $actor) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $actor->getName()
            . '<input value="Löschen" name="delete_actor" type="submit" style="float:right;">
            <input value="Details" name="person_details" type="submit" style="float:right;">
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
        echo '<ul class="list-group">';
        foreach ($directors as $director) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $director->getName()
            . '<input value="Löschen" name="delete_director" type="submit" style="float:right;">
            <input value="Details" name="director_details" type="submit" style="float:right;">
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
        echo '<ul class="list-group">';
        foreach ($series as $s) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $s->getTitle()
            . '<input value="Löschen" name="delete_series" type="submit" style="float:right;">
            <input class="details" type="submit" name="details" value="Details">
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
        echo '<ul class="list-group">';
        foreach ($movies as $movie) {
            echo '<form method="post">';
            echo '<li class="list-group-item">' . $movie->getTitle()
            . '<input value="Löschen" name="delete_movie" type="submit" style="float:right;">
            <input class="details" type="submit" name="details" value="Details">
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
        echo '<h1>' . $details->getName() . '</h1>';
        echo '<h3>Biografie</h3>';
        echo '<p>' . $details->getBio() . '</p>';
        echo '<h4>Filme</h4>';
        if (isset($movies)) {
            echo '<ul class="list-group">';
            foreach ($movies as $movie) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $movie->getTitle()
                . '<input value="Film entfernen" name="remove_movie_from_actor" type="submit" style="float:right;">
                <input value="Details" name="entry_details" type="submit" style="float:right;">
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
            echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
            $jquery = '<script type="text/javascript">
            $(document).ready(function() {
                $("#new_movie").click(function() {
                    $("#new_series_button").hide();
                    $("#existing_series").hide();
                    if (document.getElementById(\'new_series\') == null) {
                        $("#director_movies_heading").before(\'<button id="new_series" class="btn btn-success">Serie hinzufügen</button>\');

                    }

                    $("#new_movie").hide();
                    $("#new_movie_buttons").append(
                        "<button id=\'new_movie_button\' type\"button\" class=\'btn btn-info\'>Neuer Schauspieler</button>",
                        " ",
                        "<button id=\'existing_movie\' type=\'button\' class=\'btn btn-info\'>Bestehender Schauspieler</button>",
                        "<div id=\'movie_div\'></div>"
                    );
                    $("#new_movie_button").click(function() {
                        $("#new_movie_button").hide();
                        $("#existing_movie").hide();
                        $("#movie_div").append(
                            "<form id=\'new_form\' method=\'post\'>",
                        );
                        $("#new_form").append(
                            "<label for\'new_movie_title\'><strong>Name</strong></label><br />",
                            "<input type=\'text\' name=\'new_movie_title\'><br />",
                            "<input type=\'submit\' name=\'new_movie_submit\' value=\'Hinzufügen\'>",
                        );
                    });
                    $("#existing_movie").click(function() {
                        $("#new_movie_button").hide();
                        $("#existing_movie").hide();
                        $("#movie_div").append(
                            "<form id=\'select_form\' method=\'post\'>"
                        );
                        $("#select_form").append(
                            "<select id=\'movies_select\' name=\'movie\'>",
                            "&nbsp<input type=\'submit\' value=\'Hinzufügen\' name=\'add_movie\'>"
                        );
                        appendOptions();
                    });
                });
                
            });
            </script>';
        // }
        $movies = $this->storage->getMovies();
        for ($i = 0; $i < count($movies); $i++) {
            $movies[$i] = $movies[$i]->getTitle();
        }
        ob_start();
        echo $jquery;
        echo '<script type="text/javascript">
            function appendOptions() {
                let movies = ' . json_encode($movies) . ';
                movies.forEach(movie => {
                    $("#movies_select").append("<option value=\'" + movie + "\'>" + movie + "</option>");
                });
            }
        </script>';

        if (isset($_POST['new_movie_submit'])) {
            $movie = new Movie();
            $movie->setTitle($_POST['new_movie_title']);
            $this->storage->saveMovie($movie, 0, 0);
            $this->storage->addActorToMovie(
                $_GET['id'], $this->storage->getIdOfMovie($_POST['new_movie_title'])
            );
        }
        if (isset($_POST['add_movie'])) {
            $this->storage->addActorToMovie(
                $_GET['id'], $this->storage->getIdOfMovie($_POST['movie'])
            );
        }
        ob_start();
        echo '<h4>Serien</h4>';
        if (isset($series)) {
            echo '<ul class="list-group">';
            foreach ($series as $s) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $s->getTitle()
                . '<input value="Serie entfernen" name="remove_series_from_actor" type="submit" style="float:right;">
                <input value="Details" name="entry_details" type="submit" style="float:right;">
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
        $jquery = '<script type="text/javascript">
            $(document).ready(function() {
                $("#new_series").click(function() {
                    $("#new_series").remove();
                    $("#new_series_buttons").append(
                        "<button id=\'new_series_button\' type\"button\" class=\'btn btn-info\'>Neue Serie</button>",
                        " ",
                        "<button id=\'existing_series\' type=\'button\' class=\'btn btn-info\'>Bestehende Serie</button>",
                        "<div id=\'series_div\'></div>"
                    );
                    $("#new_series_button").click(function() {
                        $("#new_series_button").hide();
                        $("#existing_series").hide();
                        $("#series_div").append(
                            "<form id=\'new_form\' method=\'post\'>",
                        );
                        $("#new_form").append(
                            "<label for\'new_series_title\'><strong>Name</strong></label><br />",
                            "<input type=\'text\' name=\'new_series_title\'><br />",
                            "<input type=\'submit\' name=\'new_series_submit\' value=\'Hinzufügen\'>",
                        );
                    });
                    $("#existing_series").click(function() {
                        $("#new_series_button").hide();
                        $("#existing_series").hide();
                        $("#series_div").append(
                            "<form id=\'select_form\' method=\'post\'>"
                        );
                        $("#select_form").append(
                            "<select id=\'series_select\' name=\'series\'>",
                            "&nbsp<input type=\'submit\' value=\'Hinzufügen\' name=\'add_series\'>"
                        );
                        appendOptionsSeries();
                    });
                });
                
            });
            </script>';
        echo $jquery;
        $series = $this->storage->getSeries();
        for ($i = 0; $i < count($series); $i++) {
            $series[$i] = $series[$i]->getTitle();
        }
        echo '<script type="text/javascript">
            function appendOptionsSeries() {
                let series = ' . json_encode($series) . ';
                series.forEach(s => {
                    $("#series_select").append("<option value=\'" + s + "\'>" + s + "</option>");
                });
            }
        </script>';
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
        }
        echo '<h4 id="director_movies_heading">Regisseur in folgenden Filmen</h4>';
        if (isset($directed_movies)) {
            echo '<ul class="list-group">';
            foreach ($directed_movies as $directed_movie) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $directed_movie->getTitle()
                . '<input value="Film entfernen" name="remove_movie_from_director" type="submit" style="float:right;">
                <input value="Details" name="entry_details" type="submit" style="float:right;">
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
        $jquery = '<script type="text/javascript">
            $(document).ready(function() {
                $("#new_directed_movie").click(function() {
                    $("#new_directed_movie").remove();
                    $("#new_directed_movie_buttons").append(
                        "<button id=\'new_movie_button\' type\"button\" class=\'btn btn-info\'>Neuer Film</button>",
                        " ",
                        "<button id=\'existing_movie\' type=\'button\' class=\'btn btn-info\'>Bestehender Film</button>",
                        "<div id=\'movie_div\'></div>"
                    );
                    $("#new_movie_button").click(function() {
                        $("#new_movie_button").hide();
                        $("#existing_movie").hide();
                        $("#movie_div").append(
                            "<form id=\'new_form\' method=\'post\'>",
                        );
                        $("#new_form").append(
                            "<label for\'new_movie_title\'><strong>Name</strong></label><br />",
                            "<input type=\'text\' name=\'new_movie_title\'><br />",
                            "<input type=\'submit\' name=\'new_directed_movie_submit\' value=\'Hinzufügen\'>",
                        );
                    });
                    $("#existing_movie").click(function() {
                        $("#new_movie_button").hide();
                        $("#existing_movie").hide();
                        $("#movie_div").append(
                            "<form id=\'select_form\' method=\'post\'>"
                        );
                        $("#select_form").append(
                            "<select id=\'movies_select\' name=\'movie\'>",
                            "&nbsp<input type=\'submit\' value=\'Hinzufügen\' name=\'add_directed_movie\'>"
                        );
                        appendOptions();
                    });
                });
                
            });
            </script>';
        echo $jquery;

        if (isset($_POST['new_directed_movie_submit'])) {
            $movie = new Movie();
            $movie->setTitle($_POST['new_movie_title']);
            $this->storage->saveMovie($movie, 0, 0);
            $this->storage->addDirectorToMovie(
                $_GET['id'], $this->storage->getIdOfMovie($_POST['new_movie_title'])
            );
        }
        if (isset($_POST['add_directed_movie'])) {
            $this->storage->addDirectorToMovie(
                $_GET['id'], $this->storage->getIdOfMovie($_POST['movie'])
            );
        }

        echo '<h4>Regisseur in folgenden Serien</h4>';
        if (isset($directed_series)) {
            echo '<ul class="list-group">';
            foreach ($directed_series as $directed_s) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $directed_s->getTitle()
                . '<input value="Serie entfernen" name="remove_series_from_director" type="submit" style="float:right;">
                <input value="Details" name="entry_details" type="submit" style="float:right;">
                <input type="hidden" name="entry_details_id" value="' . $directed_s->getID() . '">
                <input type="hidden" name="entry_details_title" value="' . $directed_s->getTitle() . '">
                <input type="hidden" name="entry_type" value="series">
                <input type="hidden" name="entry_id" value="' . $details->getID() . '">
                </li>';
                echo '</form>';
            }
            echo '</ul>';
        }
        echo '<button id="new_directed_series" class="btn btn-success">Serie hinzufügen</button><br />';
        echo '<div id="new_directed_series_buttons"></div>';
        $jquery = '<script type="text/javascript">
            $(document).ready(function() {
                $("#new_directed_series").click(function() {
                    $("#new_directed_series").remove();
                    $("#new_directed_series_buttons").append(
                        "<button id=\'new_series_button\' type\"button\" class=\'btn btn-info\'>Neue Serie</button>",
                        " ",
                        "<button id=\'existing_series\' type=\'button\' class=\'btn btn-info\'>Bestehende Serie</button>",
                        "<div id=\'series_div\'></div>"
                    );
                    $("#new_series_button").click(function() {
                        $("#new_series_button").hide();
                        $("#existing_series").hide();
                        $("#series_div").append(
                            "<form id=\'new_form\' method=\'post\'>",
                        );
                        $("#new_form").append(
                            "<label for\'new_series_title\'><strong>Name</strong></label><br />",
                            "<input type=\'text\' name=\'new_series_title\'><br />",
                            "<input type=\'submit\' name=\'new_directed_series_submit\' value=\'Hinzufügen\'>",
                        );
                    });
                    $("#existing_series").click(function() {
                        $("#new_series_button").hide();
                        $("#existing_series").hide();
                        $("#series_div").append(
                            "<form id=\'select_form\' method=\'post\'>"
                        );
                        $("#select_form").append(
                            "<select id=\'series_select\' name=\'series\'>",
                            "&nbsp<input type=\'submit\' value=\'Hinzufügen\' name=\'add_directed_series\'>"
                        );
                        appendOptionsSeries();
                    });
                });
                
            });
            </script>';
        echo $jquery;
        if (isset($_POST['new_directed_series_submit'])) {
            $series = new Series();
            $series->setTitle($_POST['new_series_title']);
            $this->storage->saveSeries($series, 0, 0);
            $this->storage->addDirectorToSeries(
                $_GET['id'], $this->storage->getIdOfSeries($_POST['new_series_title'])
            );
        }
        if (isset($_POST['add_directed_series'])) {
            $this->storage->addDirectorToSeries(
                $_GET['id'], $this->storage->getIdOfSeries($_POST['series'])
            );
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
        echo '<h1>' . $details->getTitle() . '</h1>';
        echo '<ul class="list-group">';
        if (isset($actors)) {
            foreach ($actors as $actor) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $actor->getName()
                . '<input value="Löschen" class="btn btn-danger" name="remove_actor" type="submit" style="float:right;">
                <input value="Details" class="btn btn-info" name="person_details" type="submit" style="float:right;">
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
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
        $jquery = '<script type="text/javascript">
        $(document).ready(function() {
            $("#new_actor").click(function() {
                $("#new_actor").hide();
                $("#new_actor_form").append(
                    "<button id=\'new_person\' type\"button\" class=\'btn btn-info\'>Neuer Schauspieler</button>",
                    " ",
                    "<button id=\'existing_person\' type=\'button\' class=\'btn btn-info\'>Bestehender Schauspieler</button>",
                    "<div id=\'person_div\'></div>"
                );
                $("#new_person").click(function() {
                    console.log("a");
                    $("#new_person").hide();
                    $("#existing_person").hide();
                    $("#person_div").append(
                        "<form id=\'new_form\' method=\'post\'>",
                    );
                    $("#new_form").append(
                        "<label for\'new_person_name\'><strong>Name</strong></label><br />",
                        "<input type=\'text\' name=\'new_person_name\'><br />",
                        "<label for\'new_person_bio\'><strong>Biografie</strong></label><br />",
                        "<input type=\'text\' name=\'new_person_bio\'><br />",
                        "<input type=\'submit\' name=\'new_person_submit\' value=\'Hinzufügen\'>",
                    );
                });
                $("#existing_person").click(function() {
                    $("#new_person").hide();
                    $("#existing_person").hide();
                    $("#person_div").append(
                        "<form id=\'select_form\' method=\'post\'>"
                    );
                    $("#select_form").append(
                        "<select id=\'person_select\' name=\'person\'>",
                        "&nbsp<input type=\'submit\' value=\'Hinzufügen\' name=\'add_actor\'>"
                    );
                    appendOptions();
                });
            });
            
        });
        </script>';
        if (isset($_POST['new_person_submit'])) {
            if ($type == 'movie') {
                $person = new Person();
                $person->setName($_POST['new_person_name']);
                $person->setBio($_POST['new_person_bio']);
                $this->storage->savePerson($person);
                $this->storage->addActorToMovie(
                    $this->storage->getIdOfActor($_POST['new_person_name']), $_GET['id']
                );
            } elseif ($type == 'series') {
                $person = new Person();
                $person->setName($_POST['new_person_name']);
                $person->setBio($_POST['new_person_bio']);
                $this->storage->savePerson($person);
                $this->storage->addActorToSeries(
                    $this->storage->getIdOfActor($_POST['new_person_name']), $_GET['id']
                );
            }
        }
        if (isset($_POST['add_actor'])) {
            if ($type == 'movie') {
                $this->storage->addActorToMovie(
                    $this->storage->getIdOfActor($_POST['person']), $_GET['id']
                );
            } elseif ($type = 'series') {
                $this->storage->addActorToSeries(
                    $this->storage->getIdOfActor($_POST['person']), $_GET['id']
                );
            }
            
        }
        $persons = $this->storage->getPersons();
        for ($i = 0; $i < count($persons); $i++) {
            $persons[$i] = $persons[$i]->getName();
        }
        echo '<script type="text/javascript">
            function appendOptions() {
                let persons = ' . json_encode($persons) . ';
                persons.forEach(person => {
                    $("#person_select").append("<option value=\'" + person + "\'>" + person + "</option>");
                });
            }
        </script>';
        ob_start();
        echo $jquery;
        echo '<br />';
        echo '<h3>Regisseur/Produzent</h3>';
        echo '<ul class="list-group">';
        if (isset($directors)) {
            foreach ($directors as $director) {
                echo '<form method="post">';
                echo '<li class="list-group-item">' . $director->getName()
                . '<input value="Löschen" name="remove_director" type="submit" style="float:right;">
                <input value="Details" name="director_details" type="submit" style="float:right;">
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
        $jquery = '<script type="text/javascript">
        $(document).ready(function() {
            $("#new_director").click(function() {
                $("#new_director").hide();
                $("#new_director_form").append(
                    "<button id=\'new_person\' type\"button\" class=\'btn btn-info\'>Neuer Regisseur</button>",
                    " ",
                    "<button id=\'existing_person\' type=\'button\' class=\'btn btn-info\'>Bestehender Regisseur</button>",
                    "<div id=\'person_div\'></div>"
                );
                $("#new_person").click(function() {
                    console.log("a");
                    $("#new_person").hide();
                    $("#existing_person").hide();
                    $("#person_div").append(
                        "<form id=\'new_form\' method=\'post\'>"
                    );
                    $("#new_form").append(
                        "<label for\'new_person_name\'><strong>Name</strong></label><br />",
                        "<input type=\'text\' name=\'new_person_name\'><br />",
                        "<label for\'new_person_bio\'><strong>Biografie</strong></label><br />",
                        "<input type=\'text\' name=\'new_person_bio\'><br />",
                        "<input type=\'submit\' name=\'new_person_submit_director\' value=\'Hinzufügen\'>"
                    );
                });
                $("#existing_person").click(function() {
                    $("#new_person").hide();
                    $("#existing_person").hide();
                    $("#person_div").append(
                        "<form id=\'select_form_director\' method=\'post\'>"
                    );
                    $("#select_form_director").append(
                        "<select id=\'person_select_director\' name=\'person\'>",
                        "&nbsp<input type=\'submit\' value=\'Hinzufügen\' name=\'add_director\'>"
                    );
                    appendOptionsDirector();
                });
            });
        });
        </script>';
        echo $jquery;
        echo '<script type="text/javascript">
            function appendOptionsDirector() {
                let persons = ' . json_encode($persons) . ';
                persons.forEach(person => {
                    console.log(person);
                    $("#person_select_director").append("<option value=\'" + person + "\'>" + person + "</option>");
                });
            }
        </script>';
        if (isset($_POST['new_person_submit_director'])) {
            if ($type == 'movie') {
                $person = new Person();
                $person->setName($_POST['new_person_name']);
                $person->setBio($_POST['new_person_bio']);
                $this->storage->savePerson($person);
                $this->storage->addDirectorToMovie(
                    $this->storage->getIdOfDirector($_POST['new_person_name']), $_GET['id']
                );
            } elseif ($type == 'series') {
                $person = new Person();
                $person->setName($_POST['new_person_name']);
                $person->setBio($_POST['new_person_bio']);
                $this->storage->savePerson($person);
                $this->storage->addDirectorToSeries(
                    $this->storage->getIdOfDirector($_POST['new_person_name']), $_GET['id']
                );
            }
        }
        if (isset($_POST['add_director'])) {
            if ($type == 'movie') {
                $this->storage->addDirectorToMovie(
                    $this->storage->getIdOfDirector($_POST['person']), $_GET['id']
                );
            } elseif ($type == 'series') {
                $this->storage->addDirectorToSeries(
                    $this->storage->getIdOfDirector($_POST['person']), $_GET['id']
                );
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