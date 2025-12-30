<?php
// PBL8/app/services/email_template_service.php

class EmailTemplateService
{
    public function generateEmail($recipient, $pengumuman)
    {
        $nama = htmlspecialchars($recipient['nama_lengkap']);
        $nim = htmlspecialchars($recipient['nim'] ?? '-');
        $judul = htmlspecialchars($pengumuman['judul']);
        $isi = nl2br(htmlspecialchars($pengumuman['isi']));
        $kategori = htmlspecialchars($pengumuman['nama_kategori'] ?? 'Umum');
        $tanggal = date('d F Y, H:i', strtotime($pengumuman['created_at'])) . ' WIB';
        $tahun = date('Y');

        return "
        <!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    color: #333; 
                    margin: 0; 
                    padding: 20px;
                    background-color: #f5f5f5;
                }
                .container { 
                    max-width: 600px; 
                    margin: 0 auto; 
                    background: white;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                }
                .header { 
                    background: #51c8e9;
                    color: white; 
                    padding: 30px 25px; 
                    text-align: center; 
                }
                .header h1 { 
                    margin: 0; 
                    font-size: 24px; 
                }
                .badge { 
                    display: inline-block; 
                    background: rgba(255,255,255,0.2); 
                    padding: 5px 15px; 
                    border-radius: 15px; 
                    font-size: 12px; 
                    margin-top: 8px;
                }
                .content { 
                    padding: 30px 25px; 
                }
                .greeting { 
                    margin-bottom: 20px;
                    color: #666;
                }
                .greeting strong { 
                    color: #333; 
                }
                .announcement-title { 
                    font-size: 20px; 
                    font-weight: bold; 
                    color: #333; 
                    margin: 20px 0 15px 0;
                }
                .announcement-body { 
                    color: #555; 
                    line-height: 1.8;
                    background: #f9fafb;
                    padding: 20px;
                    border-radius: 6px;
                    border-left: 3px solid #51c8e9;
                }
                .divider { 
                    border: 0; 
                    border-top: 1px solid #e5e7eb; 
                    margin: 25px 0;
                }
                .info-box { 
                    background: #f3f4f6;
                    padding: 15px;
                    border-radius: 6px;
                    font-size: 13px;
                    color: #6b7280;
                }
                .info-row {
                    padding: 4px 0;
                }
                .footer { 
                    background: #f9fafb;
                    text-align: center; 
                    padding: 20px; 
                    font-size: 12px;
                    color: #6b7280;
                    border-top: 1px solid #e5e7eb;
                }
                @media only screen and (max-width: 600px) {
                    body {
                        padding: 10px;
                    }
                    .content { 
                        padding: 20px 15px; 
                    }
                    .header { 
                        padding: 25px 15px; 
                    }
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>📢 Pengumuman</h1>
                    <span class='badge'>{$kategori}</span>
                </div>
                
                <div class='content'>
                    <div class='greeting'>
                        Kepada Yth,<br>
                        <strong>{$nama}</strong><br>
                        <small>NIM: {$nim}</small>
                    </div>
                    
                    <div class='announcement-title'>{$judul}</div>
                    
                    <div class='announcement-body'>
                        {$isi}
                    </div>
                    
                    <hr class='divider'>
                    
                    <div class='info-box'>
                        <div class='info-row'><strong>📅 Tanggal:</strong> {$tanggal}</div>
                        <div class='info-row'><strong>📂 Kategori:</strong> {$kategori}</div>
                    </div>
                </div>
                
                <div class='footer'>
                    <p>Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.</p>
                    <p>&copy; {$tahun} Sistem Pengumuman Akademik IF1A-8</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}