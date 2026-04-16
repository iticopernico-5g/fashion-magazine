<?php

use Camezilla\Models\MailSender;

function is_mail_enabled(): bool {
    $config = get_config();
    
    try {
        return $config->get('mail.enabled') === true;
    } catch (Exception $_) {
        return false;
    }
}

function require_mail_enabled(): void {
    if (!is_mail_enabled()) {
        throw new Exception("Mail is not enabled");
    }
}

function init_mail(): void {
    if (!is_mail_enabled()) {
        return;
    }

    $config = get_config();

    $host = $config->get('mail.host');
    add_global_item('mail_sender', new MailSender($host));
}

function get_mail_sender(): MailSender {
    require_mail_enabled();

    if (get_global_item('mail_sender') === null) {
        throw new Exception("Mail sender not initialized");
    }

    return get_global_item('mail_sender');
}

function send_mail(string $to, string $subject, string $message): bool {
    require_mail_enabled();
    
    $mail_sender = get_mail_sender();

    return $mail_sender->send_mail($to, $subject, $message);
}