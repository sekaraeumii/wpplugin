<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://techinspire.my.id
 * @since      1.0.0
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/public
 * @author     Sukardi <mrsukardi@gmail.com>
 */
class Pmb_Stba_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
    
    // Add hooks for redirecting users
    add_action('template_redirect', array($this, 'redirect_registered_users'));
    
    // Add hooks for processing forms
    add_action('init', array($this, 'process_registration'));
}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pmb_Stba_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pmb_Stba_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pmb-stba-public.css', array(), $this->version, 'all' );
		wp_enqueue_style($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . 'datatables', plugin_dir_url(__FILE__) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all');


	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pmb_Stba_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pmb_Stba_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pmb-stba-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . 'datatables', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array('jquery'), $this->version, false);
		wp_enqueue_style('dashicons');

	}

	/**
	 * Register shortcodes for the plugin.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {
		add_shortcode('pmb_registration_form', array($this, 'registration_form_shortcode'));
		add_shortcode('pmb_login_form', array($this, 'login_form_shortcode'));
		add_shortcode('pmb_user_registration_form', array($this, 'user_registration_form_shortcode'));
		add_shortcode('pmb_profile', array($this, 'profile_shortcode'));
	}

	/**
	 * Shortcode for the registration form.
	 *
	 * @since    1.0.0
	 * @param    array    $atts    Shortcode attributes.
	 * @return   string            HTML content of the registration form.
	 */
	public function registration_form_shortcode($atts) {
		// If user is not logged in, we'll handle this in the template
		if (is_user_logged_in()) {
			$current_user = wp_get_current_user();
			$user_id = $current_user->ID;
			
			// Check if user has already submitted registration data
			$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
			$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
			
			if (!empty($tempat_lahir) && !empty($tanggal_lahir)) {
				// User has already submitted their data, redirect to profile
				$profile_page_id = carbon_get_theme_option('pmb_profile_page');
				if ($profile_page_id) {
					wp_redirect(get_permalink($profile_page_id));
					exit;
				}
			}
		}
		
		ob_start();
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-registration-form.php';
		return ob_get_clean();
	}

	/**
	 * Shortcode for the login form.
	 *
	 * @since    1.0.0
	 * @param    array    $atts    Shortcode attributes.
	 * @return   string            HTML content of the login form.
	 */
	public function login_form_shortcode($atts) {
		ob_start();
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-login-form.php';
		return ob_get_clean();
	}

	/**
	 * Shortcode for the user registration form.
	 *
	 * @since    1.0.0
	 * @param    array    $atts    Shortcode attributes.
	 * @return   string            HTML content of the user registration form.
	 */
	public function user_registration_form_shortcode($atts) {
		// If user is already logged in, redirect to profile page
		if (is_user_logged_in()) {
			$profile_page_id = carbon_get_theme_option('pmb_profile_page');
			if ($profile_page_id) {
				wp_redirect(get_permalink($profile_page_id));
				exit;
			}
		}
		
		ob_start();
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-user-registration-form.php';
		return ob_get_clean();
	}

	/**
	 * Shortcode for the profile.
	 *
	 * @since    1.0.0
	 * @param    array    $atts    Shortcode attributes.
	 * @return   string            HTML content of the profile.
	 */
	public function profile_shortcode($atts) {
		if (!is_user_logged_in()) {
			return '<div class="alert alert-warning">Anda harus login untuk melihat profil. <a href="' . get_permalink(carbon_get_theme_option('pmb_login_page')) . '">Login</a></div>';
		}
		
		ob_start();
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-profile.php';
		return ob_get_clean();
	}

	/**
	 * Process the registration form submission.
	 *
	 * @since    1.0.0
	 */
	public function process_registration() {
		if (!isset($_POST['pmb_register']) || !isset($_POST['pmb_nonce']) || !wp_verify_nonce($_POST['pmb_nonce'], 'pmb_registration_nonce')) {
			return;
		}
		
		// Check if user is logged in
		if (!is_user_logged_in()) {
			wp_redirect(home_url());
			exit;
		}
		
		$user_id = get_current_user_id();
		
		// Process file uploads
		$upload_dir = wp_upload_dir();
		$pmb_upload_dir = $upload_dir['basedir'] . '/pmb-uploads/' . $user_id;
		
		// Create upload directory if it doesn't exist
		if (!file_exists($pmb_upload_dir)) {
			wp_mkdir_p($pmb_upload_dir);
		}
		
		// Process photo upload
		$foto_file = $_FILES['foto'];
		$foto_path = '';
		if ($foto_file['error'] === 0) {
			$foto_filename = sanitize_file_name($foto_file['name']);
			$foto_dest = $pmb_upload_dir . '/' . $foto_filename;
			
			if (move_uploaded_file($foto_file['tmp_name'], $foto_dest)) {
				$foto_path = $upload_dir['baseurl'] . '/pmb-uploads/' . $user_id . '/' . $foto_filename;
			}
		}
		
		// Process ijazah upload
		$ijazah_file = $_FILES['ijazah'];
		$ijazah_path = '';
		if ($ijazah_file['error'] === 0) {
			$ijazah_filename = sanitize_file_name($ijazah_file['name']);
			$ijazah_dest = $pmb_upload_dir . '/' . $ijazah_filename;
			
			if (move_uploaded_file($ijazah_file['tmp_name'], $ijazah_dest)) {
				$ijazah_path = $upload_dir['baseurl'] . '/pmb-uploads/' . $user_id . '/' . $ijazah_filename;
			}
		}
		
		// Save form data to user meta
		$registration_number = isset($_POST['nomor_pendaftaran']) ? sanitize_text_field($_POST['nomor_pendaftaran']) : $this->generate_registration_number();
		update_user_meta($user_id, 'nomor_pendaftaran', $registration_number);
		
		update_user_meta($user_id, 'jurusan_dipilih', sanitize_text_field($_POST['jurusan_dipilih']));
		update_user_meta($user_id, 'waktu_kuliah', sanitize_text_field($_POST['waktu_kuliah']));
		update_user_meta($user_id, 'nama_lengkap', sanitize_text_field($_POST['nama_lengkap']));
		update_user_meta($user_id, 'tempat_lahir', sanitize_text_field($_POST['tempat_lahir']));
		update_user_meta($user_id, 'tanggal_lahir', sanitize_text_field($_POST['tanggal_lahir']));
		update_user_meta($user_id, 'no_hp', sanitize_text_field($_POST['no_hp']));
		update_user_meta($user_id, 'email', sanitize_email($_POST['email']));
		update_user_meta($user_id, 'jenis_kelamin', sanitize_text_field($_POST['jenis_kelamin']));
		update_user_meta($user_id, 'agama', sanitize_text_field($_POST['agama']));
		update_user_meta($user_id, 'alamat', sanitize_textarea_field($_POST['alamat']));
		update_user_meta($user_id, 'jenis_sekolah', sanitize_text_field($_POST['jenis_sekolah']));
		update_user_meta($user_id, 'asal_sekolah', sanitize_text_field($_POST['asal_sekolah']));
		update_user_meta($user_id, 'tahun_lulus', sanitize_text_field($_POST['tahun_lulus']));
		update_user_meta($user_id, 'status_mahasiswa', sanitize_text_field($_POST['status_mahasiswa']));
		update_user_meta($user_id, 'status_pekerjaan', sanitize_text_field($_POST['status_pekerjaan']));
		update_user_meta($user_id, 'sumber', sanitize_text_field($_POST['sumber']));
		
		// Optional fields
		if (isset($_POST['keterangan1'])) {
			update_user_meta($user_id, 'keterangan1', sanitize_text_field($_POST['keterangan1']));
		}
		if (isset($_POST['keterangan2'])) {
			update_user_meta($user_id, 'keterangan2', sanitize_text_field($_POST['keterangan2']));
		}
		
		// Set tanggal_pengisian to current date if not provided
		$tanggal_pengisian = isset($_POST['tanggal_pengisian']) ? sanitize_text_field($_POST['tanggal_pengisian']) : date('Y-m-d');
		update_user_meta($user_id, 'tanggal_pengisian', $tanggal_pengisian);
		
		// Save file paths
		if (!empty($foto_path)) {
			update_user_meta($user_id, 'foto_path', $foto_path);
		}
		if (!empty($ijazah_path)) {
			update_user_meta($user_id, 'ijazah_path', $ijazah_path);
		}
		
		// Set registration status to pending
		update_user_meta($user_id, 'status_pmb', 'pending');
		
		// Redirect to profile page
		$profile_page_id = carbon_get_theme_option('pmb_profile_page');
		if ($profile_page_id) {
			wp_redirect(add_query_arg('registration', 'success', get_permalink($profile_page_id)));
			exit;
		}
	}

	/**
	 * Process the user registration form submission.
	 *
	 * @since    1.0.0
	 */
	public function process_user_registration() {
		if (!isset($_POST['pmb_user_register']) || !isset($_POST['pmb_nonce']) || !wp_verify_nonce($_POST['pmb_nonce'], 'pmb_user_registration_nonce')) {
			return;
		}
		
		$username = sanitize_user($_POST['username']);
		$email = sanitize_email($_POST['email']);
		$password = $_POST['password'];
		$nama_lengkap = sanitize_text_field($_POST['nama_lengkap']);
		
		// Check if user exists
		if (username_exists($username)) {
			wp_redirect(add_query_arg('registration', 'username_exists', wp_get_referer()));
			exit;
		}
		
		if (email_exists($email)) {
			wp_redirect(add_query_arg('registration', 'email_exists', wp_get_referer()));
			exit;
		}
		
		// Create user
		$user_id = wp_create_user($username, $password, $email);
		
		if (is_wp_error($user_id)) {
			wp_redirect(add_query_arg('registration', 'failed', wp_get_referer()));
			exit;
		}
		
		// Update user role
		$user = new WP_User($user_id);
		$user->set_role('subscriber');
		
		// Save user meta
		update_user_meta($user_id, 'nama_lengkap', $nama_lengkap);
		
		// Auto login
		wp_set_auth_cookie($user_id);
		
		// Redirect to PMB registration form
		wp_redirect(get_permalink(carbon_get_theme_option('pmb_registration_page')));
		exit;
	}

	/**
	 * Process the login form submission.
	 *
	 * @since    1.0.0
	 */
	public function process_login() {
		if (!isset($_POST['pmb_login']) || !isset($_POST['pmb_nonce']) || !wp_verify_nonce($_POST['pmb_nonce'], 'pmb_login_nonce')) {
			return;
		}
		
		$username = sanitize_user($_POST['username']);
		$password = $_POST['password'];
		$redirect_to = isset($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) : '';
		
		$user = wp_authenticate($username, $password);
		
		if (is_wp_error($user)) {
			wp_redirect(add_query_arg('login', 'failed', wp_get_referer()));
			exit;
		}
		
		wp_set_auth_cookie($user->ID);
		
		// Check if redirection URL is provided
		if (!empty($redirect_to)) {
			wp_redirect($redirect_to);
			exit;
		}
		
		// If user has completed registration form, go to profile
		$tempat_lahir = get_user_meta($user->ID, 'tempat_lahir', true);
		$tanggal_lahir = get_user_meta($user->ID, 'tanggal_lahir', true);
		
		if (!empty($tempat_lahir) && !empty($tanggal_lahir)) {
			wp_redirect(get_permalink(carbon_get_theme_option('pmb_profile_page')));
		} else {
			// If user hasn't completed registration form, direct them to it
			wp_redirect(get_permalink(carbon_get_theme_option('pmb_registration_page')));
		}
		exit;
	}

	/**
	 * Generate a unique registration number
	 *
	 * @since    1.0.0
	 * @return   string    A unique registration number
	 */
	public function generate_registration_number() {
		// Get the current year
		$year = date('Y');
		
		// Get month as two digits
		$month = date('m');
		
		// Get the count of registrations for this month
		$args = array(
			'meta_query' => array(
				array(
					'key' => 'nomor_pendaftaran',
					'value' => 'PMB-' . $year . $month,
					'compare' => 'LIKE'
				)
			),
			'fields' => 'ids' // Only get user IDs to improve performance
		);
		$user_query = new WP_User_Query($args);
		$count = $user_query->get_total() + 1; // Add 1 for the new registration
		
		// Format the count as a 3-digit number
		$count_formatted = str_pad($count, 3, '0', STR_PAD_LEFT);
		
		// Format: PMB-YYYYMM-XXX (e.g., PMB-202505-001)
		$registration_number = 'PMB-' . $year . $month . '-' . $count_formatted;
		
		return $registration_number;
	}

	/**
	 * Redirect registered users based on page and registration status
	 *
	 * @since    1.0.0
	 */
	public function redirect_registered_users() {
		// Check if we are on a PMB page
		global $post;
		if (!is_object($post)) {
			return;
		}
		
		// If user is logged in
		if (is_user_logged_in()) {
			$user_id = get_current_user_id();
			
			// Get the current page ID
			$current_page_id = $post->ID;
			
			// Get PMB page IDs from Carbon Fields
			$login_page_id = carbon_get_theme_option('pmb_login_page');
			$registration_page_id = carbon_get_theme_option('pmb_user_registration_page');
			$profile_page_id = carbon_get_theme_option('pmb_profile_page');
			$application_form_page_id = carbon_get_theme_option('pmb_registration_page');
			$home_page_id = carbon_get_theme_option('pmb_home_page');
			
			// If on login page, redirect to profile
			if ($current_page_id == $login_page_id) {
				wp_redirect(get_permalink($profile_page_id));
				exit;
			}
			
			// If on user registration page, redirect to profile
			if ($current_page_id == $registration_page_id) {
				wp_redirect(get_permalink($profile_page_id));
				exit;
			}
			
			// If on application form page, check if already submitted
			if ($current_page_id == $application_form_page_id) {
				// Check if user already submitted data
				$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
				$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
				
				if (!empty($tempat_lahir) && !empty($tanggal_lahir)) {
					wp_redirect(get_permalink($profile_page_id));
					exit;
				}
			}
			
			// If on home/dashboard page but user hasn't submitted registration data yet
			if ($current_page_id == $home_page_id) {
				$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
				$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
				
				// If user hasn't completed their registration, redirect to registration form
				if (empty($tempat_lahir) || empty($tanggal_lahir)) {
					wp_redirect(get_permalink($application_form_page_id));
					exit;
				}
			}
		} else {
			// User is not logged in
			$current_page_id = $post->ID;
			
			// Get page IDs from Carbon Fields
			$profile_page_id = carbon_get_theme_option('pmb_profile_page');
			$application_form_page_id = carbon_get_theme_option('pmb_registration_page');
			$login_page_id = carbon_get_theme_option('pmb_login_page');
			
			// If trying to access profile or registration form, redirect to login
			if ($current_page_id == $profile_page_id || $current_page_id == $application_form_page_id) {
				wp_redirect(add_query_arg('redirect_to', get_permalink($current_page_id), get_permalink($login_page_id)));
				exit;
			}
		}
	}
}
