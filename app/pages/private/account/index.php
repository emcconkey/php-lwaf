<?
class account extends page {
    function get_page($args) {
        $this->set("page_title", "My Account");
        $this->set("themes", $this->get_themes());
        $theme = user::current()->get('theme');
        if(!$theme) $theme = "static/html/themes/default.css";
        $this->set("theme", $theme);
        $this->render("header");
        $this->render();
        $this->render("footer");
    }

    function post_page($args) {

        if($_POST['password'] != "") {
            user::current()->set_password($_POST['password']);
            html::success_message("Update", "Your password has been changed");
        }
        user::current()->set('first_name', $_POST['first_name']);
        user::current()->set('last_name', $_POST['last_name']);
        user::current()->set('email', $_POST['email']);
        user::current()->set('theme', $_POST['theme']);
        user::current()->save();
        html::success_message("Update", "Account details have been changed");
        header("Location: /account");
    }

    function get_themes() {
        $themes = [];
        if ($handle = opendir('static/html/themes')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $themes["static/html/themes/$entry"] = str_replace(".css", "", ucfirst($entry));
                }
            }
            closedir($handle);
        }
        return $themes;
    }
}
