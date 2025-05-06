<?php
// Check user capabilities and get user ID
if (!current_user_can('manage_options') || empty($_GET['user_id'])) {
    wp_die(__('Anda tidak memiliki izin untuk mengakses halaman ini.', 'pmb-stba'));
}

$user_id = intval($_GET['user_id']);
$user = get_userdata($user_id);

if (!$user) {
    wp_die(__('User tidak ditemukan.', 'pmb-stba'));
}

// Get user meta (same as before)
$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
$jenis_kelamin = get_user_meta($user_id, 'jenis_kelamin', true);
$no_hp = get_user_meta($user_id, 'no_hp', true);
$alamat = get_user_meta($user_id, 'alamat', true);
$asal_sekolah = get_user_meta($user_id, 'asal_sekolah', true);
$jurusan = get_user_meta($user_id, 'jurusan_dipilih', true);
$status = get_user_meta($user_id, 'status_pmb', true);
$nomor_pendaftaran = get_user_meta($user_id, 'nomor_pendaftaran', true);
$waktu_kuliah = get_user_meta($user_id, 'waktu_kuliah', true);
$jenis_sekolah = get_user_meta($user_id, 'jenis_sekolah', true);
$tahun_lulus = get_user_meta($user_id, 'tahun_lulus', true);
$status_mahasiswa = get_user_meta($user_id, 'status_mahasiswa', true);
$status_pekerjaan = get_user_meta($user_id, 'status_pekerjaan', true);
$sumber = get_user_meta($user_id, 'sumber', true);
$keterangan1 = get_user_meta($user_id, 'keterangan1', true);
$keterangan2 = get_user_meta($user_id, 'keterangan2', true);
$tanggal_pengisian = get_user_meta($user_id, 'tanggal_pengisian', true);

// Get file paths
$foto_path = get_user_meta($user_id, 'foto_path', true);
$ijazah_path = get_user_meta($user_id, 'ijazah_path', true);

// Format jenis kelamin
$jenis_kelamin = $jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';

// Status badge with updated styling
switch ($status) {
    case 'approved':
        $status_badge = '<span class="status-badge status-approved">Diterima</span>';
        break;
    case 'rejected':
        $status_badge = '<span class="status-badge status-rejected">Ditolak</span>';
        break;
    default:
        $status_badge = '<span class="status-badge status-pending">Menunggu</span>';
}

// Get other meta data
$nilai_un = get_user_meta($user_id, 'nilai_un', true);
$status_history = get_user_meta($user_id, 'status_history', true);
if (!is_array($status_history)) {
    $status_history = array();
}

// Format jurusan for display
$jurusan_raw = get_user_meta($user_id, 'jurusan_dipilih', true);
$jurusan_display = '';
switch ($jurusan_raw) {
    case 's1-sastra-inggris':
        $jurusan_display = 'S1 Sastra Inggris';
        break;
    case 'd3-bahasa-inggris':
        $jurusan_display = 'D3 Bahasa Inggris';
        break;
    default:
        $jurusan_display = $jurusan_raw;
}

// Calculate completion percentage
$completed = 0;
if (!empty($nama_lengkap)) $completed++;
if (!empty($tempat_lahir) && !empty($tanggal_lahir)) $completed++;
if (!empty($alamat)) $completed++;
if (!empty($asal_sekolah)) $completed++;
if (!empty($foto_path)) $completed++;
if (!empty($ijazah_path)) $completed++;
$percentage = ($completed / 6) * 100;
?>

<style>
:root {
    --primary-color: #4e73df;
    --primary-light: #6f8be8;
    --secondary-color: #858796;
    --success-color: #1cc88a;
    --info-color: #36b9cc;
    --warning-color: #f6c23e;
    --danger-color: #e74a3b;
    --light-color: #f8f9fc;
    --dark-color: #5a5c69;
    --card-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .1);
    --transition-speed: 0.3s;
}

body {
    background-color: #f8f9fc;
    font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

.pmb-container {
    padding: 1.5rem;
    margin: 0;
    width: 100%;
}

.card {
    position: relative;
    border: none;
    border-radius: 0.5rem;
    box-shadow: var(--card-shadow);
    transition: transform var(--transition-speed), box-shadow var(--transition-speed);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .25rem 2rem 0 rgba(58, 59, 69, .2);
}

.card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(0,0,0,.05);
}

