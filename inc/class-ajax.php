<?php
class Ajax_Response_Handler {
    private function get_api_key() {
        return get_theme_mod('rajaongkir_api_key');
    }

    public function __construct() {
        // Add action for handling AJAX requests
        add_action('wp_ajax_cek_ongkir', array($this, 'handle_ajax_cek_ongkir'));
        add_action('wp_ajax_nopriv_cek_ongkir', array($this, 'handle_ajax_cek_ongkir'));
    }

    // Callback function for handling "cek_ongkir" AJAX request
    public function handle_ajax_cek_ongkir() {
        // Ambil data dari permintaan AJAX
        $origin = isset($_POST['origin']) ? sanitize_text_field($_POST['origin']) : '';
        $destination = isset($_POST['destination']) ? sanitize_text_field($_POST['destination']) : '';
        $weight = isset($_POST['weight']) ? absint($_POST['weight']) : 0;

        // Lakukan permintaan ke API RajaOngkir untuk mendapatkan data ongkos kirim dari semua ekspedisi
        $response = $this->get_shipping_cost_from_all_couriers($origin, $destination, $weight);

        // Kirimkan respon sebagai JSON ke sisi klien
        wp_send_json($response);
    }

    private function format_shipping_costs($shipping_costs) {
        $html = '<div class="container">';
        $html .= '<h3>Shipping Costs</h3>';
        $html .= '<div class="row">';
    
        foreach ($shipping_costs as $courier => $costs) {
            $html .= '<div class="col-md-4">';
            $html .= '<div class="card">';
            $html .= '<div class="card-body">';
            $html .= '<h5 class="card-title">' . ucfirst($courier) . '</h5>';
            $html .= '<ul class="list-group">';
            
            foreach ($costs['results'] as $result) {
                $html .= '<li class="list-group-item">';
                $html .= 'Service: ' . $result['service'];
                $html .= '<br>Cost: ' . $result['cost'][0]['value'] . ' ' . $result['cost'][0]['etd'];
                $html .= '</li>';
            }
    
            $html .= '</ul>';
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
    
        $html .= '</div>';
        $html .= '</div>';
    
        return $html;
    }

    private function get_shipping_cost_from_all_couriers($origin, $destination, $weight) {
        $url = "https://pro.rajaongkir.com/api/cost";
        $couriers = array('jne', 'tiki', 'pos'); // Daftar kurir yang akan digunakan, Anda dapat menambahkan kurir lain sesuai kebutuhan Anda

        $shipping_costs = array();
        $api_key = $this->get_api_key(); // Ambil API key setiap kali diperlukan

        foreach ($couriers as $courier) {
            $data = "origin={$origin}&originType=city&destination={$destination}&destinationType=subdistrict&weight={$weight}&courier={$courier}";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded",
                    "key: " . $api_key, // Gunakan API key yang diambil dari fungsi get_api_key()
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (!$err) {
                $shipping_costs[$courier] = json_decode($response, true);
            }
        }

        // Kirimkan respon HTML ke sisi klien
        echo $formatted_response;
        
        // Hentikan eksekusi lebih lanjut karena ini adalah permintaan AJAX
        wp_die();
    }
}

// Initialize the class
$ajax_response_handler = new Ajax_Response_Handler();