<?php
class EmailService {

    public static function sendEmail(string $to, string $subject, string $message): bool {
        $to = filter_var($to, FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
        $headers = "From: " . $_ENV['EMAIL_FROM'] . "\r\n";
        $headers .= "Reply-To: " . $_ENV['EMAIL_FROM'] . "\r\n";
        $headers .= "Return-Path: " . $_ENV['EMAIL_FROM'] . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        return mail($to, $subject, $message, $headers);
    }

    /**
     * Send email from no-reply email
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return boolean
     */
    public static function sendNoReplayEmail(string $to, string $subject, string $message): bool {
        $to = filter_var($to, FILTER_SANITIZE_EMAIL);
        $subject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
        $headers = "From: " . $_ENV['NO_REPLY__SENDER'] . "\r\n";
        $headers .= "Reply-To: " . $_ENV['NO_REPLY__EMAIL'] . "\r\n";
        $headers .= "Return-Path: " . $_ENV['NO_REPLY__EMAIL'] . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        return mail($to, $subject, $message, $headers);
    }
}