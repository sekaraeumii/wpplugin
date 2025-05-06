<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://techinspire.my.id
 * @since      1.0.0
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/admin
 * @author     Sukardi <mrsukardi@gmail.com>
 */
class Pmb_Stba_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		// Add action for processing delete user
		add_action('admin_post_pmb_delete_user', array($this, 'delete_user'));
		
		// Add AJAX handler for deleting users
		add_action('wp_ajax_pmb_delete_user_ajax', array($this, 'delete_user_ajax'));
		
		// Register action untuk verifikasi dokumen
		add_action('admin_init', array($this, 'verify_user_documents'));
	}

	/**
	 * Register the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function define_admin_hooks() {
		// Add admin-post action handlers
		add_action('admin_post_pmb_update_status', array($this, 'update_user_status'));
		add_action('admin_post_pmb_delete_user', array($this, 'delete_user'));
		
		// Other hooks...
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pmb-stba-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . 'datatables', plugin_dir_url(__FILE__) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all');


		// Tambahkan custom CSS untuk memperlebar area admin
		$custom_css = "
			.wrap .container-fluid {
				padding-left: 5px !important;
				padding-right: 5px !important;
				max-width: 98% !important;
			}
			.pmb-stba-schedules .row {
				margin-left: -15px;
				margin-right: -15px;
				display: flex;
				flex-wrap: wrap;
			}
			.pmb-stba-schedules .col {
				padding: 15px;
				flex: 1;
			}
			@media (max-width: 767px) {
				.pmb-stba-schedules .col {
					flex-basis: 100%;
				}
			}
		";
		wp_add_inline_style( $this->plugin_name, $custom_css );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pmb-stba-admin.js', array('jquery'), $this->version, false);
		
		// Make sure to load Bootstrap 4.3.1 JS
		wp_enqueue_script($this->plugin_name . 'bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), '4.3.1', true);
		
		// DataTables
		wp_enqueue_script($this->plugin_name . 'datatables', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array('jquery'), $this->version, true);
		
		// Localize the script with new data
		wp_localize_script($this->plugin_name, 'pmb_stba_admin', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('pmb_stba_admin_nonce')
		));
	}

	/**
	 * Add menu pages for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_menu_pages() {
		// Menu Utama
		add_menu_page(
			'PMB STBA',          
			'PMB STBA',          
			'manage_options',     
			'pmb-stba',          
			array($this, 'render_registrations_page'),  // Ubah ke halaman registrasi sebagai default
			'dashicons-groups',
			30
		);

		// Hapus menu duplikat dengan hook khusus
		add_action('admin_menu', function() {
			global $submenu;
			if (isset($submenu['pmb-stba'])) {
				unset($submenu['pmb-stba'][0]); // Menghapus submenu pertama (yang duplikat)
			}
		}, 999);
		
		// Submenu Registrasi User - jadikan sebagai halaman utama
		add_submenu_page(
			'pmb-stba',
			'Registrasi User',
			'Registrasi User',
			'manage_options',
			'pmb-stba-registrations',
			array($this, 'render_registrations_page')
		 );
		 
		 // Submenu Jadwal PMB
		 add_submenu_page(
			'pmb-stba',
			'Jadwal PMB',
			'Jadwal PMB',
			'manage_options',
			'pmb-stba-schedules',
			array($this, 'render_schedules_page')
		 );
	
		// Submenu Pengaturan Pembayaran
		add_submenu_page(
			'pmb-stba',
			'Pengaturan Pembayaran',
			'Pengaturan Pembayaran',
			'manage_options',
			'pmb-stba-payment-settings',
			array($this, 'render_payment_settings_page')
		);
		
		// Submenu Bukti Pembayaran
		add_submenu_page(
			'pmb-stba',
			'Bukti Pembayaran',
			'Bukti Pembayaran',
			'manage_options',
			'pmb-stba-payment-proofs',
			array($this, 'render_payment_proofs_page')
		);

		// Detail User page (hidden from menu)
		add_submenu_page(
			null,
			'Detail Pendaftar',
			'Detail Pendaftar',
			'manage_options',
			'pmb-stba-user-details',
			array($this, 'render_user_details_page')
		);
	}

	/**
	 * Render the main dashboard page.
	 *
	 * @since    1.0.0
	 */
	public function render_main_page() {
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-dashboard.php';
	}

	/**
	 * Render the registrations page.
	 *
	 * @since    1.0.0
	 */
	public function render_registrations_page() {
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-registrations.php';
	}

	/**
	 * Render the schedules page.
	 *
	 * @since    1.0.0
	 */
	public function render_schedules_page() {
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-schedules.php';
	}

	/**
	 * Render the user details page.
	 *
	 * @since    1.0.0
	 */
	public function render_user_details_page() {
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-user-details.php';
	}

	/**
	 * Render the payment settings page.
	 *
	 * @since    1.0.0
	 */
	public function render_payment_settings_page() {
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-payment-settings.php';
	}
	
	/**
	 * Render the payment proofs page.
	 *
	 * @since    1.0.0
	 */
	public function render_payment_proofs_page() {
		include plugin_dir_path(__FILE__) . 'partials/pmb-stba-payment-proofs.php';
	}

	/**
	 * Handle the status update
	 * 
	 * @since    1.0.0
	 */
	public function handle_status_update() {
		// Check if form is submitted and user has permissions
		if (!isset($_POST['pmb_status_nonce']) || !wp_verify_nonce($_POST['pmb_status_nonce'], 'pmb_update_status_nonce')) {
			wp_die('Nonce verification failed');
		}
		
		if (!current_user_can('manage_options')) {
			wp_die('You do not have permission to perform this action');
		}
		
		// Get form data
		$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
		$status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
		$catatan = isset($_POST['catatan']) ? sanitize_textarea_field($_POST['catatan']) : '';
		$send_notification = isset($_POST['send_notification']) ? true : false;
		
		// Update user meta
		if ($user_id > 0 && !empty($status)) {
			update_user_meta($user_id, 'status_pmb', $status);
			
			if (!empty($catatan)) {
				update_user_meta($user_id, 'catatan_admin', $catatan);
			}
			
			// Record status history
			$this->record_status_history($user_id, $status, $catatan);
			
			// Send notification if checked
			if ($send_notification) {
				$this->send_status_notification($user_id, $status, $catatan);
			}
			
			// Redirect back with success message
			wp_redirect(add_query_arg('updated', 'true', wp_get_referer()));
			exit;
		}
		
		// Redirect back with error message
		wp_redirect(add_query_arg('error', 'true', wp_get_referer()));
		exit;
	}

	/**
	 * Record status change history
	 *
	 * @since    1.0.0
	 * @param    int       $user_id    The user ID
	 * @param    string    $status     The new status
	 * @param    string    $catatan    Optional notes
	 */
	public function record_status_history($user_id, $status, $catatan = '') {
		$current_user = wp_get_current_user();
		$history_item = array(
			'timestamp' => time(),
			'status' => $status,
			'admin_id' => $current_user->ID,
			'admin_name' => $current_user->display_name,
			'catatan' => $catatan
		);
		
		$status_history = get_user_meta($user_id, 'status_history', true);
		if (!is_array($status_history)) {
			$status_history = array();
		}
		
		// Add new history item at the beginning
		array_unshift($status_history, $history_item);
		
		// Limit history items
		if (count($status_history) > 20) {
			array_pop($status_history);
		}
		
		update_user_meta($user_id, 'status_history', $status_history);
	}

	/**
	 * Export registration data to Excel
	 * 
	 * @since    1.0.0
	 */
	public function export_excel() {
		// Check if user has permission
		if (!current_user_can('manage_options')) {
			wp_die('You do not have permission to perform this action');
		}
		
		// Get all subscriber users
		$args = array(
			'role' => 'subscriber',
			'orderby' => 'registered',
			'order' => 'DESC'
		);
		$users = get_users($args);
		
		// Set headers for Excel download
		$filename = 'pmb-stba-registrations-' . date('Y-m-d') . '.csv';
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		
		// Create a file handle for output
		$output = fopen('php://output', 'w');
		
		// Add UTF-8 BOM to fix Excel character display
		fputs($output, "\xEF\xBB\xBF");
		
		// CSV header row
		fputcsv($output, array(
			'ID',
			'Nama Lengkap',
			'Email',
			'Tempat Lahir',
			'Tanggal Lahir',
			'Jenis Kelamin',
			'No. HP',
			'Alamat',
			'Asal Sekolah',
			'Jurusan Dipilih', 
			'Status',
			'Tanggal Daftar'
		));
		
		// Add user data
		foreach ($users as $user) {
			$user_id = $user->ID;
			$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
			
			// Only include users who have submitted the form
			if (!empty($nama_lengkap)) {
				$tempat_lahir = get_user_meta($user_id, 'tempat_lahir', true);
				$tanggal_lahir = get_user_meta($user_id, 'tanggal_lahir', true);
				$jenis_kelamin = get_user_meta($user_id, 'jenis_kelamin', true);
				$jenis_kelamin = $jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
				$no_hp = get_user_meta($user_id, 'no_hp', true);
				$alamat = get_user_meta($user_id, 'alamat', true);
				$asal_sekolah = get_user_meta($user_id, 'asal_sekolah', true);
				$jurusan = get_user_meta($user_id, 'jurusan_dipilih', true);
				$status = get_user_meta($user_id, 'status_pmb', true);
				
				// Format status
				switch ($status) {
					case 'approved':
						$status = 'Diterima';
						break;
					case 'rejected':
						$status = 'Ditolak';
						break;
					default:
						$status = 'Menunggu';
				}
				
				// Write a row to the CSV
				fputcsv($output, array(
					$user_id,
					$nama_lengkap,
					$user->user_email,
					$tempat_lahir,
					$tanggal_lahir,
					$jenis_kelamin,
					$no_hp,
					$alamat,
					$asal_sekolah,
					$jurusan,
					$status,
					date_i18n('Y-m-d', strtotime($user->user_registered))
				));
			}
		}
		
		fclose($output);
		exit;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style($this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name . '-datatables', plugin_dir_url(__FILE__) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pmb-stba-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script($this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name . '-datatables', plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pmb-stba-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Handle printing PMB card
	 */
	public function print_pmb_card() {
		// Check if user ID is provided and user has permission
		if (empty($_GET['user_id']) || !current_user_can('manage_options')) {
			wp_die('Akses ditolak');
		}

		$user_id = intval($_GET['user_id']);
		$user = get_userdata($user_id);
		
		if (!$user) {
			wp_die('User tidak ditemukan');
		}
		
		// Get user data
		$nama_lengkap = get_user_meta($user_id, 'nama_lengkap', true);
		$jurusan = get_user_meta($user_id, 'jurusan_dipilih', true);
		$foto_url = get_user_meta($user_id, 'foto_url', true);
		$status = get_user_meta($user_id, 'status_pmb', true);
		
		// Format status badge
		switch ($status) {
			case 'approved':
				$status_text = 'Diterima';
				break;
			case 'rejected':
				$status_text = 'Ditolak';
				break;
			default:
				$status_text = 'Menunggu';
		}
		
		// Output the card
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<title>Kartu PMB - <?php echo esc_html($nama_lengkap); ?></title>
			<style>
				body {
					font-family: Arial, sans-serif;
					margin: 0;
					padding: 20px;
				}
				.card {
					width: 85mm;
					height: 55mm;
					border: 1px solid #000;
					padding: 10px;
					margin: 0 auto;
					box-sizing: border-box;
				}
				.header {
					text-align: center;
					border-bottom: 1px solid #ccc;
					padding-bottom: 5px;
					margin-bottom: 10px;
				}
				.logo {
					height: 40px;
					margin-bottom: 5px;
				}
				.title {
					font-size: 14px;
					font-weight: bold;
					margin: 0;
				}
				.subtitle {
					font-size: 12px;
					margin: 0;
				}
				.content {
					display: flex;
				}
				.photo {
					width: 25mm;
					height: 30mm;
					border: 1px solid #ccc;
					margin-right: 10px;
					overflow: hidden;
				}
				.photo img {
					width: 100%;
					height: 100%;
					object-fit: cover;
				}
				.details {
					flex: 1;
				}
				.details p {
					font-size: 11px;
					margin: 5px 0;
				}
				.footer {
					margin-top: 10px;
					font-size: 9px;
					text-align: center;
				}
				@media print {
					body {
						padding: 0;
					}
					.no-print {
						display: none;
					}
				}
			</style>
		</head>
		<body>
			<div class="no-print" style="text-align: center; margin-bottom: 20px;">
				<button onclick="window.print()">Cetak</button>
				<button onclick="window.close()">Tutup</button>
			</div>
			
			<div class="card">
				<div class="header">
					<img src="<?php echo plugins_url('admin/img/logo-stba.png', dirname(__FILE__, 2)); ?>" alt="Logo STBA" class="logo">
					<p class="title">KARTU BUKTI PENDAFTARAN</p>
					<p class="subtitle">PMB STBA Pontianak <?php echo date('Y'); ?></p>
				</div>
				
				<div class="content">
					<div class="photo">
						<?php if (!empty($foto_url)) : ?>
							<img src="<?php echo esc_url($foto_url); ?>" alt="Foto">
						<?php endif; ?>
					</div>
					<div class="details">
						<p><strong>Nama:</strong> <?php echo esc_html($nama_lengkap); ?></p>
						<p><strong>No. Pendaftaran:</strong> <?php echo esc_html($user_id); ?></p>
						<p><strong>Jurusan:</strong> <?php echo esc_html($jurusan); ?></p>
						<p><strong>Status:</strong> <?php echo esc_html($status_text); ?></p>
					</div>
				</div>
				
				<div class="footer">
					Dokumen ini dicetak pada <?php echo date_i18n('d M Y H:i'); ?>
				</div>
			</div>
			
			<script>
				// Auto print
				window.onload = function() {
					setTimeout(function() {
						window.print();
					}, 500);
				};
			</script>
		</body>
		</html>
		<?php
		exit;
	}

	/**
	 * Send notification email to user when status is updated
	 *
	 * @since    1.0.0
	 * @param    int       $user_id    The user ID
	 * @param    string    $status     The new status
	 * @param    string    $catatan    Optional notes
	 */
	public function send_status_notification($user_id, $status, $catatan = '') {
		$user = get_userdata($user_id);
		
		if (!$user) {
			return false;
		}
		
		$to = $user->user_email;
		$subject = 'Update Status Pendaftaran PMB STBA';
		
		// Format status text
		$status_text = 'Menunggu';
		if ($status === 'approved') {
			$status_text = 'Diterima';
		} elseif ($status === 'rejected') {
			$status_text = 'Ditolak';
		}
		
		$message = "Yth. {$user->display_name},\n\n";
		$message .= "Status pendaftaran PMB STBA Anda telah diperbarui menjadi: $status_text\n\n";
		
		if (!empty($catatan)) {
			$message .= "Catatan dari admin:\n$catatan\n\n";
		}
		
		$message .= "Silahkan login ke akun PMB Anda untuk informasi lebih lanjut.\n\n";
		$message .= "Hormat kami,\nPanitia PMB STBA";
		
		$headers = array('Content-Type: text/plain; charset=UTF-8');
		
		return wp_mail($to, $subject, $message, $headers);
	}

	/**
	 * Delete a user and their related files
	 */
	public function delete_user() {
		// Debug log
		if (defined('WP_DEBUG') && WP_DEBUG) {
			error_log('PMB STBA: delete_user function called');
			error_log('POST data: ' . print_r($_POST, true));
		}
		
		// Check if user has permission
		if (!current_user_can('manage_options')) {
			wp_die('Unauthorized', 'Error', array('response' => 403));
		}

		// Verify nonce
		if (!isset($_POST['pmb_delete_nonce']) || !wp_verify_nonce($_POST['pmb_delete_nonce'], 'pmb_delete_user_nonce')) {
			wp_die('Security check failed', 'Error', array('response' => 403));
		}

		// Get user ID
		$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

		if ($user_id > 0) {
			// Get upload directory
			$upload_dir = wp_upload_dir();
			$pmb_upload_dir = $upload_dir['basedir'] . '/pmb-uploads/' . $user_id;
			
			// Delete user's uploaded files
			if (file_exists($pmb_upload_dir)) {
				$this->recursive_rmdir($pmb_upload_dir);
			}
			
			// Delete the user
			$result = wp_delete_user($user_id);
			
			// Redirect back with a success/error message
			$redirect_url = admin_url('admin.php?page=pmb-stba-registrations');
			$redirect_url = add_query_arg('message', ($result ? 'deleted' : 'error'), $redirect_url);
			wp_redirect($redirect_url);
			exit;
		}

		// Redirect back with an error message
		wp_redirect(add_query_arg('message', 'error', admin_url('admin.php?page=pmb-stba-registrations')));
		exit;
	}

	/**
	 * AJAX handler for deleting a user
	 */
	public function delete_user_ajax() {
		// Check if user has permission
		if (!current_user_can('manage_options')) {
			wp_send_json_error('Unauthorized');
		}

		// Verify nonce
		if (!check_ajax_referer('pmb_delete_user_ajax_nonce', 'nonce', false)) {
			wp_send_json_error('Security check failed');
		}

		// Get user ID
		$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

		if ($user_id > 0) {
			// Get upload directory
			$upload_dir = wp_upload_dir();
			$pmb_upload_dir = $upload_dir['basedir'] . '/pmb-uploads/' . $user_id;
			
			// Delete user's uploaded files
			if (file_exists($pmb_upload_dir)) {
				$this->recursive_rmdir($pmb_upload_dir);
			}
			
			// Delete the user
			$result = wp_delete_user($user_id);
			
			if ($result) {
				wp_send_json_success('User deleted successfully');
			} else {
				wp_send_json_error('Failed to delete user');
			}
		}

		wp_send_json_error('Invalid user ID');
	}

	/**
	 * Helper function to recursively delete a directory
	 */
	private function recursive_rmdir($dir) {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object != "." && $object != "..") {
					if (is_dir($dir . DIRECTORY_SEPARATOR . $object)) {
						$this->recursive_rmdir($dir . DIRECTORY_SEPARATOR . $object);
					} else {
						unlink($dir . DIRECTORY_SEPARATOR . $object);
					}
				}
			}
			rmdir($dir);
			return true;
		}
		return false;
	}

	/**
	 * Memproses verifikasi dokumen pendaftar
	 */
	public function verify_user_documents() {
		// Periksa jika ada action verify_documents
		if (isset($_POST['action']) && $_POST['action'] === 'verify_documents') {
			// Verifikasi nonce
			if (!isset($_POST['verify_documents_nonce']) || 
				!wp_verify_nonce($_POST['verify_documents_nonce'], 'pmb_verify_documents_nonce') || 
				!current_user_can('manage_options')) {
				wp_die('Security check failed');
			}
			
			// Validasi user_id
			$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
			$status = isset($_POST['document_status']) ? sanitize_text_field($_POST['document_status']) : '';
			
			if ($user_id > 0 && !empty($status)) {
				// Update status dokumen
				update_user_meta($user_id, 'pmb_document_status', $status);
				
				// Log aktivitas admin (opsional)
				$admin_user = wp_get_current_user();
				$log_message = sprintf(
					'Dokumen untuk user ID %d diverifikasi oleh %s dengan status: %s', 
					$user_id, 
					$admin_user->user_login,
					$status
				);
				error_log($log_message);
				
				// Redirect kembali ke halaman detail user dengan notifikasi
				$redirect = add_query_arg(array(
					'page' => 'pmb-stba-users',
					'action' => 'view',
					'user_id' => $user_id,
					'verified' => 1
				), admin_url('admin.php'));
				
				wp_redirect($redirect);
				exit;
			}
		}
	}
}

/**
 * Register settings for PMB STBA
 *
 * @since    1.0.0
 */
function pmb_stba_register_settings() {
    register_setting('pmb_settings', 'pmb_payment_page');
    
    add_settings_field(
        'pmb_payment_page',
        __('Halaman Pembayaran', 'pmb-stba'),
        'pmb_payment_page_callback',
        'pmb_settings',
        'pmb_page_settings'
    );
}
add_action('admin_init', 'pmb_stba_register_settings');

function pmb_payment_page_callback() {
    $payment_page = get_option('pmb_payment_page');
    
    wp_dropdown_pages(array(
        'name' => 'pmb_payment_page',
        'show_option_none' => __('-- Pilih Halaman --', 'pmb-stba'),
        'option_none_value' => '0',
        'selected' => $payment_page,
    ));
    
    echo '<p class="description">' . __('Pilih halaman yang menampilkan shortcode [pmb_payment_info]', 'pmb-stba') . '</p>';
}
