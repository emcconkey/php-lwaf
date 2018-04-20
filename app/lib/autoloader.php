<?php

/**
 * Class autoloader
 *
 * This provides the autoloading functionality for the application framework. See the main index.php for an
 * example of how to instantiate and configure.
 *
 */
class autoloader {

    private $_dirs = [];

    function __construct() {
        spl_autoload_register([$this, "autoload"]);
    }

    /**
     * Autoloads the classes
     *
     * This searches through the $_dirs to find the class and loads it.
     *
     * @param $class
     * @return bool
     */
    function autoload($class) {
        foreach($this->_dirs as $d => $v) {
            if(file_exists($d . $class . ".php")) {
                require_once($d . $class . ".php");
                return true;
            }
        }
        return false;
    }

    /**
     * Adds a directory to the class search path
     *
     * @param $d
     */
    function add_dir($d) {
        $this->_dirs[$d] = true;
    }

    /**
     * Removes a directory from the class search path
     *
     * @param $d
     */
    function remove_dir($d) {
        unset($this->_dirs[$d]);
    }
}
