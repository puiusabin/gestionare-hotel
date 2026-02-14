<?php

require_once __DIR__ . '/../includes/mail.php';

class ContactController
{
    public function index()
    {
        $title = 'Contact Us - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        require_once __DIR__ . '/../views/contact.php';
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function submit()
    {
        if (!validateCsrfToken()) {
            setFlashMessage('Invalid security token', 'error');
            header('Location: /contact');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            setFlashMessage('All fields are required', 'error');
            header('Location: /contact');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlashMessage('Invalid email format', 'error');
            header('Location: /contact');
            exit;
        }

        $to = env('ADMIN_EMAIL', 'admin@hotel.com');
        $emailBody = "<h3>New Contact Message</h3>
                      <p><strong>From:</strong> $name ($email)</p>
                      <p><strong>Subject:</strong> $subject</p>
                      <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";

        if (sendEmail($to, "Contact: $subject", $emailBody, $email, $name)) {
            setFlashMessage('Your message has been sent successfully!', 'success');
        } else {
            setFlashMessage('Failed to send message. Please try again later.', 'error');
        }

        header('Location: /contact');
        exit;
    }
}
