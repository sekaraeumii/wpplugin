<?php
/**
 * PMB Navigation Widget
 *
 * @link       https://techinspire.my.id
 * @since      1.0.0
 *
 * @package    Pmb_Stba
 * @subpackage Pmb_Stba/includes
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * PMB Navigation Widget
 */
class PMB_Navigation_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'pmb_navigation_widget', // Base ID
            __('PMB Navigation Menu', 'pmb-stba'), // Name
            array('description' => __('Menu navigasi untuk PMB STBA', 'pmb-stba')) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        // Buat container khusus untuk sidebar full height
        echo '<div class="pmb-full-sidebar">';
        
        // Tambahkan header navigasi yang lebih menarik
        echo '<div class="pmb-nav-header">
                <div class="pmb-nav-icon"><span class="dashicons dashicons-welcome-learn-more"></span></div>
                <h4 class="pmb-nav-title">' . esc_html__('Menu PMB', 'pmb-stba') . '</h4>
              </div>';
        
        // Get page IDs dari Carbon Fields
        $dashboard_page   = carbon_get_theme_option('pmb_home_page');
        $login_page       = carbon_get_theme_option('pmb_login_page');
        $register_page    = carbon_get_theme_option('pmb_user_registration_page');
        $registration_form= carbon_get_theme_option('pmb_registration_page');
        $profile_page     = carbon_get_theme_option('pmb_profile_page');
        
        echo '<ul class="pmb-navigation-menu">';
        
        // Hapus atau komentari kode menu Dashboard berikut:
