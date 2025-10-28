<<<<<<< HEAD
<?php
namespace Opencart\Catalog\Model\Extension\Begateway\Payment;
class Begateway extends \Opencart\System\Engine\Model {
  public function getMethods(array $address = []): array {
    $this->load->language('extension/begateway/payment/begateway');

    if ($this->cart->hasSubscription()) {
        $status = false;
    } elseif (!$this->config->get('payment_begateway_geo_zone_id')) {
        $status = true;
    } else {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_begateway_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
        // if the rows found the status set to True
        if ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }
    }

    $method_data = array();

    if ($status) {
        $option_data['begateway'] = [
            'code' => 'begateway.begateway',
            'name' => $this->config->get('payment_begateway_method_title_' . $this->config->get('config_language'))
        ];

        $method_data = array(
            'code'       => 'begateway',
            'name'       => $this->config->get('payment_begateway_heading_title_' . $this->config->get('config_language')),
            'option'     => $option_data,
            'sort_order' => $this->config->get('payment_begateway_sort_order')
      );
    }

    return $method_data;
  }
}
=======
<?php
namespace Opencart\Catalog\Model\Extension\Begateway\Payment;
class Begateway extends \Opencart\System\Engine\Model {
  public function getMethods(array $address = []): array {
    $this->load->language('extension/begateway/payment/begateway');

    if ($this->cart->hasSubscription()) {
        $status = false;
    } elseif (!$this->config->get('payment_begateway_geo_zone_id')) {
        $status = true;
    } else {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_begateway_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
        // if the rows found the status set to True
        if ($query->num_rows) {
            $status = true;
        } else {
            $status = false;
        }
    }

    $method_data = array();

    if ($status) {
        $option_data['begateway'] = [
            'code' => 'begateway.begateway',
            'name' => $this->config->get('payment_begateway_method_title_' . $this->config->get('config_language'))
        ];

        $method_data = array(
            'code'       => 'begateway',
            'name'       => $this->config->get('payment_begateway_heading_title_' . $this->config->get('config_language')),
            'option'     => $option_data,
            'sort_order' => $this->config->get('payment_begateway_sort_order')
      );
    }

    return $method_data;
  }
}
>>>>>>> dc130c1 (init)
