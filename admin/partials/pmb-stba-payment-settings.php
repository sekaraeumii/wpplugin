<?php
// Ensure only admins can access
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

// Handle form submission
if (isset($_POST['save_payment_info'])) {
    // Validate nonce for security
    if (isset($_POST['pmb_payment_nonce']) && wp_verify_nonce($_POST['pmb_payment_nonce'], 'pmb_payment_action')) {
        
        // Update options
        update_option('pmb_payment_title', sanitize_text_field($_POST['pmb_payment_title']));
        update_option('pmb_payment_description', wp_kses_post($_POST['pmb_payment_description']));
        update_option('pmb_payment_amount', sanitize_text_field($_POST['pmb_payment_amount']));
        update_option('pmb_payment_page', intval($_POST['pmb_payment_page']));
        
        // Bank accounts
        $bank_accounts = array();
        if (isset($_POST['bank_name']) && is_array($_POST['bank_name'])) {
            for ($i = 0; $i < count($_POST['bank_name']); $i++) {
                if (!empty($_POST['bank_name'][$i])) {
                    $bank_account = array(
                        'bank_name' => sanitize_text_field($_POST['bank_name'][$i]),
                        'account_number' => sanitize_text_field($_POST['account_number'][$i]),
                        'account_name' => sanitize_text_field($_POST['account_name'][$i]),
                    );
                    
                    // Handle existing logo
                    if (isset($_POST['bank_logo_existing'][$i]) && !in_array($i, isset($_POST['bank_logo_remove']) ? $_POST['bank_logo_remove'] : array())) {
                        $bank_account['bank_logo'] = $_POST['bank_logo_existing'][$i];
                    }
                    
                    // Handle logo upload
                    if (isset($_FILES['bank_logo_file']['name'][$i]) && !empty($_FILES['bank_logo_file']['name'][$i])) {
                        // Check if upload directory exists, if not create it
                        $upload_dir = wp_upload_dir();
                        $bank_logos_dir = $upload_dir['basedir'] . '/bank-logos';
                        
                        if (!file_exists($bank_logos_dir)) {
                            wp_mkdir_p($bank_logos_dir);
                        }
                        
                        // Handle file upload
                        $file_name = 'bank-logo-' . sanitize_file_name($_POST['bank_name'][$i]) . '-' . time() . '.' . 
                                    pathinfo($_FILES['bank_logo_file']['name'][$i], PATHINFO_EXTENSION);
                        $file_tmp = $_FILES['bank_logo_file']['tmp_name'][$i];
                        $file_dest = $bank_logos_dir . '/' . $file_name;
                        
                        // Basic validation (you might want to add more thorough checks)
                        $allowed_types = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
                        $file_type = $_FILES['bank_logo_file']['type'][$i];
                        $file_size = $_FILES['bank_logo_file']['size'][$i];
                        
                        if (in_array($file_type, $allowed_types) && $file_size <= 500 * 1024) { // 500KB max
                            if (move_uploaded_file($file_tmp, $file_dest)) {
                                $bank_account['bank_logo'] = $upload_dir['baseurl'] . '/bank-logos/' . $file_name;
                            }
                        }
                    }
                    
                    $bank_accounts[] = $bank_account;
                }
            }
        }
        update_option('pmb_bank_accounts', $bank_accounts);
        
        // Show success message
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Pengaturan pembayaran berhasil disimpan.', 'pmb-stba') . '</p></div>';
    }
}

// Get saved options
$payment_title = get_option('pmb_payment_title', 'Informasi Pembayaran PMB');
$payment_description = get_option('pmb_payment_description', '');
$payment_amount = get_option('pmb_payment_amount', '250000');
$bank_accounts = get_option('pmb_bank_accounts', array());

// Add an empty bank if none exists
if (empty($bank_accounts)) {
    $bank_accounts[] = array(
        'bank_name' => '',
        'account_number' => '',
        'account_name' => '',
    );
}

// Add this to your admin settings page

