<?php
// PBL8/test_smtp.php

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Enable verbose debug output
    $mail->SMTPDebug = 3;
    $mail->Debugoutput = 'html';

    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'if1a8pbl@gmail.com';
    $mail->Password = 'qpkw iwlj uxkq olmp';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Disable SSL verification
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    // Recipients
    $mail->setFrom('if1a8pbl@gmail.com', 'Test Sender');
    $mail->addAddress('test@gmail.com', 'Test User'); // GANTI EMAIL INI!

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email dari Laptop Teman';
    $mail->Body = '<h1>Test berhasil!</h1><p>Jika email ini masuk, berarti SMTP berfungsi.</p>';
    $mail->AltBody = 'Test berhasil! Jika email ini masuk, berarti SMTP berfungsi.';

    $mail->send();
    echo '<div style="color: green; padding: 20px; border: 2px solid green; margin: 20px;">
            <h2>✓ Email berhasil dikirim!</h2>
            <p>Cek inbox untuk konfirmasi.</p>
          </div>';
          
} catch (Exception $e) {
    echo '<div style="color: red; padding: 20px; border: 2px solid red; margin: 20px;">
            <h2>✗ Email gagal dikirim!</h2>
            <p><strong>Error:</strong> ' . $mail->ErrorInfo . '</p>
          </div>';
}