<?php
$themes = $this->get("themes");
$current = $this->get("theme");
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>My Account Details</h2>
                </div>
                <div class="panel-body">



                <form method="post" class="col-md-7">

                    <div class="form-group">
                        <label for="first_name" class="control-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?=user::current()->get('first_name')?>" required="" title="Please enter your first name" placeholder="">

                    </div>
                    <div class="form-group">
                        <label for="last_name" class="control-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?=user::current()->get('last_name')?>" required="" title="Please enter your last name" placeholder="">
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?=user::current()->get('email')?>" required="" title="Please enter your email address" placeholder="">
                    </div>

                    <div class="form-group">
                        <label for="password" class="control-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="">
                    </div>

                    <div class="form-group">
                        <label for="theme" class="control-label">Color Theme</label>
                        <select id="theme" name="theme" class="form-control">
                            <? foreach($themes as $t => $text) { ?>
                                <option value="<?=$t?>" <?=($current == $t ? "selected" : "")?>><?=$text?></option>
                            <? } ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Save Changes</button>

                </form>

                </div>
            </div>

        </div>
    </div>
</div>