?>

<div class="wrap">
    <h1><?php _e('Pengaturan Informasi Pembayaran', 'pmb-stba'); ?></h1>
    
    <div class="card">
        <div class="card-header">
            <h2><?php _e('Informasi Rekening Bank', 'pmb-stba'); ?></h2>
            <p><?php _e('Atur informasi rekening bank untuk ditampilkan kepada pendaftar.', 'pmb-stba'); ?></p>
        </div>
        <div class="card-body">
            <!-- Ubah tag form untuk support file upload -->
            <form method="post" action="" enctype="multipart/form-data">
                <?php wp_nonce_field('pmb_payment_action', 'pmb_payment_nonce'); ?>
                
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="pmb_payment_title"><?php _e('Judul Halaman', 'pmb-stba'); ?></label>
                            </th>
                            <td>
                                <input name="pmb_payment_title" type="text" id="pmb_payment_title" 
                                       value="<?php echo esc_attr($payment_title); ?>" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="pmb_payment_description"><?php _e('Deskripsi', 'pmb-stba'); ?></label>
                            </th>
                            <td>
                                <textarea name="pmb_payment_description" id="pmb_payment_description" 
                                          class="large-text" rows="5"><?php echo esc_textarea($payment_description); ?></textarea>
                                <p class="description">
                                    <?php _e('Berikan petunjuk atau informasi tambahan tentang pembayaran.', 'pmb-stba'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="pmb_payment_amount"><?php _e('Nominal Pembayaran', 'pmb-stba'); ?></label>
                            </th>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <span style="margin-right: 5px;">Rp</span>
                                    <input name="pmb_payment_amount" type="text" id="pmb_payment_amount" 
                                           value="<?php echo esc_attr($payment_amount); ?>" class="regular-text">
                                </div>
                                <p class="description">
                                    <?php _e('Contoh: 250000 (tanpa titik atau koma)', 'pmb-stba'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="pmb_payment_page"><?php _e('Halaman Pembayaran', 'pmb-stba'); ?></label>
                            </th>
                            <td>
                                <?php
                                wp_dropdown_pages(array(
                                    'name' => 'pmb_payment_page',
                                    'show_option_none' => __('-- Pilih Halaman --', 'pmb-stba'),
                                    'option_none_value' => '0',
                                    'selected' => get_option('pmb_payment_page'),
                                ));
                                ?>
                                <p class="description">
                                    <?php _e('Pilih halaman yang menampilkan shortcode [pmb_payment_info]', 'pmb-stba'); ?>
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <h3><?php _e('Daftar Rekening Bank', 'pmb-stba'); ?></h3>
                <div id="bank-accounts-container">
                    <?php foreach ($bank_accounts as $index => $bank) : ?>
                    <div class="bank-account-row" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd;">
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                                <?php _e('Nama Bank', 'pmb-stba'); ?>
                            </label>
                            <input type="text" name="bank_name[]" value="<?php echo esc_attr($bank['bank_name']); ?>" 
                                   class="regular-text" placeholder="contoh: Bank BCA">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                                <?php _e('Nomor Rekening', 'pmb-stba'); ?>
                            </label>
                            <input type="text" name="account_number[]" value="<?php echo esc_attr($bank['account_number']); ?>" 
                                   class="regular-text" placeholder="contoh: 1234-5678-90">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                                <?php _e('Nama Pemilik', 'pmb-stba'); ?>
                            </label>
                            <input type="text" name="account_name[]" value="<?php echo esc_attr($bank['account_name']); ?>" 
                                   class="regular-text" placeholder="contoh: STBA Malang">
                        </div>
                        <div style="margin-bottom: 10px;">
                            <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                                <?php _e('Logo Bank', 'pmb-stba'); ?>
                            </label>
                            <div class="bank-logo-upload-container">
                                <?php if (!empty($bank['bank_logo'])) : ?>
                                    <div class="bank-logo-preview" style="margin-bottom: 10px;">
                                        <img src="<?php echo esc_url($bank['bank_logo']); ?>" alt="<?php echo esc_attr($bank['bank_name']); ?>" 
                                             style="max-width: 100px; max-height: 40px;">
                                        <a href="#" class="remove-bank-logo" data-index="<?php echo $index; ?>">
                                            <?php _e('Hapus Logo', 'pmb-stba'); ?>
                                        </a>
                                        <input type="hidden" name="bank_logo_existing[<?php echo $index; ?>]" value="<?php echo esc_attr($bank['bank_logo']); ?>">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="bank_logo_file[<?php echo $index; ?>]" id="bank_logo_file_<?php echo $index; ?>" class="bank-logo-upload"
                                       accept="image/png,image/jpeg,image/jpg,image/gif">
                                <p class="description">
                                    <?php _e('Upload logo bank (format: JPG, PNG, GIF. Ukuran maks: 500KB)', 'pmb-stba'); ?>
                                </p>
                            </div>
                        </div>
                        <?php if ($index > 0) : ?>
                        <button type="button" class="button remove-bank" style="margin-top: 10px; color: #a00;">
                            <?php _e('Hapus Bank Ini', 'pmb-stba'); ?>
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="button" id="add-bank" class="button button-secondary" style="margin-bottom: 20px;">
                    <?php _e('+ Tambah Bank Lain', 'pmb-stba'); ?>
                </button>
                
                <p class="submit">
                    <input type="submit" name="save_payment_info" id="submit" class="button button-primary" 
                           value="<?php _e('Simpan Pengaturan', 'pmb-stba'); ?>">
                </p>
            </form>
        </div>
    </div>
</div>

<script>
(function($) {
    // Add new bank
    $('#add-bank').on('click', function() {
        // Get the number of existing banks to create a unique index
        const index = $('.bank-account-row').length;
        
        const bankRow = `
            <div class="bank-account-row" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd;">
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                        <?php _e('Nama Bank', 'pmb-stba'); ?>
                    </label>
                    <input type="text" name="bank_name[]" value="" class="regular-text" placeholder="contoh: Bank BCA">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                        <?php _e('Nomor Rekening', 'pmb-stba'); ?>
                    </label>
                    <input type="text" name="account_number[]" value="" class="regular-text" placeholder="contoh: 1234-5678-90">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                        <?php _e('Nama Pemilik', 'pmb-stba'); ?>
                    </label>
                    <input type="text" name="account_name[]" value="" class="regular-text" placeholder="contoh: STBA Malang">
                </div>
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">
                        <?php _e('Logo Bank', 'pmb-stba'); ?>
                    </label>
                    <div class="bank-logo-upload-container">
                        <input type="file" name="bank_logo_file[${index}]" id="bank_logo_file_${index}" class="bank-logo-upload"
                               accept="image/png,image/jpeg,image/jpg,image/gif">
                        <p class="description">
                            <?php _e('Upload logo bank (format: JPG, PNG, GIF. Ukuran maks: 500KB)', 'pmb-stba'); ?>
                        </p>
                    </div>
                </div>
                <button type="button" class="button remove-bank" style="margin-top: 10px; color: #a00;">
                    <?php _e('Hapus Bank Ini', 'pmb-stba'); ?>
                </button>
            </div>
        `;
        $('#bank-accounts-container').append(bankRow);
    });
    
    // Remove bank
    $(document).on('click', '.remove-bank', function() {
        $(this).closest('.bank-account-row').remove();
    });
    
    // Remove logo
    $(document).on('click', '.remove-bank-logo', function(e) {
        e.preventDefault();
        const container = $(this).parent();
        const fileInput = container.next('.bank-logo-upload');
        
        // Show file input
        container.hide();
        
        // Flag for deletion
        const index = $(this).data('index');
        $('<input>').attr({
            type: 'hidden',
            name: 'bank_logo_remove[]',
            value: index
        }).insertAfter(container);
    });
})(jQuery);
</script>