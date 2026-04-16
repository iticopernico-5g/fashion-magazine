<?php
namespace Camezilla\Models;

class MailSender {
    
    private string $host;

    public function __construct(string $host) {
        $this->host = $host;
    }

    public function send_mail(string $to, string $subject, string $message): bool {
        $headers = 'From: ' . $this->host;
        return mail($to, $subject, $message, $headers);
    }
}