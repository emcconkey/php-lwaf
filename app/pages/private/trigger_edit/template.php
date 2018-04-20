<?
$ob = $this->get("trigger");

?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="event_trigger_id" value="<?=$ob->get('event_trigger_id')?>">
                <div class="col-md-4">
                    <?html::select("event_table", "Table to Watch",[
                        "billing" => "Billing",
                        "deposit" => "Deposit",
                        "funding" => "Funding",
                        "timesheet" => "Timesheet",
                        "user" => "User"
                    ], $ob->get('event_table'))?>
                </div>

                <div class="col-md-4">
                    <?html::select("event", "Event to Trigger on", [
                            "new_entry" => "New Entry",
                            "update_entry" => "Update Entry",
                            "delete_entry" => "Delete Entry"
                            ],
                        $ob->get('event'))?>
                </div>

                <div class="col-md-4">
                    <?html::select("status", "Status",[
                        "active" => "Active",
                        "disabled" => "Disabled"
                    ], $ob->get('status'))?>
                </div>


                <div class="col-md-12">
                    <?html::input("description", "Description", $ob->get('description'))?>
                </div>

                <div class="col-md-12">
                    <?html::text("code", "Code to Run", $ob->get('code'))?>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn pull-right">Save</button>
                    <button type="button" class="cancelbutton btn btn-default pull-right" style="margin-right: 10px;">Done</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    $(function() {
        $(".cancelbutton").on("click", function() {
            window.location.href = "/trigger_list";
            return false;
        });
    });
</script>
