<div class="wrap">
    <div class="container-fluid">
        <h1 class="h3 mb-4"><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="row">
            <!-- Card Total Pendaftar -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Total Pendaftar</h5>
                        <h2 class="card-text fw-bold">
                            <?php 
                            $total_users = count_users();
                            echo $total_users['total_users']; 
                            ?>
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Card Pendaftar Baru -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Pendaftar Baru</h5>
                        <h2 class="card-text fw-bold">
                            <?php 
                            $args = array(
                                'date_query' => array(
                                    array('after' => '1 day ago')
                                )
                            );
                            $recent_users = new WP_User_Query($args);
                            echo $recent_users->get_total();
                            ?>
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Card Status Pendaftaran -->
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Status Pendaftaran</h5>
                        <p class="card-text">
                            <?php echo '<span class="badge bg-success">Dibuka</span>'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Pendaftar Terbaru</h5>
                        <div class="table-responsive w-100">
                            <table class="table table-hover w-100" id="recent-registrations">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $recent_users = get_users(array(
                                        'number' => 5,
                                        'orderby' => 'registered',
                                        'order' => 'DESC'
                                    ));

                                    foreach($recent_users as $user) {
                                        echo '<tr>';
                                        echo '<td>' . esc_html($user->display_name) . '</td>';
                                        echo '<td>' . esc_html($user->user_email) . '</td>';
                                        echo '<td>' . esc_html($user->user_registered) . '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset CSS untuk DataTables */
#recent-registrations, 
#recent-registrations_wrapper {
    width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* Penanganan untuk struktur tabel dan wrapper */
.dataTables_wrapper .dataTables_scroll,
.dataTables_wrapper .dataTables_scrollBody,
.dataTables_wrapper .dataTables_scrollHead {
    width: 100% !important;
}

/* Struktur row dan cell */
.dataTables_wrapper .row {
    width: 100% !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    display: flex;
    flex-wrap: wrap;
}

.dataTables_wrapper .row > div {
    box-sizing: border-box;
}

/* Pastikan header juga full width */
.dataTable thead th {
    width: auto; /* Biarkan browser menanganinya */
}

/* Fitur-fitur DataTables */
div.dataTables_wrapper div.dataTables_length,
div.dataTables_wrapper div.dataTables_filter,
div.dataTables_wrapper div.dataTables_info,
div.dataTables_wrapper div.dataTables_paginate {
    padding-top: 0.755em;
}

/* Spesifik untuk tabel */
table.dataTable {
    width: 100% !important;
    margin: 0 !important;
    border-collapse: collapse !important;
}

/* Perbaikan untuk filter dan pagination */
.dataTables_filter {
    text-align: right !important;
    float: right !important;
}

.dataTables_paginate {
    float: right !important;
}

/* Override setiap selektor tabel dengan !important */
.table-responsive {
    width: 100% !important;
    overflow-x: visible !important;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Fungsi untuk memastikan lebar sudah benar sebelum inisialisasi
    function ensureWidth() {
        $('#recent-registrations, #recent-registrations_wrapper').css({
            'width': '100%',
            'max-width': '100%'
        });
    }
    
    // Panggil sebelum inisialisasi
    ensureWidth();
    
    // Destroy existing instance jika ada
    if ($.fn.DataTable.isDataTable('#recent-registrations')) {
        $('#recent-registrations').DataTable().destroy();
    }
    
    // Inisialisasi dengan pengaturan yang disederhanakan
    var table = $('#recent-registrations').DataTable({
        "pageLength": 5,
        "responsive": true,
        "autoWidth": false, // Tetapkan ke false
        "columns": [
            null, // Biarkan browser mengatur lebar
            null,
            null
        ],
        "initComplete": function(settings, json) {
            // Jalankan setelah inisialisasi selesai
            ensureWidth();
            $(window).trigger('resize');
            
            // Trik tambahan untuk memaksa lebar
            setTimeout(function() {
                ensureWidth();
                table.columns.adjust().draw();
            }, 200);
        }
    });
    
    // Event handler untuk resize window
    $(window).on('resize', function() {
        ensureWidth();
        table.columns.adjust().draw();
    });
});
</script>
