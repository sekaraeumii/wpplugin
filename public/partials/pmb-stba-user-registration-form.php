<div class="pmb-stba-user-registration-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><?php _e('Buat Akun PMB STBA Pontianak', 'pmb-stba'); ?></h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['registration'])) : ?>
                        <?php if ($_GET['registration'] == 'email_exists') : ?>
                            <div class="alert alert-danger">
                                <?php _e('Email sudah terdaftar.', 'pmb-stba'); ?>
                            </div>
                        <?php elseif ($_GET['registration'] == 'username_exists') : ?>
                            <div class="alert alert-danger">
                                <?php _e('Username sudah digunakan.', 'pmb-stba'); ?>
                            </div>
                        <?php elseif ($_GET['registration'] == 'failed') : ?>
                            <div class="alert alert-danger">
                                <?php _e('Registrasi gagal. Silakan coba lagi.', 'pmb-stba'); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <form id="pmb-user-registration-form" method="post">
                        <?php wp_nonce_field('pmb_user_registration_nonce', 'pmb_nonce'); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama_lengkap"><?php _e('Nama Lengkap', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="username"><?php _e('Username', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="text" name="username" id="username" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email"><?php _e('Email', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="password"><?php _e('Password', 'pmb-stba'); ?> <span class="text-danger">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="pmb_user_register" class="btn btn-primary w-100"><?php _e('Daftar Akun', 'pmb-stba'); ?></button>
                        </div>
                        
                        <div class="mt-3 text-center">
                            <p><?php _e('Sudah punya akun?', 'pmb-stba'); ?> <a href="<?php echo get_permalink(carbon_get_theme_option('pmb_login_page')); ?>"><?php _e('Login', 'pmb-stba'); ?></a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Efek floating dan shadow untuk card */
.pmb-stba-user-registration-container .card {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    margin-top: 20px;
    margin-bottom: 30px;
}

.pmb-stba-user-registration-container .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(30, 115, 190, 0.15);
}

/* Styling header */
.pmb-stba-user-registration-container .card-header {
    background: linear-gradient(135deg, #1e73be 0%, #2c5282 100%);
    border-radius: 8px 8px 0 0 !important;
    padding: 20px;
    border-bottom: none;
}

.pmb-stba-user-registration-container .card-header h4 {
    font-weight: 600;
    letter-spacing: 0.5px;
    margin: 0;
    color: white;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

/* Styling form body */
.pmb-stba-user-registration-container .card-body {
    padding: 30px;
    background-color: #ffffff;
    border-radius: 0 0 8px 8px;
}

/* Styling form fields */
.pmb-stba-user-registration-container .form-control {
    border-radius: 5px;
    padding: 12px 15px;
    font-size: 16px;
    border: 1px solid #e0e0e0;
    transition: all 0.3s;
}

.pmb-stba-user-registration-container .form-control:focus {
    border-color: #1e73be;
    box-shadow: 0 0 0 0.25rem rgba(30, 115, 190, 0.25);
}

/* Styling button */
.pmb-stba-user-registration-container .btn-primary {
    background: linear-gradient(135deg, #1e73be 0%, #2c5282 100%);
    border: none;
    padding: 12px 20px;
    font-weight: 600;
    border-radius: 5px;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(30, 115, 190, 0.3);
}

.pmb-stba-user-registration-container .btn-primary:hover {
    box-shadow: 0 6px 15px rgba(30, 115, 190, 0.4);
    transform: translateY(-2px);
}

/* Links styling */
.pmb-stba-user-registration-container a {
    color: #1e73be;
    text-decoration: none;
    transition: color 0.3s;
    font-weight: 600;
}

.pmb-stba-user-registration-container a:hover {
    color: #2c5282;
    text-decoration: underline;
}

/* Alert styling */
.pmb-stba-user-registration-container .alert {
    border-radius: 6px;
    border: none;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}
</style>