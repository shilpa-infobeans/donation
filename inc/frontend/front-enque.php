<?php

Class Donation_Front_Enquues {
    /**
	 * Add actions when class initiating.
	 */
    public function __construct() {
        add_action('wp_enqueue_scripts',[ $this,'register_css_js_front_script' ]);    
    }
	
    /**
	 * Enqueue css js for Donation Popup.
	 */
    public function register_css_js_front_script() {
		wp_register_script( 'donation_jquery',PLUGIN_DONATION_URL.'/js/front/jquery.min.js?v='.time());
		wp_register_script( 'custom_jquery', PLUGIN_DONATION_URL.'/js/front/front.js?v='.time());
		wp_register_style( 'donation_front_css', PLUGIN_DONATION_URL.'/css/front/front.css?v='.time());
		wp_register_style( 'donation_front_css', PLUGIN_DONATION_URL.'/css/front/bootstrap/bootstrap.min.css?v='.time());
    }
}

new Donation_Front_Enquues();