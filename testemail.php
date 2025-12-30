<?php
// PBL8/test_email.php
// Script untuk test email sebelum integrate ke sistem

// Load PHPMailer (manual - tanpa Composer)
require 'vendor/phpmailer/src/Exception.php';
require 'vendor/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/src/SMTP.php';

// Load Email Service
require 'app/services/email_service.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Email Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-top: 0;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #c3e6cb;
            margin: 15px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #f5c6cb;
            margin: 15px 0;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #bee5eb;
            margin: 15px 0;
        }
        button {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #4338CA;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            font-size: 12px;
        }
        .step {
            margin: 20px 0;
            padding-left: 30px;
            border-left: 3px solid #4F46E5;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🧪 Test Email Service</h1>
        <p>Script ini untuk testing konfigurasi email sebelum dipakai di sistem pengumuman.</p>

        <?php
        // Cek apakah form di-submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'test_connection') {
                echo '<div class="info"><strong>Testing koneksi SMTP...</strong></div>';
                
                try {
                    $emailService = new EmailService();
                    $result = $emailService->testConnection();
                    
                    if ($result['success']) {
                        echo '<div class="success">
                            <h3>✅ Koneksi Berhasil!</h3>
                            <p>' . $result['message'] . '</p>
                            <p>SMTP server bisa dihubungi. Lanjutkan ke test pengiriman email.</p>
                        </div>';
                    } else {
                        echo '<div class="error">
                            <h3>❌ Koneksi Gagal</h3>
                            <p>' . $result['message'] . '</p>
                            <p><strong>Tips:</strong></p>
                            <ul>
                                <li>Pastikan username dan password benar</li>
                                <li>Pastikan 2-Step Verification sudah aktif</li>
                                <li>Pastikan App Password sudah di-generate</li>
                                <li>Cek internet connection</li>
                            </ul>
                        </div>';
                    }
                } catch (Exception $e) {
                    echo '<div class="error">
                        <h3>❌ Error</h3>
                        <p>' . $e->getMessage() . '</p>
                    </div>';
                }
            }

            if ($action === 'send_test_email') {
                $email = $_POST['test_email'] ?? '';
                $name = $_POST['test_name'] ?? 'Test User';

                if (empty($email)) {
                    echo '<div class="error">Email tidak boleh kosong!</div>';
                } else {
                    echo '<div class="info"><strong>Mengirim test email ke ' . htmlspecialchars($email) . '...</strong></div>';
                    
                    try {
                        $emailService = new EmailService();
                        $result = $emailService->sendTestEmail($email, $name);
                        
                        if ($result) {
                            echo '<div class="success">
                                <h3>✅ Email Berhasil Dikirim!</h3>
                                <p>Cek inbox/spam di <strong>' . htmlspecialchars($email) . '</strong></p>
                                <p>Jika email masuk, berarti konfigurasi sudah benar dan siap dipakai!</p>
                            </div>';
                        } else {
                            echo '<div class="error">
                                <h3>❌ Gagal Mengirim Email</h3>
                                <p>Cek error log untuk detail lengkap.</p>
                            </div>';
                        }
                    } catch (Exception $e) {
                        echo '<div class="error">
                            <h3>❌ Error</h3>
                            <p>' . $e->getMessage() . '</p>
                        </div>';
                    }
                }
            }

            if ($action === 'send_pengumuman_test') {
                $email = $_POST['test_email'] ?? '';
                $name = $_POST['test_name'] ?? 'Test User';

                if (empty($email)) {
                    echo '<div class="error">Email tidak boleh kosong!</div>';
                } else {
                    echo '<div class="info"><strong>Mengirim test pengumuman ke ' . htmlspecialchars($email) . '...</strong></div>';
                    
                    // Dummy data pengumuman
                    $recipient = [
                        'email' => $email,
                        'nama_lengkap' => $name,
                        'nim' => '12345678'
                    ];

                    $pengumuman = [
                        'judul' => 'Test Pengumuman - Ujian Tengah Semester',
                        'isi' => "Kepada seluruh mahasiswa,\n\nDiberitahukan bahwa Ujian Tengah Semester akan dilaksanakan pada:\n\nTanggal: 15 Januari 2025\nWaktu: 08.00 - 10.00 WIB\nTempat: Ruang Ujian A\n\nHarap datang tepat waktu dan membawa kartu ujian.\n\nTerima kasih.",
                        'nama_kategori' => 'Akademik',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    try {
                        $emailService = new EmailService();
                        $result = $emailService->sendPengumuman($recipient, $pengumuman);
                        
                        if ($result) {
                            echo '<div class="success">
                                <h3>✅ Email Pengumuman Berhasil Dikirim!</h3>
                                <p>Cek inbox/spam di <strong>' . htmlspecialchars($email) . '</strong></p>
                                <p>Ini adalah preview template yang akan dipakai sistem.</p>
                            </div>';
                        } else {
                            echo '<div class="error">
                                <h3>❌ Gagal Mengirim Email</h3>
                                <p>Cek error log untuk detail lengkap.</p>
                            </div>';
                        }
                    } catch (Exception $e) {
                        echo '<div class="error">
                            <h3>❌ Error</h3>
                            <p>' . $e->getMessage() . '</p>
                        </div>';
                    }
                }
            }
        }
        ?>

        <div class="step">
            <h3>Step 1: Test Koneksi SMTP</h3>
            <p>Test apakah server SMTP bisa dihubungi dengan kredensial yang ada di <code>config/email_config.php</code></p>
            <form method="POST">
                <input type="hidden" name="action" value="test_connection">
                <button type="submit">🔌 Test Koneksi</button>
            </form>
        </div>

        <div class="step">
            <h3>Step 2: Kirim Test Email Sederhana</h3>
            <p>Kirim email test sederhana untuk memastikan pengiriman berfungsi.</p>
            <form method="POST">
                <input type="hidden" name="action" value="send_test_email">
                <div style="margin: 15px 0;">
                    <label style="display: block; margin-bottom: 5px;">Email Tujuan:</label>
                    <input type="email" name="test_email" placeholder="email@example.com" required 
                           style="padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin: 15px 0;">
                    <label style="display: block; margin-bottom: 5px;">Nama Penerima:</label>
                    <input type="text" name="test_name" placeholder="Nama Lengkap" value="Test User" required
                           style="padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <button type="submit">📧 Kirim Test Email</button>
            </form>
        </div>

        <div class="step">
            <h3>Step 3: Test Template Pengumuman</h3>
            <p>Kirim email dengan template pengumuman sebenarnya (dummy data).</p>
            <form method="POST">
                <input type="hidden" name="action" value="send_pengumuman_test">
                <div style="margin: 15px 0;">
                    <label style="display: block; margin-bottom: 5px;">Email Tujuan:</label>
                    <input type="email" name="test_email" placeholder="email@example.com" required 
                           style="padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <div style="margin: 15px 0;">
                    <label style="display: block; margin-bottom: 5px;">Nama Penerima:</label>
                    <input type="text" name="test_name" placeholder="Nama Lengkap" value="Test User" required
                           style="padding: 10px; width: 100%; max-width: 400px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
                <button type="submit">📬 Kirim Test Pengumuman</button>
            </form>
        </div>
    </div>

    <div class="card">
        <h3>📋 Checklist Setup</h3>
        <ol>
            <li>✅ Download PHPMailer ke <code>vendor/phpmailer/</code></li>
            <li>✅ Setup Gmail App Password</li>
            <li>✅ Update <code>config/email_config.php</code></li>
            <li>✅ Jalankan Step 1: Test Koneksi</li>
            <li>✅ Jalankan Step 2: Test Email Sederhana</li>
            <li>✅ Jalankan Step 3: Test Template Pengumuman</li>
            <li>✅ Jika semua berhasil → Sistem siap dipakai!</li>
        </ol>
    </div>
</body>
</html>