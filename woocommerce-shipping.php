<?php
/**
 * Plugin Name: WooCommerce Shipping
 * Plugin URI:  #
 * Description: Custom shipping calculation formula
 * Version: 1.0.0
 * Author: Irfan Manzoor Bajwa
 * Author URI: #
 * License: A short license name. Example: GPL2
 */
if(!class_exists('WCShipping'))
{
    class WCShipping
    {
	public $plugin_url;
        /**
         * Construct
         */
        public function __construct()
        {
            require plugin_dir_path( __FILE__ ). 'woocommerce-shipping-calculation.php';
         //   add_action('wp_enqueue_scripts', array( &$this, 'forntjs' ));
         
        }
        public static function plugin_url(){
                return plugin_dir_url( __FILE__ );

        }
        /**
         * Activate the plugin
         */
        public static function activate()
        {	
            add_option( 'wcshipping_plugin', 'installed' );
        } 
        /**
         * Deactivate the plugin
         */     
        static function deactivate()
        {
            delete_option( 'wcshipping_plugin');
        } 
         /**
         * Include Default Scripts and styles
         */  




 
    } // End Class
}

if(class_exists('WCShipping'))
{
    // instantiate the plugin class
	$WCShipping = new WCShipping();
	//require plugin_dir_path( __FILE__ ). 'inc/db.php';
   // register_activation_hook( __FILE__, 'user_record_table' );
    register_deactivation_hook(__FILE__, array('WCShipping', 'deactivate'));

}