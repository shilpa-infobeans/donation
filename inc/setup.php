<?php
/**
 * Setup and create instance for Events.
 *
 * @category File
 * @package  wp-events
 */

/**
 * File loader.
 *
 * @return void
 */
function setup() {

    require PLUGIN_DONATION_DIR . '/inc/admin/admin-enque.php';

    require PLUGIN_DONATION_DIR . '/inc/admin/class-option-pages.php';

    require PLUGIN_DONATION_DIR . '/inc/admin/class-donation-history.php';

    require PLUGIN_DONATION_DIR . '/inc/frontend/front-enque.php';

    require PLUGIN_DONATION_DIR . 'inc/frontend/class-donation-tbl.php';

    require PLUGIN_DONATION_DIR . 'inc/frontend/class-donation-form.php';

}
