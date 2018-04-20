<img style="width: 100%" src="/static/img/logo.png">
<div><?=html::get_page_messages()?></div>
<h2 class="form-signin-heading">Register New User</h2>
<?=html::input_bare("first_name","First Name", "", "text", "First Name")?>
<?=html::input_bare("last_name","Last Name", "", "text", "Last Name")?>
<?=html::input_bare("email","Email Address", "", "email", "Email Address")?>

<button name="submit_register" class="btn btn-lg btn-primary btn-block" type="submit">Create Account</button>
<div class="login-link">
    <a href="/login">Return to Login</a><br>
</div>

