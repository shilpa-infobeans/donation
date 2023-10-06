<?php
if ( isset( $_POST ) && isset( $_POST['pay_now'] ) ) {
    $product_name   = $_POST["product_name"] ;
    $name           = $_POST["fname"];
    $phone          = $_POST["phone_number"];
    $email          = $_POST["email"];
    $amount         = $_POST["donation_amount"];

    include PLUGIN_DONATION_URL.'src/instamojo.php';
    $api = new Instamojo\Instamojo( PAYMENT_METHOD_API_TOKEN, 
                                    PAYMENT_METHOD_API_KEY,
                                    PAYMENT_METHOD_ENDPOINT);
    try {
            $response = $api->paymentRequestCreate(
                array(
                    "purpose"                   => $product_name,
                    "amount"                    => $amount,
                    "buyer_name"                => $name,
                    "phone"                     => $phone,
                    "send_email"                => true,
                    "send_sms"                  => true,
                    "email"                     => $email,
                    "mobile"                    => $phone,
                    'allow_repeated_payments'   => false,
                    "redirect_url"              => home_url('thank-you-donation'),
                )
            );
    
        $pay_url = $response['longurl'];
        header( "Location: $pay_url" );
        exit();
    } catch ( Exception $e ) {
        print( 'Error: ' . $e->getMessage() );
    }
}