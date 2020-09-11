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
        echo '<form method="post">Neue Person anlegen<br />' . $name . '<br />' . $bio . '<br />' . $submit . '</form>';
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
}