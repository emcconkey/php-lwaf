<?php
class tools extends page {

    var $funder_map = [];
    var $funders = [];
    var $purpose = [];
    var $contract_codes = [];

    function get_page($args)
    {

        if (isset($args[0]) && $args[0] == "map_timesheet") {
            $ts = database::sql()->load_objects("timesheet");
            $codes = database::sql()->load_objects("contract_code");
            foreach($ts as $t) {
                foreach($codes as $c) {
                    if($t->get("contract_code") == $c->get("contract_code")) {
                        $t->set("contract_code", $c->get("contract_code_id"));
                        $t->save();
                    }
                }
            }
        }


        if (isset($args[0]) && $args[0] == "map_funders") {
            $nm = [];
            $this->render("header");
            ?>
            <table>
            <tr>
                <td>ID</td>
                <td>Funder</td>
                <td>New Funder ID</td>
                <td>NMF Amount</td>
                <td>Pass-Thru Amount</td>
                <td>Total Amount</td>
                <td>Status</td>
                <td>FY</td>
                <td>Purpose</td>
            </tr><?
            $obs = database::sql()->load_objects("old_funders");
            foreach ($obs as $ob) {
                if($ob->get("fiscal_year") != "FY18-19" && $ob->get("fiscal_year") != "FY17-18" && $ob->get("fiscal_year") != "FY16-17")
                    continue;

                if(stristr($ob->get("funder"),"budget") !== false) continue;
                $this->map_funder1($ob);
                $this->map_funder2($ob);

                $this->map_contract_code($ob);

                $this->fix_dollar_field($ob, "total_amount");
                $this->fix_dollar_field($ob, "nmf_amount");
                $this->fix_dollar_field($ob, "pass_thru_amount");
                $this->fix_status($ob);
                $this->fix_purpose($ob);
                $this->create_funding($ob);

                if($ob->get("status") == "total_budget" || $ob->get("status") == "program_budget") {
                    // Do budget stuff here
                } else {
                    // Do funding stuff here
                }
                ?>
                <tr>
                <td><?= $ob->get('funder_id') ?></td>
                <td><?= $ob->get('funder') ?></td>
                <td><?= $ob->get('new_funder_id') ?></td>
                <td><?= $ob->get('nmf_amount') ?></td>
                <td><?= $ob->get('pass_thru_amount') ?></td>
                <td><?= $ob->get('total_amount') ?></td>
                <td><?= $ob->get('status') ?></td>
                <td><?= $ob->get('fiscal_year') ?></td>
                <td><?= $ob->get('fund_purpose') ?></td>
                </tr><?
            }
            ?></table><?
            $this->render("footer");
            return;
        }


        if (isset($args[0]) && $args[0] == "fix_funders") {
            ?>
            <table>
            <tr>
                <td>ID</td>
                <td>NMF Amount</td>
                <td>Pass-Thru Amount</td>
                <td>Total Amount</td>
            </tr><?
            $obs = database::sql()->load_objects("funding");
            foreach ($obs as $ob) {
                $this->fix_dollar_field($ob, "Total Amount");
                $this->fix_dollar_field($ob, "NMF Amount");
                $this->fix_dollar_field($ob, "Pass-Thru Amount");
                $ob->save();
                ?>
                <tr>
                <td><?= $ob->get('funder_id') ?></td>
                <td><?= $ob->get('NMF Amount') ?></td>
                <td><?= $ob->get('Pass-Thru Amount') ?></td>
                <td><?= $ob->get('Total Amount') ?></td>
                </tr><?
            }
            ?></table><?
            exit();
        }

        if (isset($args[0]) && $args[0] == "fix_deposits") {
            ?>
            <table border="1" padding="5">
            <tr>
                <td>ID</td>
                </td>
                <td>Date Deposited</td>
            </tr><?

            $obs = database::sql()->load_objects("deposit");
            foreach ($obs as $ob) {
                $this->fix_date_field($ob, 'Date deposited');
                $this->fix_dollar_field($ob, 'Amount');
                $ob->save();
                ?>
                <tr>
                    <td><?=$ob->get('deposit_id')?></td>
                    <td><?=$ob->get('Date deposited') ?></td>
                </tr>
                <?
            }

            ?></table><?
            exit();
        }

        $this->set("page_title", "Admin Tools");

        $this->render("header");
        $this->render();
        $this->render("footer");

    }


    function post_page($args) {

    }

    function fix_dollar_field($ob, $name) {
        $old_amount = $ob->get($name);
        $new_amount = str_replace("$", "", $old_amount);
        $new_amount = str_replace(",", "", $new_amount);
        if($old_amount == '') $new_amount = "0.00";
        $ob->set($name, $new_amount);
    }

