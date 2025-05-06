<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://techinspire.my.id
 * @since      1.0.0
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/includes
 * @author     Sukardi <mrsukardi@gmail.com>
 */
class Pmb_Stba {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pmb_Stba_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PMB_STBA_VERSION' ) ) {
			$this->version = PMB_STBA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pmb-stba';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pmb_Stba_Loader. Orchestrates the hooks of the plugin.
	 * - Pmb_Stba_i18n. Defines internationalization functionality.
	 * - Pmb_Stba_Admin. Defines all hooks for the admin area.
	 * - Pmb_Stba_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pmb-stba-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pmb-stba-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pmb-stba-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pmb-stba-public.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pmb-stba-carbon.php';

		$this->loader = new Pmb_Stba_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pmb_Stba_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pmb_Stba_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Pmb_Stba_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_menu_pages');
		
		// Handle admin-post actions
		$this->loader->add_action('admin_post_pmb_update_status', $plugin_admin, 'handle_status_update');
		$this->loader->add_action('admin_post_pmb_export_excel', $plugin_admin, 'export_excel');
		$this->loader->add_action('admin_post_pmb_print_card', $plugin_admin, 'print_pmb_card');

		// Inisialisasi Carbon Fields
		new Pmb_Stba_Carbon();

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pmb_Stba_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action('init', $plugin_public, 'register_shortcodes');
		$this->loader->add_action('init', $plugin_public, 'process_user_registration');
		$this->loader->add_action('init', $plugin_public, 'process_registration');
		$this->loader->add_action('init', $plugin_public, 'process_login');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pmb_Stba_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	// Tambahkan ini setelah proses upload dokumen berhasil, sebelum redirect atau return
	public function handle_document_upload() {
		// kode yang sudah ada...
		
		// Proses upload dokumen
		if (!empty($_FILES['document_file'])) {
			// KODE UPLOAD YANG SUDAH ADA
			
			// Setelah proses upload selesai, periksa kelengkapan dokumen
			$user_id = get_current_user_id();
			$document_type = sanitize_text_field($_POST['document_type']);
			
			// Update path file dokumen sesuai tipenya
			if ($document_type === 'ijazah') {
				update_user_meta($user_id, 'ijazah_path', $upload_path);
			} elseif ($document_type === 'foto') {
				update_user_meta($user_id, 'foto_path', $upload_path);
			}
			
			// Cek apakah dokumen wajib sudah diupload
			$ijazah_path = get_user_meta($user_id, 'ijazah_path', true);
			$foto_path = get_user_meta($user_id, 'foto_path', true);
			
			// Jika dokumen wajib sudah terupload, update status dokumen
			if (!empty($ijazah_path) && !empty($foto_path)) {
				update_user_meta($user_id, 'pmb_document_status', 'waiting');
			}
		}
		
		// kode redirect/return yang sudah ada...
	}
}
