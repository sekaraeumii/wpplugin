<?php
// Check user capabilities
if (!current_user_can('manage_options')) {
    return;
}

// Add full width admin class for this specific page
add_filter('admin_body_class', 'pmb_admin_body_class');
function pmb_admin_body_class($classes) {
    $screen = get_current_screen();
    if (isset($screen->id) && strpos($screen->id, 'pmb-stba-schedules') !== false) {
        return $classes . ' pmb-full-width-admin';
    }
    return $classes;
}

// Process form submission
$message = '';
$message_type = '';

if (isset($_POST['pmb_save_schedules']) && check_admin_referer('pmb_schedules_nonce', 'pmb_schedules_nonce')) {
    // Wave 1
    $wave1_start = sanitize_text_field($_POST['wave1_start']);
    $wave1_end = sanitize_text_field($_POST['wave1_end']);
    
    // Wave 2
    $wave2_start = sanitize_text_field($_POST['wave2_start']);
    $wave2_end = sanitize_text_field($_POST['wave2_end']);
    
    // Wave 3
    $wave3_start = sanitize_text_field($_POST['wave3_start']);
    $wave3_end = sanitize_text_field($_POST['wave3_end']);
    
    // Save to options
    carbon_set_theme_option('pmb_wave1_start', $wave1_start);
    carbon_set_theme_option('pmb_wave1_end', $wave1_end);
    carbon_set_theme_option('pmb_wave2_start', $wave2_start);
    carbon_set_theme_option('pmb_wave2_end', $wave2_end);
    carbon_set_theme_option('pmb_wave3_start', $wave3_start);
    carbon_set_theme_option('pmb_wave3_end', $wave3_end);
    
    $message = 'Jadwal penerimaan berhasil disimpan.';
    $message_type = 'success';
}

// Get current values
$wave1_start = carbon_get_theme_option('pmb_wave1_start') ?: '1 Februari';
$wave1_end = carbon_get_theme_option('pmb_wave1_end') ?: '31 Maret';
$wave2_start = carbon_get_theme_option('pmb_wave2_start') ?: '1 April';
$wave2_end = carbon_get_theme_option('pmb_wave2_end') ?: '31 Mei';
$wave3_start = carbon_get_theme_option('pmb_wave3_start') ?: '1 Juni';
$wave3_end = carbon_get_theme_option('pmb_wave3_end') ?: '31 Juli';
?>

