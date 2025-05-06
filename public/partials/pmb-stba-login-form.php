<div class="pmb-stba-login-container" style="min-height: 100vh; display: flex; align-items: center; background: #f7f7f7;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card floating-card border-0">
                    <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 0.5rem 0.5rem 0 0;">
                        <h2 class="mb-0"><?php _e('Login PMB STBA Pontianak', 'pmb-stba'); ?></h2>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($_GET['login']) && $_GET['login'] == 'failed') : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php _e('Username atau password salah.', 'pmb-stba'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form id="pmb-login-form" method="post">
                            <?php wp_nonce_field('pmb_login_nonce', 'pmb_nonce'); ?>

                            <div class="form-group mb-3">
                                <label for="username"><?php _e('Username', 'pmb-stba'); ?></label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="<?php esc_attr_e('Username', 'pmb-stba'); ?>" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="password"><?php _e('Password', 'pmb-stba'); ?></label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="<?php esc_attr_e('Password', 'pmb-stba'); ?>" required>
                            </div>
                            
                            <?php
                            // Check if there's a redirect parameter
                            $redirect_to = isset($_GET['redirect_to']) ? esc_url($_GET['redirect_to']) : '';
                            if (!empty($redirect_to)) {
                                echo '<input type="hidden" name="redirect_to" value="' . esc_attr($redirect_to) . '">';
                            }
                            ?>
                            
                            <div class="form-group">
                                <button type="submit" name="pmb_login" class="btn btn-primary btn-block"><?php _e('Login', 'pmb-stba'); ?></button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <p class="mb-0">
                                    <?php _e('Belum punya akun?', 'pmb-stba'); ?> 
                                    <a href="<?php echo get_permalink(carbon_get_theme_option('pmb_registration_page')); ?>"><?php _e('Daftar', 'pmb-stba'); ?></a>
                                </p>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-light py-3" style="border-radius: 0 0 0.5rem 0.5rem;">
                        <small><?php _e('© ' . date('Y') . ' PMB STBA. All rights reserved.', 'pmb-stba'); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Efek floating dan shadow untuk card */
.pmb-stba-login-container .card {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    margin-top: 20px;
    margin-bottom: 30px;
}

.pmb-stba-login-container .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(30, 115, 190, 0.15);
}

/* Styling header */
.pmb-stba-login-container .card-header {
    background: linear-gradient(135deg, #1e73be 0%, #2c5282 100%) !important;
    border-radius: 8px 8px 0 0 !important;
    padding: 20px;
    border-bottom: none;
}

.pmb-stba-login-container .card-header h2 {
    font-weight: 600;
    letter-spacing: 0.5px;
    margin: 0;
    color: white;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

/* Styling form body */
.pmb-stba-login-container .card-body {
    padding: 30px;
    background-color: #ffffff;
    border-radius: 0 0 8px 8px;
}

/* Styling form fields */
.pmb-stba-login-container .form-control {
    border-radius: 5px;
    padding: 12px 15px;
    font-size: 16px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
}

.pmb-stba-login-container .form-control:focus {
    border-color: #1e73be;
    box-shadow: 0 0 0 0.25rem rgba(30, 115, 190, 0.25);
}

/* Styling button */
.pmb-stba-login-container .btn-primary {
    background: linear-gradient(135deg, #1e73be 0%, #2c5282 100%);
    border: none;
    padding: 12px 20px;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(30, 115, 190, 0.3);
    width: 100%;
}

.pmb-stba-login-container .btn-primary:hover {
    box-shadow: 0 6px 15px rgba(30, 115, 190, 0.4);
    transform: translateY(-2px);
}

/* Links styling */
.pmb-stba-login-container a {
    color: #1e73be;
    text-decoration: none;
    transition: color 0.3s;
    font-weight: 600;
}

.pmb-stba-login-container a:hover {
    color: #2c5282;
    text-decoration: underline;
}

/* Alert styling */
.pmb-stba-login-container .alert {
    border-radius: 6px;
    border: none;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

/* Footer styling */
.pmb-stba-login-container .card-footer {
    background: #f8f9fa;
    border-top: none;
    color: #6c757d;
    padding: 15px;
}
</style>