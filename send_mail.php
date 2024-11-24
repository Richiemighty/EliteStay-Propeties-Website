<?php
// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure you have installed PHPMailer via Composer

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
        echo "All fields are required.";
        exit;
    }

    // Get form data
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $message = htmlspecialchars(strip_tags($_POST['message']));

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                   // Use SMTP
        $mail->Host       = 'smtp.gmail.com';              // Set the SMTP server to send through (e.g., Gmail SMTP)
        $mail->SMTPAuth   = true;                          // Enable SMTP authentication
        $mail->Username   = 'your_email@gmail.com';        // Your SMTP username
        $mail->Password   = 'your_email_password';         // Your SMTP password (use App Password if 2FA is enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port       = 587;                           // TCP port to connect to

        // Recipients
        $mail->setFrom('your_email@gmail.com', 'Your Website'); // Sender's email and name
        $mail->addAddress('richiemighty5@gmail.com');   // Recipient's email

        // Content
        $mail->isHTML(true);                                // Set email format to HTML
        $mail->Subject = "Contact Form Submission from: $name";
        $mail->Body    = "
            <h2>New Contact Form Submission</h2>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Message:</strong></p>
            <p>$message</p>
        ";
        $mail->AltBody = "New Contact Form Submission\n\nName: $name\nEmail: $email\nMessage: $message"; // Plain text version

        // Send the email
        $mail->send();
        echo "Message has been sent successfully.";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
