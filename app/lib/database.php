<?php

/**
 * Class database
 *
 * This class sets up a singleton and provides functions for all database access for the application
 *
 */
class database {
    var $_db = null;
    var $_debug_errors = true;
    var $_last_query = "";
    var $_table_field_cache = [];

    /**
     * database constructor.
     *
     * This initializes and connects to the database. If there is a problem, it will show the error page  with a
     * description of the problem.
     *
     * @param $dbserver
     * @param $dbuser
     * @param $dbpass
     * @param $dbname
     */
    function __construct($dbserver, $dbuser, $dbpass, $dbname) {
        if(!isset($_SESSION['sql_cache'])) {
            $_SESSION['sql_cache'] = array();
        }

        set_error_handler(function() { });
        $this->_db = new mysqli($dbserver, $dbuser, $dbpass, $dbname);
        restore_error_handler();

        if($this->_db->connect_errno) {
            $p = new page();
            $p->set("page_title", "Database Error");
            $p->set("page_content", $this->_db->connect_error . " (" . $this->_db->connect_errno . ")");
            $p->render("errorpage");
            exit();
        }
    }

    /**
     * The main static accessor function for the database connection
     *
     * This is the primary entry point for calling the database singleton.
     * Examples:
     *      database::sql()->query("select * from table");
     *      database::sql()->esc($input_from_user);
     *      database::sql()->last_query();
     *
     * @return mixed
     */
    static function sql() {
        return $GLOBALS['database'];
    }

    /**
     * Return the last inserted id
     *
     * Returns false if there isn't a last inserted id
     *
     * @return mixed
     */
    function insert_id() {
        $tmp = $this->_db->query("SELECT LAST_INSERT_ID()");
        $res = $tmp->fetch_all();
        if(isset($res[0][0]))
            return $res[0][0];

        return false;
    }

    /**
     * Runs real_escape_string() on the data
     *
     * @param $data
     * @return string
     */
    function esc($data) {
        return $this->_db->real_escape_string($data);
    }

    /**
     * Returns the last query that was executed
     *
     * @return string
     */
    function last_query() {
        return $this->_last_query;
    }

    /**
     * Executes a query
     *
     * This prepares the query, binds the parameters, and then executes the query. Note that
     * the offset and limit parameters are used after the data is returned from the database,
     * so if you are executing large queries, it is better to set up the limits and offsets in
     * the query itself.
     *
     * The $param_types is a string that is used to pass the types to bind_param. It is a string
     * with as many characters as there are parameters passed to bind_param. See
     * @link https://secure.php.net/manual/en/mysqli-stmt.bind-param.php for more information on
     * the param types that are used.
     *
     * Examples:
     *      database::sql()->query("select * from user where email=?", [ $_POST['email'], "s");
     *      database::sql()->query("select * from accounting where user_id=? and month=?",
     *                              [ $user_id, $_POST['month'], "ii");
     *      database::sql()->query("update user set photo_data=?", [ $file_data ], "b");
     *      database::sql()->query("select * from users", [], "", 25);
     *
     *
     * @param string $query
     * @param array $params
     * @param string $param_types
     * @param int $limit
     * @param int $offset
     * @return array
     */
    function query($query, $params = array(), $param_types = "s", $limit = 0, $offset = 0) {
        $statement = $this->_db->prepare($query);
        $this->_last_query = $statement;

        if(!$statement) {
            echo htmlspecialchars($this->_db->error);
            return [];
        }

        if(count($params)) {
            $aparams = [];
            $aparams[] = &$param_types;
            for($i=0;$i<count($params);$i++) {
                $aparams[] = &$params[$i];
            }
            call_user_func_array([$statement, 'bind_param'], $aparams);
        }
        $statement->execute();
        $res = $statement->get_result();

        $data = [];
        $count = 0;
        if($offset > 0) {
            $res->data_seek($offset);
        }
        if(!$res) return [];
        while($row = $res->fetch_assoc()) {
            $data[] = $row;
            $count ++;
            if($limit > 0 && $count == $limit) return $data;
        }
        return $data;
    }

    /**
     * Executes a query but only returns true or false based on success or failure
     *
     * See query() documentation for details on the params. The only difference between this function
     * and query() is that this doesn't take limit and offset since it's not designed to return data,
     * and when it completes it will either return true or false based on whether the query caused an
     * error.
     *
     * @param string $query
     * @param array $params
     * @param string $param_types
     * @return bool
     */
    function insert($query, $params = array(), $param_types = "s") {
        $statement = $this->_db->prepare($query);


        if(!$statement) {
            echo "Error in DB query: " . $query . "(" . implode(",",$params) . ") \"$param_types\"<br>";
            exit();
        }

        if(count($params)) {

            $aparams = [];
            $aparams[] = &$param_types;
            for($i=0;$i<count($params);$i++) {
                $aparams[] = &$params[$i];
            }

            call_user_func_array([$statement, 'bind_param'], $aparams);

        }

        $statement->execute();
        $res = $statement->get_result();
        if($statement->error != "" ) {
            echo "Error: ";
            echo $statement->error;
            return false;
        }

        return true;
    }

