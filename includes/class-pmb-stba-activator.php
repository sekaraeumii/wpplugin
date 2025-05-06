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
        
        // Create the home page content
        $content = '<!-- wp:cover {"url":"https://via.placeholder.com/1920x800/0056a3/ffffff","id":99999,"dimRatio":70,"overlayColor":"primary","minHeight":500,"contentPosition":"center center","align":"full"} -->
<div class="wp-block-cover alignfull" style="min-height:500px"><span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-70 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"textColor":"white","fontSize":"x-large"} -->
<h1 class="has-text-align-center has-white-color has-text-color has-x-large-font-size">PENERIMAAN MAHASISWA BARU</h1>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"center","textColor":"white","fontSize":"large"} -->
<h2 class="has-text-align-center has-white-color has-text-color has-large-font-size">STBA PONTIANAK ' . date('Y') . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color">Jadilah Bagian dari Perguruan Tinggi Terbaik untuk Pendidikan Bahasa di Kalimantan Barat</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"vivid-cyan-blue","width":50,"className":"is-style-fill"} -->
<div class="wp-block-button has-custom-width wp-block-button__width-50 is-style-fill"><a class="wp-block-button__link has-vivid-cyan-blue-background-color has-background wp-element-button" href="/pmb-register">DAFTAR SEKARANG</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<!-- wp:spacer {"height":"40px"} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}},"backgroundColor":"pale-cyan-blue","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-pale-cyan-blue-background-color has-background" style="border-radius:8px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Pendaftaran Online</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Daftar secara online tanpa perlu datang ke kampus. Cukup isi formulir dan unggah dokumen yang diperlukan.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/pmb-register">Daftar Akun</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}},"backgroundColor":"pale-cyan-blue","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-pale-cyan-blue-background-color has-background" style="border-radius:8px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Sudah Memiliki Akun?</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Silakan login untuk melanjutkan pendaftaran atau melihat status pendaftaran Anda.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/pmb-login">Login</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"30px","right":"30px","bottom":"30px","left":"30px"}}},"backgroundColor":"pale-cyan-blue","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-pale-cyan-blue-background-color has-background" style="border-radius:8px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"textAlign":"center","level":3} -->
<h3 class="has-text-align-center">Informasi PMB</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Lihat informasi terbaru mengenai jadwal dan persyaratan penerimaan mahasiswa baru tahun ini.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="/informasi-pmb">Info Lengkap</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"40px"} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"50px","bottom":"50px","right":"30px","left":"30px"}},"border":{"radius":"8px"}},"backgroundColor":"light-gray","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide has-light-gray-background-color has-background" style="border-radius:8px;padding-top:50px;padding-right:30px;padding-bottom:50px;padding-left:30px"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">JADWAL PENERIMAAN MAHASISWA BARU</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:heading {"textAlign":"center","level":4,"style":{"color":{"text":"#0056a3"}}} -->
<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 1</h4>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><strong>' . $wave1_start . ' - ' . $wave1_end . ' ' . date('Y') . '</strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:heading {"textAlign":"center","level":4,"style":{"color":{"text":"#0056a3"}}} -->
<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 2</h4>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><strong>' . $wave2_start . ' - ' . $wave2_end . ' ' . date('Y') . '</strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}}},"backgroundColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-white-background-color has-background" style="border-radius:8px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:heading {"textAlign":"center","level":4,"style":{"color":{"text":"#0056a3"}}} -->
<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 3</h4>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><strong>' . $wave3_start . ' - ' . $wave3_end . ' ' . date('Y') . '</strong></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"vivid-cyan-blue"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-vivid-cyan-blue-background-color has-background wp-element-button" href="/pmb-register">DAFTAR SEKARANG</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:spacer {"height":"40px"} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"textAlign":"center"} -->
<h2 class="has-text-align-center">PROGRAM STUDI</h2>
<!-- /wp:heading -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px","width":"1px"},"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}}},"borderColor":"cyan-bluish-gray","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-cyan-bluish-gray-border-color" style="border-radius:8px;border-width:1px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:heading {"textAlign":"center","level":3,"style":{"color":{"text":"#0056a3"}}} -->
<h3 class="has-text-align-center has-text-color" style="color:#0056a3">Bahasa Inggris</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Program studi yang mempelajari bahasa Inggris secara mendalam, mencakup kemampuan berbicara, menulis, dan pemahaman budaya.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Info Lengkap</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"border":{"radius":"8px","width":"1px"},"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"20px"}}},"borderColor":"cyan-bluish-gray","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-border-color has-cyan-bluish-gray-border-color" style="border-radius:8px;border-width:1px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px"><!-- wp:heading {"textAlign":"center","level":3,"style":{"color":{"text":"#0056a3"}}} -->
<h3 class="has-text-align-center has-text-color" style="color:#0056a3">Bahasa Mandarin</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">Program studi yang mempelajari bahasa Mandarin modern, kemampuan berbicara, menulis, dan pemahaman budaya Tiongkok.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Info Lengkap</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:spacer {"height":"40px"} -->
<div style="height:40px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->';

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
        $wave1_pattern = '/<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 1<\/h4>[\s\S]*?<p class="has-text-align-center"><strong>([^<]*)<\/strong><\/p>/';
        $wave1_replacement = '<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 1</h4><!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><strong>' . $wave1_start . ' - ' . $wave1_end . ' ' . $year . '</strong></p>';
        $content = preg_replace($wave1_pattern, $wave1_replacement, $content);
        
        // Update Wave 2
        $wave2_pattern = '/<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 2<\/h4>[\s\S]*?<p class="has-text-align-center"><strong>([^<]*)<\/strong><\/p>/';
        $wave2_replacement = '<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 2</h4><!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><strong>' . $wave2_start . ' - ' . $wave2_end . ' ' . $year . '</strong></p>';
        $content = preg_replace($wave2_pattern, $wave2_replacement, $content);
        
        // Update Wave 3
        $wave3_pattern = '/<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 3<\/h4>[\s\S]*?<p class="has-text-align-center"><strong>([^<]*)<\/strong><\/p>/';
        $wave3_replacement = '<h4 class="has-text-align-center has-text-color" style="color:#0056a3">GELOMBANG 3</h4><!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><strong>' . $wave3_start . ' - ' . $wave3_end . ' ' . $year . '</strong></p>';
        $content = preg_replace($wave3_pattern, $wave3_replacement, $content);
        
        // Update the post
        wp_update_post(array(
            'ID' => $home_page_id,
            'post_content' => $content
        ));
    }
}
