<?php

class logout extends page {

    function get_page($args) {
        user::current()->logout();
        header("Location: /");
    }

}

?>