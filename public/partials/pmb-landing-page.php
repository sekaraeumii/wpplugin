<?php
/* Template Name: Landing PMB STBA Pontianak */
get_header();
?>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Raleway:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
        padding: 0;
        color: #333;
        background-color: #f9f9f9;
    }

    .page-wrapper {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    section {
        width: 100%;
        box-sizing: border-box;
    }

    .pmb-hero {
        text-align: center;
        padding: 120px 20px;
        background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
        background-size: 300% 300%;
        animation: gradient-animation 15s ease infinite;
        color: white;
        position: relative;
        overflow: hidden;
    }


    .pmb-hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/pattern.png');
        opacity: 0.1;
        z-index: 0;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
    }

    .pmb-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        color: #ffeb3b; /* Bright yellow color */
        text-shadow: 2px 2px 6px rgba(0,0,0,0.5), 0 0 20px rgba(0,0,0,0.3);
        font-family: 'Raleway', sans-serif;
        background: -webkit-linear-gradient(#ffffff, #fdbb2d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.5));
    }

    .pmb-hero p {
        font-size: 1.4rem;
        margin-bottom: 40px;
        font-weight: 400;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .logo-container {
        margin-bottom: 30px;
        transform: scale(1);
        transition: transform 0.3s ease;
    }

    .logo-container:hover {
        transform: scale(1.05);
    }

    .pmb-btn {
        padding: 16px 32px;
        margin: 10px;
        border-radius: 50px;
        text-decoration: none;
        display: inline-block;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
    }

    .login-btn {
        background-color: rgba(255, 255, 255, 0.9);
        color: #1a2a6c;
        border: 2px solid transparent;
    }

    .login-btn:hover {
        background-color: transparent;
        color: #ffffff;
        border: 2px solid #ffffff;
        transform: translateY(-3px);
    }

    .register-btn {
        background-color: #fdbb2d;
        color: #1a2a6c;
        border: 2px solid transparent;
    }

    .register-btn:hover {
        background-color: #ffffff;
        color: #1a2a6c;
        transform: translateY(-3px);
    }

    .announcement-bar {
        background-color: #1a2a6c;
        color: white;
        padding: 15px;
        text-align: center;
        font-weight: 500;
    }

    .announcement-bar span {
        background-color: #fdbb2d;
        color: #1a2a6c;
        padding: 5px 10px;
        border-radius: 20px;
        margin-left: 10px;
        font-weight: 600;
    }

    .steps {
        padding: 100px 20px;
        text-align: center;
        background: #ffffff;
        position: relative;
    }

    .steps::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/dots-pattern.png');
        opacity: 0.05;
        z-index: 0;
    }

    .steps h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        color: #1a2a6c;
        position: relative;
        display: inline-block;
        font-family: 'Raleway', sans-serif;
    }

    .steps h2::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: #fdbb2d;
    }

    .steps-subtitle {
        font-size: 1.2rem;
        color: #666;
        margin-bottom: 60px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .steps-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .step-item {
        flex: 0 0 calc(20% - 30px);
        margin: 15px;
        padding: 30px 20px;
        border-radius: 10px;
        background-color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        position: relative;
    }

    .step-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .step-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        line-height: 60px;
        background: linear-gradient(135deg, #1a2a6c, #b21f1f);
        color: white;
        border-radius: 50%;
        margin: 0 auto 20px;
        font-weight: bold;
        font-size: 24px;
        position: relative;
        z-index: 1;
    }

    .step-number::before {
        content: "";
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        background: linear-gradient(135deg, #1a2a6c, #fdbb2d);
        border-radius: 50%;
        z-index: -1;
        opacity: 0.3;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.1); opacity: 0.2; }
        100% { transform: scale(1); opacity: 0.3; }
    }

    .step-title {
        font-weight: 600;
        margin-bottom: 15px;
        color: #1a2a6c;
        font-size: 18px;
    }

    .step-desc {
        color: #666;
        font-size: 14px;
        line-height: 1.6;
    }

    .cta-row {
        display: flex;
        background: linear-gradient(to right, #f9f9f9, #ffffff);
    }

    .cta-column {
        flex: 1;
        padding: 80px 40px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .cta-column:hover {
        transform: translateY(-5px);
    }

    .cta-column h3 {
        font-size: 2rem;
        margin-bottom: 20px;
        color: #1a2a6c;
        font-weight: 700;
        font-family: 'Raleway', sans-serif;
    }

    .cta-column p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        color: #555;
        line-height: 1.6;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-column img {
        border: 5px solid white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .cta-column img:hover {
        transform: scale(1.05);
    }

    .program-section {
        padding: 100px 20px;
        background-color: #f9f9f9;
        text-align: center;
    }

    .program-section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        color: #1a2a6c;
        position: relative;
        display: inline-block;
        font-family: 'Raleway', sans-serif;
    }

    .program-section h2::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: #fdbb2d;
    }

    .program-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        max-width: 1200px;
        margin: 40px auto 0;
    }

    .program-card {
        flex: 0 0 calc(33.333% - 40px);
        margin: 20px;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }

    .program-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .program-image {
        height: 200px;
        background-size: cover;
        background-position: center;
    }

    .program-content {
        padding: 30px;
        text-align: left;
    }

    .program-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #1a2a6c;
    }

    .program-desc {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .timeline-section {
        padding: 100px 20px;
        background-color: #ffffff;
        text-align: center;
    }

    .timeline-section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        color: #1a2a6c;
        position: relative;
        display: inline-block;
        font-family: 'Raleway', sans-serif;
    }

    .timeline-section h2::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: #fdbb2d;
    }

    .timeline-container {
        max-width: 800px;
        margin: 60px auto 0;
        position: relative;
    }

    .timeline-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 100%;
        background-color: #1a2a6c;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 60px;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
    }

    .timeline-content {
        position: relative;
        width: calc(50% - 40px);
        padding: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .timeline-item:nth-child(odd) .timeline-content {
        margin-left: auto;
    }

    .timeline-date {
        position: absolute;
        top: 0;
        width: 120px;
        padding: 10px;
        background-color: #fdbb2d;
        color: #1a2a6c;
        border-radius: 5px;
        font-weight: 600;
        text-align: center;
    }

    .timeline-item:nth-child(odd) .timeline-date {
        left: -140px;
    }

    .timeline-item:nth-child(even) .timeline-date {
        right: -140px;
    }

    .timeline-point {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 20px;
        height: 20px;
        background-color: #fdbb2d;
        border: 4px solid #1a2a6c;
        border-radius: 50%;
    }

    .testimonial-section {
        padding: 100px 20px;
        background-color: #f9f9f9;
        text-align: center;
    }

    .testimonial-section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        color: #1a2a6c;
        position: relative;
        display: inline-block;
        font-family: 'Raleway', sans-serif;
    }

    .testimonial-section h2::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: #fdbb2d;
    }

    .testimonial-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        max-width: 1200px;
        margin: 40px auto 0;
    }

    .testimonial-card {
        flex: 0 0 calc(33.333% - 40px);
        margin: 20px;
        padding: 30px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        text-align: left;
        position: relative;
    }

    .testimonial-card::before {
        content: """;
        position: absolute;
        top: 20px;
        left: 20px;
        font-size: 80px;
        color: rgba(26, 42, 108, 0.1);
        font-family: Georgia, serif;
        line-height: 1;
    }

    .testimonial-text {
        font-style: italic;
        margin-bottom: 20px;
        color: #555;
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
    }

    .testimonial-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }

    .testimonial-info h4 {
        margin: 0 0 5px;
        color: #1a2a6c;
        font-weight: 600;
    }

    .testimonial-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    .faq-section {
        padding: 100px 20px;
        background-color: #ffffff;
        text-align: center;
    }

    .faq-section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        color: #1a2a6c;
        position: relative;
        display: inline-block;
        font-family: 'Raleway', sans-serif;
    }

    .faq-section h2::after {
        content: "";
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: #fdbb2d;
    }

    .faq-container {
        max-width: 800px;
        margin: 40px auto 0;
    }

    .faq-item {
        margin-bottom: 20px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .faq-question {
        padding: 20px;
        background-color: #f9f9f9;
        color: #1a2a6c;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-question i {
        transition: transform 0.3s ease;
    }

    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        background-color: white;
    }

    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }

    .faq-item.active .faq-answer {
        padding: 20px;
        max-height: 1000px;
    }

    .floating-whatsapp {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background-color: #25D366;
        color: white;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 30px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        z-index: 999;
        transition: all 0.3s ease;
    }

    .floating-whatsapp:hover {
        transform: scale(1.1);
    }

    /* Media queries untuk responsivitas */
    @media (max-width: 992px) {
        .step-item {
            flex: 0 0 calc(33.333% - 30px);
        }
        
        .program-card {
            flex: 0 0 calc(50% - 40px);
        }
        
        .testimonial-card {
            flex: 0 0 calc(50% - 40px);
        }
    }

    @media (max-width: 768px) {
        .pmb-hero h1 {
            font-size: 2.5rem;
        }
        
        .pmb-hero p {
            font-size: 1.2rem;
        }
        
        .step-item {
            flex: 0 0 calc(50% - 30px);
        }
        
        .cta-row {
            flex-direction: column;
        }
        
        .program-card, .testimonial-card {
            flex: 0 0 calc(100% - 40px);
        }
        
        .timeline-container::before {
            left: 30px;
        }
        
        .timeline-content {
            width: calc(100% - 80px);
            margin-left: 80px !important;
        }
        
        .timeline-date {
            width: auto;
            position: relative;
            left: 0 !important;
            right: 0 !important;
            margin-bottom: 10px;
            display: inline-block;
        }
        
        .timeline-point {
            left: 30px;
        }
    }

    @media (max-width: 576px) {
        .step-item {
            flex: 0 0 calc(100% - 30px);
        }
    }
</style>

<div class="page-wrapper">
    <div class="announcement-bar">
        Pendaftaran Gelombang 1 dibuka! <span>Diskon 25% Biaya Pendaftaran</span>
    </div>

    <section class="pmb-hero">
        <div class="hero-content">
            <div class="logo-container">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/logo-stba.png" alt="Logo STBA" style="max-width: 150px;">
            </div>
            <h1>Penerimaan Mahasiswa Baru STBA Pontianak</h1>
            <p>Wujudkan impian menjadi ahli bahasa profesional dan raih karir internasional bersama kami. Tahun Ajaran 2025/2026 telah dibuka!</p>
            <div>
                <a href="/login" class="pmb-btn login-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="/register" class="pmb-btn register-btn"><i class="fas fa-user-plus"></i> Daftar Sekarang</a>
            </div>
        </div>
    </section>

    <section class="program-section">
        <h2>Program Studi Unggulan</h2>
        <p class="steps-subtitle">Pilih program studi yang sesuai dengan minat dan bakatmu untuk mempersiapkan karir internasional</p>
        
        <div class="program-container">
            <div class="program-card">
                <div class="program-image" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/english.jpg');"></div>
                <div class="program-content">
                    <h3 class="program-title">Sastra Inggris</h3>
                    <p class="program-desc">Program studi yang mempelajari bahasa, sastra, dan budaya Inggris secara mendalam dengan peluang karir internasional.</p>
                    <a href="#" class="pmb-btn register-btn" style="padding: 10px 20px; font-size: 12px;">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            
            <div class="program-card">
                <div class="program-image" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/mandarin.jpg');"></div>
                <div class="program-content">
                    <h3 class="program-title">Bahasa Mandarin</h3>
                    <p class="program-desc">Kuasai bahasa dengan penutur terbanyak di dunia dan buka peluang karir di perusahaan multinasional.</p>
                    <a href="#" class="pmb-btn register-btn" style="padding: 10px 20px; font-size: 12px;">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            
            <div class="program-card">
                <div class="program-image" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/japanese.jpg');"></div>
                <div class="program-content">
                    <h3 class="program-title">Bahasa Jepang</h3>
                    <p class="program-desc">Program studi yang membekali mahasiswa dengan kemampuan bahasa Jepang dan pemahaman budaya Jepang.</p>
                    <a href="#" class="pmb-btn register-btn" style="padding: 10px 20px; font-size: 12px;">Pelajari Lebih Lanjut</a>
                </div>
            </div>
        </div>
    </section>

    <section class="steps">
        <h2>Langkah-langkah Pendaftaran</h2>
        <p class="steps-subtitle">Ikuti langkah-langkah berikut untuk menyelesaikan proses pendaftaran mahasiswa baru dengan mudah</p>
        
        <div class="steps-container">
            <div class="step-item">
                <div class="step-number">1</div>
                <div class="step-title">Registrasi Akun</div>
                <div class="step-desc">Buat akun melalui form registrasi dengan email dan data diri valid</div>
            </div>
            <div class="step-item">
                <div class="step-number">2</div>
                <div class="step-title">Login Sistem</div>
                <div class="step-desc">Masuk ke sistem PMB dengan akun yang telah dibuat</div>
            </div>
            <div class="step-item">
                <div class="step-number">3</div>
                <div class="step-title">Isi Formulir</div>
                <div class="step-desc">Lengkapi data diri & pendidikan dengan benar dan teliti</div>
            </div>
            <div class="step-item">
                <div class="step-number">4</div>
                <div class="step-title">Upload Dokumen</div>
                <div class="step-desc">Upload dokumen yang diminta sesuai ketentuan yang berlaku</div>
            </div>
            <div class="step-item">
                <div class="step-number">5</div>
                <div class="step-title">Cetak Bukti</div>
                <div class="step-desc">Cetak bukti pendaftaran dan ikuti seleksi sesuai jadwal</div>
            </div>
        </div>
    </section>

    <section class="timeline-section">
        <h2>Jadwal Penting PMB 2025</h2>
        <p class="steps-subtitle">Perhatikan tanggal-tanggal penting berikut agar tidak melewatkan kesempatan bergabung dengan STBA Pontianak</p>
        
        <div class="timeline-container">
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>Pendaftaran Gelombang I</h3>
                    <p>Pendaftaran online, pembayaran biaya pendaftaran, dan pengumpulan berkas</p>
                </div>
                <!-- JADWAL: Pendaftaran Gelombang I - Ubah tanggal sesuai kebutuhan -->
                <div class="timeline-date">1 Jan - 31 Mar 2025</div>
                <div class="timeline-point"></div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>Ujian Seleksi Gelombang I</h3>
                    <p>Tes potensi akademik, tes bahasa Inggris, dan wawancara</p>
                </div>
                <!-- JADWAL: Ujian Seleksi Gelombang I - Ubah tanggal sesuai kebutuhan -->
                <div class="timeline-date">15 Apr 2025</div>
                <div class="timeline-point"></div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>Pengumuman Hasil Gelombang I</h3>
                    <p>Pengumuman hasil seleksi melalui website dan email</p>
                </div>
                <!-- JADWAL: Pengumuman Hasil Gelombang I - Ubah tanggal sesuai kebutuhan -->
                <div class="timeline-date">30 Apr 2025</div>
                <div class="timeline-point"></div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>Pendaftaran Gelombang II</h3>
                    <p>Kesempatan kedua bagi calon mahasiswa yang belum mendaftar</p>
                </div>
                <!-- JADWAL: Pendaftaran Gelombang II - Ubah tanggal sesuai kebutuhan -->
                <div class="timeline-date">1 Mei - 30 Jun 2025</div>
                <div class="timeline-point"></div>
            </div>
            
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3>Orientasi Mahasiswa Baru</h3>
                    <p>Pengenalan kampus dan program studi bagi mahasiswa baru</p>
                </div>
                <!-- JADWAL: Orientasi Mahasiswa Baru - Ubah tanggal sesuai kebutuhan -->
                <div class="timeline-date">1 Sep 2025</div>
                <div class="timeline-point"></div>
            </div>
        </div>
    </section>

    <section class="testimonial-section">
        <h2>Apa Kata Mahasiswa Kami</h2>
        <p class="steps-subtitle">Dengarkan pengalaman langsung dari mahasiswa yang telah bergabung dengan STBA Pontianak</p>
        
        <div class="testimonial-container">
            <div class="testimonial-card">
                <p class="testimonial-text">Belajar di STBA Pontianak membuka peluang karir saya. Sekarang saya bekerja sebagai penerjemah di perusahaan multinasional dengan gaji yang memuaskan.</p>
                <div class="testimonial-author">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/student1.jpg" alt="Student" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h4>Anita Wijaya</h4>
                        <p>Alumni Sastra Inggris 2023</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <p class="testimonial-text">Dosen-dosen di STBA Pontianak sangat kompeten dan berpengalaman. Mereka tidak hanya mengajarkan bahasa, tetapi juga budaya dan etika profesional.</p>
                <div class="testimonial-author">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/student2.jpg" alt="Student" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h4>Budi Santoso</h4>
                        <p>Mahasiswa Bahasa Mandarin</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <p class="testimonial-text">Program pertukaran pelajar ke Jepang menjadi pengalaman tak terlupakan. Saya bisa praktik langsung bahasa yang dipelajari dan mengenal budaya secara mendalam.</p>
                <div class="testimonial-author">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/student3.jpg" alt="Student" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h4>Citra Lestari</h4>
                        <p>Mahasiswa Bahasa Jepang</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <h2>Pertanyaan Umum</h2>
        <p class="steps-subtitle">Temukan jawaban untuk pertanyaan yang sering diajukan calon mahasiswa</p>
        
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    Apa saja persyaratan pendaftaran? <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Persyaratan pendaftaran meliputi: Ijazah SMA/sederajat (atau Surat Keterangan Lulus untuk lulusan terbaru), Kartu Identitas (KTP/Kartu Pelajar), Pas foto terbaru, dan bukti pembayaran biaya pendaftaran.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    Berapa biaya kuliah di STBA Pontianak? <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Biaya kuliah bervariasi tergantung program studi yang dipilih. Kami menawarkan berbagai skema pembayaran dan beasiswa untuk mahasiswa berprestasi. Untuk informasi lebih detail, silakan hubungi bagian keuangan kami.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    Apakah ada program beasiswa? <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Ya, STBA Pontianak menyediakan berbagai program beasiswa, baik dari internal kampus maupun kerja sama dengan pihak eksternal. Beasiswa diberikan berdasarkan prestasi akademik, prestasi non-akademik, dan kebutuhan finansial.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    Bagaimana prospek karir lulusan STBA Pontianak? <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    <p>Lulusan STBA Pontianak memiliki prospek karir yang luas, antara lain sebagai penerjemah, interpreter, diplomat, staf kedutaan, guru/dosen bahasa, pemandu wisata, content writer, dan berbagai posisi di perusahaan multinasional.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-row">
        <div class="cta-column">
            <h3>Masih Bingung atau Butuh Bantuan?</h3>
            <p>Tim PMB STBA Pontianak siap membantu Anda dalam proses pendaftaran. Jangan ragu untuk menghubungi kami!</p>
            <a href="https://wa.me/6281234567890" class="pmb-btn register-btn"><i class="fab fa-whatsapp"></i> Hubungi Kami</a>
        </div>
        
        <div class="cta-column" style="background-color: #f9f9f9;">
            <h3>Scan QR untuk Hubungi Panitia PMB</h3>
            <p>Atau klik tombol di bawah untuk langsung terhubung melalui WhatsApp dengan tim kami.</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=https://wa.me/6281234567890" alt="QR WA" style="margin: 20px 0;">
            <br>
            <a href="https://wa.me/6281234567890" class="pmb-btn register-btn"><i class="fab fa-whatsapp"></i> Chat via WhatsApp</a>
        </div>
    </section>

    <a href="https://wa.me/6281234567890" class="floating-whatsapp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script>
        // Script untuk FAQ accordion
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                
                question.addEventListener('click', () => {
                    item.classList.toggle('active');
                });
            });
        });
    </script>
</div>

<?php get_footer(); ?>
