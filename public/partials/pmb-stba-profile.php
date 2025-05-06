<?php
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
$jenis_kelamin = get_user_meta($user_id, 'jenis_kelamin', true);
$no_hp = get_user_meta($user_id, 'no_hp', true);
$alamat = get_user_meta($user_id, 'alamat', true);
$asal_sekolah = get_user_meta($user_id, 'asal_sekolah', true);
$jurusan_dipilih = get_user_meta($user_id, 'jurusan_dipilih', true);
$status_pmb = get_user_meta($user_id, 'status_pmb', true);

$has_submitted = !empty($tempat_lahir) && !empty($tanggal_lahir);
?>

<div class="pmb-stba-profile-container bg-light py-4">
    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation - Left Column -->
            <div class="col-md-3">
                <?php 
                // Display sidebar if it exists
                $sidebar_id = carbon_get_theme_option('pmb_navigation_sidebar');
                
                // Check if sidebar exists and is active
                if ($sidebar_id && is_active_sidebar($sidebar_id)) {
                    dynamic_sidebar($sidebar_id);
                } else {
                    // Fallback navigation menu
                    echo '<div class="pmb-nav-widget">';
                    echo '<h4 class="pmb-nav-title">' . esc_html__('Menu PMB', 'pmb-stba') . '</h4>';
                    echo '<ul class="pmb-navigation-menu">';
                    
                    // Dashboard
                    $dashboard_page = carbon_get_theme_option('pmb_home_page');
                    if (!empty($dashboard_page)) {
                        echo '<li><a href="' . esc_url(get_permalink($dashboard_page)) . '">' . 
                            '<span class="dashicons dashicons-dashboard"></span> ' .
                            esc_html__('Dashboard', 'pmb-stba') . '</a></li>';
                    }
                    
                    // Information
                    echo '<li><a href="#">' . 
                        '<span class="dashicons dashicons-info"></span> ' .
                        esc_html__('Informasi', 'pmb-stba') . '</a></li>';
                    
                    // Registration form
                    $registration_form = carbon_get_theme_option('pmb_registration_page');
                    if (!empty($registration_form)) {
                        echo '<li><a href="' . esc_url(get_permalink($registration_form)) . '">' . 
                            '<span class="dashicons dashicons-clipboard"></span> ' .
                            esc_html__('Formulir Pendaftaran', 'pmb-stba') . '</a></li>';
                    }
                    
                    // Upload/Profile
                    $profile_page = carbon_get_theme_option('pmb_profile_page');
                    if (!empty($profile_page)) {
                        echo '<li class="active"><a href="' . esc_url(get_permalink($profile_page)) . '">' . 
                            '<span class="dashicons dashicons-id"></span> ' .
                            esc_html__('Profil Calon Mahasiswa STBA', 'pmb-stba') . '</a></li>';
                    }
                    
                    // Payment
                    echo '<li><a href="#">' . 
                        '<span class="dashicons dashicons-money-alt"></span> ' .
                        esc_html__('Pembayaran Formulir', 'pmb-stba') . '</a></li>';
                    
                    // Logout
                    echo '<li><a href="' . wp_logout_url(home_url()) . '" class="pmb-logout-link">' . 
                        '<span class="dashicons dashicons-exit"></span> ' .
                        esc_html__('Keluar', 'pmb-stba') . '</a></li>';

                    echo '</ul>';
                    echo '</div>';
                }
                ?>
                
                <?php if ($has_submitted && $status_pmb == 'approved') : ?>
                
                <?php endif; ?>
            </div>
            
            <!-- Main Content - Right Column -->
            <div class="col-md-9">
                <?php if (isset($_GET['registration']) && $_GET['registration'] === 'success') : ?>
                <div class="alert alert-success shadow-sm mb-4">
                    <div class="d-flex">
                        <div class="mr-3">
                            <i class="dashicons dashicons-yes-alt" style="font-size: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-1"><?php _e('Pendaftaran Berhasil!', 'pmb-stba'); ?></h5>
                            <p class="mb-0"><?php _e('Pendaftaran berhasil dikirim. Silahkan menunggu verifikasi dari admin.', 'pmb-stba'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($has_submitted && $status_pmb == 'approved') : ?>
                <div class="alert alert-success shadow-sm mb-4">
                    <div class="d-flex">
                        <div class="mr-3">
                            <i class="dashicons dashicons-yes-alt" style="font-size: 24px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-1"><?php _e('Selamat!', 'pmb-stba'); ?></h5>
                            <p class="mb-0"><?php _e('Pendaftaran Anda telah disetujui. Silakan lanjut ke pembayaran.', 'pmb-stba'); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="dashicons dashicons-id mr-2" style="font-size: 24px;"></i>
                            <h4 class="mb-0"><?php _e('Profil PMB', 'pmb-stba'); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($has_submitted) : ?>
                            <div class="card border-primary shadow-sm mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="dashicons dashicons-admin-users mr-2"></i><?php _e('Data Pilihan', 'pmb-stba'); ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="bg-light p-2 rounded-circle mr-3">
                                                    <i class="dashicons dashicons-id-alt text-primary"></i>
                                                </span>
                                                <div>
                                                    <small class="text-muted"><?php _e('Nomor Pendaftaran', 'pmb-stba'); ?></small>
                                                    <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'nomor_pendaftaran', true)); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="bg-light p-2 rounded-circle mr-3">
                                                    <i class="dashicons dashicons-welcome-learn-more text-primary"></i>
                                                </span>
                                                <div>
                                                    <small class="text-muted"><?php _e('Program Studi', 'pmb-stba'); ?></small>
                                                    <p class="mb-0 font-weight-bold">
                                                        <?php 
                                                        $jurusan = get_user_meta($user_id, 'jurusan_dipilih', true);
                                                        echo $jurusan === 's1-sastra-inggris' ? 'S1 Sastra Inggris' : 'D3 Bahasa Inggris'; 
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <span class="bg-light p-2 rounded-circle mr-3">
                                                    <i class="dashicons dashicons-clock text-primary"></i>
                                                </span>
                                                <div>
                                                    <small class="text-muted"><?php _e('Waktu Kuliah', 'pmb-stba'); ?></small>
                                                    <p class="mb-0 font-weight-bold">
                                                        <?php 
                                                        $waktu = get_user_meta($user_id, 'waktu_kuliah', true);
                                                        echo ucfirst($waktu); 
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="border-bottom pb-2 mb-3 d-flex align-items-center">
                                        <i class="dashicons dashicons-admin-users text-primary mr-2"></i>
                                        <?php _e('Data Diri', 'pmb-stba'); ?>
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Nama Lengkap', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html($nama_lengkap); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Tempat, Tanggal Lahir', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html($tempat_lahir); ?>, <?php echo esc_html($tanggal_lahir); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Jenis Kelamin', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo $jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Agama', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'agama', true)); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('No. HP', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html($no_hp); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Email', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'email', true)); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Alamat', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html($alamat); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="border-bottom pb-2 mb-3 d-flex align-items-center">
                                        <i class="dashicons dashicons-admin-site text-primary mr-2"></i>
                                        <?php _e('Sekolah Asal', 'pmb-stba'); ?>
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Jenis Sekolah', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'jenis_sekolah', true)); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Nama Sekolah', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html($asal_sekolah); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Tahun Lulus', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'tahun_lulus', true)); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Status Mahasiswa', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'status_mahasiswa', true)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="border-bottom pb-2 mb-3 d-flex align-items-center">
                                        <i class="dashicons dashicons-chart-bar text-primary mr-2"></i>
                                        <?php _e('Status Pendaftaran', 'pmb-stba'); ?>
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Status Pekerjaan', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'status_pekerjaan', true)); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Status PMB', 'pmb-stba'); ?></small>
                                                <?php if ($status_pmb == 'pending') : ?>
                                                    <span class="badge badge-warning text-white px-3 py-2">Menunggu Verifikasi</span>
                                                <?php elseif ($status_pmb == 'approved') : ?>
                                                    <span class="badge badge-success px-3 py-2">Diterima</span>
                                                <?php elseif ($status_pmb == 'rejected') : ?>
                                                    <span class="badge badge-danger px-3 py-2">Ditolak</span>
                                                <?php else : ?>
                                                    <span class="badge badge-secondary px-3 py-2">Belum Diketahui</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="border-bottom pb-2 mb-3 d-flex align-items-center">
                                        <i class="dashicons dashicons-groups text-primary mr-2"></i>
                                        <?php _e('Rekomendasi', 'pmb-stba'); ?>
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Sumber', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'sumber', true)); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Keterangan 1', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'keterangan1', true)); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-item mb-3">
                                                <small class="text-muted d-block"><?php _e('Keterangan 2', 'pmb-stba'); ?></small>
                                                <p class="mb-0 font-weight-bold"><?php echo esc_html(get_user_meta($user_id, 'keterangan2', true)); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="border-bottom pb-2 mb-3 d-flex align-items-center">
                                        <i class="dashicons dashicons-media-document text-primary mr-2"></i>
                                        <?php _e('Dokumen', 'pmb-stba'); ?>
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <strong><?php _e('Pas Foto', 'pmb-stba'); ?></strong>
                                                </div>
                                                <div class="card-body text-center p-3">
                                                    <?php 
                                                    $foto_path = get_user_meta($user_id, 'foto_path', true);
                                                    if (!empty($foto_path)) {
                                                        echo '<img src="' . esc_url($foto_path) . '" class="img-thumbnail" style="max-width: 150px;">';
                                                        echo '<p class="mt-2 mb-0"><a href="' . esc_url($foto_path) . '" class="btn btn-sm btn-outline-primary" target="_blank">Lihat Full</a></p>';
                                                    } else {
                                                        echo '<div class="text-center py-4">';
                                                        echo '<i class="dashicons dashicons-format-image" style="font-size: 48px; color: #ccc;"></i>';
                                                        echo '<p class="text-muted mt-2">Tidak Ada</p>';
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card mb-3">
                                                <div class="card-header bg-light">
                                                    <strong><?php _e('Ijazah', 'pmb-stba'); ?></strong>
                                                </div>
                                                <div class="card-body text-center p-3">
                                                    <?php 
                                                    $ijazah_path = get_user_meta($user_id, 'ijazah_path', true);
                                                    if (!empty($ijazah_path)) {
                                                        $file_ext = pathinfo($ijazah_path, PATHINFO_EXTENSION);
                                                        if (strtolower($file_ext) === 'pdf') {
                                                            echo '<div class="py-4">';
                                                            echo '<i class="dashicons dashicons-pdf" style="font-size: 48px; color: #dc3545;"></i>';
                                                            echo '<p class="mt-2"><a href="' . esc_url($ijazah_path) . '" class="btn btn-sm btn-danger" target="_blank">' . 
                                                                '<i class="dashicons dashicons-pdf"></i> Lihat PDF</a></p>';
                                                            echo '</div>';
                                                        } else {
                                                            echo '<img src="' . esc_url($ijazah_path) . '" class="img-thumbnail" style="max-width: 150px;">';
                                                            echo '<p class="mt-2 mb-0"><a href="' . esc_url($ijazah_path) . '" class="btn btn-sm btn-outline-primary" target="_blank">Lihat Full</a></p>';
                                                        }
                                                    } else {
                                                        echo '<div class="text-center py-4">';
                                                        echo '<i class="dashicons dashicons-media-document" style="font-size: 48px; color: #ccc;"></i>';
                                                        echo '<p class="text-muted mt-2">Tidak Ada</p>';
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-info shadow-sm mb-0">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <i class="dashicons dashicons-info" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-2"><?php _e('Formulir Belum Lengkap', 'pmb-stba'); ?></h5>
                                        <p class="mb-3"><?php _e('Anda belum melengkapi formulir pendaftaran. Silakan lengkapi data untuk melanjutkan proses pendaftaran.', 'pmb-stba'); ?></p>
                                        <a href="<?php echo get_permalink(carbon_get_theme_option('pmb_registration_page')); ?>" class="btn btn-primary"><?php _e('Lengkapi Pendaftaran', 'pmb-stba'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for profile page */
.pmb-stba-profile-container .card {
    border-radius: 0.5rem;
    transition: transform 0.2s, box-shadow 0.2s;
    margin-bottom: 1rem;
}

.pmb-stba-profile-container .card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.pmb-stba-profile-container .card-header {
    border-radius: 0.5rem 0.5rem 0 0;
    padding: 0.75rem 1rem;
}

/* Navigation menu improvements */
.pmb-navigation-menu {
    position: sticky;
    top: 1rem;
}

.pmb-navigation-menu .list-group-item {
    border-left: none;
    border-right: none;
    border-radius: 0;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
}

.pmb-navigation-menu .list-group-item:first-child {
    border-top: none;
}

.pmb-navigation-menu .list-group-item:last-child {
    border-bottom: none;
}

.pmb-navigation-menu .list-group-item:hover {
    background-color: #f8f9fa;
}

.pmb-navigation-menu .list-group-item.active {
    background-color: #e9ecef;
    font-weight: bold;
}

.info-item small {
    font-size: 0.75rem;
}

.dashicons {
    line-height: 1.5;
    margin-right: 0.5rem;
}

.mr-2 {
    margin-right: 0.5rem;
}

.mr-3 {
    margin-right: 1rem;
}

/* Container adjustments for better spacing */
.container {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
}

/* Responsive adjustments - special attention to 1366x768 */
@media (max-width: 1366px) {
    .pmb-stba-profile-container .container {
        max-width: 100%;
        padding: 0 0.5rem;
    }
    
    .pmb-navigation-menu .list-group-item {
        padding: 0.6rem 0.75rem;
        font-size: 0.9rem;
    }
    
    .card-header h4, .card-header h5 {
        font-size: 1.1rem;
    }
    
    .pmb-stba-profile-container .row {
        margin-right: -0.5rem;
        margin-left: -0.5rem;
    }
    
    .pmb-stba-profile-container [class*="col-"] {
        padding-right: 0.5rem;
        padding-left: 0.5rem;
    }
    
    .pmb-stba-profile-container .card {
        margin-bottom: 0.75rem;
    }
    
    .pmb-stba-profile-container .card-body {
        padding: 0.75rem;
    }
}

@media (max-width: 991.98px) {
    .pmb-navigation-menu {
        margin-bottom: 1rem;
        position: relative;
    }
    
    .col-lg-3, .col-md-3 {
        margin-bottom: 1rem;
    }
}

@media (max-width: 767.98px) {
    .col-md-3, .col-md-9 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .info-item .d-flex {
        flex-direction: column;
    }
    
    .info-item .bg-light.p-2 {
        margin-bottom: 0.5rem;
    }
    
    .pmb-stba-profile-container {
        padding: 0.5rem !important;
    }
}

@media (max-width: 575.98px) {
    .pmb-stba-profile-container .card-body {
        padding: 0.75rem;
    }
    
    .d-flex.align-items-center {
        flex-wrap: wrap;
    }
    
    .bg-light.p-2.rounded-circle.mr-3 {
        margin-bottom: 0.5rem;
    }
    
    .card-header h4, .card-header h5 {
        font-size: 1rem;
    }
    
    .dashicons {
        font-size: 1.1rem !important;
    }
    
    /* Fix for the Data Pilihan section on mobile */
    .col-md-12.mb-4 .card-body .row .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }
    
    /* Fix for the document preview */
    .col-md-6 .card-body img.img-thumbnail {
        max-width: 100px;
    }
    
    .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
}

/* Fix for dashboard icons alignment */
.dashicons {
    vertical-align: middle;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Specific adjustments for notification area */
.alert {
    padding: 0.75rem 1rem;
    margin-bottom: 1rem;
}

/* Make form notification more readable */
.pmb-stba-profile-container .alert-info {
    background-color: #e3f2fd;
    border-color: #b3e0ff;
}

/* Improve button appearance */
.btn-primary {
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
}

/* Fix for the blue header areas */
.card-header.bg-primary {
    color: white;
    font-weight: 500;
}

/* Fix excessive padding on small screens */
@media (max-width: 480px) {
    .pmb-stba-profile-container {
        padding: 0.25rem !important;
    }
    
    .container {
        padding-right: 8px;
        padding-left: 8px;
    }
    
    .card-body {
        padding: 0.625rem;
    }
    
    .mb-4 {
        margin-bottom: 0.75rem !important;
    }
    
    .py-3 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }
    
    h5.mb-0 {
        font-size: 0.95rem;
    }
    
    .badge {
        font-size: 0.7rem;
    }
}
</style>