<?php
class trigger_edit extends page {
    function get_page($args) {

        if($args[0] != "new") {
            $trigger = database::sql()->load_object("event_trigger", $args[0]);
            if(!$trigger) {
                echo "Invalid ID";
                exit();
            }
        } else {
            $trigger = new event_trigger();
            $trigger->set("event_trigger_id", "new");
        }

        $this->set("trigger", $trigger);
        $this->set("page_title", "Edit Trigger");
        $this->render("header");
        $this->render();
        $this->render("footer");
    }

    function post_page($args) {
        $ob = new event_trigger();
        $id = intval($_POST['event_trigger_id']);

        if($id != 0) {
            $ob->load($id);
            if(!$ob->loaded()) {
                echo "Invalid object ID";
                return;
            }
        }

        $ob->set('event_table', $_POST['event_table']);
        $ob->set('event', $_POST['event']);
        $ob->set('status', $_POST['status']);
        $ob->set('description', $_POST['description']);
        $ob->set('code', $_POST['code']);
        $ob->save();
        $object_id = $ob->get("event_trigger_id");

        header("Location: /trigger_edit/$object_id");
    }
}