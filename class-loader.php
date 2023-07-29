<?php
/**
 * Class Loader for Cek Ongkir Gratis Plugin
 */

if (!class_exists('cek_ongkir_gratis')) {

    class cek_ongkir_gratis
    {
        /**
         * Constructor
         */
        public function __construct()
        {
            // Add your constructor code here, if needed.
        }

        /**
         * Run the plugin
         */
        public function run()
        {
            // Load other classes from the 'inc' folder
            require_once plugin_dir_path(__FILE__) . 'inc/class-shortcode.php';
            require_once plugin_dir_path(__FILE__) . 'inc/class-ajax.php';
            require_once plugin_dir_path(__FILE__) . 'inc/class-customizer.php';
        }

        /**
         * Example of a shortcode callback
         */
        public function cek_ongkir_shortcode($atts, $content = null)
        {
            // Implement your shortcode functionality here.
            // This function will be called when the shortcode [cek_ongkir] is used.
            // You can return the output you want to display.
        }
    }
}