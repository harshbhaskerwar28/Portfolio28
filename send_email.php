<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log errors to a file
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');

// Function to send JSON response
function sendResponse($status, $message) {
    echo json_encode([
        'status' => $status,
        'message' => $message
    ]);
    exit;
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    error_log("Method not allowed: " . $_SERVER["REQUEST_METHOD"]);
    sendResponse('error', 'Method not allowed');
}

try {
    // Validate that all required fields exist
    if (!isset($_POST['fullname']) || !isset($_POST['email']) || !isset($_POST['message'])) {
        throw new Exception('Missing required fields');
    }

    // Sanitize and validate input data
    $fullname = htmlspecialchars(trim($_POST['fullname']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Validation checks
    if (empty($fullname) || empty($email) || empty($message)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Email configuration
    $to = "9849475949harsh@gmail.com";
    $subject = "New Contact Form Submission from $fullname";
    
    // Create email body
    $body = "You have received a new message from your website contact form.\n\n";
    $body .= "Name: $fullname\n";
    $body .= "Email: $email\n";
    $body .= "Message:\n$message\n";

    // Headers
    $headers = [
        'From' => $email,
        'Reply-To' => $email,
        'X-Mailer' => 'PHP/' . phpversion(),
        'Content-Type' => 'text/plain; charset=UTF-8',
        'MIME-Version' => '1.0'
    ];

    // Attempt to send email
    if (!mail($to, $subject, $body, implode("\r\n", $headers))) {
        error_log("Failed to send email");
        throw new Exception('Failed to send email. Please try again later.');
    }

    sendResponse('success', 'Message sent successfully!');

} catch (Exception $e) {
    error_log("Error in contact form: " . $e->getMessage());
    sendResponse('error', $e->getMessage());
}
?>
