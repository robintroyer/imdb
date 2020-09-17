$(document).ready(function() {
    $("#new_actor").click(function() {
        console.log("x");
        $("#new_actor").hide();
        
        $("#new_actor_form").append(
            "<button id=\"new_person\" type\"button\" class=\"btn btn-info\">Neuer Schauspieler</button>",
            "<div id=\"new_person_form\"></div>",
            " ",
            "<button id=\"existing_person\" type=\"button\" class=\"btn btn-info\">Bestehender Schauspieler</button>",
            "<div id=\"existing_person_select\"></div>"
        );
    })
    $("#new_person").click(function() {
        $("#new_person_form").append(
            "<form>",
            "<input type=\"text\" name=\"new_person_name\">",
            "<input type=\"text\" name=\"new_person_bio\">",
            "<input type=\"submit\" name=\"new_person_submit\" value=\"Hinzufügen\">",
            "</form>"
        );
    })
});