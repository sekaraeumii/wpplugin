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
                        <div class="table-responsive">
                            <table class="table table-hover" id="recent-registrations">
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

<script>
jQuery(document).ready(function($) {
    $('#recent-registrations').DataTable({
        "pageLength": 5,
        "ordering": false
    });
});
</script>