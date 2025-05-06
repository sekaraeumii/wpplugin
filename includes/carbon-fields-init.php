<?php
// Add this to your existing Carbon Fields initialization

// Payment Settings
Container::make('theme_options', __('Pengaturan Pembayaran', 'pmb-stba'))
    ->set_page_parent('pmb-stba') // Set parent menu slug
    ->add_fields(array(
        Field::make('checkbox', 'pmb_payment_enabled', __('Aktifkan Pembayaran', 'pmb-stba'))
            ->set_option_value('yes')
            ->set_help_text(__('Aktifkan fitur pembayaran untuk PMB', 'pmb-stba')),
            
        Field::make('text', 'pmb_payment_title', __('Judul Halaman Pembayaran', 'pmb-stba'))
            ->set_help_text(__('Masukkan judul untuk halaman pembayaran', 'pmb-stba')),
            
        Field::make('textarea', 'pmb_payment_description', __('Deskripsi Pembayaran', 'pmb-stba'))
            ->set_help_text(__('Informasi tambahan tentang pembayaran', 'pmb-stba')),
            
        Field::make('text', 'pmb_payment_amount', __('Nominal Pembayaran', 'pmb-stba'))
            ->set_help_text(__('Masukkan nominal pembayaran (tanpa titik/koma)', 'pmb-stba')),
            
        Field::make('complex', 'pmb_bank_accounts', __('Rekening Bank', 'pmb-stba'))
            ->set_layout('tabbed-vertical')
            ->add_fields(array(
                Field::make('text', 'bank_name', __('Nama Bank', 'pmb-stba'))
                    ->set_width(50)
                    ->set_required(true),
                    
                Field::make('text', 'account_number', __('Nomor Rekening', 'pmb-stba'))
                    ->set_width(50)
                    ->set_required(true),
                    
                Field::make('text', 'account_name', __('Nama Pemilik', 'pmb-stba'))
                    ->set_width(50)
                    ->set_required(true),
                    
                Field::make('image', 'bank_logo', __('Logo Bank', 'pmb-stba'))
                    ->set_width(50)
                    ->set_value_type('url')
                    ->set_help_text(__('Upload logo bank (opsional)', 'pmb-stba')),
            ))
            ->set_header_template('<%- bank_name %> - <%= account_number %>'),
            
        Field::make('page', 'pmb_payment_page', __('Halaman Pembayaran', 'pmb-stba'))
            ->set_help_text(__('Pilih halaman yang menampilkan shortcode [pmb_payment_info]', 'pmb-stba')),
    ));