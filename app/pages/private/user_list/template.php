<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-body">

                <table class="datatable edittable table table-striped table-bordered table-hover no-footer">
                    <thead>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Last Activity</th>
                    <th>Status</th>
                    <th>Actions</th>
                    </thead>
                    <tbody>
                    <?
                    $users = $this->get("users");
                    foreach($users as $u) {
                        ?>
                        <tr>
                            <td><a href="/user_edit/<?=$u['user_id']?>"><?=$u['email']?></a></td>
                            <td><?=$u['first_name']?></td>
                            <td><?=$u['last_name']?></td>
                            <td><?=$u['timestamp']?></td>
                            <td><?=$u['status']?></td>
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
