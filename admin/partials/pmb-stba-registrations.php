<?php
// Check user capabilities
if (!current_user_can('manage_options')) {
    return;
}
?>

<div class="wrap">
    <div class="container-fluid">
        <h1 class="h3 mb-4"><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title m-0">Data Pendaftar PMB</h5>
                    <a href="<?php echo admin_url('admin-post.php?action=pmb_export_excel'); ?>" class="btn btn-success">
                        <i class="dashicons dashicons-download" style="vertical-align: text-bottom;"></i> 
                        Download Excel
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover" id="registrations-table">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. HP</th>
                                <th>Asal Sekolah</th>
                                <th>Jurusan</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get subscribers role users
                            $args = array(
                                'role' => 'subscriber',
                                'orderby' => 'registered',
                                'order' => 'DESC'
                            );
                            $users = get_users($args);
                            
                            foreach ($users as $user) {
                                $user_id = $user->ID;
                                $nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
                                $no_hp = get_user_meta($user_id, 'no_hp', true);
                                $asal_sekolah = get_user_meta($user_id, 'asal_sekolah', true);
                                $jurusan = get_user_meta($user_id, 'jurusan_dipilih', true);
                                $status = get_user_meta($user_id, 'status_pmb', true);
                                
                                // Only show users who have started filling out the form
                                if (!empty($nama_lengkap)) {
                                    // Set default status if empty
                                    if (empty($status)) {
                                        $status = 'pending';
                                    }
                                    
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
                                    
                                    echo '<tr>';
                                    echo '<td>' . esc_html($user_id) . '</td>';
                                    echo '<td>' . esc_html($nama_lengkap) . '</td>';
                                    echo '<td>' . esc_html($user->user_email) . '</td>';
                                    echo '<td>' . esc_html($no_hp) . '</td>';
                                    echo '<td>' . esc_html($asal_sekolah) . '</td>';
                                    echo '<td>' . esc_html($jurusan) . '</td>';
                                    echo '<td>' . date_i18n('d M Y', strtotime($user->user_registered)) . '</td>';
                                    echo '<td>' . $status_badge . '</td>';
                                    echo '<td>';
                                    echo '<div class="btn-group" role="group">';
                                    echo '<a href="' . admin_url('admin.php?page=pmb-stba-user-details&user_id=' . $user_id) . '" class="btn btn-sm btn-primary">Detail</a>';
                                    echo '<button class="btn btn-sm btn-success update-status" data-user-id="' . $user_id . '" data-status="approved">Terima</button>';
                                    echo '<button class="btn btn-sm btn-danger update-status" data-user-id="' . $user_id . '" data-status="rejected">Tolak</button>';
                                    // Removed the delete button
                                    echo '</div>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="update-status-form" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Konfirmasi Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="status-confirm-message">Apakah Anda yakin ingin mengubah status pendaftaran?</p>
                    <input type="hidden" name="user_id" id="status-user-id">
                    <input type="hidden" name="status" id="status-value">
                    <?php wp_nonce_field('pmb_update_status_nonce', 'pmb_status_nonce'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="pmb_update_status" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Initialize DataTable with export buttons
    $('#registrations-table').DataTable({
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data yang ditampilkan",
            "infoFiltered": "(disaring dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
    
    // Status update button handler
    $('.update-status').click(function(e) {
        e.preventDefault();
        
        var userId = $(this).data('user-id');
        var status = $(this).data('status');
        var statusText = status === 'approved' ? 'menerima' : 'menolak';
        
        $('#status-confirm-message').text('Apakah Anda yakin ingin ' + statusText + ' pendaftaran ini?');
        $('#status-user-id').val(userId);
        $('#status-value').val(status);
        
        $('#statusModal').modal('show');
    });
});
</script>

<script>
jQuery(document).ready(function($) {
    // Aplikasikan solusi yang Anda temukan dengan F12
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