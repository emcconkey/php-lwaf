<?php

class email {

    /**
     * Sends an email using PHPMailer
     *
     * If site_config.php has "email_redirect" and "email_redirect_address" configured, this will redirect all
     * emails to "email_redirect_address"
     *
     * @param $to
     * @param $subject
     * @param $message
     * @param bool $attachment
     * @return array|bool
     * @throws phpmailerException
     */
    static function send_message($to, $subject, $message, $attachment = false) {
        $mail = new PHPMailer;

        // For testing, send all emails to me
        if(config::get("email_redirect")) {
            $to = config::get("email_redirect_address");
        }

        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->msgHTML($message);
        $mail->addAttachment($attachment);
        $mail->IsSMTP();

        $mail->Host = config::get("mail_server");
        $mail->SMTPAuth = config::get("smtp_auth");
        $mail->Port = config::get("mail_port");
        $mail->setFrom(config::get("email_from"), config::get("email_from_name"));

        if(config::get("mail_smtp_secure")) {
            $mail->SMTPSecure = config::get("mail_smtp_secure");
        } else {
            $mail->SMTPAutoTLS = false;
        }

        if(config::get("mail_user")) {
            $mail->Username = config::get("mail_user");
            $mail->Password = config::get("mail_pass");
        }

        //send the message, check for errors

        if(!$mail->send()) {
            $arr = $mail->getSMTPInstance()->getError();
            return $arr;
        }
        return true;
    }

}
