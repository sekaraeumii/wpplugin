<?php

/**
 * Fired during plugin activation
 *
 * @link       https://techinspire.my.id
 * @since      1.0.0
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/includes
 * @author     Tech Inspire <help.techinspire@gmail.com>
 */
class Pmb_Stba_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        self::create_required_pages();
    }

    /**
     * Create required pages for the plugin
     *
     * @since    1.0.0
     */
    private static function create_required_pages() {
        // Create payment page if it doesn't exist
        self::create_payment_page();
        
        // Create information page if it doesn't exist
        self::create_information_page();
        
        // Create home page (PMB landing page)
        self::create_home_page();
        
        // Create login page
        self::create_login_page();
        
        // Create user registration page
        self::create_user_registration_page();
        
        // Create PMB application form page
        self::create_registration_page();
        
        // Create profile page
        self::create_profile_page();
    }

    /**
     * Create the payment info page
     *
     * @since    1.0.0
     * @return int Page ID
     */
    private static function create_payment_page() {
        // Check if the payment page already exists
        $payment_page = get_option('pmb_payment_page');
        
        if (!empty($payment_page)) {
            $page = get_post($payment_page);
            if ($page && $page->post_status != 'trash') {
                return $payment_page; // Page already exists
            }
        }
        
        // Create the payment page
        $page_data = array(
            'post_title'    => 'Pembayaran Pendaftaran',
            'post_content'  => '[pmb_payment_info]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'pembayaran-pendaftaran',
            'comment_status' => 'closed'
        );
        
        // Insert the page into the database
        $page_id = wp_insert_post($page_data);
        
        if (!is_wp_error($page_id)) {
            // Save the page ID to options
            update_option('pmb_payment_page', $page_id);
            
            // Set default payment settings if they don't exist
            if (!get_option('pmb_payment_title')) {
                update_option('pmb_payment_title', 'Informasi Pembayaran PMB');
            }
            
            if (!get_option('pmb_payment_description')) {
                update_option('pmb_payment_description', 'Silakan melakukan pembayaran sesuai dengan informasi di bawah ini untuk menyelesaikan proses pendaftaran PMB.');
            }
            
            if (!get_option('pmb_payment_amount')) {
                update_option('pmb_payment_amount', '250000');
            }
            
            if (!get_option('pmb_bank_accounts')) {
                $default_bank = array(
                    array(
                        'bank_name' => 'Bank BCA',
                        'account_number' => '0123456789',
                        'account_name' => 'STBA Malang'
                    )
                );
                update_option('pmb_bank_accounts', $default_bank);
            }
        }
        
        return $page_id;
    }

    /**
     * Create the information page
     *
     * @since    1.0.0
     * @return int Page ID
     */
    private static function create_information_page() {
        // Check if the information page already exists
        $information_page = get_option('pmb_information_page');
        
        if (!empty($information_page)) {
            $page = get_post($information_page);
            if ($page && $page->post_status != 'trash') {
                return $information_page; // Page already exists
            }
        }
        
        // Create the information page
        $page_data = array(
            'post_title'    => 'Informasi Pendaftaran',
            'post_content'  => '[pmb_information]',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'informasi-pendaftaran',
            'comment_status' => 'closed'
        );
        
        // Insert the page into the database
        $page_id = wp_insert_post($page_data);
        
        if (!is_wp_error($page_id)) {
            // Save the page ID to options
            update_option('pmb_information_page', $page_id);
        }
        
        return $page_id;
    }
    
    /**
     * Create the PMB home page
     *
     * @since    1.0.0
     * @return int Page ID
     */
    private static function create_home_page() {
        // Check if the home page already exists
        $home_page = get_option('pmb_home_page');
        
        if (!empty($home_page)) {
            $page = get_post($home_page);
            if ($page && $page->post_status != 'trash') {
                return $home_page; // Page already exists
            }
        }
        
        // Get wave dates from Carbon Fields if available
        $wave1_start = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave1_start') : '';
        $wave1_end = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave1_end') : '';
        $wave2_start = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave2_start') : '';
        $wave2_end = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave2_end') : '';
        $wave3_start = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave3_start') : '';
        $wave3_end = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave3_end') : '';
        
        // Use defaults if values are empty
        $wave1_start = !empty($wave1_start) ? $wave1_start : '1 Februari';
        $wave1_end = !empty($wave1_end) ? $wave1_end : '31 Maret';
        $wave2_start = !empty($wave2_start) ? $wave2_start : '1 April';
        $wave2_end = !empty($wave2_end) ? $wave2_end : '31 Mei';
        $wave3_start = !empty($wave3_start) ? $wave3_start : '1 Juni';
        $wave3_end = !empty($wave3_end) ? $wave3_end : '31 Juli';
        
        // Create the home page content with Astra compatibility
        $content = '<!-- wp:cover {"url":"https://via.placeholder.com/1920x800/0056a3/ffffff","dimRatio":80,"overlayColor":"ast-global-color-0","minHeight":600,"contentPosition":"center center","align":"full","className":"ast-theme-cover"} -->
<div class="wp-block-cover alignfull ast-theme-cover" style="min-height:600px"><span aria-hidden="true" class="wp-block-cover__background has-ast-global-color-0-background-color has-background-dim-80 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"textColor":"white","fontSize":"xx-large","className":"has-ast-h1-font-size"} -->
<h1 class="wp-block-heading has-text-align-center has-white-color has-text-color has-xx-large-font-size has-ast-h1-font-size" style="margin-bottom:10px">PENERIMAAN MAHASISWA BARU</h1>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"center","textColor":"white","fontSize":"large","className":"has-ast-h2-font-size"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-large-font-size has-ast-h2-font-size" style="margin-top:0">STBA PONTIANAK ' . date('Y') . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white","className":"has-medium-font-size"} -->
<p class="has-text-align-center has-white-color has-text-color has-medium-font-size">Jadilah Bagian dari Perguruan Tinggi Terbaik untuk Pendidikan Bahasa di Kalimantan Barat</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center","orientation":"horizontal"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"ast-global-color-1","textColor":"white","className":"is-style-fill","fontSize":"normal"} -->
<div class="wp-block-button has-custom-font-size is-style-fill has-normal-font-size"><a class="wp-block-button__link has-white-color has-ast-global-color-1-background-color has-text-color has-background wp-element-button" href="/pmb-register" style="padding:14px 32px;border-radius:4px">DAFTAR SEKARANG</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}},"color":{"background":"#ffffff"}},"className":"ast-container","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull ast-container has-background" style="background-color:#ffffff;padding-top:80px;padding-bottom:80px"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"backgroundColor":"ast-global-color-5","className":"pmb-service-box"} -->
<div class="wp-block-column pmb-service-box has-ast-global-color-5-background-color has-background" style="padding:30px;border-radius:6px;box-shadow:0 5px 20px rgba(0,0,0,0.05);transition:transform 0.3s ease,box-shadow 0.3s ease"><!-- wp:image {"align":"center","width":60,"height":60,"scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-full is-resized is-style-rounded"><img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' width=\'40\' height=\'40\'%3E%3Cpath fill=\'%230066CC\' d=\'M18 4V3c0-.55-.45-1-1-1H5c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1h12c.55 0 1-.45 1-1V6h1v4H9c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h9c.55 0 1-.45 1-1V10c0-.55-.45-1-1-1h-1V4zM5 4h12v1H5V4zm12 17H9V11h8v10z\'/%3E%3C/svg%3E" alt="Pendaftaran Online" width="60" height="60" style="object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"color":{"text":"#0066cc"}},"className":"has-ast-h3-font-size"} -->
<h3 class="wp-block-heading has-text-align-center has-ast-h3-font-size" style="color:#0066cc">Pendaftaran Online</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Daftar secara online tanpa perlu datang ke kampus. Cukup isi formulir dan unggah dokumen yang diperlukan.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"ast-global-color-5","textColor":"ast-global-color-0","className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-ast-global-color-0-color has-ast-global-color-5-background-color has-text-color has-background wp-element-button" href="/pmb-register">Daftar Akun</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"backgroundColor":"ast-global-color-5","className":"pmb-service-box"} -->
<div class="wp-block-column pmb-service-box has-ast-global-color-5-background-color has-background" style="padding:30px;border-radius:6px;box-shadow:0 5px 20px rgba(0,0,0,0.05);transition:transform 0.3s ease,box-shadow 0.3s ease"><!-- wp:image {"align":"center","width":60,"height":60,"scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-full is-resized is-style-rounded"><img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' width=\'40\' height=\'40\'%3E%3Cpath fill=\'%230066CC\' d=\'M11 7L9.6 8.4l2.6 2.6H2v2h10.2l-2.6 2.6L11 17l5-5-5-5zm9 12h-8v2h8c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-8v2h8v14z\'/%3E%3C/svg%3E" alt="Login" width="60" height="60" style="object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"color":{"text":"#0066cc"}},"className":"has-ast-h3-font-size"} -->
<h3 class="wp-block-heading has-text-align-center has-ast-h3-font-size" style="color:#0066cc">Sudah Memiliki Akun?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Silakan login untuk melanjutkan pendaftaran atau melihat status pendaftaran Anda.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"ast-global-color-5","textColor":"ast-global-color-0","className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-ast-global-color-0-color has-ast-global-color-5-background-color has-text-color has-background wp-element-button" href="/pmb-login">Login</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"backgroundColor":"ast-global-color-5","className":"pmb-service-box"} -->
<div class="wp-block-column pmb-service-box has-ast-global-color-5-background-color has-background" style="padding:30px;border-radius:6px;box-shadow:0 5px 20px rgba(0,0,0,0.05);transition:transform 0.3s ease,box-shadow 0.3s ease"><!-- wp:image {"align":"center","width":60,"height":60,"scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-rounded"} -->
<figure class="wp-block-image aligncenter size-full is-resized is-style-rounded"><img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' width=\'40\' height=\'40\'%3E%3Cpath fill=\'%230066CC\' d=\'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z\'/%3E%3C/svg%3E" alt="Informasi" width="60" height="60" style="object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"color":{"text":"#0066cc"}},"className":"has-ast-h3-font-size"} -->
<h3 class="wp-block-heading has-text-align-center has-ast-h3-font-size" style="color:#0066cc">Informasi PMB</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Lihat informasi terbaru mengenai jadwal dan persyaratan penerimaan mahasiswa baru tahun ini.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"ast-global-color-5","textColor":"ast-global-color-0","className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-ast-global-color-0-color has-ast-global-color-5-background-color has-text-color has-background wp-element-button" href="/informasi-pendaftaran">Info Lengkap</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}},"color":{"background":"#f7f7f7"}},"className":"ast-container","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull ast-container has-background" style="background-color:#f7f7f7;padding-top:80px;padding-bottom:80px"><!-- wp:heading {"textAlign":"center","style":{"color":{"text":"#0066cc"},"typography":{"fontStyle":"normal","fontWeight":"600"}},"className":"has-ast-h2-font-size"} -->
<h2 class="wp-block-heading has-text-align-center has-ast-h2-font-size" style="color:#0066cc;font-style:normal;font-weight:600">JADWAL PENERIMAAN MAHASISWA BARU</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#666666"}}} -->
<p class="has-text-align-center" style="color:#666666">Pendaftaran dibuka dalam tiga gelombang dengan jadwal sebagai berikut.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"margin":{"top":"3rem"}}}} -->
<div class="wp-block-columns" style="margin-top:3rem"><!-- wp:column {"className":"pmb-schedule-box"} -->
<div class="wp-block-column pmb-schedule-box" style="border-radius:8px;overflow:hidden;transition:transform 0.3s ease,box-shadow 0.3s ease;box-shadow:0 5px 15px rgba(0,0,0,0.05)"><!-- wp:group {"style":{"color":{"background":"#0056a3","text":"#ffffff"},"spacing":{"padding":{"top":"15px","bottom":"15px"}}},"className":"pmb-schedule-header"} -->
<div class="wp-block-group pmb-schedule-header has-text-color has-background" style="color:#ffffff;background-color:#0056a3;padding-top:15px;padding-bottom:15px"><!-- wp:heading {"textAlign":"center","level":4,"style":{"typography":{"letterSpacing":"1px"}},"textColor":"white"} -->
<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 1</h4>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"white","textColor":"black","style":{"spacing":{"padding":{"top":"25px","bottom":"25px","left":"25px","right":"25px"}}}} -->
<div class="wp-block-group has-black-color has-white-background-color has-text-color has-background" style="padding-top:25px;padding-right:25px;padding-bottom:25px;padding-left:25px"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"600","fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px;font-weight:600">' . $wave1_start . ' - ' . $wave1_end . ' ' . date('Y') . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"},"color":{"text":"#666666"}}} -->
<p class="has-text-align-center has-text-color" style="color:#666666;font-size:14px">Daftar lebih awal untuk mendapat kesempatan terbaik</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"pmb-schedule-box"} -->
<div class="wp-block-column pmb-schedule-box" style="border-radius:8px;overflow:hidden;transition:transform 0.3s ease,box-shadow 0.3s ease;box-shadow:0 5px 15px rgba(0,0,0,0.05)"><!-- wp:group {"style":{"color":{"background":"#0066cc","text":"#ffffff"},"spacing":{"padding":{"top":"15px","bottom":"15px"}}},"className":"pmb-schedule-header"} -->
<div class="wp-block-group pmb-schedule-header has-text-color has-background" style="color:#ffffff;background-color:#0066cc;padding-top:15px;padding-bottom:15px"><!-- wp:heading {"textAlign":"center","level":4,"style":{"typography":{"letterSpacing":"1px"}},"textColor":"white"} -->
<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 2</h4>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"white","textColor":"black","style":{"spacing":{"padding":{"top":"25px","bottom":"25px","left":"25px","right":"25px"}}}} -->
<div class="wp-block-group has-black-color has-white-background-color has-text-color has-background" style="padding-top:25px;padding-right:25px;padding-bottom:25px;padding-left:25px"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"600","fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px;font-weight:600">' . $wave2_start . ' - ' . $wave2_end . ' ' . date('Y') . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"},"color":{"text":"#666666"}}} -->
<p class="has-text-align-center has-text-color" style="color:#666666;font-size:14px">Kesempatan kedua untuk mendaftar</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"pmb-schedule-box"} -->
<div class="wp-block-column pmb-schedule-box" style="border-radius:8px;overflow:hidden;transition:transform 0.3s ease,box-shadow 0.3s ease;box-shadow:0 5px 15px rgba(0,0,0,0.05)"><!-- wp:group {"style":{"color":{"background":"#0081ff","text":"#ffffff"},"spacing":{"padding":{"top":"15px","bottom":"15px"}}},"className":"pmb-schedule-header"} -->
<div class="wp-block-group pmb-schedule-header has-text-color has-background" style="color:#ffffff;background-color:#0081ff;padding-top:15px;padding-bottom:15px"><!-- wp:heading {"textAlign":"center","level":4,"style":{"typography":{"letterSpacing":"1px"}},"textColor":"white"} -->
<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 3</h4>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"white","textColor":"black","style":{"spacing":{"padding":{"top":"25px","bottom":"25px","left":"25px","right":"25px"}}}} -->
<div class="wp-block-group has-black-color has-white-background-color has-text-color has-background" style="padding-top:25px;padding-right:25px;padding-bottom:25px;padding-left:25px"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"600","fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px;font-weight:600">' . $wave3_start . ' - ' . $wave3_end . ' ' . date('Y') . '</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px"},"color":{"text":"#666666"}}} -->
<p class="has-text-align-center has-text-color" style="color:#666666;font-size:14px">Kesempatan terakhir untuk mendaftar</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"40px"}}}} -->
<div class="wp-block-buttons" style="margin-top:40px"><!-- wp:button {"backgroundColor":"ast-global-color-0","textColor":"white","style":{"border":{"radius":"4px"},"spacing":{"padding":{"top":"12px","right":"28px","bottom":"12px","left":"28px"}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-white-color has-ast-global-color-0-background-color has-text-color has-background wp-element-button" href="/pmb-register" style="border-radius:4px;padding-top:12px;padding-right:28px;padding-bottom:12px;padding-left:28px">DAFTAR SEKARANG</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}},"color":{"background":"#ffffff"}},"className":"ast-container","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull ast-container has-background" style="background-color:#ffffff;padding-top:80px;padding-bottom:80px"><!-- wp:heading {"textAlign":"center","style":{"color":{"text":"#0066cc"},"typography":{"fontWeight":"600"}},"className":"has-ast-h2-font-size"} -->
<h2 class="wp-block-heading has-text-align-center has-ast-h2-font-size" style="color:#0066cc;font-weight:600">PROGRAM STUDI</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#666666"}}} -->
<p class="has-text-align-center" style="color:#666666">Pilih program studi terbaik untuk masa depan karir Anda.</p>
<!-- /wp:paragraph -->

<!-- wp:columns {"style":{"spacing":{"margin":{"top":"3rem"}}}} -->
<div class="wp-block-columns" style="margin-top:3rem"><!-- wp:column {"className":"pmb-program-box"} -->
<div class="wp-block-column pmb-program-box" style="border:1px solid #e0e0e0;border-radius:8px;padding:30px;transition:transform 0.3s ease,box-shadow 0.3s ease"><!-- wp:image {"align":"center","width":80,"height":80,"scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"},"color":{"duotone":["#0066cc","#ffffff"]}}} -->
<figure class="wp-block-image aligncenter size-full is-resized has-custom-border" style="border-radius:50%"><img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' width=\'80\' height=\'80\'%3E%3Cpath fill=\'%23000000\' d=\'M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm0 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z\'/%3E%3C/svg%3E" alt="Bahasa Inggris" width="80" height="80" style="object-fit:cover;border-radius:50%"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"color":{"text":"#0066cc"}},"className":"has-ast-h3-font-size"} -->
<h3 class="wp-block-heading has-text-align-center has-ast-h3-font-size" style="color:#0066cc">Bahasa Inggris</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Program studi yang mempelajari bahasa Inggris secara mendalam, mencakup kemampuan berbicara, menulis, dan pemahaman budaya.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"ast-global-color-5","textColor":"ast-global-color-0","className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-ast-global-color-0-color has-ast-global-color-5-background-color has-text-color has-background wp-element-button" href="#">Info Lengkap</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"className":"pmb-program-box"} -->
<div class="wp-block-column pmb-program-box" style="border:1px solid #e0e0e0;border-radius:8px;padding:30px;transition:transform 0.3s ease,box-shadow 0.3s ease"><!-- wp:image {"align":"center","width":80,"height":80,"scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"},"color":{"duotone":["#0066cc","#ffffff"]}}} -->
<figure class="wp-block-image aligncenter size-full is-resized has-custom-border" style="border-radius:50%"><img src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' width=\'80\' height=\'80\'%3E%3Cpath fill=\'%23000000\' d=\'M12.87 15.07l-2.54-2.51.03-.03c1.74-1.94 2.98-4.17 3.71-6.53H17V4h-7V2H8v2H1v1.99h11.17C11.5 7.92 10.44 9.75 9 11.35 8.07 10.32 7.3 9.19 6.69 8h-2c.73 1.63 1.73 3.17 2.98 4.56l-5.09 5.02L4 19l5-5 3.11 3.11.76-2.04zM18.5 10h-2L12 22h2l1.12-3h4.75L21 22h2l-4.5-12zm-2.62 7l1.62-4.33L19.12 17h-3.24z\'/%3E%3C/svg%3E" alt="Bahasa Mandarin" width="80" height="80" style="object-fit:cover;border-radius:50%"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"textAlign":"center","level":3,"style":{"color":{"text":"#0066cc"}},"className":"has-ast-h3-font-size"} -->
<h3 class="wp-block-heading has-text-align-center has-ast-h3-font-size" style="color:#0066cc">Bahasa Mandarin</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Program studi yang mempelajari bahasa Mandarin modern, kemampuan berbicara, menulis, dan pemahaman budaya Tiongkok.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"ast-global-color-5","textColor":"ast-global-color-0","className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-ast-global-color-0-color has-ast-global-color-5-background-color has-text-color has-background wp-element-button" href="#">Info Lengkap</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"60px","bottom":"60px"}},"color":{"background":"#0056a3"}},"textColor":"white","className":"ast-cta-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull ast-cta-section has-white-color has-text-color has-background" style="background-color:#0056a3;padding-top:60px;padding-bottom:60px"><!-- wp:heading {"textAlign":"center","textColor":"white","className":"has-ast-h2-font-size"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color has-ast-h2-font-size">SIAP UNTUK MENDAFTAR?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">Mulai perjalanan akademik Anda bersama STBA Pontianak</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"20px"}}}} -->
<div class="wp-block-buttons" style="margin-top:20px"><!-- wp:button {"backgroundColor":"white","textColor":"ast-global-color-0","style":{"border":{"radius":"4px"},"spacing":{"padding":{"top":"12px","right":"28px","bottom":"12px","left":"28px"}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-ast-global-color-0-color has-white-background-color has-text-color has-background wp-element-button" href="/pmb-register" style="border-radius:4px;padding-top:12px;padding-right:28px;padding-bottom:12px;padding-left:28px">DAFTAR SEKARANG</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:html -->
<style>
/* Animasi hover untuk box layanan */
.pmb-service-box:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}

/* Animasi hover untuk box jadwal */
.pmb-schedule-box:hover {
    transform: translateY(-7px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}

/* Animasi hover untuk box program studi */
.pmb-program-box:hover {
    transform: translateY(-7px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

/* Responsivitas untuk layar kecil */
@media (max-width: 768px) {
    .pmb-service-box, .pmb-program-box {
        margin-bottom: 20px;
    }
    
    .pmb-schedule-box {
        margin-bottom: 20px;
    }
}
</style>
<!-- /wp:html -->';

    // Create the home page
    $page_data = array(
        'post_title'    => 'PMB STBA Home',
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_name'     => 'pmb-home',
        'comment_status' => 'closed'
    );
    
    // Insert the page into the database
    $page_id = wp_insert_post($page_data);
    
    if (!is_wp_error($page_id)) {
        // Save the page ID to options
        update_option('pmb_home_page', $page_id);
        
        // Set as homepage
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_id);
    }
    
    return $page_id;
}
    
    /**
     * Create the PMB login page
     *
     * @since    1.0.0
     * @return int Page ID
     */
    private static function create_login_page() {
        // Check if the login page already exists
        $login_page = get_option('pmb_login_page');
        
        if (!empty($login_page)) {
            $page = get_post($login_page);
            if ($page && $page->post_status != 'trash') {
                return $login_page; // Page already exists
            }
        }
        
        // Create the login page
        $page_data = array(
            'post_title'    => 'Login PMB',
            'post_content'  => '<!-- wp:shortcode -->[pmb_login_form]<!-- /wp:shortcode -->',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'pmb-login',
            'comment_status' => 'closed'
        );
        
        // Insert the page into the database
        $page_id = wp_insert_post($page_data);
        
        if (!is_wp_error($page_id)) {
            // Save the page ID to options
            update_option('pmb_login_page', $page_id);
        }
        
        return $page_id;
    }
    
    /**
     * Create the PMB user registration page
     *
     * @since    1.0.0
     * @return int Page ID
     */
    private static function create_user_registration_page() {
        // Check if the registration page already exists
        $registration_page = get_option('pmb_user_registration_page');
        
        if (!empty($registration_page)) {
            $page = get_post($registration_page);
            if ($page && $page->post_status != 'trash') {
                return $registration_page; // Page already exists
            }
        }
        
        // Create the registration page
        $page_data = array(
            'post_title'    => 'Register PMB',
            'post_content'  => '<!-- wp:shortcode -->[pmb_user_registration_form]<!-- /wp:shortcode -->',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'pmb-register',
            'comment_status' => 'closed'
        );
        
        // Insert the page into the database
        $page_id = wp_insert_post($page_data);
        
        if (!is_wp_error($page_id)) {
            // Save the page ID to options
            update_option('pmb_user_registration_page', $page_id);
        }
        
        return $page_id;
    }
    
    /**
     * Create the PMB registration form page
     *
     * @since    1.0.0
     * @return int Page ID
     */
    private static function create_registration_page() {
        // Check if the registration form page already exists
        $registration_form_page = get_option('pmb_registration_page');
        
        if (!empty($registration_form_page)) {
            $page = get_post($registration_form_page);
            if ($page && $page->post_status != 'trash') {
                return $registration_form_page; // Page already exists
            }
        }
        
        // Create the registration form page
        $page_data = array(
            'post_title'    => 'Formulir PMB',
            'post_content'  => '<!-- wp:shortcode -->[pmb_registration_form]<!-- /wp:shortcode -->',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'pmb-application',
            'comment_status' => 'closed'
        );
        
        // Insert the page into the database
        $page_id = wp_insert_post($page_data);
        
        if (!is_wp_error($page_id)) {
            // Save the page ID to options
            update_option('pmb_registration_page', $page_id);
        }
        
        return $page_id;
    }
    
    /**
     * Create the PMB profile page
     *
     * @since    1.0.0
     * @return int Page ID
     */
    private static function create_profile_page() {
        // Check if the profile page already exists
        $profile_page = get_option('pmb_profile_page');
        
        if (!empty($profile_page)) {
            $page = get_post($profile_page);
            if ($page && $page->post_status != 'trash') {
                return $profile_page; // Page already exists
            }
        }
        
        // Create the profile page
        $page_data = array(
            'post_title'    => 'Profil PMB',
            'post_content'  => '<!-- wp:shortcode -->[pmb_profile]<!-- /wp:shortcode -->',
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'pmb-profile',
            'comment_status' => 'closed'
        );
        
        // Insert the page into the database
        $page_id = wp_insert_post($page_data);
        
        if (!is_wp_error($page_id)) {
            // Save the page ID to options
            update_option('pmb_profile_page', $page_id);
        }
        
        return $page_id;
    }
    
    /**
     * Update homepage content when schedule settings change
     *
     * @since    1.0.0
     */
    public static function update_homepage_schedule() {
        if (!function_exists('carbon_get_theme_option')) {
            return;
        }
        
        $wave1_start = carbon_get_theme_option('pmb_wave1_start');
        $wave1_end = carbon_get_theme_option('pmb_wave1_end');
        $wave2_start = carbon_get_theme_option('pmb_wave2_start');
        $wave2_end = carbon_get_theme_option('pmb_wave2_end');
        $wave3_start = carbon_get_theme_option('pmb_wave3_start');
        $wave3_end = carbon_get_theme_option('pmb_wave3_end');
        
        if (!$wave1_start || !$wave1_end || !$wave2_start || !$wave2_end || !$wave3_start || !$wave3_end) {
            return;
        }
        
        $home_page_id = carbon_get_theme_option('pmb_home_page');
        if (!$home_page_id) {
            $home_page_id = get_option('pmb_home_page');
            if (!$home_page_id) {
                return;
            }
        }
        
        $home_page = get_post($home_page_id);
        if (!$home_page) {
            return;
        }
        
        // Get current content
        $content = $home_page->post_content;
        
        // Update content with new dates
        $year = date('Y');
        
        // Update Wave 1
        $wave1_pattern = '/<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 1<\/h4>[\s\S]*?<p class="has-text-align-center" style="font-size:18px;font-weight:600">([^<]*)<\/p>/';
        $wave1_replacement = '<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 1</h4><!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"white","textColor":"black","style":{"spacing":{"padding":{"top":"25px","bottom":"25px","left":"25px","right":"25px"}}}} -->
<div class="wp-block-group has-black-color has-white-background-color has-text-color has-background" style="padding-top:25px;padding-right:25px;padding-bottom:25px;padding-left:25px"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"600","fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px;font-weight:600">' . $wave1_start . ' - ' . $wave1_end . ' ' . $year . '</p>';
        $content = preg_replace($wave1_pattern, $wave1_replacement, $content);
        
        // Update Wave 2
        $wave2_pattern = '/<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 2<\/h4>[\s\S]*?<p class="has-text-align-center" style="font-size:18px;font-weight:600">([^<]*)<\/p>/';
        $wave2_replacement = '<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 2</h4><!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"white","textColor":"black","style":{"spacing":{"padding":{"top":"25px","bottom":"25px","left":"25px","right":"25px"}}}} -->
<div class="wp-block-group has-black-color has-white-background-color has-text-color has-background" style="padding-top:25px;padding-right:25px;padding-bottom:25px;padding-left:25px"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"600","fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px;font-weight:600">' . $wave2_start . ' - ' . $wave2_end . ' ' . $year . '</p>';
        $content = preg_replace($wave2_pattern, $wave2_replacement, $content);
        
        // Update Wave 3
        $wave3_pattern = '/<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 3<\/h4>[\s\S]*?<p class="has-text-align-center" style="font-size:18px;font-weight:600">([^<]*)<\/p>/';
        $wave3_replacement = '<h4 class="wp-block-heading has-text-align-center has-white-color has-text-color" style="letter-spacing:1px">GELOMBANG 3</h4><!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"backgroundColor":"white","textColor":"black","style":{"spacing":{"padding":{"top":"25px","bottom":"25px","left":"25px","right":"25px"}}}} -->
<div class="wp-block-group has-black-color has-white-background-color has-text-color has-background" style="padding-top:25px;padding-right:25px;padding-bottom:25px;padding-left:25px"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontWeight":"600","fontSize":"18px"}}} -->
<p class="has-text-align-center" style="font-size:18px;font-weight:600">' . $wave3_start . ' - ' . $wave3_end . ' ' . $year . '</p>';
        $content = preg_replace($wave3_pattern, $wave3_replacement, $content);
        
        // Update the post
        wp_update_post(array(
            'ID' => $home_page_id,
            'post_content' => $content
        ));
    }
}
