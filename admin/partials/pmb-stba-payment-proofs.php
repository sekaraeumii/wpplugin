<?php
// Check user capabilities
if (!current_user_can('manage_options')) {
    wp_die(__('Anda tidak memiliki izin untuk mengakses halaman ini.', 'pmb-stba'));
}

// Process payment verification if submitted
if (isset($_POST['action']) && $_POST['action'] === 'verify_payment') {
    if (isset($_POST['payment_nonce']) && wp_verify_nonce($_POST['payment_nonce'], 'pmb_payment_verify_nonce')) {
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        
        if ($user_id && in_array($status, ['verified', 'rejected'])) {
            update_user_meta($user_id, 'pmb_payment_status', $status);
            
            if ($status === 'verified') {
                // Add admin note
                $admin_note = isset($_POST['admin_note']) ? sanitize_textarea_field($_POST['admin_note']) : '';
                if (!empty($admin_note)) {
                    $payment_data = get_user_meta($user_id, 'pmb_payment_data', true);
                    if (is_array($payment_data)) {
                        $payment_data['admin_note'] = $admin_note;
                        $payment_data['verified_date'] = current_time('mysql');
                        update_user_meta($user_id, 'pmb_payment_data', $payment_data);
                    }
                }
                $message = 'verified';
            } else {
                $message = 'rejected';
            }
            
            // Redirect with message
            wp_redirect(add_query_arg('message', $message, admin_url('admin.php?page=pmb-stba-payment-proofs')));
            exit;
        }
    }
}

// Get users with payment data
$args = array(
    'meta_query' => array(
        array(
            'key' => 'pmb_payment_data',
            'compare' => 'EXISTS',
        )
    )
);
$users = get_users($args);

// Display messages
if (isset($_GET['message'])) {
    if ($_GET['message'] === 'verified') {
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Pembayaran berhasil diverifikasi.', 'pmb-stba') . '</p></div>';
    } elseif ($_GET['message'] === 'rejected') {
        echo '<div class="notice notice-warning is-dismissible"><p>' . __('Pembayaran ditolak.', 'pmb-stba') . '</p></div>';
    }
}
?>

