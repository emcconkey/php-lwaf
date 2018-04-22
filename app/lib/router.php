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

        $request = $this->calculate_route();

        $method = strtolower($_SERVER['REQUEST_METHOD']);

        $valid_route = $this->valid_route($request);

        if(!$valid_route) {
            header("Location: /errorpage/404" . $request);
            return;
        }

        $params = $this->get_params($request);

        $class = $this->route_class($request);

        $this->run_route($method, $class, $params);
    }

    function run_route($method, $class, $params) {

        $vc = new $class;

        if(!method_exists($vc, "pre_route")) {
            header("Location: /");
            return;
        }

        if(!$vc->can_view(user::current())) {
            echo "Access denied";
            return;
        }

        // If pre_route() returns false, don't continue rendering the page
        if(!$vc->pre_route($params)) {
            return;
        }

        // We handle GET, POST, PUT, and PATCH
        if($method == "get") {
            $vc->get_page($params);
        } elseif($method == "post") {
            $vc->post_page($params);
        } elseif($method == "put") {
            $vc->put_page($params);
        } elseif($method == "patch") {
            $vc->patch_page($params);
        } else {
            header("Location: /");
        }

        if(method_exists($vc, "post_route")) {
            $vc->post_route($params);
        }

    }

    /**
     * Calculates and returns the route for the current request
     *
     * @return string
     */
    function calculate_route() {

        // Happens sometimes when people copy/edit/paste links, so clear out duplicate slashes
        $request = str_replace("//", "/", $_SERVER['REQUEST_URI']);

        // Check redirects
        foreach($this->_rewrites as $old => $new) {
            if(strpos($old, "!") !== false) {
                $old = str_replace("!", "", $old);
                // Absolute replace routes
                if ($request == $old) {
                    $request = $new;
                }
            } else {
                // Partial replace routes
                if (substr($request, 0, strlen($old)) == $old) {
                    $request = $new . substr($request, strlen($old));
                }
            }
        }

        // Store this so child classes can use it
        $this->_request = $request;

        return $request;
    }

    /**
     * Returns the parameters of the current request
     *
     * @param $request
     * @return array
     */
    function get_params($request) {

        $params = explode("?", $request);
        $params = explode("/", $params[0]);

        // Get rid of the first two elements
        array_shift($params);
        array_shift($params);

        return $params;
    }

    /**
     * Returns the class that we need to call for this route
     *
     * @param $request
     * @return mixed
     */
    function route_class($request) {
        $params = explode("?", $request);
        $params = explode("/", $params[0]);

        // Get rid of the first empty element
        array_shift($params);
        $class = array_shift($params);

        return $class;
    }

    /**
     * Checks to see if the specified route is valid for the current user
     *
     * @param $route
     * @return bool
     */
    function valid_route($request) {


        $class = $this->route_class($request);

        $valid = false;

        if(user::current()->is_logged_in()) {
            foreach($this->_private_page_dirs as $pdir) {
                if(!$valid && file_exists("$pdir/$class/index.php")) {
                    $GLOBALS["page_dir"] = "$pdir/$class/";
                    require_once("$pdir/$class/index.php");
                    $valid = true;
                }
            }
        }

        if(!$valid) {
            foreach ($this->_public_page_dirs as $pdir) {
                if (!$valid && file_exists("$pdir/$class/index.php")) {
                    $GLOBALS["page_dir"] = "$pdir/$class/";
                    require_once("$pdir/$class/index.php");
                    $valid = true;
                }
            }
        }

        return $valid;

    }

    /**
     * Adds a rewrite rule
     *
     * Will redirect users from $old_url to $new_url
     *
     * @param $old_url
     * @param $new_url
     * @param $partial
     */
    function add_rewrite($old_url, $new_url, $partial = true) {
        if(!$partial) $old_url = $old_url . "!";
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
