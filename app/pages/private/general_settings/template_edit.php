<?php
$c = $this->get('settings');
if(!$c) {
    ?>
    <p>No such entry to edit.</p>
    <?
    return;
}
?>
<h4>Edit Setting</h4>
<form id="settings_edit">

    <div style="border: 1px solid silver; margin-top: 10px; padding: 20px;">

        <input type="hidden" name="settings_id" id="settings_id" value="<?=$c['settings_id']?>">
        <div class="col-md-12">
            <?html::input("settings_key","Name", $c['settings_key'])?>
        </div>
        <div class="col-md-12">
            <?html::input("settings_value","Value", $c['settings_value'])?>
        </div>

        <div class="col-md-12">
            <button type="submit" class="pull-right btn">Save</button>
        </div>

        <div class="clearfix"></div>
    </div>


</form>
<script>
    $(function() {

        $("#settings_edit").off("submit");
        $("#settings_edit").on("submit", function() {
            $.ajax({
                type: "POST",
                url: "/general_settings",
                data: {
                    settings_id: $("#settings_id").val(),
                    settings_key: $("#settings_key").val(),
                    settings_value: $("#settings_value").val(),
                },
                success: function(data) {
                    if(data == "OK") {
                        window.location.href = "/general_settings";
                    } else {
                        $(".featherlight-close").click();
                        swal("Error", "'" + data + "'", "error");
                    }
                }
            });
            return false;
        });

    });
</script>
