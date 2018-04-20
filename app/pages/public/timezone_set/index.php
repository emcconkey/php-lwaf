<?php
class timezone_set extends page {
    function get_page($args) {
        $_SESSION['timezone'] = intval($_GET['tz']);
        user::current()->set('timezone', $_SESSION['timezone']);
        user::current()->save();
    }
}
