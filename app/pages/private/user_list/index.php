<?php
class user_list extends page {
    function get_page($args) {

        $users = database::sql()->query("select * from user");
        $this->set("users", $users);
        $this->set("page_title", "User List");

        $this->render("header");
        $this->render();
        $this->render("footer");
    }

    function post_page($args) {
        if(isset($_POST['delete']) && $_POST['delete'] == 'true') {
            $player_id = intval($_POST['_id']);
            if(!$player_id) {
                echo "Error, invalid ID";
                return;
            }
            database::sql()->query("delete from user where user_id=?", [$player_id], "i", 1);
            echo "OK";
        }
    }
}
