<?php 

/* Template Name: Thank You */ 

namespace IB\DONATION;
use Instamojo;
use IB\DONATION\Donation_Management;

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="../../favicon.ico">
        <title>BNCA: Payment Success</title>

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <style>
            #details-container {
                padding-left: 50px;
            }
            @media(max-width: 600px) {
                #details-container  {
                    padding-left: 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="page-header">
                <h1>Demo Instamojo Product Payment </h1>
            </div>
            <?php

            include PLUGIN_DONATION_DIR.'src/instamojo.php';

            $api = new Instamojo\Instamojo( PAYMENT_METHOD_API_KEY, PAYMENT_METHOD_API_TOKEN,PAYMENT_METHOD_ENDPOINT );
            $payid = $_GET["payment_request_id"] ?? '';
            try {
                $response           = $api->paymentRequestStatus($payid);
                $payment_id         = $response['payments'][0]['payment_id'] ?? '';
                $amount             = $response['payments'][0]['amount'] ?? ''; 
                $name               = $response['payments'][0]['buyer_name'] ?? '';
                $email              = $response['payments'][0]['buyer_email'] ?? '';
                $phone              = $response['payments'][0]['buyer_phone'] ?? '';
                $acount_status      = $response['payments'][0]['status'] ?? '';
                $payment_status     = $response['status'] ?? '';
                $payment_completed  = $response['payments'][0]['created_at'] ?? '';
                $donation_id        = $response['id'] ?? '';
                if ( $response && $payment_status  === 'Completed' && $acount_status === 'Credit' ) {
                    $update_payment = new Donation_Management();
                    $update_payment->update_donation_data( $payment_status, $payment_completed, $amount, $name, $email, $phone, $donation_id, $payment_id  );
                }
            } catch (\Exception $e) {
                print('Error: ' . $e->getMessage());
            }
            ?>
        </div> <!-- /container -->
    </body>
</html>