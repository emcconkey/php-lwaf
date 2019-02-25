<?php
class login extends page {

    function get_page($args) {

        if(user::current()->loaded()) {
            header("Location: /dashboard");
            exit();
        }

        if(isset($args[0])) {
            if($args[0] == "forgot") {
                $this->set("page_title", "Forgot Password");
                $this->render("lo-header");
                $this->render("login_top");
                $this->render("forgot");
                $this->render("login_bottom");
                $this->render("lo-footer");
                return;
            }

            if($args[0] == "register") {

                $this->set("page_title", "Register New Account");
                $this->render("lo-header");
                $this->render("login_top");
                $this->render("register");
                $this->render("login_bottom");
                $this->render("lo-footer");
                return;
            }

            if($args[0] == "reset") {

                $this->set("page_title", "Password Reset");
                $token = database::sql()->load_object("reset_token", "token", $args[1]);

                if(!$token || !$token->is_valid()) {
                    html::error_message("Error", "Invalid password reset token");
                    $this->set("button_title","OK");
                    $this->set("button_url","/");

                    $this->render("lo-header");
                    $this->render("alert");
                    $this->render("lo-footer");
                    return;
                }

                $this->set("page_title", "Login");
                $this->render("lo-header");
                $this->render("login_top");
                $this->render("reset");
                $this->render("login_bottom");
                $this->render("lo-footer");
                return;
            }
        }


        $this->render("lo-header");
        $this->render("login_top");
        $this->render();
        $this->render("login_bottom");
        $this->render("lo-footer");
    }

    function post_page($args) {

        if(isset($_POST['submit_login'])) {
            if($user = user::check_login($_POST['email'], $_POST['password'])) {
                $user->login();
                header("Location: /dashboard");
                return;
            }
            html::error_message("Error", "Unknown email address or incorrect password");
            $this->render("lo-header");
            $this->render("login_top");
            $this->render();
            $this->render("login_bottom");
            $this->render("lo-footer");
            return;
        }

        // Request account
        if(isset($_POST['submit_register'])) {

            $user = database::sql()->load_object("user", "email", $_POST['email']);

            $has_error = false;
            if($user){
                html::error_message("Error", "An account with that email address already exists.");
                $has_error = true;
            }
            if(!user::validate_email($_POST['email'])) {
                html::error_message("Error", "Invalid email address");
                $has_error = true;
            }

            if($has_error) {
                $this->render("lo-header");
                $this->render("login_top");
                $this->render("register");
                $this->render("login_bottom");
                $this->render("lo-footer");
                return;
            }

            $user = new user();
            $user->set("email", $_POST['email']);
            $user->set("first_name", $_POST['first_name']);
            $user->set("last_name", $_POST['last_name']);
            $user->set("status", "active");
            $user->save();

            $token = $user->new_reset_token();
            $token->save();

            $this->set("page_title", "Registration Process");
            $this->set("button_title","OK");
            $this->set("button_url","/");
            $this->set("email_subject", "Website Registration");
            $this->set("email_content", "Thank you for registering your account. Click the button below to confirm your account.");
            $this->set("email_button_link", config::get("site_url") . "/login/reset/" . $token->get('token'));
            $this->set("email_button_text", "Confirm Account");
            ob_start();
            $this->render("email");
            $message = ob_get_clean();
            $res = email::send_message($_POST['email'], "Account Registration", $message);
            if($res !== true) {
                echo "There was an error sending the email: " . implode(', ', $res);
                exit();
            }

            html::success_message("Account Created", 'An email has been sent to ' . $_POST['email'] . ' with instructions on how to complete your account.<br>');

            $this->render("lo-header");
            $this->render("alert");
            $this->render("lo-footer");
            return;
        }

        // forgot password
        if(isset($_POST['submit_forgot'])) {
            $user = new user();
            $user->load("email", $_POST['forgot-email']);
            if(!$user->loaded()){
                html::error_message("Error", "Invalid email address");
                $this->render("lo-header");
                $this->render("login_top");
                $this->render("forgot");
                $this->render("login_bottom");
                $this->render("lo-footer");
                return;
            }

            $token = $user->new_reset_token();

            $this->set("button_title", "Continue");
            $this->set("button_url", "/");

            html::success_message("Success", "An email containing instructions on how to reset your password has been sent to {$user->get("email")}.<br>");

            $this->set("email_subject", "Password Reset");
            $this->set("email_content", "Please follow the link below to reset your password.");
            $this->set("email_button_link", config::get('site_url') . "/login/reset/" . $token->get('token'));
            $this->set("email_button_text", "Reset My Password");
            ob_start();
            $this->render("email");
            $message = ob_get_clean();
            $res = email::send_message($_POST['forgot-email'], "Password Recovery", $message);
            if($res !== true) {
                echo "There was an error sending the email: " . implode(', ', $res);
                exit();
            }
            $this->render("lo-header");
            $this->render("alert");
            $this->render("lo-footer");
            return;
        }


        // Reset password
        if(isset($_POST['submit_reset'])) {

            $user = false;
            $token = database::sql()->load_object("reset_token", "token", $args[1]);
            if($token)
                $user = database::sql()->load_object("user", "user_id", $token->get("user_id"));

            if($user === false) {
                html::error_message("Error", "Invalid password reset token");
                $this->set("button_title", "Continue");
                $this->set("button_url", "/");

                $this->render("lo-header");
                $this->render("alert");
                $this->render("lo-footer");
                return;
            }


            $set_error = false;
            if(strlen($_POST['reset-password']) < 6) {
                $set_error = true;
                html::error_message("message", "Your new password must be at least 6 characters");
            }
            if($_POST['reset-password'] != $_POST['reset-password2']) {
                $set_error = true;
                html::error_message("message", "You didn't type the password the same both times. Please try again.");
            }
            if($set_error) {
                $this->render("lo-header");
                $this->render("login_top");
                $this->render("reset");
                $this->render("login_bottom");
                $this->render("lo-footer");
                return;
            }
            $user->set_password($_POST['reset-password']);
            $user->save();
            $token->delete_object();
            $this->set("page_title", "Reset Successful");
            html::success_message("Success", "Your password has been reset. You can now log in with your new password");
            $token->delete_object();
            $this->set("button_title", "Return To Login");
            $this->set("button_url", "/");


            $this->render("lo-header");
            $this->render("alert");
            $this->render("lo-footer");
            return;
        }

        $this->render("errorpage");
        return;
    }
}
