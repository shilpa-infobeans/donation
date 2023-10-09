<?php
/**
 * Class for registering a new settings page under Settings.
 */
class Donation_History_Report {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'donation_history_page' ) );
        add_action( 'wp_ajax_donation_data_report', [ $this,'datatables_server_side_callback' ]);
	}

	/**
	 * Registers a new page to show donation data.
	 */
	public function donation_history_page() {
        add_menu_page( 
            __( 'Donation History Report', 'wp-donation' ),
            'Donation History Report',
            'manage_options',
            'donation_history_data',
            [$this, 'donation_history_data_fun'],
            'dashicons-welcome-widgets-menus',
            25
        ); 
    }

    /**
	 * Get donation data from server.
	 */
    public function datatables_server_side_callback() {
        global $wpdb;
        $table = $wpdb->prefix . 'donation_history'; 
        if ( isset( $_GET ) ) {
            $draw                   = $_GET['draw'] ?? '';
            $row                    = $_GET['start'] ?? '';
            $rowperpage             = $_GET['length'] ?? '';                          // Rows display per page
            $columnIndex            = $_GET['order'][0]['column'] ?? '';              // Column index
            $columnName             = $_GET['columns'][$columnIndex]['data'] ?? '';   // Column name
            $columnSortOrder        = $_GET['order'][0]['dir'] ?? '';                 // asc or desc
            $searchValue            = $_GET['search']['value'] ?? '';
            $total_entry            = $wpdb->query("select id from $table");
            $donation_records       = $this->get_donation_data_query( $searchValue, $columnName, $columnSortOrder, $row ,$rowperpage  );
            $totalRecord            = $total_entry;
            $totalRecordwithFilter  = count( $donation_records );
            
            // Pass each argument for every time you need it.
            $data = $this->donation_history_data_loop( $donation_records );

            ## Response     
            $response = $this->donation_history_response_data( $_GET, $data, $draw, $totalRecord, $totalRecordwithFilter );
            echo json_encode( $response );
            die();
        } else {
            $response = $this->donation_history_response_data( $_GET, [], 0, 0, 0 );
            echo json_encode($response);
            die();
        }
    }

	/**
	 * Donation Report html.
	 */
	public function donation_history_data_fun() {  
        ob_start(); ?>
        <div class="history__Sec">
            <div class="row">
                <div class="col-xs-12 ">
                    <div id="history" class="tab-pane fade in active">
                        <h3>Donation Report</h3>
                            <div class="list_history">
                                <table id="donation_data" class="display" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Donator name</th>
                                            <th>Donator Phone Number</th>
                                            <th>Donator Email</th>
                                            <th>Donation Amount</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                        <th>Sr No.</th>
                                        <th>Donator name</th>
                                            <th>Donator Phone Number</th>
                                            <th>Donator Email</th>
                                            <th>Donation Amount</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	    <?php  echo ob_get_clean();  
    }

    /**
	 * Donation Data loop.
	 */
	public function donation_history_data_loop( $donation_records ) { 
        $data = array();
        if ( !empty( $donation_records ) && is_array( $donation_records ) && count( $donation_records ) > 0) {
            $count = 1; 
            foreach (   $donation_records as $donation   ) {
                $data[] = array( 
                                "s_n"   => $count,
                                "donator_name"      =>  $donation['donator_name'],
                                "donator_phone"     =>  $donation['donator_phone'],
                                'donator_email'     =>  $donation['donator_email'],
                                'donation_amount'   =>  $donation['donation_amount'],
                                'payment_status'    =>  $donation['payment_status'],
                            );
                $count++;     
            }
        }
        return $data;
    } 

    /**
	 *Donation Data response.
	 */
	public function donation_history_response_data( $data_table_arr, $data, $draw, $totalRecord, $totalRecordwithFilter ) {
        
        if ( isset( $data_table_arr ) ) {
            $response = [   
                "aaData" => $data,
                "draw" => intval( $draw ),
                "iTotalRecords" => $totalRecord,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
            ];    
        } else {
            $response = [
                "draw" => intval(0),
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => []
            ];
        }
        return $response;
    }

    /**
	 * Query to get donation data.
	 */
    public function get_donation_data_query( $searchValue, $columnName, $columnSortOrder, $row , $rowperpage ) {
        global $wpdb;
        $table = $wpdb->prefix . 'donation_history'; 
        $sql   = '';
        $sql   = $wpdb->prepare("SELECT * FROM {$table} ");

        
        //echo strlen($searchValue);        die($searchValue);
        if ( $searchValue && !empty( $searchValue ) && $searchValue >= 3) { 
            $sql .=  " WHERE donator_name LIKE '$searchValue%' OR donator_email LIKE '$searchValue%'";
        }

        if ( $columnName && !empty( $columnName )  ) { 
            $sql .=  " ORDER BY {$columnName} {$columnSortOrder} LIMIT {$row},{$rowperpage}";
        }

        $donation_records = $wpdb->get_results( $sql, ARRAY_A );

        return $donation_records;
    }

}

new Donation_History_Report;