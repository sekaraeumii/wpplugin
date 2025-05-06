<?php
// Check if user is logged in
if (!is_user_logged_in()) {
    echo '<div class="alert alert-warning">';
    echo __('Anda harus login terlebih dahulu untuk melihat informasi pembayaran.', 'pmb-stba');
    echo ' <a href="' . get_permalink(carbon_get_theme_option('pmb_login_page')) . '">' . __('Login', 'pmb-stba') . '</a>';
    echo '</div>';
    return;
}

// Get payment status and data
$payment_status = get_user_meta(get_current_user_id(), 'pmb_payment_status', true);
$payment_data = get_user_meta(get_current_user_id(), 'pmb_payment_data', true);

// Show message after upload
if (isset($_GET['upload_status'])) {
    if ($_GET['upload_status'] == 'success') {
        echo '<div class="alert alert-success">';
        echo '<strong>' . __('Berhasil!', 'pmb-stba') . '</strong> ';
        echo __('Bukti pembayaran Anda berhasil diunggah dan sedang diverifikasi oleh admin.', 'pmb-stba');
        echo '</div>';
    } else {
        echo '<div class="alert alert-danger">';
        echo '<strong>' . __('Gagal!', 'pmb-stba') . '</strong> ';
        echo __('Terjadi kesalahan saat mengunggah bukti pembayaran. Silakan coba lagi.', 'pmb-stba');
        echo '</div>';
    }
}

// Get payment info
$payment_title = get_option('pmb_payment_title', 'Informasi Pembayaran PMB');
$payment_description = get_option('pmb_payment_description', '');
$payment_amount = get_option('pmb_payment_amount', '0');
$bank_accounts = get_option('pmb_bank_accounts', array());