    /**
     * Executes a query and returns only the first row of data
     *
     * This is useful when you expect the query to only return one row of data. See query() for
     * documentation on the params.
     *
     * @param $query
     * @param array $params
     * @param string $param_types
     * @return bool|mixed
     */
    function get_row($query, $params = array(), $param_types = "s") {
        $tmp = $this->query($query, $params, $param_types, $limit = 1);
        if(!count($tmp)) return false;
        return $tmp[0];
    }

    /**
     * Loads in an array of objects from the database.
     *
     * This will load all the objects from the database that match the criteria given.
     * If $how and $what are left out, all objects will be returned
     * If $what is left out, it will load objects with the id of $how
     *
     * Example:
     *      database::sql()->load_objects("user") - loads all the users
     *      database::sql()->load_objects("user", "first_name", "eric") - loads all the users with the first name 'eric'
     *      database::sql()->load_objects("user", 10) - loads user with 'user_id' of 10
     *      database::sql()->load_objects("user", "status", "active", "last_name asc") - loads all the users
     *             with the status of active, then sorts by last name ascending
     *
     * @param $table
     * @param $how
     * @param $what
     * @param $sorting
     * @return array
     */
    function load_objects($table, $how = false, $what = false, $sorting = false) {
        $table = $this->_db->real_escape_string($table);

        if($how == "where") {
            if($sorting) {
                $ol = $this->query("select * from $table where $what order by $sorting", [ ], "");
            } else {
                $ol = $this->query("select * from $table where $what", [ ], "");
            }
        } elseif($how !== false) {
            if(!$what) {
                $what = $how;
                $how = $table . "_id";
            }
            if($sorting) {
                $ol = $this->query("select * from $table where $how = ? order by $sorting", [ $what ], "s");
            } else {
                $ol = $this->query("select * from $table where $how = ?", [ $what ], "s");
            }
        } else {
            if($sorting) {
                $ol = $this->query("select * from $table order by $sorting");
            } else {
                $ol = $this->query("select * from $table");
            }
        }

        $object_list = [];
        foreach($ol as $o) {
            $ob = new $table();
            $ob->copy_from_array($o);
            $object_list[] = $ob;
        }
        return $object_list;
    }

    /**
     * Loads an array of objects from the database based on the given query
     *
     * See load_objects() for more details
     *
     * Example:
     *      $hooks = database::sql()->load_query("event_trigger", "select * from event_trigger " .
     *                                           "where event=? and event_table=? and status=?",
     *                                          [ $event, $table, "active" ], "sss");
     *
     * @param $table
     * @param $query
     * @param array $params
     * @param string $param_types
     * @param int $limit
     * @param int $offset
     * @return array
     */
    function load_query($table, $query, $params = array(), $param_types = "s", $limit = 0, $offset = 0) {
        $result = $this->query($query, $params, $param_types, $limit, $offset);

        $object_list = [];

        foreach($result as $r) {
            $ob = new $table();
            $ob->copy_from_array($r);
            $object_list[] = $ob;
        }

        return $object_list;
    }


    /**
     * Loads a single object from the database.
     *
     * If more than one result is found, only the first one is returned
     * Returns false if no matching objects are found.
     *
     * @param $table
     * @param $how
     * @param $what
     * @return object|false
     */
    function load_object($table, $how, $what = false) {

        if(!$what) {
            $what = $how;
            $how = $table . "_id";
        }

        $ob = new $table();
        //print_r($ob); exit();

        $ob->load($how, $what);

        if($ob->loaded()) {
            return $ob;
        } else {
            return false;
        }
    }

    /**
     * Caches the table fields when multiple objects are loaded from the database
     *
     * @param $table
     * @return mixed
     */
    function get_table_fields($table) {
        if(isset($this->_table_field_cache[$table])) {
            return $this->_table_field_cache[$table];
        }
        $tmp = database::sql()->query("show columns from $table");
        $this->_table_field_cache[$table] = $tmp;
        return $tmp;
    }

    /**
     * Return MySQL formatted datetime for the current date and time
     *
     * @return string
     */
    function now() {
        return date('Y-m-d H:i:s');
    }

    /**
     * Return MySQL formatted date for the current day
     *
     * @return string
     */
    function today() {
        return date('Y-m-d');
    }

}
