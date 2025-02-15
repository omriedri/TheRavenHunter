<?php require_once __DIR__ . '/../Helpers/StringHelper.php';
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
        $subject = StringHelper::cleanSpecialChars($subject);
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: " . $_ENV['EMAIL__SENDER_NO_REPLY'] . "\r\n";
    
        return mail($to, $subject, $message, $headers);
    }
}