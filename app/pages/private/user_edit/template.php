<?php
$user = $this->get("user");
?>
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default">
            <div class="panel-body">



            <form method="post" class="col-md-7">

                <div class="form-group">
                    <label for="first_name" class="">First Name</label>
                    <input type="text" class="" id="first_name" name="first_name" value="<?=$user->get('first_name')?>" required="" title="Please enter your first name" placeholder="">

                </div>
                <div class="form-group">
                    <label for="last_name" class="control-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?=$user->get('last_name')?>" required="" title="Please enter your last name" placeholder="">
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?=$user->get('email')?>" required="" title="Please enter your email address" placeholder="">
                </div>

                <div class="form-group">
                    <label for="password" class="control-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" value="">
                </div>

                <div class="form-group">
                    <?html::select("status", "Status", ["active" => "Active", "disabled" => "Disabled"], $user->get("status"))?>
                </div>


                <button type="submit" class="btn">Save Changes</button>
            </form>

            </div>
        </div>

    </div>
</div>
