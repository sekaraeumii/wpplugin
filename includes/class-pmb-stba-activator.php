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
     * Create the PMB home page with a modern design
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
        
        // Get logo URL or use a placeholder
        $logo_url = get_option('_crb_ppdb_logo', 'https://via.placeholder.com/150');
        
        // Create a modern home page content with better styling
        $content = '<!-- wp:cover {"url":"' . esc_url(get_option('_crb_ppdb_background', '')) . '","dimRatio":80,"overlayColor":"primary","minHeight":100,"minHeightUnit":"vh","contentPosition":"center center","align":"full","style":{"spacing":{"padding":{"top":"2rem","right":"2rem","bottom":"2rem","left":"2rem"}}}} -->
<div class="wp-block-cover alignfull" style="padding-top:2rem;padding-right:2rem;padding-bottom:2rem;padding-left:2rem;min-height:100vh">
    <span aria-hidden="true" class="wp-block-cover__background has-primary-background-color has-background-dim-80 has-background-dim"></span>
    
    <!-- wp:group {"style":{"border":{"radius":"16px"},"spacing":{"padding":{"top":"3rem","right":"3rem","bottom":"3rem","left":"3rem"}}},"backgroundColor":"background","layout":{"type":"constrained","contentSize":"800px"}} -->
    <div class="wp-block-group has-background-background-color has-background" style="border-radius:16px;padding-top:3rem;padding-right:3rem;padding-bottom:3rem;padding-left:3rem">
        
        <!-- wp:image {"align":"center","width":150,"height":150,"scale":"cover","sizeSlug":"large","className":"is-style-rounded","style":{"border":{"radius":"50%","width":"6px"},"color":{"duotone":["#0693e3","#0693e3"]}}} -->
        <figure class="wp-block-image aligncenter size-large is-resized is-style-rounded" style="border-width:6px;border-radius:50%">
            <img src="' . esc_url($logo_url) . '" alt="Logo PMB" style="object-fit:cover;width:150px;height:150px"/>
        </figure>
        <!-- /wp:image -->
        
        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontWeight":"700","lineHeight":"1.2","fontSize":"2.5rem"},"spacing":{"margin":{"bottom":"1.5rem","top":"1rem"}}}} -->
        <h1 class="wp-block-heading has-text-align-center" style="margin-top:1rem;margin-bottom:1.5rem;font-size:2.5rem;font-weight:700;line-height:1.2">' . get_bloginfo('name') . '</h1>
        <!-- /wp:heading -->
        
        <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"2rem"}}}} -->
        <p class="has-text-align-center" style="margin-bottom:2rem">Penerimaan Mahasiswa Baru Tahun Akademik ' . date('Y') . '/' . (date('Y')+1) . '</p>
        <!-- /wp:paragraph -->
        
        <!-- wp:separator {"backgroundColor":"primary","className":"is-style-wide"} -->
        <hr class="wp-block-separator has-text-color has-primary-color has-alpha-channel-opacity has-primary-background-color has-background is-style-wide"/>
        <!-- /wp:separator -->
        
        <!-- wp:spacer {"height":"20px"} -->
        <div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->
        
        <!-- wp:columns -->
        <div class="wp-block-columns">
            <!-- wp:column -->
            <div class="wp-block-column">
                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                <div class="wp-block-buttons">
                    <!-- wp:button {"backgroundColor":"primary","textColor":"white","width":100,"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"0.9rem","bottom":"0.9rem"}},"typography":{"fontSize":"1.1rem","fontWeight":"600"}},"className":"is-style-fill"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-fill" style="font-size:1.1rem;font-weight:600"><a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button" style="border-radius:8px;padding-top:0.9rem;padding-bottom:0.9rem" href="/pmb-register">Daftar PMB</a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column -->
            <div class="wp-block-column">
                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                <div class="wp-block-buttons">
                    <!-- wp:button {"backgroundColor":"primary","textColor":"white","width":100,"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"0.9rem","bottom":"0.9rem"}},"typography":{"fontSize":"1.1rem","fontWeight":"600"}},"className":"is-style-fill"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-fill" style="font-size:1.1rem;font-weight:600"><a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button" style="border-radius:8px;padding-top:0.9rem;padding-bottom:0.9rem" href="/data-pendaftar">Data Pendaftar</a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
        
        <!-- wp:spacer {"height":"10px"} -->
        <div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->
        
        <!-- wp:columns -->
        <div class="wp-block-columns">
            <!-- wp:column -->
            <div class="wp-block-column">
                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                <div class="wp-block-buttons">
                    <!-- wp:button {"backgroundColor":"secondary","textColor":"white","width":100,"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"0.9rem","bottom":"0.9rem"}},"typography":{"fontSize":"1.1rem","fontWeight":"600"}},"className":"is-style-fill"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-fill" style="font-size:1.1rem;font-weight:600"><a class="wp-block-button__link has-white-color has-secondary-background-color has-text-color has-background wp-element-button" style="border-radius:8px;padding-top:0.9rem;padding-bottom:0.9rem" href="/pmb-login">Login Akun</a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column -->
            <div class="wp-block-column">
                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                <div class="wp-block-buttons">
                    <!-- wp:button {"backgroundColor":"secondary","textColor":"white","width":100,"style":{"border":{"radius":"8px"},"spacing":{"padding":{"top":"0.9rem","bottom":"0.9rem"}},"typography":{"fontSize":"1.1rem","fontWeight":"600"}},"className":"is-style-fill"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-fill" style="font-size:1.1rem;font-weight:600"><a class="wp-block-button__link has-white-color has-secondary-background-color has-text-color has-background wp-element-button" style="border-radius:8px;padding-top:0.9rem;padding-bottom:0.9rem" href="/hubungi-admin-pmb">WA Admin PMB</a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
        
        <!-- wp:spacer {"height":"10px"} -->
        <div style="height:10px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->
        
        <!-- wp:separator {"backgroundColor":"secondary","className":"is-style-dots"} -->
        <hr class="wp-block-separator has-text-color has-secondary-color has-alpha-channel-opacity has-secondary-background-color has-background is-style-dots"/>
        <!-- /wp:separator -->
        
        <!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"0.9rem"},"spacing":{"margin":{"top":"2rem"}}}} -->
        <p class="has-text-align-center" style="margin-top:2rem;font-size:0.9rem">© ' . date('Y') . ' ' . get_bloginfo('name') . ' | Sekolah Tinggi Bahasa Asing Malang</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:cover -->

<!-- wp:html -->
<style>
.wp-block-button__link {
    transition: all 0.3s ease !important;
}
.wp-block-button__link:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.wp-block-group {
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    transition: all 0.3s ease !important;
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
