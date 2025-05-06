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
 * @author     Sukardi <mrsukardi@gmail.com>
 */
class Pmb_Stba_Activator {

    /**
     * Create necessary pages for PMB STBA plugin.
     *
     * Create pages for login, registration, and PMB application form.
     *
     * @since    1.0.0
     */
    public static function activate() {
        self::create_pmb_pages();
    }

    /**
     * Create pages for PMB STBA plugin.
     *
     * @since    1.0.0
     */
    public static function create_pmb_pages() {
        // Check if Carbon Fields values exist and set defaults if they don't
        $wave1_start = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave1_start') : '';
        $wave1_end = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave1_end') : '';
        $wave2_start = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave2_start') : '';
        $wave2_end = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave2_end') : '';
        $wave3_start = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave3_start') : '';
        $wave3_end = function_exists('carbon_get_theme_option') ? carbon_get_theme_option('pmb_wave3_end') : '';
        
        // Use defaults if values are empty
        $wave1_start = !empty($wave1_start) ? $wave1_start : '10 Februari';
        $wave1_end = !empty($wave1_end) ? $wave1_end : '31 Maret';
        $wave2_start = !empty($wave2_start) ? $wave2_start : '1 April';
        $wave2_end = !empty($wave2_end) ? $wave2_end : '31 Mei';
        $wave3_start = !empty($wave3_start) ? $wave3_start : '1 Juni';
        $wave3_end = !empty($wave3_end) ? $wave3_end : '31 Juli';
        
        // Page definitions
        $pages = array(
            'pmb-home' => array(
                'title' => 'PMB STBA Home',
                'content' => '<!-- wp:cover {"url":"https://via.placeholder.com/1920x800/0056a3/ffffff","id":99999,"dimRatio":70,"overlayColor":"primary","minHeight":500,"contentPosition":"center center","align":"full"} -->
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
<!-- /wp:spacer -->',
                'option_name' => 'pmb_home_page'
            ),
            'pmb-login' => array(
                'title' => 'Login PMB',
                'content' => '<!-- wp:shortcode -->[pmb_login_form]<!-- /wp:shortcode -->',
                'option_name' => 'pmb_login_page'
            ),
            'pmb-register' => array(
                'title' => 'Register PMB',
                'content' => '<!-- wp:shortcode -->[pmb_user_registration_form]<!-- /wp:shortcode -->',
                'option_name' => 'pmb_user_registration_page'
            ),
            'pmb-application' => array(
                'title' => 'Formulir PMB',
                'content' => '<!-- wp:shortcode -->[pmb_registration_form]<!-- /wp:shortcode -->',
                'option_name' => 'pmb_registration_page'
            ),
            'pmb-profile' => array(
                'title' => 'Profil PMB',
                'content' => '<!-- wp:shortcode -->[pmb_profile]<!-- /wp:shortcode -->',
                'option_name' => 'pmb_profile_page'
            )
        );

        $home_page_id = 0;

        // Create each page if it doesn't exist
        foreach ($pages as $slug => $page_data) {
            // Check if the page exists by slug
            $existing_page = get_page_by_path($slug);

            if (!$existing_page) {
                // Create page
                $page_id = wp_insert_post(array(
                    'post_title' => $page_data['title'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $slug
                ));

                // Store page ID in options for Carbon Fields
                if ($page_id && !is_wp_error($page_id)) {
                    // Store in WordPress options temporarily until Carbon Fields is initialized
                    update_option($page_data['option_name'], $page_id);
                    
                    // Save home page ID for later use
                    if ($slug === 'pmb-home') {
                        $home_page_id = $page_id;
                    }
                }
            } else {
                // If page exists, make sure it has the correct content
                wp_update_post(array(
                    'ID' => $existing_page->ID,
                    'post_content' => $page_data['content'],
                ));
                
                // Store existing page ID in options
                update_option($page_data['option_name'], $existing_page->ID);
                
                // Save home page ID for later use
                if ($slug === 'pmb-home') {
                    $home_page_id = $existing_page->ID;
                }
            }
        }

        // Set the homepage as static front page
        if ($home_page_id > 0) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home_page_id);
        }
    }

    /**
     * Update homepage content when schedule settings change
     */
    public function update_homepage_schedule() {
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
            return;
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
        $wave1_pattern = '/<p class="has-text-align-center"><strong>(.+?) - (.+?) ' . $year . '<\/strong><\/p>/';
        $wave1_replacement = '<p class="has-text-align-center"><strong>' . $wave1_start . ' - ' . $wave1_end . ' ' . $year . '</strong></p>';
        $content = preg_replace($wave1_pattern, $wave1_replacement, $content, 1);
        
        // Update Wave 2
        $wave2_pattern = '/<p class="has-text-align-center"><strong>(.+?) - (.+?) ' . $year . '<\/strong><\/p>/';
        $wave2_replacement = '<p class="has-text-align-center"><strong>' . $wave2_start . ' - ' . $wave2_end . ' ' . $year . '</strong></p>';
        $content = preg_replace($wave2_pattern, $wave2_replacement, $content, 1);
        
        // Update Wave 3
        $wave3_pattern = '/<p class="has-text-align-center"><strong>(.+?) - (.+?) ' . $year . '<\/strong><\/p>/';
        $wave3_replacement = '<p class="has-text-align-center"><strong>' . $wave3_start . ' - ' . $wave3_end . ' ' . $year . '</strong></p>';
        $content = preg_replace($wave3_pattern, $wave3_replacement, $content, 1);
        
        // Update the post
        wp_update_post(array(
            'ID' => $home_page_id,
            'post_content' => $content
        ));
    }
}
