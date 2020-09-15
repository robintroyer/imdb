<?php
class Form
{
    private $storage;
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
    public function newPerson()
    {
        $name = '<input type="text" name="name">';
        $bio = '<input type="text" name="bio">';
        $submit = '<input type="submit">';
        echo '<form method="post"><strong>Neue Person anlegen</strong><br />Name:<br />' . $name . '<br />Biografie:<br />' . $bio . '<br />' . $submit . '</form>';
        if (
            // isset($_POST['name'])
            // && isset($_POST['bio'])
            !empty($_POST['name'])
            && !empty($_POST['bio'])
        ) {
            $person = new Person();
            $person->setName($_POST['name']);
            $person->setBio($_POST['bio']);
            $this->storage->savePerson($person);
        }
    }
    public function newMovie()
    {
        $persons = $this->storage->getPersons();
        // print_r($persons);

        $title = '<input type="text" name="title">';

        $submit = '<input id="submit" type="submit" name="submit">';
        // $new_actor = '<input type="submit" name="new_actor" value="Neuer Schauspieler">';

        echo '<form id="form" method="post"><strong>Film/Serie anlegen</strong><br />';
        echo '<div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="checkbox_movie" id="inlineRadio1" value="movie">
                <label class="form-check-label" for="inlineRadio1">Film</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="checkbox_series" id="inlineRadio2" value="series">
                <label class="form-check-label" for="inlineRadio2">Serie</label>
            </div><br />';
        echo 'Titel:<br />' . $title . '<br />';
        echo '<label for="director">Regisseur/Produzent</label><br />';
        echo '<select name="directors[]">';
        echo '<option>---</option>';
        foreach ($persons as $person) {
            echo '<option value="' . $person->getID() . '">' . $person->getName() . '</option>';
        }
        echo '</select><br />';
        echo '<button type="button" name="add" id="add_director">Neuer Regisseur/Produzent</button><br />';

        echo '<label id="actor_label" for="actor">Schauspieler</label><br />';

        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
        $option_string = '';
        $option_string .= '<option>---</option>';
        foreach ($persons as $person) {
            $option_string .= '<option value=\'' . $person->getID() . '\'>' . $person->getName() . '</option>';
        }
        
        

        
        
        echo '<select name="actors[]">';
        echo '<option>---</option>';
        foreach ($persons as $person) {
            echo '<option value="' . $person->getID() . '">' . $person->getName() . '</option>';
        }
        
        echo '</select><br />';
        echo '<script type="text/javascript">
            $(document).ready(function() {
                $("#add").click(function() {
                    $("#form select:last").after("<br /><select name=\'actors[]\'>' . $option_string . '</select>");
                })
                $("#add_director").click(function() {
                    $("#add_director").before("<select name=\'directors[]\'>' . $option_string . '</select><br />");
                })
            });
        </script>';
        echo '<button type="button" name="add" id="add">Neuer Schauspieler</button><br />';
        echo $submit . '</form>';

        if (isset($_POST['submit'])) {
            if (
                !empty($_POST['title'])
                && !empty($_POST['directors'])
                && !empty($_POST['actors'])
            ) {
                if (isset($_POST['checkbox_movie'])) {
                    $movie = new Movie();
                    $movie->setTitle($_POST['title']);
                    $this->storage->saveMovie($movie, $_POST['actors'], $_POST['directors']);    
                } elseif (isset($_POST['checkbox_series'])) {
                    $series = new Series();
                    $series->setTitle($_POST['title']);
                    $this->storage->saveSeries($series, $_POST['actors'], $_POST['directors']);
                }
            }
        }
    }
}