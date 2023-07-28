<?php
class Ajax_Response_Handler {
    private $api_key = 'your-api-key'; // Ganti dengan API key Anda dari RajaOngkir

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

    // Fungsi untuk mengambil data ongkos kirim dari semua ekspedisi di API RajaOngkir
    private function get_shipping_cost_from_all_couriers($origin, $destination, $weight) {
        $url = "https://pro.rajaongkir.com/api/cost";
        $couriers = array('jne', 'tiki', 'pos'); // Daftar kurir yang akan digunakan, Anda dapat menambahkan kurir lain sesuai kebutuhan Anda

        $shipping_costs = array();

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
                    "key: " . $this->api_key,
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if (!$err) {
                $shipping_costs[$courier] = json_decode($response, true);
            }
        }

        return $shipping_costs;
    }
}

// Inisialisasi class
$ajax_response_handler = new Ajax_Response_Handler();
