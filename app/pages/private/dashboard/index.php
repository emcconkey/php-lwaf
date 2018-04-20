<?php
class dashboard extends page {
    function get_page($args) {

        $this->set("no_header", true);
        $this->set("page_title", "Dashboard");
        $this->render("header");
        $this->render();
        $this->render("footer");
    }


    function post_page($args) {

    }


}