<div class="wrap pmb-admin-page">
    <div class="pmb-container ai-style-change-1">
        <h1 class="h3 mb-4"><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <?php echo esc_html($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="card shadow-sm ai-style-change-2">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title m-0">Jadwal Penerimaan Mahasiswa Baru</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Atur jadwal penerimaan mahasiswa baru yang akan ditampilkan di halaman utama PMB.
                </p>
                
                <form method="post" action="">
                    <?php wp_nonce_field('pmb_schedules_nonce', 'pmb_schedules_nonce'); ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Gelombang 1</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="wave1_start" class="form-label">Tanggal Mulai</label>
                                        <input type="text" class="form-control" id="wave1_start" name="wave1_start" value="<?php echo esc_attr($wave1_start); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="wave1_end" class="form-label">Tanggal Selesai</label>
                                        <input type="text" class="form-control" id="wave1_end" name="wave1_end" value="<?php echo esc_attr($wave1_end); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Gelombang 2</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="wave2_start" class="form-label">Tanggal Mulai</label>
                                        <input type="text" class="form-control" id="wave2_start" name="wave2_start" value="<?php echo esc_attr($wave2_start); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="wave2_end" class="form-label">Tanggal Selesai</label>
                                        <input type="text" class="form-control" id="wave2_end" name="wave2_end" value="<?php echo esc_attr($wave2_end); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="card-title">Gelombang 3</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="wave3_start" class="form-label">Tanggal Mulai</label>
                                        <input type="text" class="form-control" id="wave3_start" name="wave3_start" value="<?php echo esc_attr($wave3_start); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="wave3_end" class="form-label">Tanggal Selesai</label>
                                        <input type="text" class="form-control" id="wave3_end" name="wave3_end" value="<?php echo esc_attr($wave3_end); ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="pmb_save_schedules" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm mt-4 ai-style-change-4">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title m-0">Preview Jadwal</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">
                    Tampilan jadwal di halaman utama akan terlihat seperti ini:
                </p>
                
                <div class="bg-light p-4 rounded w-100">
                    <h2 class="text-center mb-4">JADWAL PENERIMAAN MAHASISWA BARU</h2>
                    
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <div class="col">
                            <div class="bg-white p-4 rounded text-center mb-3 h-100 shadow-sm">
                                <h4 style="color:#0056a3" class="mb-3">GELOMBANG 1</h4>
                                <p class="mb-0"><strong class="preview-wave1"><?php echo esc_html($wave1_start . ' - ' . $wave1_end . ' ' . date('Y')); ?></strong></p>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="bg-white p-4 rounded text-center mb-3 h-100 shadow-sm">
                                <h4 style="color:#0056a3" class="mb-3">GELOMBANG 2</h4>
                                <p class="mb-0"><strong class="preview-wave2"><?php echo esc_html($wave2_start . ' - ' . $wave2_end . ' ' . date('Y')); ?></strong></p>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="bg-white p-4 rounded text-center mb-3 h-100 shadow-sm">
                                <h4 style="color:#0056a3" class="mb-3">GELOMBANG 3</h4>
                                <p class="mb-0"><strong class="preview-wave3"><?php echo esc_html($wave3_start . ' - ' . $wave3_end . ' ' . date('Y')); ?></strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Full width admin styles */
.pmb-admin-page {
    margin: 0 !important;
    padding: 0 !important;
    max-width: 100% !important;
}

.pmb-admin-page .pmb-container {
    padding: 20px;
    max-width: 100%;
    width: 100%;
}

/* Kode CSS yang ditambahkan */
.pmb-container.ai-style-change-1 {
  text-align: center;
}

.card.shadow-sm.ai-style-change-2 {
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
  padding-left: 10px;
  padding-right: 10px;
  width: auto;
}

.card.shadow-sm.mt-4.ai-style-change-4 {
  margin-left: auto;
  margin-right: auto;
  max-width: 100%;
  padding-left: 10px;
  padding-right: 10px;
  width: auto;
}

/* Fix for WordPress admin */
.pmb-full-width-admin #wpcontent, 
.pmb-full-width-admin #wpbody-content {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

/* Card and UI styles */
.card {
    margin-bottom: 2rem;
    border: none;
}

.bg-white {
    transition: all 0.3s ease;
}

.bg-white:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15) !important;
}

@media (min-width: 768px) {
    .row-cols-md-3 > * {
        flex: 0 0 auto;
        width: 33.33333%;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Tambahkan class untuk tujuan styling
    $('.pmb-container').addClass('ai-style-change-1');
    $('.card.shadow-sm').first().addClass('ai-style-change-2');
    $('.card.shadow-sm.mt-4').addClass('ai-style-change-4');
    
    // Additional full-width styles for WordPress admin
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            #wpcontent, #wpbody-content {
                padding-left: 0 !important;
                max-width: 100% !important;
            }
            .wrap {
                margin: 0 !important;
                padding: 0 !important;
                max-width: 100% !important;
            }
            .auto-fold #wpcontent {
                padding-left: 0 !important;
            }
        `)
        .appendTo('head');
    
    // Function to update preview
    function updatePreview() {
        var year = <?php echo date('Y'); ?>;
        var wave1_start = $('#wave1_start').val();
        var wave1_end = $('#wave1_end').val();
        var wave2_start = $('#wave2_start').val();
        var wave2_end = $('#wave2_end').val();
        var wave3_start = $('#wave3_start').val();
        var wave3_end = $('#wave3_end').val();
        
        $('.preview-wave1').text(wave1_start + ' - ' + wave1_end + ' ' + year);
        $('.preview-wave2').text(wave2_start + ' - ' + wave2_end + ' ' + year);
        $('.preview-wave3').text(wave3_start + ' - ' + wave3_end + ' ' + year);
    }
    
    // Update preview when input changes
    $('input[name^="wave"]').on('input', updatePreview);
});
</script>