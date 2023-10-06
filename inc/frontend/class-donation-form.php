<?php
namespace IB\DONATION;
 use Instamojo;
 use DateTime;
class Donation_Management {
    public $table_name;
    public $conn;
    protected $success_message  = "";
    protected $error_message    = "";   
    protected $wpdb;

    /**
     * Constructor of class for loading default features. 
    **/
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'donation_history';
        add_shortcode( 'donation_page', [ $this, 'filter_vehicle_as_per_condition'],20 );
        add_shortcode( 'donation_form', [ $this, 'create_donation_form_shortcode'],20 );
        add_action( 'wp', [ $this, 'save_donation_in_db'],10);
    }

    /**
     * Display all vehicle list.
     */
    public function filter_vehicle_as_per_condition() {
        ob_start(); 
        echo do_shortcode( '[donation_form]' );
        return ob_get_clean();
    }
    /**
     * Save user donation entry into custom table 
     */
    public function payment_request_create( $api, $product_name,$amount,$name, $phone,$email,$donation_data) { 
        try {
            $response = $api->paymentRequestCreate(array(
                "purpose"       => $product_name,
                "amount"        => $amount,
                "buyer_name"    => $name,
                "phone"         => $phone,
                "send_email"    => true,
                "send_sms"      => true,
                "email"         => $email,
                "mobile"        => $phone,
                'allow_repeated_payments' => false,
                "redirect_url" => home_url( PAYMENT_METHOD_REDIRECT_URL ),
            ));

            $pay_ulr = $response['longurl'];
            $payment_status = $response['status'];
            $payment_initiated = $response['created_at'];
            $donation_id = $response['id'];

            header("Location: $pay_ulr");
            $this->add_donation_data( $donation_data, $payment_status, $payment_initiated, $donation_id );
            exit();

        } catch ( \Exception $e) {
            print('Error: ' . $e->getMessage());
        }
    }
    /**
     * Save user donation entry into custom table 
     */
    public function save_donation_in_db() {

        if (isset($_POST) && isset($_POST['pay_now'])) {
            $donation_data = $_POST;
            $product_name   = $donation_data["product_name"] ;
            $name           = $donation_data["fname"];
            $phone          = $donation_data["phone_number"];
            $email          = $donation_data["email"];
            $amount         = $donation_data["donation_amount"];

            include PLUGIN_DONATION_DIR.'src/instamojo.php';
            $api = new Instamojo\Instamojo( PAYMENT_METHOD_API_KEY, PAYMENT_METHOD_API_TOKEN,PAYMENT_METHOD_ENDPOINT );
            $this->payment_request_create( $api, $product_name,$amount,$name, $phone,$email,$donation_data);
        }
    }

    public function add_donation_data( $donation_data, $payment_status, $payment_initiated, $donation_id ) {
        global $wpdb;
        if( $donation_data ) {
            $donation_post_data = [
                'donator_name'       => $donation_data['fname'],
                'donator_phone'      => $donation_data['phone_number'],
                'donator_email'      => $donation_data['email'],
                'donation_amount'    => $donation_data[ 'donation_amount' ],
                'donation_id'        => $donation_id,
                'payment_status'     => $payment_status,
                'created_date'       => $payment_initiated,
                'updated_date'       => $payment_initiated,
            ];  

            $inserted = $wpdb->insert( $this->table_name, $donation_post_data );

            if( $inserted && 0!== $inserted ) {
                $this->success_message = "Event added successfully";
            } else {
                $this->error_message = "Failed to insert donation data.";
            }
        }
        exit;
    }

    public function update_donation_data( $payment_status, $payment_completed, $amount, $name, $email, $phone, $donation_id, $payment_id ) {
        global $wpdb;
        $datetime                       = new DateTime( $payment_completed );
        $payment_date                   = $datetime->format('Y-m-d H:i:s');
        $update_arr                     = [];
        $update_arr['payment_status']   = $payment_status;
        $update_arr['updated_date']     = $payment_date;
        $update_arr['payment_id']       = $payment_id;
      
        $sql_select = "SELECT id FROM `$this->table_name` WHERE `donation_id` = '$donation_id'";
        $getData = $wpdb->get_row($sql_select);

        $sql = $wpdb->update( $this->table_name, $update_arr, array( 'id' => $getData->id ) );
     
        if ( $sql ) {
            echo "<h3 style='color:#6da552'>Thank You, Payment Successful!</h3>";
            echo "<h4 style='color:#6da552'>Keep payment deatils safe for future reference.</h4>";
            echo "<h4>Amount Paid: " . $amount."</h4>"; 
            echo "<h4>Applicant Name: " . $name . "</h4>";
            echo "<h4>Applicant Email: " . $email . "</h4>";
            echo "<h4>Applicant Mobile Number: " . $phone . "</h4>";
        } else {
            echo "Thank you ".$name." your payment is completed.";
        }
        exit;
    }

    /**
     * Shortcode for donation form.
     */
    public function create_donation_form_shortcode() {
        ob_start(); 
        $prd_name = "AAP";?>
          <div class="container">
            <div class="row">
                <div>
                    <img src=<?php echo PLUGIN_DONATION_URL ."/img/orphen.jpeg" ?> alt="">
                </div>
                <div>
                <h1>Donation Form</h1>
                <form method="POST" action="">
                    <div class="mb-3">
                    <label for="phone_number" class="form-label">Donator Fullname</label>
                        <input type="text" aria-label="First name" class="form-control" name="fname">
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone No.</label>
                        <input type="text" class="form-control" id="phone_number" aria-describedby="emailHelp" name="phone_number">
                    </div>
                    <div class="mb-3">
                        <label for="input_email1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="input_email1" aria-describedby="emailHelp" name="email">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                    </div>
                    <div class="mb-3">
                        <label for="donation_amount" class="form-label">Donation Amount</label>
                        <input type="text" class="form-control" id="donation_amount" name="donation_amount">
                    </div>
                    <input type="hidden" name="product_name" value="<?php echo $prd_name; ?>"> 
                    <button type="submit" class="btn btn-primary" name="pay_now">Pay Now</button>
                </form>
                </div>
            </div>
        </div>
    <?php
      return ob_get_clean();
    } 
}

new Donation_Management();

