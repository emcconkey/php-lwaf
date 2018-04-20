<?php
class settings extends db_ob {

    static function get_settingscache() {
        if(!isset($GLOBALS['settingscache'])) {
            $GLOBALS['settingscache'] = database::sql()->query("select * from settings");
        }
        return $GLOBALS['settingscache'];
    }

    static function value($key) {
        $settingscache = self::get_settingscache();
        foreach($settingscache as $s) {
            if($s['settings_key'] == $key) {
                return $s['settings_value'];
            }
        }
        return "";
    }

}