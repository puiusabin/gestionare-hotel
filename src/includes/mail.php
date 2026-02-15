<?php

use Resend\Client as ResendClient;

function sendEmail($to, $subject, $message, $fromEmail = null, $fromName = null)
{
    $apiKey = env('RESEND_API_KEY');
    if (!$apiKey) {
        error_log("Resend API key not configured");
        return false;
    }

    try {
        $resend = ResendClient::client($apiKey);

        $from = $fromEmail ?? env('MAIL_FROM_ADDRESS', 'noreply@php.blanc.is');
        $fromName = $fromName ?? env('MAIL_FROM_NAME', 'Hotel Reservation');

        $resend->emails->send([
            'from' => "{$fromName} <{$from}>",
            'to' => [$to],
            'subject' => $subject,
            'html' => $message,
        ]);

        return true;
    } catch (Exception $e) {
        error_log("Resend error: " . $e->getMessage());
        return false;
    }
}