.card-body {
    padding: 1.25rem;
}

.primary-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
}

.info-header {
    background: linear-gradient(135deg, var(--info-color) 0%, #4dd4e9 100%);
    color: white;
}

.secondary-header {
    background: linear-gradient(135deg, var(--secondary-color) 0%, #9da2b3 100%);
    color: white;
}

.back-button {
    display: inline-flex;
    align-items: center;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: bold;
    transition: color var(--transition-speed);
}

.back-button:hover {
    color: var(--primary-light);
}

.back-button .dashicons {
    margin-right: 0.5rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.btn {
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all var(--transition-speed);
    cursor: pointer;
    border: none;
}

.btn-print {
    background-color: var(--info-color);
    color: white;
}

.btn-print:hover {
    background-color: #2aa1b3;
}

.status-badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    border-radius: 50rem;
    display: inline-block;
    text-align: center;
}

.status-approved {
    color: #fff;
    background-color: var(--success-color);
}

.status-rejected {
    color: #fff;
    background-color: var(--danger-color);
}

.status-pending {
    color: #fff;
    background-color: var(--warning-color);
}

.progress-container {
    background-color: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: var(--card-shadow);
    margin-bottom: 1.5rem;
}

.progress {
    height: 0.8rem;
    background-color: #e9ecef;
    border-radius: 0.5rem;
    overflow: hidden;
    margin-bottom: 1rem;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
}

.document-status {
    display: flex;
    justify-content: space-between;
}

.status-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.status-item .dashicons-yes {
    color: var(--success-color);
    margin-right: 0.5rem;
}

.status-item .dashicons-no {
    color: var(--danger-color);
    margin-right: 0.5rem;
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color var(--transition-speed);
}

.form-control:focus {
    border-color: var(--primary-light);
    outline: 0;
}

.form-group {
    margin-bottom: 1rem;
}

label {
    display: inline-block;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.user-photo {
    position: relative;
    overflow: hidden;
    border-radius: 0.5rem;
    max-height: 200px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform var(--transition-speed);
}

.user-photo:hover {
    transform: scale(1.02);
}

.document-link {
    display: inline-block;
    color: var(--primary-color);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border: 1px solid var(--primary-color);
    border-radius: 0.25rem;
    transition: all var(--transition-speed);
    margin-top: 0.5rem;
}

.document-link:hover {
    background-color: var(--primary-color);
    color: white;
}

.info-field {
    display: flex;
    margin-bottom: 0.75rem;
}

.info-label {
    width: 40%;
    font-weight: 600;
    color: var(--dark-color);
}

.info-value {
    width: 60%;
    color: #333;
}

.table-responsive {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th, .table td {
    padding: 0.75rem;
    border-bottom: 1px solid #e3e6f0;
}

.table th {
    text-align: left;
    font-weight: 600;
    background-color: #f8f9fc;
}

.card-preview {
    border: 1px dashed #ccc;
    border-radius: 0.5rem;
    padding: 1rem;
    background-color: white;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .pmb-row {
        flex-direction: column;
    }
}
</style>

<div class="wrap pmb-container">
    <div class="page-header">
        <div>
            <a href="<?php echo admin_url('admin.php?page=pmb-stba-registrations'); ?>" class="back-button">
                <span class="dashicons dashicons-arrow-left-alt"></span> Kembali
            </a>
            <h1 style="margin-top: 0.5rem;">Detail Pendaftar</h1>
        </div>
        
        <a href="<?php echo admin_url('admin-post.php?action=pmb_print_card&user_id=' . $user_id); ?>" target="_blank" class="btn btn-print">
            <span class="dashicons dashicons-printer" style="margin-right: 5px;"></span> Cetak Kartu PMB
        </a>
    </div>
    
    <!-- Status berkas - indikator visual -->
    <div class="progress-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
            <h3 style="margin: 0; font-size: 1.1rem;">Status Kelengkapan Berkas</h3>
            <span class="status-badge" style="background-color: <?php echo $percentage == 100 ? '#1cc88a' : '#f6c23e'; ?>;">
                <?php echo round($percentage); ?>% Lengkap
            </span>
        </div>
        
        <div class="progress">
            <div class="progress-bar" style="width: <?php echo $percentage; ?>%"></div>
        </div>
        
        <div class="document-status">
            <div class="col-md-4">
                <div class="status-item">
                    <span class="dashicons <?php echo !empty($nama_lengkap) ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
                    Data Pribadi
                </div>
            </div>
            <div class="col-md-4">
                <div class="status-item">
                    <span class="dashicons <?php echo !empty($foto_path) ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
                    Pas Foto
                </div>
            </div>
            <div class="col-md-4">
                <div class="status-item">
                    <span class="dashicons <?php echo !empty($ijazah_path) ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
                    Ijazah
                </div>
            </div>
        </div>
    </div>
    
    <div class="pmb-row" style="display: flex; gap: 1.5rem;">
        <div style="flex: 7;">
            <!-- Data Pendaftar -->
            <div class="card">
                <div class="card-header primary-header">
                    <h3 style="margin: 0; font-size: 1.25rem;">Data Pendaftar</h3>
                </div>
                <div class="card-body">
                    <div style="display: flex; flex-wrap: wrap;">
                        <div style="flex: 1; min-width: 250px; padding-right: 1.5rem;">
                            <div class="info-field">
                                <div class="info-label">Nama Lengkap:</div>
                                <div class="info-value"><?php echo esc_html($nama_lengkap); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Email:</div>
                                <div class="info-value"><?php echo esc_html($user->user_email); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">TTL:</div>
                                <div class="info-value"><?php echo esc_html($tempat_lahir . ', ' . $tanggal_lahir); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Jenis Kelamin:</div>
                                <div class="info-value"><?php echo esc_html($jenis_kelamin); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Agama:</div>
                                <div class="info-value"><?php echo esc_html(get_user_meta($user_id, 'agama', true)); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">No. HP:</div>
                                <div class="info-value"><?php echo esc_html($no_hp); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Alamat:</div>
                                <div class="info-value"><?php echo esc_html($alamat); ?></div>
                            </div>
                        </div>
                        
                        <div style="flex: 1; min-width: 250px;">
                            <div class="info-field">
                                <div class="info-label">No. Pendaftaran:</div>
                                <div class="info-value"><?php echo esc_html($nomor_pendaftaran); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Jurusan:</div>
                                <div class="info-value"><?php echo esc_html($jurusan_display); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Waktu Kuliah:</div>
                                <div class="info-value"><?php echo esc_html(ucfirst($waktu_kuliah)); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Jenis Sekolah:</div>
                                <div class="info-value"><?php echo esc_html($jenis_sekolah); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Asal Sekolah:</div>
                                <div class="info-value"><?php echo esc_html($asal_sekolah); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Tahun Lulus:</div>
                                <div class="info-value"><?php echo esc_html($tahun_lulus); ?></div>
                            </div>
                            <div class="info-field">
                                <div class="info-label">Status:</div>
                                <div class="info-value"><?php echo esc_html($status_mahasiswa); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e3e6f0;">
                        <h4 style="margin-bottom: 1rem; font-size: 1.1rem;">Informasi Tambahan</h4>
                        <div style="display: flex; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 250px; padding-right: 1.5rem;">
                                <div class="info-field">
                                    <div class="info-label">Status Pekerjaan:</div>
                                    <div class="info-value"><?php echo esc_html($status_pekerjaan); ?></div>
                                </div>
                                <div class="info-field">
                                    <div class="info-label">Sumber Info:</div>
                                    <div class="info-value"><?php echo esc_html($sumber); ?></div>
                                </div>
                                <div class="info-field">
                                    <div class="info-label">Tgl Pengisian:</div>
                                    <div class="info-value"><?php echo esc_html($tanggal_pengisian); ?></div>
                                </div>
                            </div>
                            <div style="flex: 1; min-width: 250px;">
                                <div class="info-field">
                                    <div class="info-label">Keterangan 1:</div>
                                    <div class="info-value"><?php echo esc_html($keterangan1); ?></div>
                                </div>
                                <div class="info-field">
                                    <div class="info-label">Keterangan 2:</div>
                                    <div class="info-value"><?php echo esc_html($keterangan2); ?></div>
                                </div>
                                <div class="info-field">
                                    <div class="info-label">Status PMB:</div>
                                    <div class="info-value"><?php echo $status_badge; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Kelola Pendaftaran -->
            <div class="card">
                <div class="card-header primary-header">
                    <h3 style="margin: 0; font-size: 1.25rem;">Kelola Status Pendaftaran</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="pmb_update_status">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <?php wp_nonce_field('pmb_update_status_nonce', 'pmb_status_nonce'); ?>
                        
                        <div class="form-group">
                            <label for="status">Status Pendaftaran</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending" <?php selected($status, 'pending'); ?>>Menunggu</option>
                                <option value="approved" <?php selected($status, 'approved'); ?>>Diterima</option>
                                <option value="rejected" <?php selected($status, 'rejected'); ?>>Ditolak</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="catatan">Catatan untuk Pendaftar</label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3"><?php echo esc_textarea(get_user_meta($user_id, 'catatan_admin', true)); ?></textarea>
                        </div>
                        
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <input type="checkbox" name="send_notification" id="send_notification" value="1" checked style="margin-right: 0.5rem;">
                            <label for="send_notification" style="margin-bottom: 0;">
                                Kirim notifikasi email ke pendaftar
                            </label>
                        </div>
                        
                        <button type="submit" class="btn" style="background-color: var(--primary-color); color: white; font-weight: 600;">
                            Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Riwayat Status -->
            <div class="card">
                <div class="card-header secondary-header">
                    <h3 style="margin: 0; font-size: 1.25rem;">Riwayat Status</h3>
                </div>
                <div class="card-body">
                    <?php if (empty($status_history)): ?>
                        <p style="color: var(--secondary-color); text-align: center; padding: 1rem;">
                            Belum ada perubahan status pendaftaran.
                        </p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Admin</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($status_history as $history): ?>
                                        <tr>
                                            <td><?php echo date_i18n('d M Y H:i', $history['timestamp']); ?></td>
                                            <td>
                                                <?php 
                                                switch ($history['status']) {
                                                    case 'approved':
                                                        echo '<span class="status-badge status-approved">Diterima</span>';
                                                        break;
                                                    case 'rejected':
                                                        echo '<span class="status-badge status-rejected">Ditolak</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="status-badge status-pending">Menunggu</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo esc_html($history['admin_name']); ?></td>
                                            <td><?php echo esc_html($history['catatan']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div style="flex: 3; min-width: 300px;">
            <!-- Dokumen -->
            <div class="card">
                <div class="card-header primary-header">
                    <h3 style="margin: 0; font-size: 1.25rem;">Dokumen Pendaftar</h3>
                </div>
                <div class="card-body">
                    <!-- Pas Foto -->
                    <h4 style="margin-top: 0; font-size: 1.1rem;">Pas Foto</h4>
                    <?php if (!empty($foto_path)) : ?>
                        <div class="user-photo">
                            <img src="<?php echo esc_url($foto_path); ?>" alt="Foto Pendaftar" style="width: 100%;">
                        </div>
                        <a href="<?php echo esc_url($foto_path); ?>" target="_blank" class="document-link">
                            <span class="dashicons dashicons-visibility"></span> Lihat Full Size
                        </a>
                    <?php else : ?>
                        <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                            <span class="dashicons dashicons-no-alt" style="color: #721c24;"></span>
                            Foto belum diunggah
                        </div>
                    <?php endif; ?>
                    
                    <!-- Ijazah -->
                    <h4 style="margin-top: 1.5rem; font-size: 1.1rem;">Ijazah</h4>
                    <?php if (!empty($ijazah_path)) : ?>
                        <?php $file_ext = pathinfo($ijazah_path, PATHINFO_EXTENSION); ?>
                        <?php if (strtolower($file_ext) === 'pdf') : ?>
                            <div style="background-color: #f2f7fd; padding: 1.5rem; border-radius: 0.25rem; text-align: center; margin-bottom: 1rem;">
                                <span class="dashicons dashicons-pdf" style="font-size: 3rem; width: auto; height: auto; color: #e74a3b;"></span>
                                <p style="margin: 0.5rem 0 0;">Dokumen PDF Ijazah</p>
                            </div>
                            <a href="<?php echo esc_url($ijazah_path); ?>" target="_blank" class="document-link">
                                <span class="dashicons dashicons-pdf"></span> Buka PDF
                            </a>
                        <?php else : ?>
                            <div class="user-photo">
                                <img src="<?php echo esc_url($ijazah_path); ?>" alt="Ijazah" style="width: 100%;">
                            </div>
                            <a href="<?php echo esc_url($ijazah_path); ?>" target="_blank" class="document-link">
                                <span class="dashicons dashicons-visibility"></span> Lihat Full Size
                            </a>
                        <?php endif; ?>
                    <?php else : ?>
                        <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                            <span class="dashicons dashicons-no-alt" style="color: #721c24;"></span>
                            Ijazah belum diunggah
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Kartu Pendaftaran -->
            <div class="card">
                <div class="card-header info-header">
                    <h3 style="margin: 0; font-size: 1.25rem;">Kartu Pendaftaran</h3>
                </div>
                <div class="card-body">
                    <div class="card-preview">
                        <div style="text-align: center; margin-bottom: 1rem;">
                            <h4 style="margin: 0; font-size: 1rem;">KARTU BUKTI PENDAFTARAN</h4>
                            <p style="margin: 0.25rem 0 0; font-size: 0.9rem;">PMB STBA Pontianak <?php echo date('Y'); ?></p>
                        </div>
                        
                        <div style="display: flex;">
                            <div style="width: 30%; padding-right: 0.75rem;">
                                <?php if (!empty($foto_path)) : ?>
                                    <img src="<?php echo esc_url($foto_path); ?>" alt="Foto Pendaftar" style="width: 100%; max-height: 100px; object-fit: cover; border-radius: 0.25rem;">
                                <?php else : ?>
                                    <div style="height: 100px; background-color: #e9ecef; border-radius: 0.25rem;"></div>
                                <?php endif; ?>
                            </div>
                            <div style="width: 70%;">
                                <p style="margin: 0 0 0.25rem; font-size: 0.8rem;">Nama: <strong><?php echo esc_html($nama_lengkap); ?></strong></p>
                                <p style="margin: 0 0 0.25rem; font-size: 0.8rem;">No. Pendaftaran: <strong><?php echo esc_html($nomor_pendaftaran); ?></strong></p>
                                <p style="margin: 0 0 0.25rem; font-size: 0.8rem;">Jurusan: <strong><?php echo esc_html($jurusan_display); ?></strong></p>
                                <p style="margin: 0; font-size: 0.8rem;">Status: <?php echo $status_badge; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div style="text-align: center; margin-top: 1rem;">
                        <a href="<?php echo admin_url('admin-post.php?action=pmb_print_card&user_id=' . $user_id); ?>" target="_blank" 
                           style="background-color: var(--info-color); color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none; display: inline-flex; align-items: center;">
                            <span class="dashicons dashicons-printer" style="margin-right: 5px;"></span> Cetak Kartu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Add smooth hover effects to buttons
    $('.btn, .document-link').hover(
        function() { $(this).css('transform', 'translateY(-2px)'); },
        function() { $(this).css('transform', 'translateY(0)'); }
    );
    
    // Make sure images load properly with fallbacks
    $('img').on('error', function() {
        $(this).attr('src', 'data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22286%22%20height%3D%22180%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20286%20180%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_17a3f093956%20text%20%7B%20fill%3A%23999%3Bfont-weight%3Anormal%3Bfont-family%3A-apple-system%2CBlinkMacSystemFont%2C%26quot%3BSegoe%20UI%26quot%3B%2CRoboto%2C%26quot%3BHelvetica%20Neue%26quot%3B%2CArial%2C%26quot%3BNoto%20Sans%26quot%3B%2Csans-serif%2C%26quot%3BApple%20Color%20Emoji%26quot%3B%2C%26quot%3BSegoe%20UI%20Emoji%26quot%3B%2C%26quot%3BSegoe%20UI%20Symbol%26quot%3B%2C%26quot%3BNoto%20Color%20Emoji%26quot%3B%2C%20monospace%3Bfont-size%3A14pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_17a3f093956%22%3E%3Crect%20width%3D%22286%22%20height%3D%22180%22%20fill%3D%22%23373940%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22107.1953125%22%20y%3D%2296.3%22%3ENo Image%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E');
    });
    
    // Improve form experience with focus styling
    $('.form-control').focus(function() {
        $(this).css('box-shadow', '0 0 0 0.2rem rgba(78, 115, 223, 0.25)');
    }).blur(function() {
        $(this).css('box-shadow', 'none');
    });
});
</script>