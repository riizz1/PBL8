<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Akademik</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .header-icons {
            display: flex;
            gap: 15px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f0f0f0;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .icon-btn:hover {
            background: #667eea;
            color: white;
            transform: scale(1.1);
        }

        .main-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .content-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
        }

        .content-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .content-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content-body {
            padding: 40px;
        }

        .info-section {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .info-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-list {
            list-style: none;
        }

        .info-list li {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: start;
            gap: 15px;
            transition: all 0.3s;
        }

        .info-list li:hover {
            transform: translateX(10px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }

        .info-number {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .info-text {
            flex: 1;
            line-height: 1.6;
            color: #333;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .calendar-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 25px;
            border-radius: 12px;
            color: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            transition: all 0.3s;
            cursor: pointer;
        }

        .calendar-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .calendar-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .calendar-card p {
            font-size: 14px;
            opacity: 0.9;
        }

        .btn-download {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-download:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        footer {
            background: #2d3748;
            color: white;
            text-align: center;
            padding: 25px;
            margin-top: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        footer p {
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                gap: 20px;
            }

            .content-body {
                padding: 20px;
            }

            .calendar-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                <div class="logo-icon">📚</div>
                <div class="logo-text">Portal Akademik</div>
            </div>
            <div class="header-icons">
                <button class="icon-btn" title="Notifikasi">🔔</button>
                <button class="icon-btn" title="Profil">👤</button>
            </div>
        </header>

        <div class="main-content">
            <div class="content-header">
                <h1>Jadwal Akademik</h1>
                <p>Informasi lengkap tentang kalender dan jadwal akademik</p>
            </div>

            <div class="content-body">
                <div class="info-section">
                    <h2>📋 Jadwal Table</h2>
                    <ul class="info-list">
                        <li>
                            <div class="info-number">1</div>
                            <div class="info-text">
                                <strong>Kalender akademik</strong> mencakup jadwal untuk awal kuliah, Ujian Tengah Semester (UTS), Ujian Akhir Semester (UAS), dan periode wisuda.
                            </div>
                        </li>
                        <li>
                            <div class="info-number">2</div>
                            <div class="info-text">
                                <strong>Jadwal pengisian KRS/KHS</strong> - Periode pengisian Kartu Rencana Studi dan Kartu Hasil Studi sesuai dengan kalender akademik yang berlaku.
                            </div>
                        </li>
                        <li>
                            <div class="info-number">3</div>
                            <div class="info-text">
                                <strong>File download kalender akademik (PDF)</strong> - Unduh dokumen kalender akademik lengkap dalam format PDF untuk referensi offline.
                            </div>
                        </li>
                        <li>
                            <div class="info-number">4</div>
                            <div class="info-text">
                                <strong>Info perubahan kalender</strong> - Informasi terkini mengenai revisi atau perubahan jadwal akademik akan diumumkan di halaman ini.
                            </div>
                        </li>
                    </ul>

                    <button class="btn-download" onclick="alert('Mengunduh kalender akademik...')">
                        📥 Download Kalender Akademik (PDF)
                    </button>
                </div>

                <div class="info-section">
                    <h2>📅 Kalender Cepat</h2>
                    <div class="calendar-grid">
                        <div class="calendar-card">
                            <h3>📚 Semester Ganjil</h3>
                            <p>Periode: September - Januari</p>
                            <p>Perkuliahan, UTS, UAS, dan pengisian KRS/KHS</p>
                        </div>
                        <div class="calendar-card">
                            <h3>📖 Semester Genap</h3>
                            <p>Periode: Februari - Juni</p>
                            <p>Perkuliahan, UTS, UAS, dan pengisian KRS/KHS</p>
                        </div>
                        <div class="calendar-card">
                            <h3>🎓 Wisuda</h3>
                            <p>Periode: Juli & Desember</p>
                            <p>Jadwal wisuda untuk lulusan semester ganjil dan genap</p>
                        </div>
                    </div>
                </div>

                <div class="info-section">
                    <h2>⚠️ Pengumuman Penting</h2>
                    <ul class="info-list">
                        <li>
                            <div class="info-number">ℹ️</div>
                            <div class="info-text">
                                Harap selalu periksa halaman ini secara berkala untuk mendapatkan informasi terbaru mengenai perubahan jadwal akademik.
                            </div>
                        </li>
                        <li>
                            <div class="info-number">✅</div>
                            <div class="info-text">
                                Pastikan untuk mengisi KRS tepat waktu sesuai jadwal yang telah ditentukan untuk menghindari keterlambatan registrasi.
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <footer>
            <p><strong>Footer</strong></p>
            <p>© 2025 Portal Akademik. All rights reserved.</p>
            <p>Hubungi kami: info@akademik.ac.id</p>
        </footer>
    </div>
</body>
</html>