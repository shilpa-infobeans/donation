<?php
/**
 * Class for registering a new settings page under Settings.
 */
class Payment_Method_Setting_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Registers a new settings page under Settings.
	 */
	public function admin_menu() {
		add_options_page( __( 'Page Title', 'basetheme' ), __( 'Payment Gateway Details', 'basetheme' ), 'manage_options', 'instamojo_login', [ $this, 'payment_settings_page' ] );
	}

	/**
	 * Settings page display callback.
	 */
	public function payment_settings_page() { 
        $api_data = $_POST;
        $this->save_api_credentials( $api_data );

        $api_url        = get_option('instamojo_payment_methods_endpoint', true ) ?? '';
        $api_key        = get_option('instamojo_payment_methods_api_key', true ) ?? '';
        $api_token      = get_option('instamojo_payment_methods_api_token', true ) ?? '';
        $redirect_url   = get_option('instamojo_payment_methods_redirect_url', true ) ?? '';
        
        ?>
        <div id="payment-credential" class="wrap"> 
            <h2>Payment Settings</h2>
            <form id="form_data" name="form" method="post"><br>                              
                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row"><label>API URL</label></th>
                            <td><input class="form-control" type="text" placeholder="API URL" name="api_url" value="<?php echo $api_url; ?>"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label>Private API Key</label></th>
                            <td><input class="form-control" type="text" placeholder="API Key" name="api_key" value="<?php echo $api_key; ?>"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label>Private Auth Token</label></th>
                            <td><input class="form-control" type="text" placeholder="Auth Token" name="api_token" value="<?php echo $api_token; ?>"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><label>Redirect Url</label></th>
                            <td><input class="form-control" type="text" placeholder="Redirect Url Slug" name="redirect_url" value="<?php echo $redirect_url; ?>"></td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" name="submit_changes" class="button-primary" value="Save Changes">
                </p>
            </form>
        </div>
	<?php }

    /**
	 * Settings page display callback.
	 */
	public function save_api_credentials( $api_data ) { 
        if (isset($api_data['submit_changes']) && !empty($api_data['submit_changes'])) {
            $api_url        = $api_data['api_url'] ?? '';
            $api_key        = $api_data['api_key'] ?? '';
            $api_token      = $api_data['api_token'] ?? '';
            $redirect_url   = $api_data['redirect_url'] ?? '';

            update_option('instamojo_payment_methods_endpoint',$api_url);

            update_option('instamojo_payment_methods_api_key',$api_key);

            update_option('instamojo_payment_methods_api_token',$api_token);

            update_option('instamojo_payment_methods_redirect_url',$redirect_url);

            echo '
            <div id="message" class="updated notice notice-success is-dismissible">
            <p>Setting saved successfully. </p>
            <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
            </button>
            </div>';
        }
    }

}

new Payment_Method_Setting_Page;