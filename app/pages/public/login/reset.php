<img style="width: 100%" src="/static/img/logo.png">
<div><?=html::get_page_messages()?></div>
<h2 class="form-signin-heading">Set New Password</h2>
<?=html::input_bare("reset-password","New Password", "", "password", "New Password")?>
<?=html::input_bare("reset-password2","New Password (again)", "", "password", "New Password (again)")?>
<button name="submit_reset" class="btn btn-lg btn-primary btn-block" type="submit">Set Password</button>
<div class="login-link">
    <a href="/login">Return to Login</a><br>
</div>