    function fix_date_field($ob, $name) {
        $old_date = $ob->get($name);
        $new_date = strtotime($old_date);
        $new_date = date("Y-m-d", $new_date);
        $ob->set($name, $new_date);
    }

    function map_funder1($ob) {
        if(!count($this->funder_map)) {
            $data = database::sql()->query("select * from funder_map");
            $this->funder_map = $data;
        }

        foreach($this->funder_map as $f) {
            if($ob->get("funder") == $f['old_name']) {
                $ob->set("funder", $f['new_name']);
            }
        }
    }

    function map_funder2($ob) {
        if(!count($this->funders)) $this->reload_funders();

        $ob->set("new_funder_id", 0);
        foreach($this->funders as $f) {
            if($f['name'] == $ob->get("funder")) {
                $ob->set("new_funder_id", $f['funder_id']);
            }
        }
        if(!$ob->get("new_funder_id")) {
            $f = new funder();
            $f->set("name", $ob->get("funder"));
            $f->set("category", strtolower($ob->get("funder_category")));
            $f->save();
            $this->reload_funders();
            foreach($this->funders as $f) {
                if($f['name'] == $ob->get("funder")) {
                    $ob->set("new_funder_id", $f['funder_id']);
                }
            }
        }
    }

    function create_funding($ob) {
        $f = new funding();
        $props = [
            "fiscal_year",
            "nmf_amount",
            "pass_thru_amount",
            "total_amount",
            "fundraising_notes",
            "status",
            "likely_success",
            "lead_nmf_advocate",
            "request_approach",
            "fundraising_notes",
            "contract_code",
            "fund_purpose"
        ];
        foreach($props as $p) {
            $f->set($p, $ob->get($p));
        }
        $f->set("funder_id", $ob->get("new_funder_id"));
        $f->set("policy_area", strtolower(str_replace(" ", "_", $ob->get("policy_area"))));
        $f->set("scholarship_sponsor", ($ob->get("scholarship_sponsor") != "" ? "yes" : ""));
        $f->set("board_donation", ($ob->get("board_donation") != "" ? "yes" : ""));
        $f->set("multi_year_record", ($ob->get("multi_year_record") != "" ? "yes" : ""));

        $f->set("identified_date", "1900-01-01");
        $f->set("start_date", "1900-01-01");
        $f->set("end_date", "1900-01-01");
        $f->set("thank_you_sent_date", "1900-01-01");

        $f->save();
        $ob->set("mapping_complete", 1);
        $ob->save();
    }

    function map_contract_code($ob) {
        if(!count($this->contract_codes)) $this->reload_codes();
        $found = false;
        foreach($this->contract_codes as $c) {
            if(strstr($ob->get("contract_code"),$c['contract_code'])) {
                $ob->set("contract_code", $c['contract_code_id']);
                $found = true;
            }
        }
        if(!$found) {
            echo "Code not found: " . $ob->get("contract_code") . "<br>";
            $ob->set("contract_code", -1);
        }

    }

    function fix_status($ob) {
        $smap = [
            "Approved!" => "approved",
            "Decision Pending" => "pending",
            "Declined" => "declined" ,
            "Not started" => "not_started",
            "Program Budget" => "program_budget",
            "Total Budget"=> "total_budget",
            "Prospects" => "prospects"
        ];
        $ob->set("status", $smap[$ob->get("status")]);

    }

    function fix_purpose($ob) {
        if(!count($this->purpose)) {
            $data = database::sql()->query("select * from fund_purpose");
            $this->purpose = $data;
        }
        $found = false;
        foreach($this->purpose as $p) {
            if(strtolower($ob->get("fund_purpose")) == strtolower($p['name'])) {
                $found = true;
                $ob->set("fund_purpose", $p['fund_purpose_id']);
            }
        }
        if(strstr($ob->get("fund_purpose"), ",") !== false) {
            $ob->set("fund_purpose", 4); // Multi-purpose ID
            $found = true;
        }

        if(!$found && $ob->get("fund_purpose") != "") {
            $s = new fund_purpose();
            echo "Adding \"" . $ob->get("fund_purpose") . "\"<br>";
            $s->set("name", $ob->get("fund_purpose"));
            $s->save();
        }

    }

    function reload_funders() {
        $this->funders = database::sql()->query("select * from funder");
    }

    function reload_codes() {
        $this->contract_codes = database::sql()->query("select * from contract_code");
    }
}
