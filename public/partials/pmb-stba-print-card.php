<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php _e('Kartu Pendaftaran', 'pmb-stba'); ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #1e73be;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        .logo {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            max-width: 80px;
            max-height: 80px;
        }
        .institution-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-title {
            font-size: 18px;
            font-weight: normal;
        }
        .content {
            padding: 20px;
        }
        .registration-number {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px dashed #ccc;
        }
        .registration-number h2 {
            margin: 0;
            font-size: 24px;
        }
        .reg-data {
            margin-bottom: 20px;
        }
        .reg-data table {
            width: 100%;
            border-collapse: collapse;
        }
        .reg-data table td {
            padding: 8px 5px;
            vertical-align: top;
        }
        .reg-data table td:first-child {
            width: 150px;
            font-weight: bold;
        }
        .photo {
            float: right;
            width: 120px;
            height: 160px;
            border: 1px solid #ddd;
            margin-left: 20px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .photo img {
            width: 100%;
            height: auto;
        }
        .footer {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-bottom: 1px solid #000;
        }
        .print-btn {
            text-align: center;
            margin: 20px;
        }
        .print-btn button {
            background-color: #1e73be;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }
        @media print {
            body {
                background: none;
            }
            .container {
                box-shadow: none;
                margin: 0;
                width: 100%;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <?php 
            $logo_url = carbon_get_theme_option('pmb_logo');
            if (!empty($logo_url)) {
                echo '<img src="' . esc_url($logo_url) . '" class="logo">';
            }
            ?>
            <div class="institution-name">SEKOLAH TINGGI BAHASA ASING</div>
            <div class="card-title"><?php _e('KARTU TANDA PENDAFTARAN MAHASISWA BARU', 'pmb-stba'); ?></div>
        </div>
        
        <div class="content">
            <div class="registration-number">
                <h2><?php echo esc_html($registration_data['nomor_pendaftaran']); ?></h2>
            </div>
            
            <div class="reg-data">
                <?php 
                // Display photo if exists
                $photo_url = get_user_meta($user_id, 'pmb_photo', true);
                if (!empty($photo_url)) {
                    echo '<div class="photo"><img src="' . esc_url($photo_url) . '" alt="Foto"></div>';
                }
                ?>
                
                <table>
                    <tr>
                        <td><?php _e('Nama Lengkap', 'pmb-stba'); ?></td>
                        <td>: <?php echo esc_html($registration_data['nama_lengkap']); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Tempat, Tgl Lahir', 'pmb-stba'); ?></td>
                        <td>: <?php echo esc_html($registration_data['tempat_lahir'] . ', ' . date('d-m-Y', strtotime($registration_data['tanggal_lahir']))); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Jenis Kelamin', 'pmb-stba'); ?></td>
                        <td>: <?php echo $registration_data['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Program Studi', 'pmb-stba'); ?></td>
                        <td>: <?php 
                            $jurusan = $registration_data['jurusan_dipilih'];
                            if ($jurusan === 's1-sastra-inggris') {
                                echo 'S1 Sastra Inggris';
                            } elseif ($jurusan === 'd3-bahasa-inggris') {
                                echo 'D3 Bahasa Inggris';
                            } else {
                                echo esc_html($jurusan);
                            }
                        ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Asal Sekolah', 'pmb-stba'); ?></td>
                        <td>: <?php echo esc_html($registration_data['asal_sekolah']); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Waktu Kuliah', 'pmb-stba'); ?></td>
                        <td>: <?php echo ucfirst(esc_html($registration_data['waktu_kuliah'])); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('No Telepon', 'pmb-stba'); ?></td>
                        <td>: <?php echo esc_html($registration_data['no_hp']); ?></td>
                    </tr>
                    <tr>
                        <td><?php _e('Terdaftar pada', 'pmb-stba'); ?></td>
                        <td>: <?php echo date('d-m-Y', strtotime($registration_data['tanggal_pengisian'])); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="footer">
            <div class="signature">
                <div>Calon Mahasiswa</div>
                <div class="signature-line"></div>
                <div><?php echo esc_html($registration_data['nama_lengkap']); ?></div>
            </div>
            <div class="signature">
                <div>Ketua STBA</div>
                <div class="signature-line"></div>
                <div>____________________</div>
            </div>
        </div>
    </div>
    
    <div class="print-btn">
        <button onclick="window.print()"><?php _e('Cetak Kartu', 'pmb-stba'); ?></button>
    </div>
    
    <script>
        // Auto print on load
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>