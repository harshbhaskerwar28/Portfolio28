<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Sanitize and validate input data
        $fullname = htmlspecialchars(trim($_POST['fullname']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $message = htmlspecialchars(trim($_POST['message']));

        // Validation checks
        if (empty($fullname) || empty($email) || empty($message)) {
            throw new Exception("All fields are required.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // Email configuration
        $to = "9849475949harsh@gmail.com";
        $subject = "New Contact Form Submission from $fullname";
        
        // Create email body
        $body = "You have received a new message from your website contact form.\n\n";
        $body .= "Name: $fullname\n";
        $body .= "Email: $email\n";
        $body .= "Message:\n$message\n";

        // More robust headers
        $headers = array(
            'From' => $email,
            'Reply-To' => $email,
            'X-Mailer' => 'PHP/' . phpversion(),
            'Content-Type' => 'text/plain; charset=UTF-8'
        );

        // Send email
        if (mail($to, $subject, $body, implode("\r\n", $headers))) {
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully!']);
        } else {
            throw new Exception("Failed to send email. Please try again later.");
        }
        
    } catch (Exception $e) {
        // Return error message
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
