<?php
if (is_readable(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
} else {
    die('Konfigurationsdatei nicht gefunden');
}
session_start();
require __DIR__ . '/vendor/autoload.php';
echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
$storage = new Database();
$configDB = new stdClass();
$configDB->host = $DB_HOST;
$configDB->user = $DB_USER;
$configDB->pass = $DB_PASS;
$configDB->name = $DB_NAME;
$storage->initialize($configDB);
$form = new Form($storage);
$view = new View($storage);
$view->detailsPage();
$persons = $storage->getPersons();
for ($i = 0; $i < count($persons); $i++) {
    $persons[$i] = $persons[$i]->getName();
}
if (isset($_POST['person_details_type'])) {
    if (isset($_POST['remove_actor'])) {
        if ($_POST['person_details_type'] == 'movie') {
            $storage->deleteActorOfMovie($_POST['person_details_id'], $_GET['id']);
            $view->reloadPage('movie_details');
        } elseif ($_POST['person_details_type'] == 'series') {
            $storage->deleteActorOfSeries($_POST['person_details_id'], $_GET['id']);
            $view->reloadPage('movie_details');
        }
    }
}
if (isset($_POST['director_details_type'])) {
    if (isset($_POST['remove_director'])) {
        if ($_POST['director_details_type'] == 'movie') {
            $storage->deleteDirectorOfMovie($_POST['director_details_id'], $_GET['id']);
            $view->reloadPage('movie_details');
        } elseif ($_POST['director_details_type'] == 'series') {
            $storage->deleteDirectorOfSeries($_POST['director_details_id'], $_GET['id']);
            $view->reloadPage('movie_details');
        }
    }
}
if (isset($_POST['submit_edit_movie'])) {
    $movie = new Movie();
    $movie->setID($_GET['id']);
    $movie->setTitle($_POST['new_title']);
    $storage->editMovie($movie);
    $view->reloadPage('movie_details');
}
if (isset($_POST['submit_edit_series'])) {
    $series = new Series();
    $series->setID($_GET['id']);
    $series->setTitle($_POST['new_title']);
    print_r($series);
    $storage->editSeries($series);
    $view->reloadPage('movie_details');
}
?>
<!doctype html>
    <html>
        <head>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script type="text/javascript">
                persons = <?php echo json_encode($persons) ?>;
                function appendOptions()
                {
                    persons.forEach(person => {
                        $('#person_select').append('<option value="' + person + '">' + person + '</option>');
                    });
                }
                $(document).ready(function() {
                    $(document).on('click', '#edit_movie', function() {
                        $('#edit_movie').remove();
                        $('#title_heading').after('<div id="edit_div">');
                        $('#edit_div').append('<form method="post" id="edit_form">');
                        title = document.getElementById('title_movie').textContent;
                        $('#edit_form').append(
                            '<input type="text" name="new_title" value="' + title + '">',
                            '<input type="submit" name="submit_edit_movie" value="Senden">'
                        );
                    });
                    $(document).on('click', '#edit_series', function() {
                        $('#edit_series').remove();
                        $('#title_heading').after('<div id="edit_div">');
                        $('#edit_div').append('<form method="post" id="edit_form">');
                        title = document.getElementById('title_series').textContent;
                        $('#edit_form').append(
                            '<input type="text" name="new_title" value="' + title + '">',
                            '<input type="submit" name="submit_edit_series" value="Senden">'
                        );
                    })
                    $(document).on('click', '#new_actor', function() {
                        $('#new_actor').remove();
                        $("#new_actor_form").append(
                            "<button id='new_person' type'button' class='btn btn-info'>Neuer Schauspieler</button>",
                            " ",
                            "<button id='existing_person' type='button' class='btn btn-info'>Bestehender Schauspieler</button>",
                            "<div id='person_div'></div>"
                        );
                        if (document.getElementById('new_director') == null) {
                            $('#new_director_form').before('<button id="new_director" class="btn btn-success">Regisseur hinzufügen</button>');
                            $('#new_director_form').empty();
                        }
                    });
                    $(document).on('click', '#new_director', function() {
                        $('#new_director').remove();
                        $("#new_director_form").append(
                            "<button id='new_person_director' type'button' class='btn btn-info'>Neuer Regisseur</button>",
                            " ",
                            "<button id='existing_person_director' type='button' class='btn btn-info'>Bestehender Regisseur</button>",
                            "<div id='person_div'></div>"
                        );
                        if (document.getElementById('new_actor') == null) {
                            $('#new_actor_form').before('<button id="new_actor" class="btn btn-success">Schauspieler hinzufügen</button>');
                            $('#new_actor_form').empty();
                        }
                    });
                    $(document).on('click', '#new_person', function() {
                        $("#new_person").hide();
                        $("#existing_person").hide();
                        $("#person_div").append(
                            "<form id='new_form' method='post'>",
                        );
                        $("#new_form").append(
                            "<label for'new_person_name'><strong>Name</strong></label><br />",
                            "<input type='text' name='new_person_name'><br />",
                            "<label for'new_person_bio'><strong>Biografie</strong></label><br />",
                            "<input type='text' name='new_person_bio'><br />",
                            "<input type='submit' name='new_person_submit' value='Hinzufügen'>",
                        );
                    });
                    $(document).on('click', '#existing_person', function() {
                        $("#new_person").hide();
                        $("#existing_person").hide();
                        $("#person_div").append(
                            "<form id='select_form' method='post'>"
                        );
                        $("#select_form").append(
                            "<select id='person_select' name='person'>",
                            "&nbsp<input type='submit' value='Hinzufügen' name='add_actor'>"
                        );
                        appendOptions();
                    });
                    $(document).on('click', '#new_person_director', function() {
                        $("#new_person").hide();
                        $("#existing_person").hide();
                        $("#person_div").append(
                            "<form id='new_form' method='post'>",
                        );
                        $("#new_form").append(
                            "<label for'new_person_name'><strong>Name</strong></label><br />",
                            "<input type='text' name='new_person_name'><br />",
                            "<label for'new_person_bio'><strong>Biografie</strong></label><br />",
                            "<input type='text' name='new_person_bio'><br />",
                            "<input type='submit' name='new_person_submit_director' value='Hinzufügen'>",
                        );
                    });
                    $(document).on('click', '#existing_person_director', function() {
                        $("#new_person").hide();
                        $("#existing_person").hide();
                        $("#person_div").append(
                            "<form id='select_form' method='post'>"
                        );
                        $("#select_form").append(
                            "<select id='person_select' name='person'>",
                            "&nbsp<input type='submit' value='Hinzufügen' name='add_director'>"
                        );
                        appendOptions();
                    });
                })
            </script>
            <link rel="stylesheet" href="../assets/main.css">
        </head>
        <body>
            <button id="back" onclick="history.go(-1)"><img alt="Zurueck" src="../src/images/2x/baseline_keyboard_backspace_black_18dp.png"></button>
        </body>
    </html>