<div class="wrap">
    <div class="container-fluid">
        <h1 class="h3 mb-4"><?php echo esc_html__('Bukti Pembayaran PMB', 'pmb-stba'); ?></h1>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title m-0"><?php _e('Daftar Bukti Pembayaran', 'pmb-stba'); ?></h5>
                </div>
                
                <div class="table-responsive">
                    <?php if (empty($users)) : ?>
                        <p><?php _e('Belum ada bukti pembayaran yang diunggah.', 'pmb-stba'); ?></p>
                    <?php else: ?>
                        <table class="table table-hover" id="payment-proofs-table">
                            <thead class="table-light">
                                <tr>
                                    <th><?php _e('No', 'pmb-stba'); ?></th>
                                    <th><?php _e('Nama Pendaftar', 'pmb-stba'); ?></th>
                                    <th><?php _e('No. Pendaftaran', 'pmb-stba'); ?></th>
                                    <th><?php _e('Tanggal Upload', 'pmb-stba'); ?></th>
                                    <th><?php _e('Jumlah', 'pmb-stba'); ?></th>
                                    <th><?php _e('Bank', 'pmb-stba'); ?></th>
                                    <th><?php _e('Status', 'pmb-stba'); ?></th>
                                    <th><?php _e('Aksi', 'pmb-stba'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $count = 1;
                                foreach ($users as $user):
                                    $payment_data = get_user_meta($user->ID, 'pmb_payment_data', true);
                                    $payment_status = get_user_meta($user->ID, 'pmb_payment_status', true);
                                    
                                    if (empty($payment_data) || !is_array($payment_data)) {
                                        continue;
                                    }
                                    
                                    $registration_data = get_user_meta($user->ID, 'pmb_registration_data', true);
                                    $nama_lengkap = !empty($registration_data['nama_lengkap']) ? $registration_data['nama_lengkap'] : $user->display_name;
                                    $nomor_pendaftaran = !empty($registration_data['nomor_pendaftaran']) ? $registration_data['nomor_pendaftaran'] : '-';
                                ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo esc_html($nama_lengkap); ?></td>
                                    <td><?php echo esc_html($nomor_pendaftaran); ?></td>
                                    <td><?php echo isset($payment_data['upload_date']) ? date_i18n('d M Y H:i', strtotime($payment_data['upload_date'])) : '-'; ?></td>
                                    <td>Rp <?php echo isset($payment_data['amount']) ? number_format((int)str_replace('.', '', $payment_data['amount']), 0, ',', '.') : '-'; ?></td>
                                    <td><?php echo isset($payment_data['payment_bank']) ? esc_html($payment_data['payment_bank']) : '-'; ?></td>
                                    <td>
                                        <?php
                                        switch ($payment_status) {
                                            case 'verified':
                                                echo '<span class="badge bg-success">Terverifikasi</span>';
                                                break;
                                            case 'rejected':
                                                echo '<span class="badge bg-danger">Ditolak</span>';
                                                break;
                                            default:
                                                echo '<span class="badge bg-warning">Menunggu</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo esc_url($payment_data['file_url']); ?>" class="btn btn-sm btn-secondary" target="_blank">
                                                <span class="dashicons dashicons-visibility" style="vertical-align: text-bottom;"></span> <?php _e('Lihat', 'pmb-stba'); ?>
                                            </a>
                                            
                                            <?php if ($payment_status !== 'verified' && $payment_status !== 'rejected'): ?>
                                                <button type="button" class="btn btn-sm btn-success verify-payment-btn" data-user-id="<?php echo $user->ID; ?>" data-nama="<?php echo esc_attr($nama_lengkap); ?>">
                                                    <span class="dashicons dashicons-yes" style="vertical-align: text-bottom;"></span> <?php _e('Terima', 'pmb-stba'); ?>
                                                </button>
                                                
                                                <button type="button" class="btn btn-sm btn-danger reject-payment-btn" data-user-id="<?php echo $user->ID; ?>" data-nama="<?php echo esc_attr($nama_lengkap); ?>">
                                                    <span class="dashicons dashicons-no" style="vertical-align: text-bottom;"></span> <?php _e('Tolak', 'pmb-stba'); ?>
                                                </button>
                                            <?php endif; ?>
                                            
                                            <a href="<?php echo esc_url(admin_url('admin.php?page=pmb-stba-user-details&user_id=' . $user->ID)); ?>" class="btn btn-sm btn-primary">
                                                <?php _e('Detail', 'pmb-stba'); ?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal verifikasi pembayaran dengan style Bootstrap -->
<div class="modal fade" id="verify-payment-modal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel"><?php _e('Verifikasi Pembayaran', 'pmb-stba'); ?></h5>
                <button type="button" class="btn-close close-modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <?php wp_nonce_field('pmb_payment_verify_nonce', 'payment_nonce'); ?>
                    <input type="hidden" name="action" value="verify_payment">
                    <input type="hidden" name="user_id" id="verify-user-id" value="">
                    <input type="hidden" name="status" value="verified">
                    
                    <p id="verify-payment-message"></p>
                    
                    <div class="mb-3">
                        <label for="admin_note" class="form-label"><b><?php _e('Catatan Admin (opsional)', 'pmb-stba'); ?></b></label>
                        <textarea name="admin_note" id="admin_note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal"><?php _e('Batal', 'pmb-stba'); ?></button>
                    <button type="submit" class="btn btn-success"><?php _e('Verifikasi', 'pmb-stba'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal tolak pembayaran dengan style Bootstrap -->
<div class="modal fade" id="reject-payment-modal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel"><?php _e('Tolak Pembayaran', 'pmb-stba'); ?></h5>
                <button type="button" class="btn-close close-modal" aria-label="Close"></button>
            </div>
            <form method="post" action="">
                <div class="modal-body">
                    <?php wp_nonce_field('pmb_payment_verify_nonce', 'payment_nonce'); ?>
                    <input type="hidden" name="action" value="verify_payment">
                    <input type="hidden" name="user_id" id="reject-user-id" value="">
                    <input type="hidden" name="status" value="rejected">
                    
                    <p id="reject-payment-message"></p>
                    
                    <div class="mb-3">
                        <label for="admin_note_reject" class="form-label"><b><?php _e('Alasan Penolakan', 'pmb-stba'); ?></b></label>
                        <textarea name="admin_note" id="admin_note_reject" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-modal"><?php _e('Batal', 'pmb-stba'); ?></button>
                    <button type="submit" class="btn btn-danger"><?php _e('Tolak Pembayaran', 'pmb-stba'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize DataTables
    if ($.fn.DataTable) {
        $('#payment-proofs-table').DataTable({
            "pageLength": 10,
            "order": [[3, 'desc']], // Sort by upload date
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Tidak ada data yang ditampilkan",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "zeroRecords": "Tidak ada data yang cocok",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    }
    
    // Handle verify payment button click
    $('.verify-payment-btn').on('click', function() {
        const userId = $(this).data('user-id');
        const nama = $(this).data('nama');
        
        $('#verify-user-id').val(userId);
        $('#verify-payment-message').html('<?php _e('Anda akan memverifikasi pembayaran untuk pendaftar:', 'pmb-stba'); ?> <strong>' + nama + '</strong>');
        
        $('#verify-payment-modal').modal('show');
    });
    
    // Handle reject payment button click
    $('.reject-payment-btn').on('click', function() {
        const userId = $(this).data('user-id');
        const nama = $(this).data('nama');
        
        $('#reject-user-id').val(userId);
        $('#reject-payment-message').html('<?php _e('Anda akan menolak pembayaran untuk pendaftar:', 'pmb-stba'); ?> <strong>' + nama + '</strong>');
        
        $('#reject-payment-modal').modal('show');
    });
    
    // Close modal
    $('.close-modal').on('click', function() {
        $('.modal').modal('hide');
    });
    
    // Fix layout issues
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.classList.remove('mb-4');
        card.classList.remove('shadow-sm');
        card.classList.remove('card');
        card.style.width = '100%';
        card.style.marginLeft = '0px';
        card.style.marginRight = '0px';
        card.style.marginTop = '0px';
        card.style.marginBottom = '20px';
        card.style.border = '1px solid rgba(0,0,0,.125)';
        card.style.borderRadius = '0';
    });
    
    const parents = document.querySelectorAll('.container-fluid');
    parents.forEach(parent => {
        parent.style.marginLeft = '0px';
        parent.style.marginRight = '0px';
        parent.style.marginTop = '0px';
        parent.style.marginBottom = '0px';
        parent.style.paddingLeft = '0px';
        parent.style.paddingRight = '0px';
        parent.style.paddingTop = '0px';
        parent.style.paddingBottom = '0px';
        parent.style.width = '100%';
        parent.style.maxWidth = '100%';
    });
    
    // Perbaikan untuk rows dan columns
    const rows = document.querySelectorAll('.row');
    rows.forEach(row => {
        row.style.marginLeft = '0px';
        row.style.marginRight = '0px';
        row.style.width = '100%';
    });
    
    const cols = document.querySelectorAll('[class*="col-"]');
    cols.forEach(col => {
        col.style.paddingLeft = '0px';
        col.style.paddingRight = '0px';
    });
});
</script>