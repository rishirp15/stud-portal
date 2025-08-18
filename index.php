<?php
// A simple variable to store the message to the user
$feedback_message = '';

// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- Sanitize and Validate Input ---
    // Use filter_input to get and sanitize POST data. It's a good security practice.
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // --- Basic Validation ---
    if (empty($name) || empty($email) || empty($message)) {
        $feedback_message = '<div class="message error">All fields are required.</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback_message = '<div class="message error">Please enter a valid email address.</div>';
    } else {
        // --- Process the Form (e.g., Send an Email) ---
        
        // **IMPORTANT**: The mail() function's success depends on your server's configuration.
        // On a local server (like XAMPP), you need to configure PHP to send emails.
        // On a live web host, it usually works out of the box.
        
        $to = 'rishirphadale1507@gmail.com'; // <<< REPLACE WITH YOUR EMAIL ADDRESS
        $subject = 'New Contact Form Submission from ' . $name;
        $body = "You have received a new message from your website contact form.\n\n";
        $body .= "Here are the details:\n\n";
        $body .= "Name: " . $name . "\n";
        $body .= "Email: " . $email . "\n";
        $body .= "Message:\n" . $message . "\n";
        
        $headers = 'From: noreply@yourwebsite.com' . "\r\n" .
                   'Reply-To: ' . $email;

        // Attempt to send the email
        if (mail($to, $subject, $body, $headers)) {
            $feedback_message = '<div class="message success">Thank you! Your message has been sent.</div>';
        } else {
            // This error is common on local servers that aren't set up as mail servers.
            $feedback_message = '<div class="message error">Sorry, there was an error sending your message. Please try again later.</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="form-container">
        <h2>Contact Us</h2>
        <p>Have a question? Fill out the form below to get in touch.</p>
        
        <!-- Display feedback message here -->
        <?php echo $feedback_message; ?>

        <form action="index.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="6" required></textarea>
            
            <button type="submit">Send Message</button>
        </form>
    </div>

</body>
</html>