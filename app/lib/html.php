<?php

/**
 * Class html
 *
 * This is a static helper class used to generate HTML elements
 */
class html
{

    /**
     * Adds an error message to the message queue
     *
     * @param $title
     * @param $message
     */
    static function error_message($title, $message) {
        $message = "<strong>$title &nbsp;&nbsp;</strong>$message";
        if(isset($_SESSION['error_message']))
            $_SESSION['error_message'] .= "<br>$message";
        else
            $_SESSION["error_message"] = $message;


    }

    /**
     * Adds a warning message to the message queue
     *
     * @param $title
     * @param $message
     */
    static function warning_message($title, $message) {
        $message = "<strong>$title &nbsp;&nbsp;</strong>$message";
        $_SESSION["warning_message_title"] = $title;
        if(isset($_SESSION['warning_message']))
            $_SESSION['warning_message'] .= "<br>$message";
        else
            $_SESSION["warning_message"] = "$message";
    }

    /**
     * Adds an info message to the message queue
     *
     * @param $title
     * @param $message
     */
    static function info_message($title, $message) {
        $message = "<strong>$title &nbsp;&nbsp;</strong>$message";
        $_SESSION["info_message_title"] = $title;
        if(isset($_SESSION['info_message']))
            $_SESSION['info_message'] .= "<br>$message";
        else
            $_SESSION["info_message"] = $message;
    }

    /**
     * Adds a success message to the message queue
     *
     * @param $title
     * @param $message
     */
    static function success_message($title, $message) {
        $message = "<strong>$title &nbsp;&nbsp;</strong>$message";
        $_SESSION["success_message_title"] = $title;
        if(isset($_SESSION['success_message']))
            $_SESSION['success_message'] .= "<br>$message";
        else
            $_SESSION["success_message"] = $message;

    }

    /**
     * Displays the message queue.
     *
     * This should be called from somewhere near the top of the page templates.
     *
     */
    static function get_page_messages() {
        if(isset($_SESSION["error_message"])) {
            ?>
            <div class="alert alert-danger fade in">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <?=($_SESSION["error_message"])?>
            </div>
            <?
            unset($_SESSION['error_message']);
        }
        if(isset($_SESSION["warning_message"])) {
            ?>
            <div class="alert alert-warning fade in">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <?=($_SESSION["warning_message"])?>
            </div>
            <?
            unset($_SESSION['warning_message']);
        }
        if(isset($_SESSION["info_message"])) {
            ?>
            <div class="alert alert-info fade in">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <?=($_SESSION["info_message"])?>
            </div>
            <?
            unset($_SESSION['info_message']);
        }
        if(isset($_SESSION["success_message"])) {
            ?>
            <div class="alert alert-success fade in">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <?=($_SESSION["success_message"])?>
            </div>
            <?
            unset($_SESSION['success_message']);
        }
    }

    /**
     * Prints debug information
     *
     * @param $mess
     * @param bool $all
     */
    static function debug_print($mess, $all = false) {
        echo "<pre>";
        if($all) {
            print_r($mess);
        } else {
            var_dump($mess);
        }
        echo "</pre>";
    }

    /**
     * Creates an HTML select element with a timezone list
     *
     * @param $name
     * @param $default
     */
    static function tz_dropdown($name, $default) {
        ?><select name="<?=$name?>" id="<?=$name?>" class="form-control tz_dropdown"><?
        foreach(tz::get_map() as $tz => $names) {
            foreach($names as $windows_name => $unix_name) {
                ?><option value="<?=$tz?>"><?=$tz?> <?=$windows_name?></option><?
            }

        }
        ?></select><?
    }

    /**
     * Creates an HTML input element
     *
     * @param $name
     * @param $title
     * @param string $value
     * @param string $type
     * @param string $placeholder
     * @param string $class
     */
    static function input($name, $title, $value="", $type="text", $placeholder="", $class="") {
        ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <input type="<?=$type?>" name="<?=$name?>" id="<?=$name?>" class="form-control" placeholder="<?=$placeholder?>" <?=$class?>" value="<?=$value?>">
        </div>
        <?
    }

