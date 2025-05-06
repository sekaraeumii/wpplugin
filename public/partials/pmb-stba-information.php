<?php
// Check if user is logged in
if (!is_user_logged_in()) {
    echo '<div class="alert alert-warning">';
    echo __('Anda harus login terlebih dahulu untuk melihat informasi pendaftaran.', 'pmb-stba');
    echo ' <a href="' . get_permalink(carbon_get_theme_option('pmb_login_page')) . '">' . __('Login', 'pmb-stba') . '</a>';
    echo '</div>';
    return;
}

// Get current user data
$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Get user registration data
$registration_data = get_user_meta($user_id, 'pmb_registration_data', true);
$has_registered = !empty($registration_data);
$nomor_pendaftaran = $has_registered ? $registration_data['nomor_pendaftaran'] : '';
$payment_status = get_user_meta($user_id, 'pmb_payment_status', true);

// Cek apakah ada tanggal pengisian untuk menentukan status dokumen
$tanggal_pengisian = get_user_meta($user_id, 'tanggal_pengisian', true);
$status_pmb = get_user_meta($user_id, 'status_pmb', true);

// Tentukan status dokumen berdasarkan data ijazah, tanggal_pengisian dan status_pmb
$ijazah_path = get_user_meta($user_id, 'ijazah_path', true);
$foto_path = get_user_meta($user_id, 'foto_path', true);

if ($status_pmb === 'approved') {
    $document_status = 'complete'; // Status sudah diverifikasi
} elseif (!empty($ijazah_path) && !empty($foto_path)) {
    // Prioritaskan nilai dari pmb_document_status jika ada
    $pmb_doc_status = get_user_meta($user_id, 'pmb_document_status', true);
    $document_status = !empty($pmb_doc_status) ? $pmb_doc_status : 'waiting';
} elseif (!empty($tanggal_pengisian)) {
    $document_status = 'partial'; // Formulir sudah diisi tapi dokumen belum lengkap
} else {
    $document_status = ''; // Belum lengkap sama sekali
}

// Get PMB status
$pmb_status = carbon_get_theme_option('pmb_status') === 'open' ? 'Dibuka' : 'Ditutup';
$pmb_announcement_date = carbon_get_theme_option('pmb_announcement_date');

