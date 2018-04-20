<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-body">

                <table class="datatable edittable table table-striped table-bordered table-hover no-footer">
                    <thead>
                    <th>Description</th>
                    <th>Table</th>
                    <th>Event</th>
                    <th>Status</th>
                    <th>Action</th>
                    </thead>
                    <tbody>
                    <?
                    $triggers = $this->get("triggers");
                    foreach($triggers as $f) {
                        ?>
                        <tr>
                            <td><a href="/trigger_edit/<?=$f['event_trigger_id']?>"><?=$f['description']?></a></td>
                            <td><?=$f['event_table']?></td>
                            <td><?=$f['event']?></td>
                            <td><?=$f['status']?></td>
                            <td></td>
                        </tr>
                        <?
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
