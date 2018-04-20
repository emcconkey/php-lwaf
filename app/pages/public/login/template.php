
        <img style="width: 100%" src="/static/img/logo.png">
        <div><?=html::get_page_messages()?></div>
        <h2 class="form-signin-heading">Log In</h2>
        <?=html::input_bare("email","Email Address", "", "email", "Email Address")?>
        <?=html::input_bare("password", "Password", "","password", "Password")?>
        <button name="submit_login" class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <div class="login-link">
            <a href="/login/forgot">Reset your password</a><br>
        </div>
        <div class="login-link">
            <a href="/login/register">Register new account</a>
        </div>
        <div class="hidden">
            <strong>If you can read this, you didn't follow step 3 in the install directions.</strong>
        </div>
