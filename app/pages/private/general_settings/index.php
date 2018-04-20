<?php
class general_settings extends page {
    function get_page($args) {

        $settings = database::sql()->query("select * from settings order by `settings_id`");
        $this->set("settings", $settings);

        if(isset($args[0]) && $args[0] == "edit") {
            $this->set('settings', false);
            foreach($settings as $s) {
                if($s['settings_id'] == $args[1]) {
                    $this->set('settings', $s);
                }
            }
            if($args[1] == "new") $this->set('settings', ["settings_id"=>"new"]);
            $this->render("template_edit");
            return;
        }

        $this->set("page_title", "General Settings");
        $this->render("header");
        $this->render();
        $this->render("footer");
    }

    function post_page($args) {

        if($_POST['action'] == "delete") {
            $t = new settings();
            $t->load($_POST['id']);
            if(!$t->loaded()) {
                echo "Invalid id";
                exit();
            }

            $t->delete_object();
            echo "OK";
            return;
        }


        $settings_id = $_POST['settings_id'];
        $ob = new settings();

        if($settings_id != "new") {
            $ob->load($settings_id);
            if(!$ob->loaded()) {
                echo "Invalid settings id";
                return;
            }
        }

        $ob->set('settings_key', $_POST['settings_key']);
        $ob->set('settings_value', $_POST['settings_value']);
        $ob->save();

        echo "OK";
    }
}