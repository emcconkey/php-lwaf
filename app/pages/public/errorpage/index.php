<?php
class errorpage extends page {
    function get_page($args) {
        if($args[0] == "404") {
            $this->set("page_title", "Page Not Found");
            $this->set("page_content", "The page you requested does not exist");
            $this->set("show_button", true);
            $this->render("errorpage");
            return;
        }

        if($args[0] == "denied") {
            $this->set("page_title", "Access Denied");
            $this->set("page_content", "You do not have access to the requested page");
            $this->set("show_button", true);
            $this->render("errorpage");
            return;
        }

        $this->set("page_title", "Unspecified Error");
        $this->set("page_content", "There was an unspecified error");
        $this->set("show_button", true);
        $this->render("errorpage");

    }
}