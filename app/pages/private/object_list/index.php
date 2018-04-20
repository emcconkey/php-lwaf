<?php
class object_list extends page {
    function get_page($args) {

        $ob = database::sql()->esc($args[0]);

        $objects = database::sql()->query("select * from $ob");

        $this->set("objects", $objects);

        $this->set("page_title", "Object List");
        $this->render("header");
        $this->render();
        $this->render("footer");
    }

    function post_page($args) {

        if($_POST['action'] == "delete") {
            $t = new deposit();
            $t->load($_POST['id']);
            if(!$t->loaded()) {
                echo "Invalid id";
                exit();
            }

            $t->delete_object();
            echo "OK";
            return;
        }

    }
}