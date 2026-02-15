<?php

require_once __DIR__ . '/../includes/mail.php';

class ContactController
{
    public function index()
    {
        $title = 'Contact Us - Hotel Reservation System';
        require_once __DIR__ . '/../views/layout/header.php';
        recaptchaScript();
        require_once __DIR__ . '/../views/contact.php';
        recaptchaExecute('contact');
        require_once __DIR__ . '/../views/layout/footer.php';
    }

    public function submit()
    {
        if (!validateCsrfToken()) {
            setFlashMessage('error', 'Invalid security token');
            header('Location: /contact');
            exit;
        }

        $expectedFields = ['csrf_token', 'name', 'email', 'subject', 'message', 'g-recaptcha-response'];
        if (!validateExpectedFields($expectedFields)) {
            setFlashMessage('error', 'Invalid form submission');
            header('Location: /contact');
            exit;
        }

        if (!validateRecaptcha()) {
            setFlashMessage('error', 'reCAPTCHA verification failed. Please try again.');
            header('Location: /contact');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            setFlashMessage('error', 'All fields are required');
            header('Location: /contact');
            exit;
        }

        // Sanitize inputs to prevent email header injection
        $name = sanitizeEmailName($name);
        $email = sanitizeEmailAddress($email);
        $subject = preg_replace('/[\r\n\t\0]/', '', $subject);

        if ($email === false) {
            setFlashMessage('error', 'Invalid email format');
            header('Location: /contact');
            exit;
        }

        $to = DEFAULT_ADMIN_EMAIL;
        $emailBody = "<h3>New Contact Message</h3>
                      <p><strong>From:</strong> " . htmlspecialchars($name) . " (" . htmlspecialchars($email) . ")</p>
                      <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
                      <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>";

        if (sendEmail($to, "Contact: $subject", $emailBody, $email, $name)) {
            setFlashMessage('success', 'Your message has been sent successfully!');
        } else {
            setFlashMessage('error', 'Failed to send message. Please try again later.');
        }

        header('Location: /contact');
        exit;
    }
}
