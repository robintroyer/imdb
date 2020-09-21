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
        } elseif ($_POST['person_details_type'] == 'series') {
            $storage->deleteActorOfSeries($_POST['person_details_id'], $_GET['id']);
        }
    }
}
if (isset($_POST['director_details_type'])) {
    if (isset($_POST['remove_director'])) {
        if ($_POST['director_details_type'] == 'movie') {
            $storage->deleteDirectorOfMovie($_POST['director_details_id'], $_GET['id']);
        } elseif ($_POST['director_details_type'] == 'series') {
            $storage->deleteDirectorOfSeries($_POST['director_details_id'], $_GET['id']);
        }
    }
}
?>
<!doctype html>
    <html>
        <head>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script type="text/javascript">
                persons = <?php echo json_encode($persons) ?>;
                function appendOptions()
                {
                    persons.forEach(person => {
                        $('#person_select').append('<option value="' + person + '">' + person + '</option>');
                    });
                }
                // function appendOptionsDirectors()
                // {

                // }
                $(document).ready(function() {
                    $(document).on('click', '#new_actor', function() {
                        $('#new_actor').remove();
                        // $('#new_form').remove();
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
        </head>
        <body>
            <button onclick="history.go(-1)">Zurück</button>
        </body>
    </html>