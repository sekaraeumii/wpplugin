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

<div class="pmb-stba-profile-container">
    <div class="row">
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
                
                // Upload
                $profile_page = carbon_get_theme_option('pmb_profile_page');
                if (!empty($profile_page)) {
                    echo '<li><a href="' . esc_url(get_permalink($profile_page)) . '">' . 
                        '<span class="dashicons dashicons-upload"></span> ' .
                        esc_html__('Upload Dokumen', 'pmb-stba') . '</a></li>';
                }
                
                // Payment
                echo '<li><a href="#">' . 
                    '<span class="dashicons dashicons-money-alt"></span> ' .
                    esc_html__('Pembayaran Formulir', 'pmb-stba') . '</a></li>';
                
                // Logout - Add this new item
                echo '<li><a href="' . wp_logout_url(home_url()) . '" class="pmb-logout-link">' . 
                    '<span class="dashicons dashicons-exit"></span> ' .
                    esc_html__('Keluar', 'pmb-stba') . '</a></li>';

                echo '</ul>';
                echo '</div>';
            }
            ?>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><?php _e('Profil PMB', 'pmb-stba'); ?></h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['registration']) && $_GET['registration'] === 'success') : ?>
                        <div class="alert alert-success">
                            <?php _e('Pendaftaran berhasil dikirim. Silahkan menunggu verifikasi dari admin.', 'pmb-stba'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($has_submitted) : ?>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <h5 class="border-bottom pb-2 mb-3"><?php _e('Data Pilihan', 'pmb-stba'); ?></h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong><?php _e('Nomor Pendaftaran:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'nomor_pendaftaran', true)); ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong><?php _e('Jurusan / Program Studi:', 'pmb-stba'); ?></strong> 
                                            <?php 
                                            $jurusan = get_user_meta($user_id, 'jurusan_dipilih', true);
                                            echo $jurusan === 's1-sastra-inggris' ? 'S1 Sastra Inggris' : 'D3 Bahasa Inggris'; 
                                            ?>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong><?php _e('Waktu Kuliah:', 'pmb-stba'); ?></strong> 
                                            <?php 
                                            $waktu = get_user_meta($user_id, 'waktu_kuliah', true);
                                            echo ucfirst($waktu); 
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2 mb-3"><?php _e('Data Diri', 'pmb-stba'); ?></h5>
                                <p><strong><?php _e('Nama Lengkap:', 'pmb-stba'); ?></strong> <?php echo esc_html($nama_lengkap); ?></p>
                                <p><strong><?php _e('Tempat, Tanggal Lahir:', 'pmb-stba'); ?></strong> <?php echo esc_html($tempat_lahir); ?>, <?php echo esc_html($tanggal_lahir); ?></p>
                                <p><strong><?php _e('Jenis Kelamin:', 'pmb-stba'); ?></strong> <?php echo $jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'; ?></p>
                                <p><strong><?php _e('Agama:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'agama', true)); ?></p>
                                <p><strong><?php _e('No. HP:', 'pmb-stba'); ?></strong> <?php echo esc_html($no_hp); ?></p>
                                <p><strong><?php _e('Email:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'email', true)); ?></p>
                                <p><strong><?php _e('Alamat:', 'pmb-stba'); ?></strong> <?php echo esc_html($alamat); ?></p>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="border-bottom pb-2 mb-3"><?php _e('Sekolah Asal', 'pmb-stba'); ?></h5>
                                <p><strong><?php _e('Jenis Sekolah:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'jenis_sekolah', true)); ?></p>
                                <p><strong><?php _e('Nama Sekolah:', 'pmb-stba'); ?></strong> <?php echo esc_html($asal_sekolah); ?></p>
                                <p><strong><?php _e('Tahun Lulus:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'tahun_lulus', true)); ?></p>
                                <p><strong><?php _e('Status Mahasiswa:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'status_mahasiswa', true)); ?></p>
                                
                                <h5 class="border-bottom pb-2 mb-3 mt-4"><?php _e('Status Pendaftaran', 'pmb-stba'); ?></h5>
                                <p><strong><?php _e('Status Pekerjaan:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'status_pekerjaan', true)); ?></p>
                                <p><strong><?php _e('Status PMB:', 'pmb-stba'); ?></strong> 
                                    <?php if ($status_pmb == 'pending') : ?>
                                        <span class="badge bg-warning">Menunggu Verifikasi</span>
                                    <?php elseif ($status_pmb == 'approved') : ?>
                                        <span class="badge bg-success">Diterima</span>
                                    <?php elseif ($status_pmb == 'rejected') : ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary">Belum Diketahui</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3 mt-4"><?php _e('Rekomendasi', 'pmb-stba'); ?></h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p><strong><?php _e('Sumber:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'sumber', true)); ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong><?php _e('Keterangan 1:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'keterangan1', true)); ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <p><strong><?php _e('Keterangan 2:', 'pmb-stba'); ?></strong> <?php echo esc_html(get_user_meta($user_id, 'keterangan2', true)); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <h5 class="border-bottom pb-2 mb-3 mt-4"><?php _e('Dokumen', 'pmb-stba'); ?></h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong><?php _e('Pas Foto:', 'pmb-stba'); ?></strong></p>
                                        <?php 
                                        $foto_path = get_user_meta($user_id, 'foto_path', true);
                                        if (!empty($foto_path)) {
                                            echo '<img src="' . esc_url($foto_path) . '" class="img-thumbnail" style="max-width: 150px;">';
                                        } else {
                                            echo '<span class="badge bg-secondary">Tidak Ada</span>';
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong><?php _e('Ijazah:', 'pmb-stba'); ?></strong></p>
                                        <?php 
                                        $ijazah_path = get_user_meta($user_id, 'ijazah_path', true);
                                        if (!empty($ijazah_path)) {
                                            $file_ext = pathinfo($ijazah_path, PATHINFO_EXTENSION);
                                            if (strtolower($file_ext) === 'pdf') {
                                                echo '<a href="' . esc_url($ijazah_path) . '" class="btn btn-sm btn-secondary" target="_blank">' . 
                                                    '<span class="dashicons dashicons-pdf"></span> Lihat PDF</a>';
                                            } else {
                                                echo '<img src="' . esc_url($ijazah_path) . '" class="img-thumbnail" style="max-width: 150px;">';
                                            }
                                        } else {
                                            echo '<span class="badge bg-secondary">Tidak Ada</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-info">
                            <p><?php _e('Anda belum melengkapi formulir pendaftaran.', 'pmb-stba'); ?></p>
                            <a href="<?php echo get_permalink(carbon_get_theme_option('pmb_registration_page')); ?>" class="btn btn-primary mt-2"><?php _e('Lengkapi Pendaftaran', 'pmb-stba'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>