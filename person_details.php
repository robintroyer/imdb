<?php
if (is_readable(__DIR__ . '/config.php')) {
    require __DIR__ . '/config.php';
} else {
    die('Konfigurationsdatei nicht gefunden');
}
session_start();
require __DIR__ . '/vendor/autoload.php';


$storage = new Database();
$configDB = new stdClass();
$configDB->host = $DB_HOST;
$configDB->user = $DB_USER;
$configDB->pass = $DB_PASS;
$configDB->name = $DB_NAME;
$storage->initialize($configDB);
$form = new Form($storage);
$view = new View($storage);

$movies = $storage->getMovies();
for ($i = 0; $i < count($movies); $i++) {
    $movies[$i] = $movies[$i]->getTitle();
}
$series = $storage->getSeries();
for ($i = 0; $i < count($series); $i++) {
    $series[$i] = $series[$i]->getTitle();
}


// echo '<script src="' . __DIR__ . '\script.js"></script>';

$view->personDetailsPage();
if (isset($_POST['remove_movie_from_actor'])) {
    $storage->removeMovieFromActor($_POST['entry_details_id'], $_POST['entry_id']);
    $view->reloadPage('person_details');
}
if (isset($_POST['remove_series_from_actor'])) {
    $storage->removeSeriesFromActor($_POST['entry_details_id'], $_POST['entry_id']);
    $view->reloadPage('person_details');
}
if (isset($_POST['remove_movie_from_director'])) {
    $storage->removeMovieFromDirector($_POST['entry_details_id'], $_POST['entry_id']);
    $view->reloadPage('person_details');
}
if (isset($_POST['remove_series_from_director'])) {
    $storage->removeSeriesFromDirector($_POST['entry_details_id'], $_POST['entry_id']);
    $view->reloadPage('person_details');
}
if (isset($_POST['edit_submit'])) {
    $person = new Person();
    $person->setID($_GET['id']);
    $person->setName($_POST['edit_name']);
    $person->setBio($_POST['edit_bio']);
    $storage->editPerson($person);
    $view->reloadPage('person_details');
}
?>


