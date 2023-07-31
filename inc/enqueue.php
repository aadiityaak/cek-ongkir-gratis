<?php

/**
 * Enqueue scripts and styles
 */
function cek_ongkir_gratis_enqueue_scripts()
{

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue jQuery UI
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-autocomplete');

    // Enqueue the JavaScript file
    wp_enqueue_script('cek-ongkir-gratis-script', plugins_url('assets/js/cek-ongkir-gratis.js', dirname(__FILE__)), array('jquery-ui-autocomplete'), '1.0', true);
    wp_enqueue_script('cek-ongkir-gratis-customizer', plugins_url('assets/js/customizer.js', dirname(__FILE__)), array('jquery-ui-autocomplete'), '1.0', true);

    // Pass data to JavaScript
    wp_localize_script('cek-ongkir-gratis-script', 'cek_ongkir_gratis_data', array(
        'data_city_url' => plugins_url('../data/data-city.json', __FILE__),
        'data_country_url' => plugins_url('../data/data-country.json', __FILE__),
        'data_state_url' => plugins_url('../data/data-state.json', __FILE__),
        'ajaxurl' => admin_url('admin-ajax.php') // Tambahkan ajaxurl di sini
    ));
}
add_action('wp_enqueue_scripts', 'cek_ongkir_gratis_enqueue_scripts');

function my_enqueue_styles()
{
    // Memuat CSS dari jQuery UI
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.13.0/themes/smoothness/jquery-ui.css');
}
add_action('wp_enqueue_scripts', 'my_enqueue_styles');
