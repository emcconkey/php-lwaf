<?php

/**
 * Class router
 *
 * This handles the page routing for the application
 */
class router {
    var $_rewrites = [];
    var $_request = "";
    private $_public_page_dirs = [
        '../usr/pages/public',
        '../app/pages/public'
    ];
    private $_private_page_dirs = [
        '../usr/pages/private',
        '../app/pages/private'
    ];

    /**
     * Routes to the page based on the REQUEST_URI
     *
     * If a user is logged in, it will check the private pages first for the existence of a page.
     * If a page is not found, or the user is not logged in, the router will then check the public pages.
     *
     * The router will only route to classes that extend the page class (or have the pre_route() member
     * function)
     *
     */
    function route() {
        // Check redirects
        foreach($this->_rewrites as $old => $new) {
            if ($_SERVER['REQUEST_URI'] == $old) {
                $_SERVER['REQUEST_URI'] = $new;
            }
        }

        $request = $_SERVER['REQUEST_URI'];

        // Happens sometimes when people copy/edit/paste links, so clear out duplicate slashes
        $request = str_replace("//", "/", $request);
        $params = explode("?", $request);
        $params = explode("/", $params[0]);
        // Get rid of the first empty element
        unset($params[0]);
        $class = array_shift($params);
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        $found = false;
        if(user::current()->is_logged_in()) {
            foreach($this->_private_page_dirs as $pdir) {
                if(!$found && file_exists("$pdir/$class/index.php")) {
                    $GLOBALS["page_dir"] = "$pdir/$class/";
                    require_once("$pdir/$class/index.php");
                    $found = true;
                }
            }
        }

        if(!$found) {
            foreach ($this->_public_page_dirs as $pdir) {
                if (!$found && file_exists("$pdir/$class/index.php")) {
                    $GLOBALS["page_dir"] = "$pdir/$class/";
                    require_once("$pdir/$class/index.php");
                    $found = true;
                }
            }
        }

        $this->_request = $request;

        if(!$found) {
            header("Location: /errorpage/404" . $request);
            return;
        }

        $vc = new $class;

        if(!method_exists($vc, "pre_route")) {
            header("Location: /");
            return;
        }

        if(!$vc->can_view(user::current())) {
            echo "Access denied";
            return;
        }

        $vc->pre_route($params);

        // We only handle GET and POST currently
        if($method == "get") {
            $vc->get_page($params);
        } elseif($method == "post") {
            $vc->post_page($params);
        } else {
            header("Location: /");
        }

        if(method_exists($vc, "post_route")) {
            $vc->post_route($params);
        }

    }

    /**
     * Adds a rewrite rule
     *
     * Will redirect users from $old_url to $new_url
     *
     * @param $old_url
     * @param $new_url
     */
    function add_rewrite($old_url, $new_url) {
        $this->_rewrites[$old_url] = $new_url;
    }

    /**
     * Removes a rewrite rule
     *
     * @param $old_url
     */
    function remove_rewrite($old_url) {
        unset($this->_rewrites[$old_url]);
    }

    /**
     * Returns the request string
     *
     * @return string
     */
    function get_request() {
        return $this->_request;
    }

}