    /**
     * Creates an HTML input element without the label
     *
     *
     * @param $name
     * @param $title
     * @param string $value
     * @param string $type
     * @param string $placeholder
     * @param string $class
     */
    static function input_bare($name, $title, $value="", $type="text", $placeholder="", $class="") {
        ?>
        <div class="form-group">
            <input type="<?=$type?>" name="<?=$name?>" id="<?=$name?>" class="form-control" placeholder="<?=$placeholder?>" <?=$class?>" value="<?=$value?>">
        </div>
        <?
    }

    /**
     * Creates HTML input with a dollar sign at the beginning
     *
     * @param $name
     * @param $title
     * @param string $value
     * @param string $placeholder
     */
    static function dollar_in($name, $title, $value="", $placeholder="") {
        ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control" id="<?=$name?>" name="<?=$name?>" required="" title="<?=$title?>" placeholder="<?=$placeholder?>" value="<?=$value?>">
            </div>
        </div>
        <?
    }

    /**
     * Creates a datepicker input field using the pickadate library
     *
     * See @link http://amsul.ca/pickadate.js for more information.
     *
     * @param $name
     * @param $title
     * @param string $value
     * @param string $placeholder
     */
    static function date($name, $title, $value="", $placeholder="") {
        ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <input type="text" class="form-control datepicker" id="<?=$name?>" name="<?=$name?>" required="" title="<?=$title?>" placeholder="<?=$placeholder?>" data-value="<?=$value?>">
        </div>
        <script>

            dinput_<?=$name?> = $('#<?=$name?>').pickadate({
                formatSubmit: 'yyyy/mm/dd',
                // min: [2015, 7, 14],
                container: '#datepicker_container',
                // editable: true,
                closeOnSelect: true,
                closeOnClear: true
            });
            datepicker_<?=$name?> = dinput_<?=$name?>.pickadate('picker');
            if($('#<?=$name?>').val() != "") {
                datepicker_<?=$name?>.set('select', $('#<?=$name?>').val());
            }

        </script>
        <?
    }

    /**
     * Creates a group of HTML checkboxes
     *
     * $values should be an array of names:
     *      [ "option_one", "option_two", "option_three" ]
     * $default should be the item that is checked:
     *      "option_two"
     *
     * The checkboxes will be named by $name_$value:
     *      html::check("options", "Your Options", [ "option_one", "option_two", "option_three" ])
     *          This will create the following names:
     *              options_option_one
     *              options_option_two
     *              options_option_three
     *
     *          The titles of the above checkboxes will be displayed as:
     *              [ ] Option One
     *              [ ] Option Two
     *              [ ] Option Three
     *
     * @param $name
     * @param $title
     * @param array $values
     * @param string $default
     */
    static function check($name, $title, $values=[], $default="") {
        $default = explode(",", $default);
        ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <div class="checkbox">
                    <?
                    foreach($values as $k => $v) {
                        $check_title = ucwords(str_replace("_", " ", $k));
                        $check_name = $name . "_" . $k;
                        $checked = in_array($k, $default);
                    ?>
                        <p>
                            <input name="<?=$check_name?>" class="<?=$name?>" id="<?=$check_name?>" type="checkbox" <?=($checked ? "checked" : "")?> >
                            <label for="<?=$check_name?>"><?=$check_title?></label>
                        </p>
                        <?
                    }
                    ?>
            </div>
        </div>

        <?
    }

    /**
     * Creates a group of HTML checkboxes, loading the options from a database
     *
     * Selects all the rows from $db_table, sets the list of the checkboxes
     * based on the field $db_column
     *
     * @param $name
     * @param $title
     * @param $db_table
     * @param $db_column
     * @param array $default
     */
    static function db_check($name, $title, $db_table, $db_column, $default=[]) {
        $values = database::sql()->query("select * from $db_table");
        if(!is_array($default)) $default = [ $default ];
        ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <div class="checkbox">
                <?
                foreach($values as $v) {
                    $check_title = $v[$db_column];
                    $check_name = $name . "_" . $v[$db_table . '_id'];
                    ?>
                    <p>
                        <input name="<?=$check_name?>" class="<?=$name?>" id="<?=$check_name?>" type="checkbox" <?=(in_array($v[$db_table . '_id'], $default) ? "checked" : "")?> >
                        <label for="<?=$check_name?>"><?=$check_title?></label>
                    </p>
                    <?
                }
                ?>

            </div>
        </div>

        <?
    }