// Current user
$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
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
                    
                    // Profile
                    $profile_page = carbon_get_theme_option('pmb_profile_page');
                    if (!empty($profile_page)) {
                        echo '<li><a href="' . esc_url(get_permalink($profile_page)) . '">' . 
                            '<span class="dashicons dashicons-id"></span> ' .
                            esc_html__('Profil PMB', 'pmb-stba') . '</a></li>';
                    }
                    
                    // Payment - active
                    $payment_page = get_option('pmb_payment_page');
                    if (!empty($payment_page) && get_the_ID() == $payment_page) {
                        echo '<li class="active"><a href="' . esc_url(get_permalink($payment_page)) . '">' . 
                            '<span class="dashicons dashicons-money-alt"></span> ' .
                            esc_html__('Pembayaran Formulir', 'pmb-stba') . '</a></li>';
                    } else {
                        echo '<li><a href="' . esc_url(get_permalink($payment_page)) . '">' . 
                            '<span class="dashicons dashicons-money-alt"></span> ' .
                            esc_html__('Pembayaran Formulir', 'pmb-stba') . '</a></li>';
                    }
                    
                    // Logout
                    echo '<li><a href="' . wp_logout_url(home_url()) . '" class="pmb-logout-link">' . 
                        '<span class="dashicons dashicons-exit"></span> ' .
                        esc_html__('Keluar', 'pmb-stba') . '</a></li>';

                    echo '</ul>';
                    echo '</div>';
                }
                ?>
            </div>
            
            <!-- Main Content - Right Column -->
            <div class="col-md-9">
                <!-- TAMBAHKAN notifikasi status pembayaran di sini -->
                <?php
                // Show payment status notification
                if ($payment_status === 'verified') {
                    echo '<div class="alert alert-success mb-4">';
                    echo '<i class="dashicons dashicons-yes-alt"></i> ';
                    echo __('Pembayaran Anda sudah terverifikasi.', 'pmb-stba');
                    echo '</div>';
                } elseif ($payment_status === 'pending') {
                    echo '<div class="alert alert-info mb-4">';
                    echo '<i class="dashicons dashicons-clock"></i> ';
                    echo __('Bukti pembayaran Anda sedang diverifikasi oleh admin.', 'pmb-stba');
                    echo '</div>';
                } elseif ($payment_status === 'rejected') {
                    echo '<div class="alert alert-danger mb-4">';
                    echo '<i class="dashicons dashicons-no"></i> ';
                    echo __('Bukti pembayaran Anda ditolak. Silakan upload ulang bukti pembayaran yang valid.', 'pmb-stba');
                    echo '</div>';
                }
                
                // Show message after upload
                if (isset($_GET['upload_status'])) {
                    if ($_GET['upload_status'] == 'success') {
                        echo '<div class="alert alert-success mb-4">';
                        echo '<strong>' . __('Berhasil!', 'pmb-stba') . '</strong> ';
                        echo __('Bukti pembayaran Anda berhasil diunggah dan sedang diverifikasi oleh admin.', 'pmb-stba');
                        echo '</div>';
                    } else {
                        echo '<div class="alert alert-danger mb-4">';
                        echo '<strong>' . __('Gagal!', 'pmb-stba') . '</strong> ';
                        echo __('Terjadi kesalahan saat mengunggah bukti pembayaran. Silakan coba lagi.', 'pmb-stba');
                        echo '</div>';
                    }
                }
                ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="dashicons dashicons-money-alt mr-2" style="font-size: 24px;"></i>
                            <h4 class="mb-0"><?php echo esc_html($payment_title); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Konten halaman pembayaran tetap sama -->
                        <?php if (!empty($payment_description)) : ?>
                            <div class="alert alert-info mb-4">
                                <?php echo wpautop(wp_kses_post($payment_description)); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h5 class="border-bottom pb-2 mb-3">
                            <?php _e('Nominal Pembayaran', 'pmb-stba'); ?>
                        </h5>
                        <div class="mb-4">
                            <h2 class="text-primary">Rp <?php echo number_format(intval($payment_amount), 0, ',', '.'); ?></h2>
                        </div>
                        
                        <?php if (!empty($bank_accounts)) : ?>
                            <h5 class="border-bottom pb-2 mb-3">
                                <?php _e('Transfer ke Bank di bawah : ', 'pmb-stba'); ?>
                            </h5>
                            
                            <div class="row">
                                <?php foreach ($bank_accounts as $bank) : ?>
                                    <div class="col-md-6 mb-4">
                                        <div class="card atm-card">
                                            <?php if (!empty($bank['bank_logo'])) : ?>
                                                <div class="atm-card-logo">
                                                    <img src="<?php echo esc_url($bank['bank_logo']); ?>" alt="<?php echo esc_attr($bank['bank_name']); ?>" class="bank-logo">
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="atm-card-chip">
                                                <div class="chip-line"></div>
                                                <div class="chip-line"></div>
                                                <div class="chip-line"></div>
                                                <div class="chip-line"></div>
                                            </div>
                                            
                                            <div class="atm-card-content">
                                                <div class="atm-card-number">
                                                    <?php 
                                                        // Format account number untuk tampilan ATM
                                                        $formatted_number = str_replace(' ', '', $bank['account_number']);
                                                        $chunks = str_split($formatted_number, 4);
                                                        echo implode(' ', $chunks);
                                                    ?>
                                                </div>
                                                
                                                <div class="atm-card-name">
                                                    <div class="atm-label"><?php _e('Nama Pemilik', 'pmb-stba'); ?></div>
                                                    <div class="atm-value"><?php echo esc_html($bank['account_name']); ?></div>
                                                </div>
                                                
                                                <div class="atm-card-bank">
                                                    <div class="atm-label"><?php _e('Bank', 'pmb-stba'); ?></div>
                                                    <div class="atm-value"><?php echo esc_html($bank['bank_name']); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="alert alert-warning mt-3">
                                <h6 class="mb-2"><i class="dashicons dashicons-info mr-2"></i> <?php _e('Petunjuk Pembayaran:', 'pmb-stba'); ?></h6>
                                <ol class="mb-0 pl-3">
                                    <li><?php _e('Transfer sesuai nominal yang tertera.', 'pmb-stba'); ?></li>
                                    <li><?php _e('Gunakan nama Anda sebagai keterangan transfer: ', 'pmb-stba'); ?><strong><?php echo esc_html($nama_lengkap); ?></strong></li>
                                    <li><?php _e('Simpan bukti pembayaran untuk verifikasi jika diperlukan.', 'pmb-stba'); ?></li>
                                </ol>
                            </div>
                        <?php else : ?>
                            <div class="alert alert-warning">
                                <?php _e('Informasi rekening bank belum tersedia. Silakan hubungi admin untuk informasi lebih lanjut.', 'pmb-stba'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Tambahkan setelah informasi rekening bank -->
                <?php if (is_user_logged_in() && !empty($bank_accounts)): ?>
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="dashicons dashicons-upload mr-2"></i> <?php _e('Upload Bukti Pembayaran', 'pmb-stba'); ?></h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        // Hapus notifikasi status di sini, karena sudah dipindahkan ke atas
                        // Tampilkan hanya bagian preview bukti pembayaran jika ada
                        if ($payment_status === 'pending' && !empty($payment_data) && !empty($payment_data['file_url'])): ?>
                            <div class="mb-3">
                                <p><strong><?php _e('Bukti pembayaran yang diupload:', 'pmb-stba'); ?></strong></p>
                                <?php if (strpos($payment_data['file_url'], '.pdf') !== false): ?>
                                    <p><a href="<?php echo esc_url($payment_data['file_url']); ?>" target="_blank" class="btn btn-sm btn-secondary">
                                        <i class="dashicons dashicons-pdf"></i> <?php _e('Lihat PDF', 'pmb-stba'); ?>
                                    </a></p>
                                <?php else: ?>
                                    <img src="<?php echo esc_url($payment_data['file_url']); ?>" class="img-fluid img-thumbnail" style="max-height: 300px;">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($payment_status !== 'verified'): ?>
                            <form method="post" enctype="multipart/form-data" id="payment-proof-form">
                                <?php wp_nonce_field('pmb_payment_proof_nonce', 'payment_proof_nonce'); ?>
                                <input type="hidden" name="action" value="upload_payment_proof">
                                
                                <div class="form-group mb-3">
                                    <label for="payment_proof"><?php _e('Bukti Pembayaran', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/jpeg,image/png,image/jpg,application/pdf" required>
                                    <small class="text-muted"><?php _e('Format: JPG, PNG, PDF. Maks: 2MB', 'pmb-stba'); ?></small>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="payment_date"><?php _e('Tanggal Pembayaran', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="payment_amount"><?php _e('Jumlah Pembayaran', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" name="payment_amount" id="payment_amount" class="form-control" value="<?php echo esc_attr($payment_amount); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="payment_bank"><?php _e('Bank Tujuan', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <select name="payment_bank" id="payment_bank" class="form-control" required>
                                        <option value=""><?php _e('-- Pilih Bank --', 'pmb-stba'); ?></option>
                                        <?php foreach ($bank_accounts as $bank): ?>
                                            <option value="<?php echo esc_attr($bank['bank_name']); ?>"><?php echo esc_html($bank['bank_name'] . ' - ' . $bank['account_number']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="payment_notes"><?php _e('Catatan', 'pmb-stba'); ?></label>
                                    <textarea name="payment_notes" id="payment_notes" class="form-control" rows="3"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="dashicons dashicons-upload" style="vertical-align: middle;"></i> 
                                    <?php _e('Upload Bukti Pembayaran', 'pmb-stba'); ?>
                                </button>
                            </form>
                            
                            <script>
                            jQuery(document).ready(function($) {
                                $('#payment_amount').on('input', function() {
                                    // Format as currency
                                    let value = $(this).val().replace(/[^\d]/g, '');
                                    if (value.length > 0) {
                                        value = parseInt(value).toLocaleString('id-ID');
                                        $(this).val(value);
                                    }
                                });
                                
                                $('#payment-proof-form').on('submit', function(e) {
                                    let fileInput = $('#payment_proof')[0];
                                    if (fileInput.files[0].size > 2 * 1024 * 1024) { // 2MB in bytes
                                        e.preventDefault();
                                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                                        return false;
                                    }
                                });
                            });
                            </script>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan CSS yang sama dengan halaman profile -->
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

/* Memastikan teks judul pada header card berwarna putih */
.pmb-stba-profile-container .card-header.bg-primary h4,
.pmb-stba-profile-container .card-header.bg-primary h5 {
    color: white !important;
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

/* Style untuk kartu ATM */
.atm-card {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    padding: 0;
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    color: white;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    height: 200px;
    margin-bottom: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.atm-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
}

.atm-card-logo {
    position: absolute;
    top: 20px;
    right: 20px;
    height: 40px;
    width: auto;
    max-width: 100px;
    display: flex;
    justify-content: flex-end;
    z-index: 1;
}

.bank-logo {
    max-height: 40px;
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.atm-card-chip {
    position: absolute;
    top: 50px; /* Sesuaikan posisi chip */
    left: 20px;
    width: 45px;
    height: 35px;
    background: linear-gradient(135deg, #cda349, #e6b94d);
    border-radius: 5px;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    padding: 5px 0;
}

.chip-line {
    height: 3px;
    background: #8d7a36;
    margin: 0 auto;
    width: 80%;
    border-radius: 1px;
}

.atm-card-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 12px;
    background: rgba(0, 0, 0, 0.2);
}

.atm-card-number {
    font-size: 22px;
    letter-spacing: 2px;
    margin-top: 15px; /* Tambahkan margin atas */
    margin-bottom: 15px;
    font-family: 'Courier New', monospace;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.atm-card-name, .atm-card-bank {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

.atm-label {
    font-size: 10px;
    text-transform: uppercase;
    opacity: 0.7;
}

.atm-value {
    font-size: 14px;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #ffd700; /* Warna gold */
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); /* Tambahkan shadow untuk efek metalik */
}

/* Penyesuaian responsif khusus untuk kartu ATM */
@media (max-width: 767px) {
    .atm-card {
        height: 180px;
    }
    
    .atm-card-number {
        font-size: 18px;
    }
    
    .atm-card-logo {
        top: 15px;
        right: 15px;
    }
    
    .atm-card-chip {
        top: 50px;
        left: 15px;
        width: 40px;
        height: 30px;
    }
}

@media (max-width: 575px) {
    .atm-card {
        height: 160px;
    }
    
    .atm-card-number {
        font-size: 16px;
        margin-bottom: 10px;
    }
    
    .bank-logo {
        max-height: 30px;
    }
    
    .atm-card-content {
        padding: 15px;
    }
}
</style>