<?php
class trigger_list extends page {
    function get_page($args) {

        $triggers = database::sql()->query("select * from event_trigger");
        $this->set("triggers", $triggers);

        $this->set("page_title", "Event Trigger List");
        $this->render("header");
        $this->render();
        $this->render("footer");
    }

    function post_page($args) {

    }
}