<!doctype html>
    <html>
        <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script type="text/javascript">
            
                movies = <?php echo json_encode($movies); ?>;
                series = <?php echo json_encode($series); ?>;

                // console.log(movies);
                // console.log(series);
                function appendOptions()
                {
                    movies.forEach(movie => {
                        $("#movies_select").append('<option value="' + movie + '">' + movie + '</option>');
                    });
                }
                function appendOptionsSeries()
                {
                    series.forEach(s => {
                        $("#series_select").append('<option value="' + s + '">' + s + '</option>');
                    });
                }
                $(document).ready(function() {
                    $(document).on('click', '#edit_person', function() {
                        $('#edit_person').remove();
                        $('#person_bio').after('<div id="edit_div">');
                        $('#edit_div').append('<form method="post" id="edit_form">');
                        name = document.getElementById('person_name').textContent;
                        bio = document.getElementById('person_bio').textContent;
                        $('#edit_form').append(
                            '<input type="text" name="edit_name" value="' + name + '"><br />',
                            '<input type="text" name="edit_bio" value="' + bio + '"><br />',
                            '<input type="submit" name="edit_submit" value="Senden">'  
                        );
                    })
                    $(document).on('click', '#new_movie', function() {
                        $("#new_movie").remove();
                        $("#movie_div").remove();
                        $("#new_form").remove();
                        $("#select_form").remove();
                        $("#new_movie_buttons").append(
                            "<button id='new_movie_button' type'button' class='btn btn-info'>Neuer Film</button>",
                            " ",
                            "<button id='existing_movie' type='button' class='btn btn-info'>Bestehender Film</button>",
                            "<div id='movie_div'></div>"
                        );
                        if (document.getElementById('new_series') == null) {
                            $("#new_series_buttons").before('<button id="new_series" class="btn btn-success">Serie hinzufügen</button>');
                            $("#new_series_buttons").empty();
                        }
                        if (document.getElementById('new_directed_movie') == null) {
                            $("#new_directed_movie_buttons").before('<button id="new_directed_movie" class="btn btn-success">Film hinzufügen</button>');
                            $("#new_directed_movie_buttons").empty();
                        }
                        if (document.getElementById('new_directed_series') == null) {
                            $("#new_directed_series_buttons").before('<button id="new_directed_series" class="btn btn-success">Serie hinzufügen</button>');
                            $("#new_directed_series_buttons").empty();
                        }
                    });
                    $(document).on('click', "#new_series", function() {
                        $("#new_series").remove();
                        $("#movie_div").remove();
                        $("#new_form").remove();
                        $("#select_form").remove();
                        $("#new_series_buttons").append(
                            "<button id='new_series_button' type'button' class='btn btn-info'>Neue Serie</button>",
                            " ",
                            "<button id='existing_series' type=\'button' class='btn btn-info'>Bestehende Serie</button>",
                            "<div id='series_div'></div>"
                        );
                        if (document.getElementById('new_movie') == null) {
                            $("#new_movie_buttons").before('<button id="new_movie" class="btn btn-success">Film hinzufügen</button>');
                            $("#new_movie_buttons").empty();
                        }
                        if (document.getElementById('new_directed_movie') == null) {
                            $("#new_directed_movie_buttons").before('<button id="new_directed_movie" class="btn btn-success">Film hinzufügen</button>');
                            $("#new_directed_movie_buttons").empty();
                        }
                        if (document.getElementById('new_directed_series') == null) {
                            $("#new_directed_series_buttons").before('<button id="new_directed_series" class="btn btn-success">Serie hinzufügen</button>');
                            $("#new_directed_series_buttons").empty();
                        }
                    });
                    $(document).on('click', "#new_directed_movie", function() {
                        $("#new_directed_movie").remove();
                        $("#movie_div").remove();
                        $("#new_form").remove();
                        $("#select_form").remove();
                        $("#new_directed_movie_buttons").append(
                            "<button id='new_directed_movie_button' type'button' class='btn btn-info'>Neuer Film</button>",
                            " ",
                            "<button id='existing_directed_movie' type='button' class='btn btn-info'>Bestehender Film</button>",
                            "<div id='movie_div'></div>"
                        );
                        if (document.getElementById('new_movie') == null) {
                            $("#new_movie_buttons").before('<button id="new_movie" class="btn btn-success">Film hinzufügen</button>');
                            $("#new_movie_buttons").empty();
                        }
                        if (document.getElementById('new_series') == null) {
                            $("#new_series_buttons").before('<button id="new_series" class="btn btn-success">Serie hinzufügen</button>');
                            $("#new_series_buttons").empty();
                        }
                        if (document.getElementById('new_directed_series') == null) {
                            $("#new_directed_series_buttons").before('<button id="new_directed_series" class="btn btn-success">Serie hinzufügen</button>');
                            $("#new_directed_series_buttons").empty();
                        }
                    });
                    $(document).on('click', "#new_directed_series", function() {
                        $("#new_directed_series").remove();
                        $("#movie_div").remove();
                        $("#new_form").remove();
                        $("#select_form").remove();
                        $("#new_directed_series_buttons").append(
                            "<button id='new_directed_series_button' type'button' class='btn btn-info'>Neue Serie</button>",
                            " ",
                            "<button id='existing_directed_series' type='button' class='btn btn-info'>Bestehende Serie</button>",
                            "<div id='series_div'></div>"
                        );
                        if (document.getElementById('new_movie') == null) {
                            $("#new_movie_buttons").before('<button id="new_movie" class="btn btn-success">Film hinzufügen</button>');
                            $("#new_movie_buttons").empty();
                        }
                        if (document.getElementById('new_series') == null) {
                            $("#new_series_buttons").before('<button id="new_series" class="btn btn-success">Serie hinzufügen</button>');
                            $("#new_series_buttons").empty();
                        }
                        if (document.getElementById('new_directed_movie') == null) {
                            $("#new_directed_movie_buttons").before('<button id="new_directed_movie" class="btn btn-success">Film hinzufügen</button>');
                            $("#new_directed_movie_buttons").empty();
                        }
                    });
                    $(document).on('click', "#new_movie_button", function() {
                        $("#new_movie_button").hide();
                        $("#existing_movie").hide();
                        $("#movie_div").append(
                            "<form id='new_form' method='post'>",
                        );
                        $("#new_form").append(
                            "<label for'new_movie_title'><strong>Name</strong></label><br />",
                            "<input type='text' name='new_movie_title'><br />",
                            "<input type='submit' name='new_movie_submit' value='Hinzufügen'>",
                        );
                    });
                    $(document).on('click', "#existing_movie", function() {
                        $("#new_movie_button").remove();
                        $("#existing_movie").remove();
                        $("#movie_div").append(
                            "<form id='select_form' method='post'>"
                        );
                        $("#select_form").append(
                            "<select id='movies_select' name='movie'>",
                            "&nbsp<input type='submit' value='Hinzufügen' name='add_movie'>"
                        );
                        appendOptions();
                    });
                    $(document).on('click', "#new_directed_movie_button", function() {
                        $("#new_movie_button").hide();
                        $("#existing_movie").hide();
                        $("#movie_div").append(
                            "<form id='new_form' method='post'>",
                        );
                        $("#new_form").append(
                            "<label for'new_movie_title'><strong>Name</strong></label><br />",
                            "<input type='text' name='new_movie_title'><br />",
                            "<input type='submit' name='new_directed_movie_submit' value='Hinzufügen'>",
                        );
                    });
                    $(document).on('click', "#existing_directed_movie", function() {
                        $("#new_movie_button").remove();
                        $("#existing_movie").remove();
                        $("#movie_div").append(
                            "<form id='select_form' method='post'>"
                        );
                        $("#select_form").append(
                            "<select id='movies_select' name='movie'>",
                            "&nbsp<input type='submit' value='Hinzufügen' name='add_directed_movie'>"
                        );
                        appendOptions();
                    });
                    $(document).on('click', "#new_series_button", function() {
                        $("#new_series_button").hide();
                        $("#existing_series").hide();
                        $("#series_div").append(
                            "<form id='new_form' method='post'>",
                        );
                        $("#new_form").append(
                            "<label for'new_series_title'><strong>Name</strong></label><br />",
                            "<input type='text' name='new_series_title\'><br />",
                            "<input type='submit' name='new_series_submit' value='Hinzufügen'>",
                        );
                    });
                    $(document).on('click', "#existing_series", function() {
                        $("#new_series_button").hide();
                        $("#existing_series").hide();
                        $("#series_div").append(
                            "<form id='select_form' method='post'>"
                        );
                        $("#select_form").append(
                            "<select id='series_select' name='series'>",
                            "&nbsp<input type='submit' value='Hinzufügen' name='add_series'>"
                        );
                        appendOptionsSeries();
                    });
                    $(document).on('click', "#new_directed_series_button", function() {
                        $("#new_series_button").hide();
                        $("#existing_series").hide();
                        $("#series_div").append(
                            "<form id='new_form' method='post'>",
                        );
                        $("#new_form").append(
                            "<label for'new_series_title'><strong>Name</strong></label><br />",
                            "<input type='text' name='new_series_title\'><br />",
                            "<input type='submit' name='new_directed_series_submit' value='Hinzufügen'>",
                        );
                    });
                    $(document).on('click', "#existing_directed_series", function() {
                        $("#new_series_button").hide();
                        $("#existing_series").hide();
                        $("#series_div").append(
                            "<form id='select_form' method='post'>"
                        );
                        $("#select_form").append(
                            "<select id='series_select' name='series'>",
                            "&nbsp<input type='submit' value='Hinzufügen' name='add_directed_series'>"
                        );
                        appendOptionsSeries();
                    });
                
                });
            </script>
        </head>
        <body>
            <button onclick="history.go(-1)">Zurück</button>
        </body>
    </html>