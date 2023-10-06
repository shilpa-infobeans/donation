<?php
/**
 *  Donation Plugin Constant Variable
 *  Author: Shilpa Choudhary
 *  Created Date: 06-10-2023
 */

$api_url        = get_option('instamojo_payment_methods_endpoint', true ) ?? '';
$api_key        = get_option('instamojo_payment_methods_api_key', true ) ?? '';
$api_token      = get_option('instamojo_payment_methods_api_token', true ) ?? '';
$redirect_url   = get_option('instamojo_payment_methods_redirect_url', true ) ?? '';

if ( ! defined( 'PAYMENT_METHOD_ENDPOINT' ) ) {
    define('PAYMENT_METHOD_ENDPOINT', $api_url );
}

if ( ! defined( 'PAYMENT_METHOD_API_KEY' ) ) {
    define('PAYMENT_METHOD_API_KEY', $api_key );
}

if ( ! defined( 'PAYMENT_METHOD_API_TOKEN' ) ) {
    define('PAYMENT_METHOD_API_TOKEN', $api_token );
}

if ( ! defined( 'PAYMENT_METHOD_REDIRECT_URL' ) ) {
    define('PAYMENT_METHOD_REDIRECT_URL', $redirect_url );
}
$api_url        = get_option('instamojo_payment_methods_endpoint', true ) ?? '';
$api_key        = get_option('instamojo_payment_methods_api_key', true ) ?? '';
$api_token      = get_option('instamojo_payment_methods_api_token', true ) ?? '';
$redirect_url   = get_option('instamojo_payment_methods_redirect_url', true ) ?? '';

if ( ! defined( 'PAYMENT_METHOD_ENDPOINT' ) ) {
    define('PAYMENT_METHOD_ENDPOINT', $api_url );
}

if ( ! defined( 'PAYMENT_METHOD_API_KEY' ) ) {
    define('PAYMENT_METHOD_API_KEY', $api_key );
}

if ( ! defined( 'PAYMENT_METHOD_API_TOKEN' ) ) {
    define('PAYMENT_METHOD_API_TOKEN', $api_token );
}

if ( ! defined( 'PAYMENT_METHOD_REDIRECT_URL' ) ) {
    define('PAYMENT_METHOD_REDIRECT_URL', $redirect_url );
}
if ( ! defined( 'DONATION_FORM' ) ) {
    define('DONATION_FORM', 'donation-form');
}

if ( ! defined( 'THANK_YOU' ) ) {
    define('THANK_YOU', 'thank-you-donation');
}