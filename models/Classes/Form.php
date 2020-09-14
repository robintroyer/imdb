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
            isset($_POST['name'])
            && isset($_POST['bio'])
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
        $title = '<input type="text" name="title">';

        $submit = '<input id="submit" type="submit">';
        $new_actor = '<input type="submit" name="new_actor" value="Neuer Schauspieler">';

        echo '<form id="form" method="post"><strong>Neuen Film anlegen</strong><br />Titel:<br />' . $title . '<br />';
        echo '<label for="director">Regisseur</label><br />';
        echo '<select name="director">';
        foreach ($persons as $person) {
            echo '<option value="' . $person->getName() . '">' . $person->getName() . '</option>';
        }
        echo '</select><br />';
        echo '<label for="actor">Schauspieler</label><br />';

        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>';
        $option_string = '';
        foreach ($persons as $person) {
            $option_string .= '<option value\'' . $person->getname() . '\'>' . $person->getName() . '</option>';
        }
        
        echo '<script type="text/javascript">
            $(document).ready(function() {
                $("#add").click(function() {
                    $("#form").append("<br /><select>' . $option_string . '</select>");
                })
            });
        </script>';

        
        
        echo '<select name="actor">';
        foreach ($persons as $person) {
            echo '<option value="' . $person->getName() . '">' . $person->getName() . '</option>';
        }
        
        echo '</select></form><br />';
        echo '<button type="button" name="add" id="add">Neuer Schauspieler</button><br />';
        echo $submit . '</form>';

        
        
    }
}