<?php

class db_ob {
    private $_sql = false;
    private $_loaded = false;
    private $_vars = [];
    private $_tmpvars = [];
    private $_updated = [];
    private $_fields = [];
    private $_field_types = [];

    /**
     * db_ob constructor.
     *
     * If $sql is omitted, it will load the default database singleton. To connect to an alternate database, simply
     * pass its object and then load() and save() will execute against that database.
     *
     * @param $sql
     */
    function __construct($sql = false) {
        if(!$sql) $sql = $GLOBALS['database'];

        $this->_sql = $sql;

        // Load up the valid columns when we construct the object
        $res = database::sql()->get_table_fields(get_class($this));
        foreach($res as $r) {
            $this->_fields[] = $r['Field'];
            $ftype = "s";
            if(substr($r['Type'],0, 3) == "int" || substr($r['Type'],0, 6) == "bigint") {
                $ftype = "i";
            } elseif(substr($r['Type'],0, 6) == "double") {
                $ftype = "d";
            }
            $this->_field_types[$r['Field']] = $ftype;
        }

    }

    /**
     * Loads the object
     *
     * When called from a class that extends db_ob, it will load the object from the table named the same
     * as the class, as defined by the parameters.
     *
     * If $what is left off, it will load where the ID equals $how
     * Otherwise it will execute "select * from [classname] where $how=$what"
     *
     * Returns false if the object was not successfully loaded.
     *
     * @param $how
     * @param $what
     * @return bool
     */
    function load($how, $what = false) {
        if($what === false) {
            $what = $how;
            $how = get_class($this) . "_id";
        }
        $query = "select * from " . get_class($this) . " where $how = ?";
        $param = $this->get_field_type($how);
        $res = $this->_sql->get_row($query, [$what], $param);

        if(!$res) return false;

        foreach($res as $k => $v) {
            $this->_vars[$k] = $v;
        }

        $this->_loaded = true;

        return true;
    }

    /**
     * Loads the data into the object from an array
     *
     * This can be used when the object data is stored in an array and you want to use the data as an object
     *
     * @param array $arr
     * @param bool $loaded
     */
    function copy_from_array($arr, $loaded = true) {
        $this->_vars = $arr;
        $this->_loaded = $loaded;
    }

    /**
     * Saves the current object to the database
     *
     * @return bool
     * @throws Exception
     */
    function save() {
        $idname = get_class($this) . "_id";

        if(!$this->_loaded) {
            // We are a new object, save and record our ID
            $qlist = [];
            $valuelist = [];
            $param_types = "";
            foreach($this->_fields as $f) {
                if($f == $idname && !$this->get($idname)) continue; // Skip setting the id on a new object if we don't
                                                                    // have an id to set it to

                $qlist[] = "?";
                $valuelist[] = $this->get($f);
                $param = $this->get_field_type($f);
                $param_types .= $param;
            }

            $qlist = implode(", ", $qlist);
            $fieldlist = implode(", ", $this->_fields);
            if(!$this->get($idname)) {
                $fieldlist = str_replace($idname . ", ", "", $fieldlist);
            }
            $query = "insert into " . get_class($this) . " ($fieldlist) values ($qlist)";
            $this->_sql->insert($query, $valuelist, $param_types);
            $this->set($idname, $this->_sql->insert_id());
            $this->_loaded = true;

            // Process new item triggers
            event_trigger::process("new", get_class($this), [ "user" => user::current(), "object" => $this]);

            return true;
        }

        $query = "update " . get_class($this). " set %values% where $idname='" . $this->get($idname) . "'";
        $values = "";
        $first = false;
        $params = [];
        $param_types = "";

        if(!count($this->_updated)) return true;

        // Process edit item triggers
        event_trigger::process("edit", get_class($this), [ "user" => user::current(), "object" => $this]);


        foreach(array_keys($this->_updated) as $k) {
            if($first) $values .= ", ";
            $values .= "`$k` = ?";
            $first = true;
            $params[] = $this->get($k);
            $param = $this->get_field_type($k);
            $param_types .= $param;
        }
        $query = str_replace("%values%", $values, $query);
        $res = $this->_sql->insert($query, $params, $param_types);

        return $res;
    }

    /**
     * Returns an array of this object's id field and its ID
     *
     * Example:
     *      [ "user_id", 101 ]
     *
     * @return array
     */
    function get_idarr() {
        return [ get_class($this) . "_id", $this->get(get_class($this) . "_id") ];
    }

    /**
     * Returns the ID of this objects
     *
     * @return integer
     */
    function get_id() {
        return $this->get(get_class($this) . "_id");
    }

    /**
     * Sets a temporary variable that will not get saved to the database
     *
     * Temporary variables override saved variables of the same name.
     *
     * @param $var
     * @param $val
     */
    function set_tmpvar($var, $val) {
        $this->_tmpvars[$var] = $val;
    }

    /**
     * Sets a variable that will get saved to the database
     *
     * This will throw an exception if $var is not a valid field in the database schema
     *
     * @param $var
     * @param $val
     * @throws Exception
     */
    function set($var, $val) {
        if(!in_array($var, $this->_fields)) {
            throw new Exception("Invalid field '$var' for class '" . get_class($this) . "'");
        }
        $this->_vars[$var] = $val;
        $this->_updated[$var] = true;
    }

    /**
     * Gets a variable
     *
     * This will first check for the existence of a temporary variable and return it if it is found.
     *
     * @param $var
     * @return bool|mixed
     */
    function get($var) {
        if(isset($this->_tmpvars[$var])) return $this->_tmpvars[$var];
        if(isset($this->_vars[$var])) return $this->_vars[$var];
        return false;
    }

    /**
     * This will clear the contents of a variable and remove a temporary variable
     *
     * @param $var
     */
    function clear($var) {
        unset($this->_tmpvars[$var]);
        $this->_vars[$var] = "";
        $this->_updated[$var] = true;
    }

    /**
     * Returns an array of all the temporary variables
     *
     * @return array
     */
    function list_tmpvars() {
        return $this->_tmpvars;
    }

    /**
     * Returns an array of all the variables
     *
     * @return array
     */
    function list_vars() {
        return $this->_vars;
    }

    /**
     * Returns an array of all the valid data fields for this object
     *
     * @return array
     */
    function list_fields() {
        return $this->_fields;
    }

    /**
     * Gets the field type of the specified field
     *
     * @param $field
     * @return mixed
     */
    function get_field_type($field) {
        return $this->_field_types[$field];
    }

    /**
     * Deletes the current object from the database
     *
     * @return bool
     */
    function delete_object() {
        if(!$this->_loaded) return false;
        $idname = get_class($this) . "_id";
        $query = "delete from " . get_class($this). " where $idname=" . $this->get($idname);
        $res = $this->_sql->insert($query);
        return $res;
    }

    /**
     * Checks if the current object has been successfully loaded from the database
     *
     * @return bool
     */
    function loaded() {
        return $this->_loaded;
    }

}
