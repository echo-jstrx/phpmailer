function sendEmail($to, $subject, $templateFile, $placeholders) {
    // Read the email template file
    $template = file_get_contents($templateFile);

    // Replace placeholders with actual values
    foreach ($placeholders as $key => $value) {
        $template = str_replace("[$key]", $value, $template);
    }

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

        // Recipients
        if (is_array($to)) {
            foreach ($to as $email) {
                $mail->addAddress($email);
            }
        } else {
            $mail->addAddress($to);
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $template;

        $mail->send();
        echo "Email sent to $to: $subject\n";
    } catch (Exception $e) {
        echo "Failed to send email. Mailer Error: {$mail->ErrorInfo}\n";
    }
}

// Example usage
$to = ['user1@example.com', 'user2@example.com'];
$subject = 'Event Reminder';
$templateFile = 'email_template.html';
$placeholders = [
    'NAME' => 'John Doe',
    'EVENT_DATE' => '2024-12-25 18:00:00'
];

sendEmail($to, $subject, $templateFile, $placeholders);
