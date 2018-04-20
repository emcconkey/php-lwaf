<img style="width: 100%" src="/static/img/logo.png">
<div><?=html::get_page_messages()?></div>
<h2 class="form-signin-heading">Log In</h2>
<?=html::input_bare("forgot-email","Email Address", "", "email", "Email Address")?>
<button name="submit_forgot" class="btn btn-lg btn-primary btn-block" type="submit">Reset Password</button>
<div class="login-link">
    <a href="/login/">Return to Login</a><br>
</div>