/*
        // Dashboard
        $is_dashboard_active = get_the_ID() == $dashboard_page ? 'active' : '';
        if (!empty($dashboard_page)) {
            echo '<li class="' . $is_dashboard_active . '"><a href="' . esc_url(get_permalink($dashboard_page)) . '">' .
                '<span class="dashicons dashicons-dashboard"></span>' .
                '<span class="menu-text">' . esc_html__('Dashboard', 'pmb-stba') . '</span>' .
                '<span class="menu-arrow"><span class="dashicons dashicons-arrow-right-alt2"></span></span>' .
                '</a></li>';
        }
*/
        
        // Information
        $information_page = get_option('pmb_information_page');
        $is_info_active = get_the_ID() == $information_page ? 'active' : '';
        if (!empty($information_page)) {
            echo '<li class="' . $is_info_active . '"><a href="' . esc_url(get_permalink($information_page)) . '">' .
                 '<span class="dashicons dashicons-info"></span>' .
                 '<span class="menu-text">' . esc_html__('Informasi', 'pmb-stba') . '</span>' .
                 '<span class="menu-arrow"><span class="dashicons dashicons-arrow-right-alt2"></span></span>' .
                 '</a></li>';
        }
        
        // Registration form
        $is_reg_active = get_the_ID() == $registration_form ? 'active' : '';
        if (!empty($registration_form)) {
            echo '<li class="' . $is_reg_active . '"><a href="' . esc_url(get_permalink($registration_form)) . '">' .
                '<span class="dashicons dashicons-clipboard"></span>' .
                '<span class="menu-text">' . esc_html__('Formulir Pendaftaran', 'pmb-stba') . '</span>' .
                '<span class="menu-arrow"><span class="dashicons dashicons-arrow-right-alt2"></span></span>' .
                '</a></li>';
        }
        
        // Profile
        $is_profile_active = get_the_ID() == $profile_page ? 'active' : '';
        if (!empty($profile_page)) {
            echo '<li class="' . $is_profile_active . '"><a href="' . esc_url(get_permalink($profile_page)) . '">' .
                '<span class="dashicons dashicons-id"></span>' .
                '<span class="menu-text">' . esc_html__('Profil Saya', 'pmb-stba') . '</span>' .
                '<span class="menu-arrow"><span class="dashicons dashicons-arrow-right-alt2"></span></span>' .
                '</a></li>';
        }
        
        // Payment
        $payment_page = get_option('pmb_payment_page');
        $is_payment_active = get_the_ID() == $payment_page ? 'active' : '';
        if (!empty($payment_page)) {
            echo '<li class="' . $is_payment_active . '"><a href="' . esc_url(get_permalink($payment_page)) . '">' .
                 '<span class="dashicons dashicons-money-alt"></span>' .
                 '<span class="menu-text">' . esc_html__('Pembayaran Pendaftaran', 'pmb-stba') . '</span>' .
                 '<span class="menu-arrow"><span class="dashicons dashicons-arrow-right-alt2"></span></span>' .
                 '</a></li>';
        }
        
        // Logout
        echo '<li class="pmb-logout"><a href="' . wp_logout_url(home_url()) . '" class="pmb-logout-link">' .
             '<span class="dashicons dashicons-exit"></span>' .
             '<span class="menu-text">' . esc_html__('Keluar', 'pmb-stba') . '</span>' .
             '</a></li>';
        
        echo '</ul>';
        
        // Tambahkan CSS untuk styling widget
        echo '<style>
            .pmb-full-sidebar {
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }
            
            .pmb-nav-header {
                display: flex;
                align-items: center;
                padding: 15px 20px;
                background: #1e73be;
                color: white;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }
            
            .pmb-nav-icon {
                margin-right: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 36px;
                height: 36px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
            }
            
            .pmb-nav-icon .dashicons {
                font-size: 20px;
                width: auto;
                height: auto;
                color: white;
            }
            
            .pmb-nav-title {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: 0.5px;
                color: white;
            }
            
            .pmb-navigation-menu {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            
            .pmb-navigation-menu li {
                border-bottom: 1px solid #f0f0f0;
                transition: all 0.2s ease;
            }
            
            .pmb-navigation-menu li:last-child {
                border-bottom: none;
            }
            
            .pmb-navigation-menu li.active {
                background-color: #f8f9fa;
                border-left: 4px solid #1e73be;
            }
            
            .pmb-navigation-menu li.active a {
                padding-left: 16px;
                font-weight: 600;
                color: #1e73be;
            }
            
            .pmb-navigation-menu li a {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 20px;
                color: #444;
                text-decoration: none;
                font-size: 14px;
                transition: all 0.2s ease;
            }
            
            .pmb-navigation-menu li a:hover {
                background-color: rgba(30, 115, 190, 0.05);
                color: #1e73be;
            }
            
            .pmb-navigation-menu li a:hover .dashicons,
            .pmb-navigation-menu li.active a .dashicons {
                color: #1e73be;
            }
            
            .pmb-navigation-menu li a:hover .menu-arrow {
                transform: translateX(3px);
                opacity: 1;
            }
            
            .pmb-navigation-menu .dashicons {
                font-size: 18px;
                width: 20px;
                height: 20px;
                color: #777;
                margin-right: 10px;
                transition: all 0.2s ease;
            }
            
            .menu-text {
                flex: 1;
            }
            
            .menu-arrow {
                opacity: 0;
                transition: all 0.3s ease;
            }
            
            .menu-arrow .dashicons {
                font-size: 16px;
                width: 16px;
                height: 16px;
                margin-right: 0;
            }
            
            /* Logout button special styling */
            .pmb-navigation-menu li.pmb-logout {
                margin-top: 10px;
                border-top: 1px dashed #eee;
            }
            
            .pmb-navigation-menu li.pmb-logout a {
                color: #d63638;
            }
            
            .pmb-navigation-menu li.pmb-logout a:hover {
                background-color: rgba(214, 54, 56, 0.05);
            }
            
            .pmb-navigation-menu li.pmb-logout .dashicons {
                color: #d63638;
            }
            
            @media (max-width: 767px) {
                .pmb-nav-title {
                    font-size: 14px;
                }
                
                .pmb-navigation-menu li a {
                    padding: 10px 15px;
                    font-size: 13px;
                }
            }
        </style>';
        
        echo '</div>';
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title (leave blank to hide):', 'pmb-stba'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" 
                value="<?php echo esc_attr($title); ?>" placeholder="<?php esc_attr_e('Leave blank to hide title', 'pmb-stba'); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

        return $instance;
    }
}