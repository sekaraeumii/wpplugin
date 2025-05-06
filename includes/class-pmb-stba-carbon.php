<?php
/**
 * Carbon Fields implementation for the PMB STBA plugin
 *
 * @link       https://techinspire.my.id
 * @since      1.0.0
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/includes
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Pmb_Stba_Carbon {

    public function __construct() {
        add_action('carbon_fields_register_fields', array($this, 'register_carbon_fields'));
        add_action('carbon_fields_fields_registered', array($this, 'import_page_ids_to_carbon_fields'));
        add_action('carbon_fields_theme_options_container_saved', array($this, 'update_homepage_schedule'));
        add_action('carbon_fields_theme_options_container_saved', array($this, 'add_widget_to_sidebar'));
        
        // Add these new hooks
        add_action('widgets_init', array($this, 'register_pmb_sidebar'));
        add_action('widgets_init', array($this, 'register_pmb_widgets'));
    }

    public function register_carbon_fields() {
        $this->create_shortcode_settings();
    }

    private function create_shortcode_settings() {
        Container::make('theme_options', __('PMB Settings', 'pmb-stba'))
            ->set_page_parent('pmb-stba')
            ->add_fields(array(
                Field::make('text', 'pmb_year', __('Tahun PMB', 'pmb-stba'))
                    ->set_default_value(date('Y')),
                Field::make('date', 'pmb_start_date', __('Tanggal Pembukaan', 'pmb-stba')),
                Field::make('date', 'pmb_end_date', __('Tanggal Penutupan', 'pmb-stba')),
                Field::make('select', 'pmb_status', __('Status PMB', 'pmb-stba'))
                    ->set_options(array(
                        'open' => __('Dibuka', 'pmb-stba'),
                        'closed' => __('Ditutup', 'pmb-stba')
                    ))
                    ->set_default_value('open'),
                
                // Jadwal Gelombang fields
                Field::make('separator', 'schedule_separator', __('Jadwal Gelombang', 'pmb-stba')),
                Field::make('text', 'pmb_wave1_start', __('Gelombang 1 - Mulai', 'pmb-stba'))
                    ->set_default_value('1 Februari'),
                Field::make('text', 'pmb_wave1_end', __('Gelombang 1 - Selesai', 'pmb-stba'))
                    ->set_default_value('31 Maret'),
                Field::make('text', 'pmb_wave2_start', __('Gelombang 2 - Mulai', 'pmb-stba'))
                    ->set_default_value('1 April'),
                Field::make('text', 'pmb_wave2_end', __('Gelombang 2 - Selesai', 'pmb-stba'))
                    ->set_default_value('31 Mei'),
                Field::make('text', 'pmb_wave3_start', __('Gelombang 3 - Mulai', 'pmb-stba'))
                    ->set_default_value('1 Juni'),
                Field::make('text', 'pmb_wave3_end', __('Gelombang 3 - Selesai', 'pmb-stba'))
                    ->set_default_value('31 Juli'),
                
                Field::make('separator', 'form_separator', __('Pengaturan Form', 'pmb-stba')),
                Field::make('text', 'pmb_home_page', __('Halaman Beranda', 'pmb-stba'))
                    ->set_help_text(__('ID atau slug halaman beranda', 'pmb-stba')),
                Field::make('text', 'pmb_login_page', __('Halaman Login', 'pmb-stba'))
                    ->set_help_text(__('ID atau slug halaman login', 'pmb-stba')),
                Field::make('text', 'pmb_user_registration_page', __('Halaman Registrasi Akun', 'pmb-stba'))
                    ->set_help_text(__('ID atau slug halaman registrasi akun', 'pmb-stba')),
                Field::make('text', 'pmb_registration_page', __('Halaman Pendaftaran PMB', 'pmb-stba'))
                    ->set_help_text(__('ID atau slug halaman pendaftaran PMB', 'pmb-stba')),
                Field::make('text', 'pmb_profile_page', __('Halaman Profil', 'pmb-stba'))
                    ->set_help_text(__('ID atau slug halaman profil', 'pmb-stba')),
                Field::make('text', 'pmb_payment_page', __('Halaman Pembayaran', 'pmb-stba'))
                    ->set_help_text(__('ID atau slug halaman pembayaran', 'pmb-stba')),
                Field::make('text', 'pmb_information_page', __('Halaman Informasi', 'pmb-stba'))
                    ->set_help_text(__('ID atau slug halaman informasi', 'pmb-stba')),
                Field::make('separator', 'sidebar_separator', __('Pengaturan Sidebar', 'pmb-stba')),
                Field::make('sidebar', 'pmb_navigation_sidebar', __('Sidebar Navigasi PMB', 'pmb-stba'))
                    ->set_help_text(__('Pilih atau buat sidebar untuk navigasi PMB', 'pmb-stba')),
            ));
    }

    /**
     * Import page IDs from WordPress options to Carbon Fields
     * 
     * This is needed because Carbon Fields might not be available during plugin activation
     */
    public function import_page_ids_to_carbon_fields() {
        $fields = array(
            'pmb_home_page',
            'pmb_login_page',
            'pmb_user_registration_page',
            'pmb_registration_page',
            'pmb_profile_page',
            'pmb_payment_page',
            'pmb_information_page'
        );

        foreach ($fields as $field) {
            $page_id = get_option($field);
            if ($page_id) {
                carbon_set_theme_option($field, $page_id);
                // Delete the temporary option after import
                delete_option($field);
            }
        }
    }

    /**
     * Update homepage content when PMB schedule settings are changed
     * 
     * @param int $container_id The container ID being saved
     */
    public function update_homepage_schedule($container_id) {
        try {
            // Ambil ID halaman beranda
            $homepage_id = carbon_get_theme_option('pmb_home_page');
            
            // Jika tidak ada ID, coba cari halaman berdasarkan slug
            if (empty($homepage_id)) {
                // Coba cari halaman dengan slug pmb-home
                $homepage = get_page_by_path('pmb-home');
                if ($homepage) {
                    $homepage_id = $homepage->ID;
                } else {
                    return; // Keluar jika tidak ada halaman yang ditemukan
                }
            }
            
            // Pastikan homepage_id ada dan valid
            if (empty($homepage_id) || !get_post($homepage_id)) {
                return;
            }
            
            // Get current year
            $year = carbon_get_theme_option('pmb_year');
            if (empty($year)) {
                $year = date('Y');
            }
            
            // Dapatkan nilai jadwal
            $wave1_start = carbon_get_theme_option('pmb_wave1_start');
            $wave1_end = carbon_get_theme_option('pmb_wave1_end');
            $wave2_start = carbon_get_theme_option('pmb_wave2_start');
            $wave2_end = carbon_get_theme_option('pmb_wave2_end');
            $wave3_start = carbon_get_theme_option('pmb_wave3_start');
            $wave3_end = carbon_get_theme_option('pmb_wave3_end');
            
            // Dapatkan konten halaman
            $homepage_post = get_post($homepage_id);
            if (!$homepage_post) {
                return;
            }
            
            $content = $homepage_post->post_content;
            
            // Log untuk debugging
            error_log('Original content: ' . substr($content, 0, 100) . '...');
            
            // Gunakan pola regex yang lebih fleksibel untuk menangkap blok konten gelombang
            $gelombang1_pattern = '/(GELOMBANG 1<\/h4>.*?<p[^>]*>.*?<strong>)(.*?)(<\/strong>)/is';
            $gelombang2_pattern = '/(GELOMBANG 2<\/h4>.*?<p[^>]*>.*?<strong>)(.*?)(<\/strong>)/is';
            $gelombang3_pattern = '/(GELOMBANG 3<\/h4>.*?<p[^>]*>.*?<strong>)(.*?)(<\/strong>)/is';
            
            // Format tanggal yang akan ditampilkan
            $wave1_text = $wave1_start . ' - ' . $wave1_end . ' ' . $year;
            $wave2_text = $wave2_start . ' - ' . $wave2_end . ' ' . $year;
            $wave3_text = $wave3_start . ' - ' . $wave3_end . ' ' . $year;
            
            // Lakukan penggantian dengan preg_replace
            $content = preg_replace($gelombang1_pattern, '$1' . $wave1_text . '$3', $content);
            $content = preg_replace($gelombang2_pattern, '$1' . $wave2_text . '$3', $content);
            $content = preg_replace($gelombang3_pattern, '$1' . $wave3_text . '$3', $content);
            
            // Log untuk debugging
            error_log('Modified content: ' . substr($content, 0, 100) . '...');
            
            // Update halaman
            $update_args = array(
                'ID' => $homepage_id,
                'post_content' => $content
            );
            
            $result = wp_update_post($update_args);
            
            // Log hasil
            if (is_wp_error($result)) {
                error_log('Error updating PMB homepage: ' . $result->get_error_message());
            } else {
                error_log('PMB homepage updated successfully');
            }
        } catch (Exception $e) {
            error_log('PMB STBA: Error in update_homepage_schedule - ' . $e->getMessage());
            return; // Hentikan eksekusi tetapi biarkan penyimpanan berhasil
        }
    }

    /**
     * Register PMB navigation sidebar
     */
    public function register_pmb_sidebar() {
        // Get sidebar ID from options
        $sidebar_id = carbon_get_theme_option('pmb_navigation_sidebar');
        
        if (!$sidebar_id || $sidebar_id === '0') {
            return;
        }
        
        // Register the navigation widget
        register_sidebar(array(
            'id' => $sidebar_id,
            'name' => __('PMB Navigation', 'pmb-stba'),
            'description' => __('Navigation sidebar for PMB', 'pmb-stba'),
            'before_widget' => '<div class="pmb-nav-widget">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="pmb-nav-title">',
            'after_title' => '</h4>',
        ));
    }

    /**
     * Register custom widgets
     */
    public function register_pmb_widgets() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pmb-navigation-widget.php';
        register_widget('PMB_Navigation_Widget');
    }

    /**
     * Programmatically add navigation widget to sidebar
     */
    public function add_widget_to_sidebar() {
        try {
            $sidebar_id = carbon_get_theme_option('pmb_navigation_sidebar');
            
            if (empty($sidebar_id)) {
                return;
            }
            
            // Check if sidebar exists
            $sidebars_widgets = get_option('sidebars_widgets', array());
            
            // Jika sidebar belum terdaftar, kita skip proses ini
            if (!isset($sidebars_widgets[$sidebar_id])) {
                return;
            }
            
            // Proses widget...
        } catch (Exception $e) {
            error_log('PMB STBA: Error in add_widget_to_sidebar - ' . $e->getMessage());
            return; // Hentikan eksekusi tetapi biarkan penyimpanan berhasil
        }
    }
}