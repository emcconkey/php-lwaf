<?
class reset_token extends db_ob {

    function is_valid() {

        $valid = false;

        $token_time = strtotime($this->get('token_time'));
        $diff = time() - $token_time;

        // Tokens are only good for 24 hours
        if($diff < 86400) {
            $valid = true;
        } else {
            // Nuke this if it's invalid
            $this->delete_object();
        }

        return $valid;
    }

}