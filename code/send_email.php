<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format. Please enter a valid email.";
        exit;
    }

    // Check if required fields are filled
    if (empty($fullname) || empty($email) || empty($message)) {
        echo "All fields are required.";
        exit;
    }

    // Recipient email and email details
    $to = "9849475949harsh@gmail.com"; // Replace with your email
    $subject = "New Contact Form Submission from $fullname";
    $body = "You have received a new message from your website contact form.\n\n";
    $body .= "Name: $fullname\n";
    $body .= "Email: $email\n";
    $body .= "Message:\n$message\n";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    if (mail($to, $subject, $body, $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message. Please try again.";
    }
} else {
    echo "Invalid request method.";
}
?>