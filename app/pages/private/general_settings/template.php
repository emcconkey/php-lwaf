<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-body">
                <table class="datatable edittable table table-striped table-bordered table-hover no-footer">
                    <thead>
                    <th>Setting</th>
                    <th>Value</th>
                    <th class="hide_print">Actions</th>
                    </thead>
                    <tbody>
                    <?
                    $settings = $this->get("settings");
                    foreach($settings as $f) {
                        ?>
                        <tr data-element-id="<?=$f['settings_id']?>" data-delete-url="/general_settings" data-edit-url="/general_settings/edit">
                            <td><?=$f['settings_key']?></td>
                            <td><?=$f['settings_value']?></td>
                            <td class="actions hide_print">
                                <button class="edit btn btn-success">Edit</button>
                                <button class="delete btn btn-danger">Delete</button>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                    </tbody>
                </table>
                <span data-element-id="new" data-edit-url="/general_settings/edit"><span>
                    <btn class="edit btn btn-info">Add New Value</btn>
                    </span></span>
            </div>
        </div>
    </div>
</div>

<div class="pwrap" style="display: none">
    <div class="popup">

    </div>
</div>

<script>
    $(function() {
        $(document).on("click", ".edit", function() {
            row = $(this).parent().parent();
            $(".pwrap > .popup").load(row.attr('data-edit-url') + "/" + row.attr('data-element-id'), function() {
                $.featherlight($(".pwrap > .popup"));
                $(".pwrap > .popup").html('');
            });
            return false;
        });
    });
</script>