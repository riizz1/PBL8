<?php
// PBL8/app/services/email_service.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private $mail;
    private $config;

    public function __construct()
    {
        // Load email config
        $this->config = require __DIR__ . '/../../config/email_config.php';

        // Initialize PHPMailer
        $this->mail = new PHPMailer(true);

        try {
            // SMTP Configuration
            $this->mail->isSMTP();
            $this->mail->Host = $this->config['smtp_host'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $this->config['smtp_username'];
            $this->mail->Password = $this->config['smtp_password'];
            $this->mail->SMTPSecure = $this->config['smtp_secure'];
            $this->mail->Port = $this->config['smtp_port'];

            // Sender Info
            $this->mail->setFrom($this->config['from_email'], $this->config['from_name']);

            // UTF-8 encoding
            $this->mail->CharSet = 'UTF-8';

            // Disable SSL verification (only for development!)
            $this->mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

        } catch (Exception $e) {
            error_log("Email Service Init Error: " . $e->getMessage());
        }
    }

    /**
     * Kirim email pengumuman
     */
    public function sendPengumuman($recipient, $pengumuman)
    {
        try {
            // Reset recipients
            $this->mail->clearAddresses();

            // Add recipient
            $this->mail->addAddress($recipient['email'], $recipient['nama_lengkap']);

            // Subject
            $this->mail->Subject = "[Pengumuman] " . $pengumuman['judul'];

            // Body (HTML)
            $this->mail->isHTML(true);
            $this->mail->Body = $this->getEmailHTML($recipient, $pengumuman);

            // Plain text alternative
            $this->mail->AltBody = strip_tags($pengumuman['isi']);

            // Send
            return $this->mail->send();

        } catch (Exception $e) {
            error_log("Email Send Error to {$recipient['email']}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Generate HTML email menggunakan template
     */
    private function getEmailHTML($recipient, $pengumuman)
    {
        // Load template service
        require_once __DIR__ . '/email_template_service.php';
        $templateService = new EmailTemplateService();

        // Generate email
        return $templateService->generateEmail($recipient, $pengumuman);
    }

    /**
     * Test koneksi SMTP
     */
    public function testConnection()
    {
        try {
            $this->mail->smtpConnect();
            return [
                'success' => true,
                'message' => 'Koneksi SMTP berhasil!'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Koneksi SMTP gagal: ' . $this->mail->ErrorInfo
            ];
        }
    }

    /**
     * Kirim test email sederhana
     */
    public function sendTestEmail($to, $name)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to, $name);
            $this->mail->Subject = 'Test Email - Sistem Pengumuman';
            $this->mail->isHTML(true);
            $this->mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px;'>
                    <h2 style='color: #4F46E5;'>✅ Test Email Berhasil!</h2>
                    <p>Halo <strong>{$name}</strong>,</p>
                    <p>Email ini adalah test pengiriman dari sistem pengumuman.</p>
                    <p>Jika kamu menerima email ini, berarti konfigurasi email sudah <strong style='color: green;'>BENAR</strong>.</p>
                    <hr>
                    <p style='font-size: 12px; color: #666;'>Dikirim pada: " . date('d F Y H:i:s') . "</p>
                </div>
            ";

            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Test Email Error: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}