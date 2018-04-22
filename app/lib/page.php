<?php

/**
 * Class page
 *
 * This is the class the handles the rendering of all the pages
 */
class page {
    private $vars;
    private $_template_dirs = [
        '../usr/templates',
        '../app/templates'
    ];

    /**
     * Stub of the function that is called prior to routing to this page
     *
     * The router will pass the page arguments to the page
     *
     * @param $args
     */
    function pre_route($args) {

    }

    /**
     * Stub of the function that is called after a page is displayed
     *
     * The router will pass the page arguments to the page
     *
     * @param $args
     */
    function post_route($args) {

    }

    /**
     * Loads and executes a page template
     *
     * If called with no arguments, it will look for template.php in the same folder as the page that's currently
     * being displayed.
     *
     * It will search for $template in the current directory (with or without the .php extension)
     * It will then search for the $template in the /templates directory
     *
     * If no templates are found it will echo "no template found"
     *
     * @param string $template
     */
    function render($template = false) {
        if($this->get('page_title') == "") $this->set('page_title', "No Page Title");
        if(!$template) $template = "template.php";

        if(file_exists($GLOBALS["page_dir"] . $template . ".php")) {
            include($GLOBALS["page_dir"] . $template . ".php");
            return;
        }

        if(file_exists($GLOBALS["page_dir"] . $template)) {
            include($GLOBALS["page_dir"] . $template);
            return;
        }


        foreach($this->_template_dirs as $tdir) {

            if(file_exists("$tdir/$template.php")) {
                include("$tdir/$template.php");
                return;
            }

            if(file_exists("$tdir/$template")) {
                include("$tdir/$template");
                return;
            }

        }

        echo "No template found";
        return;
    }

    /**
     * Makes a php variable available on the page as a javascript variable
     *
     * Note that this creates a new javascript variable and does no syncing back to the php variable
     *
     * @param $var
     * @param $value
     */
    function set_js_var($var, $value) {
        $tmp = $this->get("extra_head");
        $tmp .= "<script>var $var = $value;</script>\n";
        $this->set("extra_head", $tmp);
    }

    /**
     * Adds a javascript file to the header of the page
     *
     * @param $file
     */
    function add_js($file) {
        $tmp = $this->get("extra_head");
        $tmp .= '<script src="/' . $file . '"></script>' . "\n";
        $this->set("extra_head", $tmp);
    }

    /**
     * Adds a css file to the header of the page
     *
     * @param $file
     */
    function add_css($file) {
        $tmp = $this->get("extra_head");
        $tmp .= '<link rel="stylesheet" href="/' . $file . '">' . "\n";
        $this->set("extra_head", $tmp);
    }

    /**
     * Forces the browser to reload the current page
     *
     */
    function reload() {
        $loc = $_SERVER['REQUEST_URI'];

        header("Location: " . $loc);
    }

    /**
     * Sets a variable in the page that can be read from the templates
     *
     * @param $var
     * @param $val
     */
    function set($var, $val) {
        $this->vars[$var] = $val;
    }

    /**
     * Gets a variable that was set in the page
     *
     * @param $var
     * @return bool
     */
    function get($var) {
        if(isset($this->vars[$var])) return $this->vars[$var];
        return false;
    }

    /**
     * Stub of the function that is called when an HTTP GET is sent to this page
     *
     * @param $args
     */
    function get_page($args) {
        echo "Page loaded: " . get_class($this) . ", Method: GET<br>";
        echo "No get_page(\$args) {} function defined for class " . get_class($this);
    }

    /**
     * Stub of the function that is called when an HTTP POST is sent to this page
     *
     * @param $args
     */
    function post_page($args) {
        echo "Page loaded: " . get_class($this) . ", Method: POST";
        echo "No post_page(\$args) {} function defined for class " . get_class($this);
    }

    /**
     * Stub of the function that is called when an HTTP PUT is sent to this page
     *
     * @param $args
     */
    function put_page($args) {
        echo "Page loaded: " . get_class($this) . ", Method: PUT";
        echo "No put_page(\$args) {} function defined for class " . get_class($this);
    }
    /**
     * Stub of the function that is called by the router to see if a user can view this page
     *
     * Override this function in the page to restrict who gets access to this page
     *
     * @param $user
     * @return bool
     */
    function can_view($user) {
        return true;
    }

}