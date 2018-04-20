<?php

/**
 * Class event_trigger
 *
 * This is currently a work in progress
 *
 * This handles the event triggers for the system. Currently there are triggers for:
 *      Objects saved to database:
 *          new
 *          edit
 *
 */
class event_trigger extends db_ob {

    /**
     * Summary
     *
     * Description
     *
     * @param $event
     * @param $table
     * @param $data
     */
    static function process($event, $table, $data) {
        $hooks = database::sql()->load_objects("event_trigger", "action", $data);
    }

    /**
     * Summary
     *
     * Description
     *
     * @param $event
     * @param $table
     */
    static function trigger_exists($event, $table) {
        $hooks = database::sql()->load_query("event_trigger", "select * from event_trigger where event=? and event_table=? and status=?",
            [
                $event,
                $table,
                "active"
            ],
            "sss");
    }

}