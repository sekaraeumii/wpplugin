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

// Get user meta
$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
$jenis_kelamin = get_user_meta($user_id, 'jenis_kelamin', true);
$no_hp = get_user_meta($user_id, 'no_hp', true);
$alamat = get_user_meta($user_id, 'alamat', true);
$asal_sekolah = get_user_meta($user_id, 'asal_sekolah', true);
$jurusan = get_user_meta($user_id, 'jurusan_dipilih', true);
$status = get_user_meta($user_id, 'status_pmb', true);
$foto_url = get_user_meta($user_id, 'foto_url', true);
$ijazah_url = get_user_meta($user_id, 'ijazah_url', true);

// Format jenis kelamin
$jenis_kelamin = $jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';

// Status badge
switch ($status) {
    case 'approved':
        $status_badge = '<span class="badge bg-success">Diterima</span>';
        break;
    case 'rejected':
        $status_badge = '<span class="badge bg-danger">Ditolak</span>';
        break;
    default:
        $status_badge = '<span class="badge bg-warning">Menunggu</span>';
}
?>

<div class="wrap">
    <div class="container-fluid">
        <h1 class="h3 mb-4">
            <a href="<?php echo admin_url('admin.php?page=pmb-stba-registrations'); ?>" class="text-decoration-none">
                <span class="dashicons dashicons-arrow-left-alt"></span>
            </a>
            Detail Pendaftar
        </h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0">Data Pendaftar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Nama Lengkap:</strong> <?php echo esc_html($nama_lengkap); ?></p>
                                <p><strong>Email:</strong> <?php echo esc_html($user->user_email); ?></p>
                                <p><strong>Tempat, Tanggal Lahir:</strong> <?php echo esc_html($tempat_lahir . ', ' . $tanggal_lahir); ?></p>
                                <p><strong>Jenis Kelamin:</strong> <?php echo esc_html($jenis_kelamin); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>No. HP:</strong> <?php echo esc_html($no_hp); ?></p>
                                <p><strong>Alamat:</strong> <?php echo esc_html($alamat); ?></p>
                                <p><strong>Asal Sekolah:</strong> <?php echo esc_html($asal_sekolah); ?></p>
                                <p><strong>Jurusan Dipilih:</strong> <?php echo esc_html($jurusan); ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p><strong>Status:</strong> <?php echo $status_badge; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0">Kelola Pendaftaran</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                            <input type="hidden" name="action" value="pmb_update_status">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                            <?php wp_nonce_field('pmb_update_status_nonce', 'pmb_status_nonce'); ?>
                            
                            <div class="form-group mb-3">
                                <label for="status">Status Pendaftaran</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" <?php selected($status, 'pending'); ?>>Menunggu</option>
                                    <option value="approved" <?php selected($status, 'approved'); ?>>Diterima</option>
                                    <option value="rejected" <?php selected($status, 'rejected'); ?>>Ditolak</option>
                                </select>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="catatan">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" rows="3"><?php echo esc_textarea(get_user_meta($user_id, 'catatan_admin', true)); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0">Foto</h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($foto_url)) : ?>
                            <img src="<?php echo esc_url($foto_url); ?>" alt="Foto Pendaftar" class="img-fluid mb-2" style="max-height: 200px;">
                            <a href="<?php echo esc_url($foto_url); ?>" target="_blank" class="btn btn-sm btn-outline-secondary d-block">Lihat Foto</a>
                        <?php else : ?>
                            <div class="alert alert-warning">Foto belum diunggah</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title m-0">Dokumen</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Ijazah</h6>
                            <?php if (!empty($ijazah_url)) : ?>
                                <a href="<?php echo esc_url($ijazah_url); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Ijazah</a>
                            <?php else : ?>
                                <div class="alert alert-warning">Ijazah belum diunggah</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>