<?php
function sendEmail($recipients, $subject, $templateFile) {
    // Read the email template file
    $template = file_get_contents($templateFile);

    // Set up PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com';
        $mail->Password = 'your_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Loop through each recipient
        foreach ($recipients as $recipient) {
            $personalizedTemplate = $template;

            // Replace placeholders with actual values
            foreach ($recipient['placeholders'] as $key => $value) {
                $personalizedTemplate = str_replace("[$key]", $value, $personalizedTemplate);
            }

            // Clear previous recipients
            $mail->clearAddresses();

            // Add the current recipient
            $mail->addAddress($recipient['email']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $personalizedTemplate;

            // Send the email
            $mail->send();
            echo "Email sent to {$recipient['email']}: $subject\n";
        }
    } catch (Exception $e) {
        echo "Failed to send email. Mailer Error: {$mail->ErrorInfo}\n";
    }
}

// Example usage
$recipients = [
    [
        'email' => 'user1@example.com',
        'placeholders' => [
            'NAME' => 'John Doe',
            'EVENT_DATE' => '2024-12-25 18:00:00'
        ]
    ],
    [
        'email' => 'user2@example.com',
        'placeholders' => [
            'NAME' => 'Jane Smith',
            'EVENT_DATE' => '2024-12-25 18:00:00'
        ]
    ]
];

$subject = 'Event Reminder';
$templateFile = 'email_template.html';

sendEmail($recipients, $subject, $templateFile);
