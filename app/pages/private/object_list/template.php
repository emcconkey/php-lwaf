<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-body">

                    <table class="datatable edittable table table-striped table-bordered table-hover no-footer">
                        <thead>
                        <th>Deposit Date</th>
                        <th>Payee</th>
                        <th>Funder</th>
                        <th>Project</th>
                        <th>Fiscal Year</th>
                        <th>Check Date</th>
                        <th>Check Amount</th>
                        <th>Check Number</th>
                        <th class="hide_print">Actions</th>
                        </thead>
                        <tbody>
                        <?
                        $objects = $this->get("objects");
                        foreach($objects as $o) {
                            ?>
                            <tr data-element-id="<?=$o['deposit_id']?>" data-delete-url="/object_list">
                                <td><?=d($o['date_deposited'])?></td>
                                <td><?=$o['payee']?></td>
                                <td><?=$funder?></td>
                                <td></td>
                                <td><?=$o['fiscal_year']?></td>
                                <td><?=d($o['check_date'])?></td>
                                <td><?=$o['check_amount']?></td>
                                <td><?=$o['check_number']?></td>
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
                    <a class="btn" href="/deposit_edit/new">Add Deposit Record</a>
                </div>
            </div>
        </div>
    </div>
</div>
