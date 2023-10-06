<?php

Class Create_Donation_Table {
    /**
     * Constructor of class for loading default features. 
    **/
    public $file;

    public function __construct( $file ) {
        $this->file = $file;
        register_activation_hook( $this->file, array( $this, 'create_donation_history_tbl' ) );
    }
    
    /**
     * Function for creating donation history tables. 
    **/
    public function create_donation_history_tbl() {
		global $wpdb;
        $table_name = $wpdb->prefix.'donation_history';
        
        $event_table = count( $wpdb->get_col( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_name ) ) );  // db call ok; no-cache ok.
		if ( 0 === $event_table ) {
			if ( ! function_exists( 'dbDelta' ) ) {
                require_once ABSPATH . '/wp-admin/includes/upgrade.php';
            }
            $charset_collate = $wpdb->get_charset_collate();
            $event_query  = "CREATE TABLE IF NOT EXISTS $table_name (
            `id` bigint(11) UNSIGNED AUTO_INCREMENT,
            `donator_name` varchar(255) NOT NULL,
            `donator_phone` varchar(255) NOT NULL,
            `donator_email` varchar(255) NOT NULL,
            `donation_id` varchar(255) NOT NULL,
            `donation_amount` varchar(255) NOT NULL,
            `payment_id` varchar(255) NOT NULL,
			`payment_status` varchar(255) NOT NULL,
            `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
			`updated_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) $charset_collate;";
            dbDelta( $event_query );           
		}
	}
}

$class = new Create_Donation_Table(__FILE__);
$class->create_donation_history_tbl();