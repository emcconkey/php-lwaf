<?php

$GLOBALS['config'] = false;

/**
 * Class config
 *
 * This loads the configuration variables from the site_config.php file and then provides static functions to
 * access those variables.
 *
 */
class config {

    /**
     * Loads the configuration
     *
     * This reads in site_config.php and loads in the variables
     *
     */
    static function load() {
        $ini_data = parse_ini_file("../site_config.php");
        $GLOBALS['config'] = $ini_data;
    }

    /**
     * Get a configuration variable
     *
     * @param $key
     * @return mixed
     */
    static function get($key) {
        if(!$GLOBALS['config']) {
            config::load();
        }
        return $GLOBALS['config'][$key];
    }

}
