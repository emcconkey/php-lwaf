<?php

class user extends db_ob {

    var $_perms = [];
    var $_roles = [];
    var $_session_time = 7200;

    static function startup() {
        $GLOBALS['user'] = new user();

        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            // Load the user
            $GLOBALS['user'] = new user($_SESSION['userid']);

            // Verify that we actually loaded someone and we got the person we expected
            if($GLOBALS['user']->get("user_id") != $_SESSION['userid']) {
                session_destroy();
                header("Location: /login");
                exit();
            }

            // If the user has been idle for too long, log them out
        }

        if(!$GLOBALS['user']->loaded()) return;

        // If the user remains active, set the timestamp
        $GLOBALS['user']->save();
    }

    function save() {
        $this->set("timestamp", date("y-m-d H:i:s"));
        parent::save();
    }

    // Global access function
    static function current() {
            return $GLOBALS['user'];
    }


    function __construct($how = false, $what = false) {
        parent::__construct();
        if(!$how) return;
        return $this->load($how, $what);
    }

    function load($how, $what = false) {
        $tmp = false;
        if($what === false) {
            $tmp = $how;
        }
        if($how == "email") {
            $what = "$what%";
            $tmp = database::sql()->query("select user_id from user where email like ?", [$what], "s");
            if(sizeof($tmp) == 1) {
                $tmp = $tmp[0]['user_id'];
            } else {
                $tmp = null;
            }

        } else if($how == "token") {
            if($res = $this->validate_token($what)) {
                $tmp = $res;
            }
        } else if($how == "web_key") {
            if($res = $this->validate_webkey($what)) {
                $tmp = $res;
            }
        } else if($what != '') {
            $tmp = database::sql()->get_row("select user_id from user where $how=?", [$what], "s");
            $tmp = $tmp['user_id'];
        }
        parent::load("user_id", $tmp);

        if(!$this->loaded()) {
            return false;
        }

        $userperms = database::sql()->query("select * from userperms where user_id=?", [ $this->get('user_id')], "i");
        if(!$userperms) $userperms = [];
        foreach($userperms as $p) {
            $this->_perms[$p['permission_slug']] = true;
            $this->_roles[$p['role_slug']] = true;
        }

        $this->_session_time = config::get("session_time");

        return true;
    }

    static function check_login($email, $password) {
        $user = new user("email", $email);

        if(!$user->loaded()) return false;
        if(!$user->check_pass($password)) return false;
        return $user;
    }

    function add_history($h) {
        if(sizeof($_SESSION['history']) < 1) {
            array_push($_SESSION['history'], $h);
        } else {
            $tmp = array_pop($_SESSION['history']);
            array_push($_SESSION['history'], $tmp);
            if($h != $tmp)
                array_push($_SESSION['history'], $h);
        }
    }

    /** Logs the user in
     */
    function login() {
        //$this->set("last_login", time());
        $this->save();

        // If it's a ne user, we need to make sure to set the session user id after the save() operation
        // so that the user object gets an id assigned to it first.
        $_SESSION['logged_in'] = true;
        $_SESSION['userid'] = $this->get("user_id");
        $GLOBALS['user'] = $this;

    }

    function is_logged_in() {
        return $this->loaded();
    }

    function logout() {
        session_destroy();
    }

    function create_token_from_email($email) {
        $id = database::sql()->get_one("select id from user where email=?", array(1 => $email));

        return $this->create_token($id);
    }

    function create_token($user = -1) {
        if($user == -1)
            $token_user = $this->query("id");
        else
            $token_user = $user;

        $pretoken = $this->alpha_name() . date("y-m-d H:i:s");
        $token = md5($pretoken);
        database::sql()->query("insert into login_tokens (user_id, date, token) values (?, ?, ?)",
            array(1 => $token_user, 2 => date("y-m-d H:i:s"), 3 => $token));

        return $token;
    }

    function validate_token($token) {
        // Clear out all the old tokens first
        database::sql()->query("delete from login_tokens where date < DATE_SUB(now(), INTERVAL 24 HOUR)");

        // Find the user that this token belongs to
        $id = database::sql()->get_one("select user_id from login_tokens where token=?1?",
            array($token));
        return $id;
    }

    function validate_webkey($key) {
        $id = database::sql()->get_one("select id from user where web_key=?", array(1 => $key));
        return $id;
    }

    function new_web_key() {
        $pretoken = $this->query("fullname") . date("y-m-d H:i:s");
        $token = substr(hash("sha256",$pretoken), 0, 8);
        $this->set("web_key", $token);

        return $token;
    }

    function get_theme_css() {
        $theme = $this->get('theme');

        if(!$theme) $theme = "static/html/themes/default.css";
        return "<link href=\"/$theme\" rel=\"stylesheet\">";
    }

    /** Returns a combined name for displaying
     */
    function display_name() {
        return $this->get("first_name") . " " . $this->get("last_name");
    }

    /** Returns a combined name, last name first for alpha sorted displays
     */
    function alpha_name() {
        return $this->get("last_name") . ", " . $this->get("first_name");
    }

    function crypt_password($plaintext) {
        $hasher = new PasswordHash(8, FALSE);
        return $hasher->HashPassword($plaintext);
    }

    function check_pass($password) {
        $hasher = new PasswordHash(8, FALSE);
        return  $hasher->CheckPassword($password, $this->get("password"));
    }

    function set_password($pass) {
        $this->set("password", $this->crypt_password($pass));
        $this->check_pass($pass);
    }

    function new_reset_token() {
        $token = new reset_token();
        $token->load("user_id", $this->get("user_id"));
        if(!$token->loaded()) {
            $token->set("user_id", $this->get("user_id"));
            $token->set("token", sha1(password_hash($this->get("user_id"), PASSWORD_BCRYPT)));
        }
        $token->set("token_time", date("Y-m-d H:i:s"));
        $token->save();
        return $token;
    }

    static function validate_email($email) {
        if($email == "" || $email == false || $email == null)
            return false;

        // Create the syntactical validation regular expression
        $regexp = '/^([_A-Za-z0-9-]+)(\.[_A-Za-z0-9-]+)*@([A-Za-z0-9-]+)(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,4})$/';

        // Presume that the email is invalid
        $valid = false;

        // Validate the syntax
        if (preg_match($regexp, $email)) {

            list($username,$domaintld) = explode("@",$email);

            // Validate the domain
            //if (getmxrr($domaintld,$mxrecords))
            $valid = true;

        } else {
            $valid = false;
        }

        return $valid;

    }

    // convert the time from mysql to a display format, and offset with the current user's timezone
    function tz_date($date, $format) {
        if(intval($date) != $date) {
            $sd_time = strtotime($date);
        } else {
            $sd_time = $date;
        }
        $sd_time = ($this->get('timezone') * 3600) + $sd_time;
        $tz_date = date($format, $sd_time);
        return $tz_date;
    }

    // Convert a timestamp from the server to the current user's timezone
    function tz_time($time) {
        return ($this->get('timezone') * 3600) + $time;
    }

    // Convert the timestamp from the user input to the server's time
    function tz_query_shift($time) {
        return $time - ($this->get('timezone') * 3600);
    }

    function get_roles() {
        return array_keys($this->_roles);
    }

    function has_role($role) {
        return (array_key_exists($role, $this->_roles));
    }

    function get_perms() {
        return array_keys($this->_perms);
    }

    function has_perm($perm) {
        return (array_key_exists($perm, $this->_perms));
    }

    static function get_usercache() {
        if(!isset($GLOBALS['usercache'])) {
            $GLOBALS['usercache'] = database::sql()->query("select * from user");
        }
        return $GLOBALS['usercache'];
    }

    static function id_to_name($id) {
        $usercache = self::get_usercache();
        foreach($usercache as $u) {
            if($u['user_id'] == $id) {
                return $u['first_name'] . " " . $u['last_name'];
            }
        }
        return "";
    }

}

