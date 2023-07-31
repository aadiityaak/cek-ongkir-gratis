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
        $html = '<div class="mt-4">';
        $html .= '<h3>Shipping Costs</h3>';
        $html .= '<table class="table table-bordered">';
    
        // Loop through each courier and its costs
        foreach ($shipping_costs['rajaongkir']['results'] as $result) {
            $courier = $result['name'];
            $html .= '<tr>';
            $html .= '<th colspan="2">' . $courier . '</th>';
            $html .= '</tr>';
    
            // Loop through each service and its cost
            foreach ($result['costs'] as $service) {
                $html .= '<tr>';
                $html .= '<td>Service: ' . $service['service'] . '</td>';
                $html .= '<td>Cost: ' . $service['cost'][0]['value'] . ' ' . $service['cost'][0]['etd'] . '</td>';
                $html .= '</tr>';
            }
        }
    
        $html .= '</table>';
        $html .= '</div>';
    
        return $html;
    }
    

    private function get_shipping_cost_from_all_couriers($origin, $destination, $weight) {
        $url = "https://pro.rajaongkir.com/api/cost";
        $couriers = [
            'pos'       => 'POS Indonesia (POS)',
            'lion'      => 'Lion Parcel (LION)',
            'ninja'     => 'Ninja Xpress (NINJA)',
            'ide'       => 'ID Express (IDE)',
            'sicepat'   => 'SiCepat Express (SICEPAT)',
            'sap'       => 'SAP Express (SAP)',
            'ncs'       => 'Nusantara Card Semesta (NCS)',
            'anteraja'  => 'AnterAja (ANTERAJA)',
            'rex'       => 'Royal Express Indonesia (REX)',
            'jtl'       => 'JTL Express (JTL)',
            'sentral'   => 'Sentral Cargo (SENTRAL)',
            'jne'       => 'Jalur Nugraha Ekakurir (JNE)',
            'tiki'      => 'Citra Van Titipan Kilat (TIKI)',
            'rpx'       => 'RPX Holding (RPX)',
            'pandu'     => 'Pandu Logistics (PANDU)',
            'wahana'    => 'Wahana Prestasi Logistik (WAHANA)',
            'jnt'       => 'J&T Express (J&T)',
            'pahala'    => 'Pahala Kencana Express (PAHALA)',
            // 'slis'      => 'Solusi Ekspres (SLIS)',
            'dse'       => '21 Express (DSE)',
            'first'     => 'First Logistics (FIRST)',
            'star'      => 'Star Cargo (STAR)',
            'idl'       => 'IDL Cargo (IDL)',
            // 'jet'       => 'JET Express (JET)',
            // 'esl'       => 'Eka Sari Lorena (ESL)',
            // 'pcp'       => 'Priority Cargo and Package (PCP)',
            // 'cahaya'    => 'Cahaya Logistik (CAHAYA)',
            // 'indah'     => 'Indah Logistic (INDAH)',
        ];
        
        $couriers = implode(':', array_keys($couriers));

        $shipping_costs = array();
        $api_key = $this->get_api_key(); // Ambil API key setiap kali diperlukan

        $data = "origin={$origin}&originType=city&destination={$destination}&destinationType=subdistrict&weight={$weight}&courier={$couriers}";

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
            $shipping_costs = json_decode($response, true);
        }

        // Kirimkan respon HTML ke sisi klien
        $result = $this->format_shipping_costs($shipping_costs);
        echo $result;
        
        // Hentikan eksekusi lebih lanjut karena ini adalah permintaan AJAX
        wp_die();
    }
}

// Initialize the class
$ajax_response_handler = new Ajax_Response_Handler();