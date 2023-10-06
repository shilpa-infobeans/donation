<?php

Class Donation_Admin_Enques {
    /**
	 * Add actions when class initiating.
	 */
    public function __construct() {
        add_action('admin_enqueue_scripts',[ $this,'register_css_js_admin_script' ]);    
    }

    /**
	 * Enqueue css js for Donation.
	 */
    public function register_css_js_admin_script() {
		wp_enqueue_script( 'donation_jquery',PLUGIN_DONATION_URL.'js/admin/jquery.min.js');
		wp_enqueue_script( 'donation_datatable_js',PLUGIN_DONATION_URL.'js/admin/jquery.dataTables.min.js');
		wp_enqueue_script( 'custom_jquery',PLUGIN_DONATION_URL.'js/admin/admin.js', array(), time(), false);
		wp_localize_script( 'custom_jquery', 'global_object', array(
			'ajaxurl' => admin_url('admin-ajax.php?action=donation_data_report'),
		));
		wp_enqueue_style( 'donation_datatable_css',PLUGIN_DONATION_URL.'css/admin/jquery.dataTables.min.css');
		wp_enqueue_style( 'donation_admin_css',PLUGIN_DONATION_URL.'css/admin/admin.css');
	}
}

new Donation_Admin_Enques();