// Tambahkan kode ini di awal file, setelah mendapatkan user_id
$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
$alamat = get_user_meta($user_id, 'alamat', true);
$asal_sekolah = get_user_meta($user_id, 'asal_sekolah', true);
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
                    
                    // Information - active
                    $information_page = get_option('pmb_information_page');
                    if (!empty($information_page) && get_the_ID() == $information_page) {
                        echo '<li class="active"><a href="' . esc_url(get_permalink($information_page)) . '">' . 
                            '<span class="dashicons dashicons-info"></span> ' .
                            esc_html__('Informasi', 'pmb-stba') . '</a></li>';
                    } else {
                        echo '<li><a href="' . esc_url(get_permalink($information_page)) . '">' . 
                            '<span class="dashicons dashicons-info"></span> ' .
                            esc_html__('Informasi', 'pmb-stba') . '</a></li>';
                    }
                    
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
                            esc_html__('Profil Saya', 'pmb-stba') . '</a></li>';
                    }
                    
                    // Payment
                    $payment_page = get_option('pmb_payment_page');
                    if (!empty($payment_page)) {
                        echo '<li><a href="' . esc_url(get_permalink($payment_page)) . '">' . 
                            '<span class="dashicons dashicons-money-alt"></span> ' .
                            esc_html__('Pembayaran Pendaftaran', 'pmb-stba') . '</a></li>';
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
                <!-- Status PMB Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="dashicons dashicons-info mr-2" style="font-size: 24px;"></i>
                            <h4 class="mb-0"><?php echo esc_html__('Informasi Pendaftaran', 'pmb-stba'); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <p><strong><?php _e('Status PMB:', 'pmb-stba'); ?></strong> <?php echo esc_html($pmb_status); ?></p>
                            <?php if (!empty($pmb_announcement_date)) : ?>
                                <p><strong><?php _e('Tanggal Pengumuman:', 'pmb-stba'); ?></strong> <?php echo esc_html($pmb_announcement_date); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Pendaftaran -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="dashicons dashicons-chart-line mr-2" style="font-size: 24px;"></i>
                            <h4 class="mb-0"><?php echo esc_html__('Status Pendaftaran', 'pmb-stba'); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Status berkas dan progres pendaftaran - mirip dengan pmb-stba-user-details.php -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2 mb-3"><?php _e('Progres Administrasi', 'pmb-stba'); ?></h5>
                            <div class="progress mb-3">
                                <?php
                                // Modifikasi cara menghitung progress - disederhanakan
                                $completed = 0;
                                $total_steps = 5; // Total item yang diperiksa (disederhanakan dari 8)
                                
                                // Data pribadi (2 item)
                                // Cek setidaknya 3 field utama data diri
                                if (!empty($nama_lengkap) && !empty($tempat_lahir) && !empty($tanggal_lahir)) $completed++;
                                if (!empty($alamat) && !empty($asal_sekolah)) $completed++;
                                
                                // Dokumen (2 item)
                                if (!empty($foto_path)) $completed++;
                                if (!empty($ijazah_path)) $completed++;
                                
                                // Pembayaran (1 item)
                                if ($payment_status === 'verified') $completed++;
                                
                                $percentage = ($completed / $total_steps) * 100;
                                ?>
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $percentage; ?>%" 
                                    aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo round($percentage); ?>%
                                </div>
                            </div>
                            
                            <div class="row mb-3 small">
                                <div class="col-md-4">
                                    <i class="dashicons <?php echo !empty($asal_sekolah) ? 'dashicons-yes-alt text-success' : 'dashicons-no'; ?>"></i> 
                                    Data Pribadi
                                </div>
                                <div class="col-md-4">
                                    <i class="dashicons <?php echo !empty($foto_path) ? 'dashicons-yes-alt text-success' : 'dashicons-no'; ?>"></i> 
                                    Foto
                                </div>
                                <div class="col-md-4">
                                    <i class="dashicons <?php echo !empty($ijazah_path) ? 'dashicons-yes-alt text-success' : 'dashicons-no'; ?>"></i> 
                                    Ijazah/Dokumen
                                </div>
                            </div>
                            
                            <ul class="pmb-status-list">
                                <!-- Hapus seluruh list item Formulir & Dokumen dari sini -->
                                
                                <!-- Hanya tampilkan status pembayaran saja -->
                                <li class="<?php echo $payment_status === 'verified' ? 'completed' : ($payment_status === 'pending' ? 'in-progress' : 'pending'); ?>">
                                    <span class="status-icon">
                                        <?php if ($payment_status === 'verified'): ?>
                                            <i class="dashicons dashicons-yes-alt"></i>
                                        <?php elseif ($payment_status === 'pending'): ?>
                                            <i class="dashicons dashicons-clock"></i>
                                        <?php else: ?>
                                            <i class="dashicons dashicons-no"></i>
                                        <?php endif; ?>
                                    </span>
                                    <div class="status-content">
                                        <h6 class="mb-0"><?php _e('Pembayaran', 'pmb-stba'); ?></h6>
                                        <?php if ($payment_status === 'verified'): ?>
                                            <span class="badge bg-success"><?php _e('Terverifikasi', 'pmb-stba'); ?></span>
                                        <?php elseif ($payment_status === 'pending'): ?>
                                            <span class="badge bg-info"><?php _e('Menunggu Verifikasi', 'pmb-stba'); ?></span>
                                            <small class="text-muted d-block"><?php _e('Bukti pembayaran sedang diverifikasi admin', 'pmb-stba'); ?></small>
                                        <?php else: ?>
                                            <span class="badge bg-warning"><?php _e('Belum Bayar', 'pmb-stba'); ?></span>
                                            <?php if ($has_registered): ?>
                                                <a href="<?php echo esc_url(get_permalink($payment_page)); ?>" class="btn btn-sm btn-primary mt-1">
                                                    <?php _e('Lihat Informasi Pembayaran', 'pmb-stba'); ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        
                        <!-- Status Pendaftaran -->
                        <div class="mt-4">
                            <h5 class="border-bottom pb-2 mb-3"><?php _e('Status PMB', 'pmb-stba'); ?></h5>
                            <?php 
                            $status = get_user_meta($user_id, 'status_pmb', true);
                            $catatan_admin = get_user_meta($user_id, 'catatan_admin', true);
                            
                            switch ($status) {
                                case 'approved':
                                    echo '<div class="alert alert-success">';
                                    echo '<h6 class="alert-heading"><i class="dashicons dashicons-yes-alt"></i> ' . __('Selamat! Anda diterima', 'pmb-stba') . '</h6>';
                                    if (!empty($catatan_admin)) {
                                        echo '<p class="mb-0">' . esc_html($catatan_admin) . '</p>';
                                    }
                                    echo '</div>';
                                    break;
                                    
                                case 'rejected':
                                    echo '<div class="alert alert-danger">';
                                    echo '<h6 class="alert-heading"><i class="dashicons dashicons-no"></i> ' . __('Maaf, pendaftaran Anda ditolak', 'pmb-stba') . '</h6>';
                                    if (!empty($catatan_admin)) {
                                        echo '<p class="mb-0">' . esc_html($catatan_admin) . '</p>';
                                    }
                                    echo '</div>';
                                    break;
                                    
                                default:
                                    echo '<div class="alert alert-info">';
                                    echo '<h6 class="alert-heading"><i class="dashicons dashicons-clock"></i> ' . __('Pendaftaran Anda sedang diproses', 'pmb-stba') . '</h6>';
                                    echo '<p class="mb-0">' . __('Mohon tunggu konfirmasi dari panitia PMB.', 'pmb-stba') . '</p>';
                                    echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <!-- Kartu Pendaftaran -->
                <?php if ($has_registered): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="dashicons dashicons-id-alt mr-2" style="font-size: 24px;"></i>
                            <h4 class="mb-0"><?php echo esc_html__('Kartu Pendaftaran', 'pmb-stba'); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><?php _e('Anda dapat mencetak kartu pendaftaran sebagai bukti telah mendaftar.', 'pmb-stba'); ?></p>
                        <a href="<?php echo esc_url(add_query_arg(array('action' => 'print_card', 'user_id' => $user_id), get_permalink())); ?>" class="btn btn-primary" target="_blank">
                            <i class="dashicons dashicons-printer" style="vertical-align: middle;"></i> 
                            <?php _e('Cetak Kartu Pendaftaran', 'pmb-stba'); ?>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Upload Bukti Pembayaran -->
                <?php if ($has_registered && $payment_status !== 'verified'): ?>
                <div class="card shadow-sm mb-4" id="payment-section">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="dashicons dashicons-upload mr-2" style="font-size: 24px;"></i>
                            <h4 class="mb-0"><?php echo esc_html__('Upload Bukti Pembayaran', 'pmb-stba'); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" id="payment-proof-form">
                            <?php wp_nonce_field('pmb_payment_proof_nonce', 'payment_proof_nonce'); ?>
                            <input type="hidden" name="action" value="upload_payment_proof">
                            
                            <div class="form-group mb-3">
                                <label for="payment_proof"><?php _e('Bukti Pembayaran', 'pmb-stba'); ?></label>
                                <input type="file" name="payment_proof" id="payment_proof" class="form-control" accept="image/jpeg,image/png,image/jpg,application/pdf" required>
                                <small class="text-muted"><?php _e('Format: JPG, PNG, PDF. Maks: 2MB', 'pmb-stba'); ?></small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="payment_date"><?php _e('Tanggal Pembayaran', 'pmb-stba'); ?></label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="payment_amount"><?php _e('Jumlah Pembayaran', 'pmb-stba'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Rp</span>
                                    </div>
                                    <input type="text" name="payment_amount" id="payment_amount" class="form-control" required>
                                </div>
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
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Upload Dokumen -->
                <?php if ($has_registered && $document_status !== 'complete'): ?>
                <div class="card shadow-sm mb-4" id="document-section">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <i class="dashicons dashicons-upload mr-2" style="font-size: 24px;"></i>
                            <h4 class="mb-0"><?php echo esc_html__('Upload Dokumen', 'pmb-stba'); ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" id="document-upload-form">
                            <?php wp_nonce_field('pmb_document_upload_nonce', 'document_upload_nonce'); ?>
                            <input type="hidden" name="action" value="upload_document">
                            
                            <div class="form-group mb-3">
                                <label for="document_file"><?php _e('File Dokumen', 'pmb-stba'); ?></label>
                                <input type="file" name="document_file" id="document_file" class="form-control" accept="image/jpeg,image/png,image/jpg,application/pdf" required>
                                <small class="text-muted"><?php _e('Format: JPG, PNG, PDF. Maks: 2MB', 'pmb-stba'); ?></small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="document_type"><?php _e('Tipe Dokumen', 'pmb-stba'); ?></label>
                                <select name="document_type" id="document_type" class="form-control" required>
                                    <option value=""><?php _e('Pilih Tipe Dokumen', 'pmb-stba'); ?></option>
                                    <option value="ktp"><?php _e('KTP', 'pmb-stba'); ?></option>
                                    <option value="kk"><?php _e('Kartu Keluarga', 'pmb-stba'); ?></option>
                                    <option value="ijazah"><?php _e('Ijazah', 'pmb-stba'); ?></option>
                                    <option value="transkrip"><?php _e('Transkrip Nilai', 'pmb-stba'); ?></option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="document_notes"><?php _e('Catatan', 'pmb-stba'); ?></label>
                                <textarea name="document_notes" id="document_notes" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="dashicons dashicons-upload" style="vertical-align: middle;"></i> 
                                <?php _e('Upload Dokumen', 'pmb-stba'); ?>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles for progress steps */
.pmb-progress-steps {
    position: relative;
    margin: 30px 0;
}

.pmb-step {
    position: relative;
    padding-left: 70px;
    padding-bottom: 30px;
}

.pmb-step:not(:last-child):before {
    content: '';
    position: absolute;
    top: 30px;
    left: 19px;
    height: calc(100% - 30px);
    width: 2px;
    background-color: #e9e9e9;
}

.pmb-step.completed:not(:last-child):before {
    background-color: #28a745;
}

.pmb-step-icon {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f8f9fa;
    border: 2px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.pmb-step.active .pmb-step-icon {
    background-color: #e7f5ff;
    border-color: #007bff;
}

.pmb-step.completed .pmb-step-icon {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.pmb-step-content {
    margin-bottom: 15px;
}

.pmb-step-content h5 {
    margin-bottom: 5px;
    font-weight: 600;
}

.pmb-step-content p {
    color: #6c757d;
    margin-bottom: 10px;
}

.badge {
    font-weight: 500;
    padding: 5px 10px;
    border-radius: 4px;
}

.bg-success {
    background-color: #28a745 !important;
    color: white;
}

.bg-info {
    background-color: #17a2b8 !important;
    color: white;
}

.bg-secondary {
    background-color: #6c757d !important;
    color: white;
}

/* Card styles */
.card {
    border-radius: 0.5rem;
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.dashicons {
    font-size: 20px;
    width: auto;
    height: auto;
    vertical-align: middle;
    line-height: 1;
}

.mr-2 {
    margin-right: 0.5rem;
}

/* Button styling */
.btn-primary {
    background-color: #1e73be;
    border-color: #1e73be;
}

.btn-primary:hover {
    background-color: #1a65a8;
    border-color: #1a65a8;
}

/* Status list styling */
.pmb-status-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.pmb-status-list li {
    display: flex;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px dashed #eee;
}

.pmb-status-list li:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.pmb-status-list .status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f8f9fa;
    border: 2px solid #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
}

.pmb-status-list .completed .status-icon {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.pmb-status-list .in-progress .status-icon {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

.pmb-status-list .pending .status-icon {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}

.pmb-status-list .status-content {
    flex-grow: 1;
}

.pmb-status-list h6 {
    margin-bottom: 5px;
}

.pmb-status-list .badge {
    margin-bottom: 5px;
    padding: 5px 10px;
    font-weight: 500;
}
</style>

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