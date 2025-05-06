<?php 
    // Check if user is logged in
    if (!is_user_logged_in()) {
        echo '<div class="alert alert-warning">';
        echo __('Anda harus login terlebih dahulu untuk mengisi formulir pendaftaran.', 'pmb-stba');
        echo ' <a href="' . get_permalink(carbon_get_theme_option('pmb_login_page')) . '">' . __('Login', 'pmb-stba') . '</a>';
        echo '</div>';
        return;
    }
    
    // Get current user data
    $current_user = wp_get_current_user();
?>

<style>
/* Efek floating dan shadow untuk card */
.pmb-stba-registration-container .card {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    margin-bottom: 30px;
}

.pmb-stba-registration-container .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(30, 115, 190, 0.15);
}

/* Styling header */
.pmb-stba-registration-container .card-header {
    background: linear-gradient(135deg, #1e73be 0%, #2c5282 100%) !important;
    border-radius: 8px 8px 0 0 !important;
    padding: 20px;
    border-bottom: none;
}

.pmb-stba-registration-container .card-header h4 {
    font-weight: 600;
    letter-spacing: 0.5px;
    margin: 0;
    color: white;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

/* Styling form body */
.pmb-stba-registration-container .card-body {
    padding: 30px;
    background-color: #ffffff;
    border-radius: 0 0 8px 8px;
}

/* Styling form fields */
.pmb-stba-registration-container .form-control {
    border-radius: 5px;
    padding: 10px 12px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
}

.pmb-stba-registration-container .form-control:focus {
    border-color: #1e73be;
    box-shadow: 0 0 0 0.25rem rgba(30, 115, 190, 0.25);
}

/* Section headers */
.pmb-stba-registration-container h5 {
    color: #2c5282;
    font-weight: 600;
    margin-top: 1.5rem;
}

/* Styling button */
.pmb-stba-registration-container .btn-primary {
    background: linear-gradient(135deg, #1e73be 0%, #2c5282 100%);
    border: none;
    padding: 12px 20px;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(30, 115, 190, 0.3);
}

.pmb-stba-registration-container .btn-primary:hover {
    box-shadow: 0 6px 15px rgba(30, 115, 190, 0.4);
    transform: translateY(-2px);
}

/* Sidebar navigation styling */
.pmb-nav-widget {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    padding: 15px;
    margin-bottom: 20px;
}

.pmb-nav-title {
    font-size: 18px;
    font-weight: 600;
    color: #2c5282;
    padding-bottom: 10px;
    border-bottom: 2px solid #edf2f7;
    margin-bottom: 15px;
}

.pmb-navigation-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.pmb-navigation-menu li {
    margin-bottom: 8px;
}

.pmb-navigation-menu li a {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    color: #4a5568;
    text-decoration: none;
    border-radius: 5px;
    transition: all 0.2s;
}

.pmb-navigation-menu li a:hover {
    background: #edf2f7;
    color: #1e73be;
}

.pmb-navigation-menu li.active a {
    background: rgba(30, 115, 190, 0.1);
    color: #1e73be;
    font-weight: 600;
}

.pmb-navigation-menu .dashicons {
    margin-right: 10px;
    color: #1e73be;
}

/* Required field indication */
.text-danger {
    color: #e53e3e !important;
}

/* Form groups spacing */
.form-group {
    margin-bottom: 1.25rem;
}
</style>

<div class="pmb-stba-registration-container">
    <div class="row">
        <div class="col-md-3">
            <?php 
            // Debug sidebar ID
            $sidebar_id = carbon_get_theme_option('pmb_navigation_sidebar');
            echo '<!-- Debug: Sidebar ID is: ' . esc_html($sidebar_id) . ' -->';
            
            // Check if sidebar exists and is active
            if ($sidebar_id && is_active_sidebar($sidebar_id)) {
                dynamic_sidebar($sidebar_id);
            } else {
                echo '<!-- Debug: Sidebar is not active or does not exist -->';
                
                // Directly output the navigation menu as a fallback
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
                
                // Logout
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
                    <h4><?php _e('Formulir Pendaftaran Mahasiswa Baru STBA Pontianak', 'pmb-stba'); ?></h4>
                </div>
                <div class="card-body">
                    <?php if (carbon_get_theme_option('pmb_status') === 'open') : ?>
                    
                    <form id="pmb-registration-form" method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field('pmb_registration_nonce', 'pmb_nonce'); ?>
                        
                        <!-- 1. Pilihan -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Pilihan', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="nomor_pendaftaran"><?php _e('Nomor Pendaftaran', 'pmb-stba'); ?></label>
                                    <input type="text" id="nomor_pendaftaran" class="form-control" value="<?php echo esc_attr($this->generate_registration_number()); ?>" readonly>
                                    <input type="hidden" name="nomor_pendaftaran" value="<?php echo esc_attr($this->generate_registration_number()); ?>">
                                    <small class="form-text text-muted"><?php _e('Nomor pendaftaran otomatis dari sistem', 'pmb-stba'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="jurusan_dipilih"><?php _e('Jurusan / Program Studi', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="jurusan_dipilih" id="jurusan_dipilih" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="s1-sastra-inggris"><?php _e('S1 Sastra Inggris', 'pmb-stba'); ?></option>
                                        <option value="d3-bahasa-inggris"><?php _e('D3 Bahasa Inggris', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="waktu_kuliah"><?php _e('Waktu Kuliah', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="waktu_kuliah" id="waktu_kuliah" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="pagi"><?php _e('Pagi', 'pmb-stba'); ?></option>
                                        <option value="sore"><?php _e('Sore', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 2. Data Diri -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Data Diri', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama_lengkap"><?php _e('Nama Lengkap (sesuai Akta Lahir)', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'nama_lengkap', true)); ?>" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="tempat_lahir"><?php _e('Tempat Lahir', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="tanggal_lahir"><?php _e('Tanggal Lahir', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="no_hp"><?php _e('Nomor HP', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="tel" name="no_hp" id="no_hp" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email"><?php _e('E-mail', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" value="<?php echo esc_attr($current_user->user_email); ?>" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="jenis_kelamin"><?php _e('Jenis Kelamin', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="L"><?php _e('Laki-laki', 'pmb-stba'); ?></option>
                                        <option value="P"><?php _e('Perempuan', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="agama"><?php _e('Agama', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="agama" id="agama" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="Islam"><?php _e('Islam', 'pmb-stba'); ?></option>
                                        <option value="Kristen"><?php _e('Kristen', 'pmb-stba'); ?></option>
                                        <option value="Katolik"><?php _e('Katolik', 'pmb-stba'); ?></option>
                                        <option value="Hindu"><?php _e('Hindu', 'pmb-stba'); ?></option>
                                        <option value="Budha"><?php _e('Budha', 'pmb-stba'); ?></option>
                                        <option value="Konghucu"><?php _e('Konghucu', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="alamat"><?php _e('Alamat (di Pontianak)', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 3. Sekolah Asal -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Sekolah Asal', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jenis_sekolah"><?php _e('Jenis Sekolah Asal', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="jenis_sekolah" id="jenis_sekolah" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="SMA"><?php _e('SMA', 'pmb-stba'); ?></option>
                                        <option value="SMK"><?php _e('SMK', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="asal_sekolah"><?php _e('Nama Sekolah Asal', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="asal_sekolah" id="asal_sekolah" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tahun_lulus"><?php _e('Tahun Lulus', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="tahun_lulus" id="tahun_lulus" class="form-control" pattern="[0-9]{4}" maxlength="4" required>
                                    <small class="form-text text-muted"><?php _e('Format: YYYY (contoh: 2023)', 'pmb-stba'); ?></small>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="status_mahasiswa"><?php _e('Status Mahasiswa', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="status_mahasiswa" id="status_mahasiswa" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="Reguler"><?php _e('Reguler', 'pmb-stba'); ?></option>
                                        <option value="Transfer"><?php _e('Transfer', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 4. Status Pekerjaan -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Status Pekerjaan', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="status_pekerjaan"><?php _e('Status Pekerjaan', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="status_pekerjaan" id="status_pekerjaan" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="Sudah Bekerja"><?php _e('Sudah Bekerja', 'pmb-stba'); ?></option>
                                        <option value="Belum Bekerja"><?php _e('Belum Bekerja', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 5. Rekomendasi -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Rekomendasi', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="sumber"><?php _e('Sumber', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="sumber" id="sumber" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih --', 'pmb-stba'); ?></option>
                                        <option value="Expo"><?php _e('Expo', 'pmb-stba'); ?></option>
                                        <option value="Teman Keluarga"><?php _e('Teman Keluarga', 'pmb-stba'); ?></option>
                                        <option value="Sosial Media"><?php _e('Sosial Media', 'pmb-stba'); ?></option>
                                        <option value="Guru"><?php _e('Guru', 'pmb-stba'); ?></option>
                                        <option value="Kepala Sekolah"><?php _e('Kepala Sekolah', 'pmb-stba'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="keterangan1"><?php _e('Keterangan 1', 'pmb-stba'); ?></label>
                                    <input type="text" name="keterangan1" id="keterangan1" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="keterangan2"><?php _e('Keterangan 2', 'pmb-stba'); ?></label>
                                    <input type="text" name="keterangan2" id="keterangan2" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="tanggal_pengisian"><?php _e('Tanggal Pengisian', 'pmb-stba'); ?></label>
                                    <input type="text" id="tanggal_pengisian" class="form-control" value="<?php echo date('d-m-Y'); ?>" readonly>
                                    <input type="hidden" name="tanggal_pengisian" value="<?php echo date('Y-m-d'); ?>">
                                    <small class="form-text text-muted"><?php _e('Tanggal pengisian otomatis', 'pmb-stba'); ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 6. Upload File -->
                        <h5 class="border-bottom pb-2 mb-3"><?php _e('Upload File', 'pmb-stba'); ?></h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="foto"><?php _e('Pas Foto', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="file" name="foto" id="foto" class="form-control" required>
                                    <small class="form-text text-muted"><?php _e('Format: JPG/PNG, Maks: 2MB', 'pmb-stba'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="ijazah"><?php _e('Ijazah', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="file" name="ijazah" id="ijazah" class="form-control" required>
                                    <small class="form-text text-muted"><?php _e('Format: PDF/JPG, Maks: 5MB', 'pmb-stba'); ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="form-group mt-4">
                            <button type="submit" name="pmb_register" class="btn btn-primary w-100"><?php _e('Kirim Pendaftaran', 'pmb-stba'); ?></button>
                        </div>
                    </form>
                    
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <p><?php _e('Pendaftaran saat ini ditutup.', 'pmb-stba'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>