    /**
     * Creates an HTML select element styled by the select2 script
     *
     * See @link https://select2.org/ for more information
     *
     * Use $dropdown_parent when showing the script in a modal popup div
     *
     * @param $name
     * @param $title
     * @param array $values
     * @param string $default
     * @param string $dropdown_parent
     * @param bool $bare
     */
    static function select($name, $title, $values=[], $default="", $dropdown_parent=false, $bare=false) {
        // This is a single select, so multi-values won't work here - only take the first
        if(is_array($default)) $default = $default[0];

        if(!$bare) { ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <? } ?>
            <select class="form-control" id="<?=$name?>" name="<?=$name?>" title="<?=$title?>" data-width="100%">
                <option></option>
                <?
                    foreach($values as $k => $v) {
                        ?><option value="<?=$k?>" <?=($k == $default ? "selected" : "")?>><?=$v?></option><?
                    }
                ?>
            </select>
            <?if(!$bare) { ?>
        </div>
        <? } ?>
        <script>
            setTimeout(function() {
                <? if($dropdown_parent) { ?>
                $("#<?=$name?>").select2({placeholder:"Select One",dropdownParent: $('<?=$dropdown_parent?>')});
                <? } else { ?>
                $("#<?=$name?>").select2({placeholder:"Select One"});
                <? } ?>
            }, 100);
        </script>

        <?
    }


    /**
     * Creates an HTML select element styled by the select2 script for multiple options
     *
     * See @link https://select2.org/ for more information
     *
     * Use $dropdown_parent when showing the script in a modal popup div
     *
     * @param $name
     * @param $title
     * @param array $values
     * @param string $default
     * @param string $dropdown_parent
     */
    static function multi_select($name, $title, $values=[], $default="", $dropdown_parent=false) {
        // This is a multi select, so we need multi-values here
        if(!is_array($default)) $default = [ $default ];
        ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <select class="form-control" id="<?=$name?>" name="<?=$name?>[]" title="<?=$title?>" multiple="multiple" data-width="100%">
                <?
                foreach($values as $k => $v) {
                    ?><option value="<?=$k?>" <?=(in_array($k, $default) ? "selected" : "")?>><?=$v?></option><?
                }
                ?>
            </select>
        </div>
        <script>
            setTimeout(function() {
                <? if($dropdown_parent) { ?>
                $("#<?=$name?>").select2({placeholder:"Select Multiple",dropdownParent: $('<?=$dropdown_parent?>')});
                <? } else { ?>
                $("#<?=$name?>").select2({placeholder:"Select Multiple"});
                <? } ?>
            }, 100);
        </script>
        <?
    }


    /**
     * Creates an HTML select element styled by the select2 script with the options loaded from a database
     *
     * See @link https://select2.org/ for more information
     *
     * Use $dropdown_parent when showing the script in a modal popup div
     *
     * @param $name
     * @param $title
     * @param $db_table
     * @param $db_column
     * @param string $default
     * @param string $dropdown_parent
     */
    static function db_select($name, $title, $db_table, $db_column, $default="", $dropdown_parent=false) {
        $values = database::sql()->query("select * from $db_table");
        $class = "db-select-{$name}";
        ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <select class="form-control  <?=$class?>" id="<?=$name?>" name="<?=$name?>" title="<?=$title?>" data-width="100%">
                <option></option>
                <?
                foreach($values as $v) {
                    ?><option value="<?=$v[$db_table . '_id']?>" <?=($v[$db_table . '_id'] == $default ? "selected" : "")?>><?=$v[$db_column]?></option><?
                }
                ?>
            </select>
        </div>
        <script>
            setTimeout(function() {
                <? if($dropdown_parent) { ?>
                    $("#<?=$name?>").select2({placeholder:"Select One",dropdownParent: $('<?=$dropdown_parent?>')});
                <? } else { ?>
                    $("#<?=$name?>").select2({placeholder:"Select One"});
                <? } ?>
            }, 100);
        </script>
        <?
    }

