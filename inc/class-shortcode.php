<?php
/**
 * Class for Shortcode to Display Ongkir Form
 */

class Shortcode_Class
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Add shortcode handler
        add_shortcode('cek_ongkir_form', array($this, 'display_cek_ongkir_form'));
    }

    /**
     * Callback function for [cek_ongkir_form] shortcode
     */
    public function display_cek_ongkir_form($atts)
    {
        // Shortcode attributes
        $atts = shortcode_atts(array(
            // Add any default attributes you want to set here
        ), $atts);

        // Get the HTML for the form
        $form_html = $this->get_cek_ongkir_form_html();

        return $form_html;
    }

    /**
     * Function to generate the ongkir form HTML
     */
    private function get_cek_ongkir_form_html()
    {
        ob_start();
        ?>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="origin" class="form-label">Origin:</label>
                    <input type="text" name="origin" id="origin" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="destination" class="form-label">Destination:</label>
                    <input type="text" name="destination" id="destination" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="weight" class="form-label">Weight (in grams):</label>
                    <input type="number" name="weight" id="weight" class="form-control" required>
                </div>
                <button id="cek-ongkir-button" type="submit" class="btn btn-primary">Cek Ongkir</button>
            </form>
            <div id="return-ongkir">

            </div>
        <?php
        return ob_get_clean();
    }
}
$shortcode_class = new Shortcode_Class();