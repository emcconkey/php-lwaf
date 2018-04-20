<?
class user_edit extends page {
    function get_page($args) {
        $user = new user();
        $user->load($args[0]);
        if(!$user->loaded()) {
            echo "Invalid user";
            return;
        }
        $this->set("page_title", "Edit User");

        $this->set("breadcrumb",
            [
                [ "url" => "/user_list", "title" => "Users"]
            ]);

        $this->set("user", $user);

        $this->render("header");
        $this->render();
        $this->render("footer");
    }

    function post_page($args) {

        $user = new user();
        $user->load($args[0]);
        if(!$user->loaded()) {
            echo "Invalid user";
            return;
        }

        if($_POST['password'] != "") {
            $user->set_password($_POST['password']);
            html::success_message("Update", "Your password has been changed");
        }
        $user->set('first_name', $_POST['first_name']);
        $user->set('last_name', $_POST['last_name']);
        $user->set('email', $_POST['email']);
        $user->set('status', $_POST['status']);
        $user->save();
        html::success_message("Update", "Account details have been changed");
        $this->reload();
    }

}
