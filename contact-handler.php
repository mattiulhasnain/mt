<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $message = sanitize_input($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Please provide a valid email address.']);
        exit;
    }
    
    // In a real application, you would send an email here
    // For demo purposes, we'll just log to a file
    $log_entry = date('Y-m-d H:i:s') . " - Contact Form Submission\n";
    $log_entry .= "Name: $name\n";
    $log_entry .= "Email: $email\n";
    $log_entry .= "Message: $message\n";
    $log_entry .= "---\n\n";
    
    file_put_contents('contact_messages.log', $log_entry, FILE_APPEND | LOCK_EX);
    
    echo json_encode(['status' => 'success', 'message' => 'Thank you for your message! We will get back to you soon.']);
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>