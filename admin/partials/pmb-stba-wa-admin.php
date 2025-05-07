<?php

/**
 * Provide a admin area view for the WhatsApp Admin settings
 *
 * @link       https://techinspire.my.id
 * @since      1.0.0
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/admin/partials
 */

// Check if user has permissions
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Save settings if form is submitted
if (isset($_POST['pmb_stba_wa_admin_settings_submit'])) {
    // Check nonce for security
    if (!isset($_POST['pmb_stba_wa_admin_nonce']) || !wp_verify_nonce($_POST['pmb_stba_wa_admin_nonce'], 'pmb_stba_wa_admin_save')) {
        wp_die('Security check failed');
    }
    
    // Save Admin 1 settings
    update_option('pmb_stba_wa_no_1', sanitize_text_field($_POST['pmb_stba_wa_no_1']));
    update_option('pmb_stba_wa_nama_1', sanitize_text_field($_POST['pmb_stba_wa_nama_1']));
    update_option('pmb_stba_wa_pesan_1', sanitize_textarea_field($_POST['pmb_stba_wa_pesan_1']));
    
    // Save Admin 2 settings
    update_option('pmb_stba_wa_no_2', sanitize_text_field($_POST['pmb_stba_wa_no_2']));
    update_option('pmb_stba_wa_nama_2', sanitize_text_field($_POST['pmb_stba_wa_nama_2']));
    update_option('pmb_stba_wa_pesan_2', sanitize_textarea_field($_POST['pmb_stba_wa_pesan_2']));
    
    // Show success message
    echo '<div class="notice notice-success is-dismissible"><p>Pengaturan WhatsApp Admin berhasil disimpan.</p></div>';
}

// Get current settings
$no_wa_1 = get_option('pmb_stba_wa_no_1', '');
$nama_wa_1 = get_option('pmb_stba_wa_nama_1', '');
$pesan_wa_1 = get_option('pmb_stba_wa_pesan_1', 'Saya mau tanya tentang pendaftaran Mahasiswa baru.');

$no_wa_2 = get_option('pmb_stba_wa_no_2', '');
$nama_wa_2 = get_option('pmb_stba_wa_nama_2', '');
$pesan_wa_2 = get_option('pmb_stba_wa_pesan_2', 'Saya mau tanya tentang pendaftaran Mahasiswa baru.');

?>

<div class="wrap">
    <h1>Pengaturan WhatsApp Admin</h1>
    <p>Konfigurasi nomor WhatsApp admin untuk bantuan pendaftaran</p>

    <form method="post" action="">
        <?php wp_nonce_field('pmb_stba_wa_admin_save', 'pmb_stba_wa_admin_nonce'); ?>
        
        <div class="card" style="max-width: 800px; padding: 20px; margin-bottom: 20px;">
            <h2>Admin 1</h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="pmb_stba_wa_no_1">Nomor WhatsApp Admin 1</label></th>
                    <td>
                        <input name="pmb_stba_wa_no_1" type="number" id="pmb_stba_wa_no_1" 
                               value="<?php echo esc_attr($no_wa_1); ?>" class="regular-text">
                        <p class="description">Masukkan nomor WhatsApp dalam format internasional tanpa tanda + (contoh: 628123456789)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="pmb_stba_wa_nama_1">Nama Admin 1</label></th>
                    <td>
                        <input name="pmb_stba_wa_nama_1" type="text" id="pmb_stba_wa_nama_1" 
                               value="<?php echo esc_attr($nama_wa_1); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="pmb_stba_wa_pesan_1">Template Pesan Admin 1</label></th>
                    <td>
                        <textarea name="pmb_stba_wa_pesan_1" id="pmb_stba_wa_pesan_1" 
                                  rows="3" class="large-text"><?php echo esc_textarea($pesan_wa_1); ?></textarea>
                        <p class="description">Template pesan yang akan ditampilkan saat user menekan tombol hubungi admin</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="card" style="max-width: 800px; padding: 20px; margin-bottom: 20px;">
            <h2>Admin 2</h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="pmb_stba_wa_no_2">Nomor WhatsApp Admin 2</label></th>
                    <td>
                        <input name="pmb_stba_wa_no_2" type="number" id="pmb_stba_wa_no_2" 
                               value="<?php echo esc_attr($no_wa_2); ?>" class="regular-text">
                        <p class="description">Masukkan nomor WhatsApp dalam format internasional tanpa tanda + (contoh: 628123456789)</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="pmb_stba_wa_nama_2">Nama Admin 2</label></th>
                    <td>
                        <input name="pmb_stba_wa_nama_2" type="text" id="pmb_stba_wa_nama_2" 
                               value="<?php echo esc_attr($nama_wa_2); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="pmb_stba_wa_pesan_2">Template Pesan Admin 2</label></th>
                    <td>
                        <textarea name="pmb_stba_wa_pesan_2" id="pmb_stba_wa_pesan_2" 
                                  rows="3" class="large-text"><?php echo esc_textarea($pesan_wa_2); ?></textarea>
                        <p class="description">Template pesan yang akan ditampilkan saat user menekan tombol hubungi admin</p>
                    </td>
                </tr>
            </table>
        </div>
        
        <p class="submit">
            <input type="submit" name="pmb_stba_wa_admin_settings_submit" id="submit" class="button button-primary" value="Simpan Perubahan">
        </p>
    </form>
</div>