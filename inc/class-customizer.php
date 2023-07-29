<?php
/**
 * Contains methods for customizing the theme customization screen.
 * 
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since MyTheme 1.0
 */
class RajaOngkir_Customizer_Settings {
    private $theme_mod_name = 'rajaongkir_api_key';

    public function __construct() {
        // Add settings and controls to Customizer
        add_action('customize_register', array($this, 'customize_register'));

        // Sanitize and validate the API key value
        add_filter('customize_sanitize_js_params', array($this, 'sanitize_api_key'), 10, 2);
        add_filter('sanitize_option_' . $this->theme_mod_name, array($this, 'sanitize_api_key'));

        // Enqueue scripts and styles for Customizer
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_customizer_scripts'));
    }

    // Register settings and controls in Customizer
    public function customize_register($wp_customize) {
        $wp_customize->add_section('rajaongkir_options', array(
            'title' => __('RajaOngkir Options', 'mytheme'),
            'priority' => 35,
            'capability' => 'edit_theme_options',
            'description' => __('Allows you to enter your RajaOngkir API key.', 'mytheme'),
        ));

        $wp_customize->add_setting($this->theme_mod_name, array(
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control($this->theme_mod_name, array(
            'type' => 'text',
            'label' => __('RajaOngkir API Key', 'mytheme'),
            'description' => __('Enter your RajaOngkir API key here.', 'mytheme'),
            'section' => 'rajaongkir_options',
        ));
    }

    // Sanitize and validate the API key value
    public function sanitize_api_key($params, $value) {
        if (is_array($params)) {
            $params['value'] = sanitize_text_field($value);
        } else {
            $value = sanitize_text_field($value);
        }
        return $value;
    }

    // Enqueue scripts and styles for Customizer
    public function enqueue_customizer_scripts() {
        wp_enqueue_script('rajaongkir-customizer-script', plugin_dir_url(__FILE__) . 'assets/js/customizer.js', array('jquery'), '1.0', true);
    }
}

// Initialize the class
$rajaongkir_customizer_settings = new RajaOngkir_Customizer_Settings();