    /**
     * Creates an HTML select element styled by the select2 script for multiple
     * options with the options loaded from a database
     *
     * See @link https://select2.org/ for more information
     *
     * Use $dropdown_parent when showing the script in a modal popup div
     *
     * @param $name
     * @param $title
     * @param $db_table
     * @param $db_column
     * @param string $default
     * @param string $dropdown_parent
     */
    static function db_multi_select($name, $title, $db_table, $db_column, $default="", $dropdown_parent=false) {
        $values = database::sql()->query("select * from $db_table");
        ?>
        <div class="form-group">
            <label for="<?=$name?>" class="control-label"><?=$title?></label>
            <select class="form-control" id="<?=$name?>" name="<?=$name?>" title="<?=$title?>" multiple="multiple" data-width="100%">
                <?
                foreach($values as $v) {
                    ?><option value="<?=$v[$db_table . '_id']?>" <?=($v[$db_table . '_id'] == $default ? "selected" : "")?>><?=$v[$db_column]?></option><?
                }
                ?>
            </select>
        </div>
        <script>
            setTimeout(function() {
                <? if($dropdown_parent) { ?>
                $("#<?=$name?>").select2({placeholder:"Select Multiple",dropdownParent: $('<?=$dropdown_parent?>')});
                <? } else { ?>
                $("#<?=$name?>").select2({placeholder:"Select Multiple"});
                <? } ?>
            }, 100);
        </script>
        <?
    }

    /**
     * Creats an HTML textarea element
     *
     * @param string $name
     * @param string $title
     * @param string $value
     * @param string $class
     */
    static function text($name, $title, $value="", $class="") {
        ?>
            <div class="form-group">
                <label for="<?=$name?>" class="control-label"><?=$title?></label>
                <textarea name="<?=$name?>" id="<?=$name?>" class="form-control <?=$class?>"><?=$value?></textarea>
            </div>
        <?
    }



    /**
     * Converts an array to a downloadable CSV file
     *
     * @param $arr
     * @param $filename
     * @param bool $inline
     */
    static function arr_to_csv($arr, $filename, $inline = false) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        if(!$inline) {
            // force download
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");

            // disposition / encoding on response body
            header("Content-Disposition: attachment;filename={$filename}");
            header("Content-Transfer-Encoding: binary");
        }

        $df = fopen("php://output", 'w');
        $header = false;
        foreach ($arr as $row) {
            if(!$header) {
                fputcsv($df, array_keys($row));
                $header = true;
            }
            fputcsv($df, $row);
        }
        fclose($df);
    }

    /**
     * Converts a list of objects to a downloadable CSV file
     *
     * @param $obs
     * @param $filename
     * @param bool $inline
     * @param array $exclude
     */
    static function obs_to_csv($obs, $filename, $inline = false, $exclude = []) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        if(!$inline) {
            // force download
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");

            // disposition / encoding on response body
            header("Content-Disposition: attachment;filename={$filename}");
            header("Content-Transfer-Encoding: binary");
        }

        $df = fopen("php://output", 'w');
        $header = false;
        // TODO: add in exclusions here
        foreach ($obs as $ob) {
            if(!$header) {
                fputcsv($df, array_keys($ob->list_vars()));
                $header = true;
            }
            fputcsv($df, $ob->list_vars());
        }
        fclose($df);
    }

}

/**
 * Global helper function to convert MySQL dates to human preferred dates
 *
 * @param string $date
 * @return string
 */
function d($date) {
    $num = strtotime($date);
    if($num < 1) return "";
    return date("m/d/Y", $num);
}

/**
 * Global helper function to convert dates to MySQL date format
 *
 * @param string $date
 * @return string
 */
function md($date) {
    $num = strtotime($date);
    if($num < 1)return "";
    return date("Y-m-d", $num);
}

/**
 * Global helper function to convert dates/times to MySQL datetime format
 *
 * @param string $date
 * @return string
 */
function mdt($date) {
    $num = strtotime($date);
    if($num < 1) return "";
    return date("Y-m-d H:i:s", $num);
}

