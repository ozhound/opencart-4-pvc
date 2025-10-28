<<<<<<< HEAD
<?php
namespace Opencart\Admin\Controller\Extension\Begateway\Payment;

require_once DIR_EXTENSION . 'begateway/system/library/utils.php';

use Begateway\Utils;

class Begateway extends \Opencart\System\Engine\Controller {
  private $error = array();

  public function index(): void {

    $this->load->language('extension/begateway/payment/begateway');
    
    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    $data['version'] = Utils::getModuleVersion();

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_edit'] = $this->language->get('text_edit');

    $data['text_live_mode'] = $this->language->get('text_live_mode');
    $data['text_test_mode'] = $this->language->get('text_test_mode');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_all_zones'] = $this->language->get('text_all_zones');

    $data['entry_email'] = $this->language->get('entry_email');
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['entry_order_status_completed_text'] = $this->language->get('entry_order_status_completed_text');
    $data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
    $data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
    $data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');
    $data['entry_order_status_failed_text'] = $this->language->get('entry_order_status_failed_text');
    $data['entry_order_status_processing'] = $this->language->get('entry_order_status_processing');
    $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');
    $data['entry_companyid'] = $this->language->get('entry_companyid');
    $data['entry_companyid_help'] = $this->language->get('entry_companyid_help');
    $data['entry_encyptionkey'] = $this->language->get('entry_encyptionkey');
    $data['entry_encyptionkey_help'] = $this->language->get('entry_encyptionkey_help');
    $data['entry_publickey'] = $this->language->get('entry_publickey');
    $data['entry_publickey_help'] = $this->language->get('entry_publickey_help');
    $data['entry_domain_payment_page'] = $this->language->get('entry_domain_payment_page');
    $data['entry_domain_payment_page_help'] = $this->language->get('entry_domain_payment_page_help');
    $data['entry_payment_type'] = $this->language->get('entry_payment_type');
    $data['entry_payment_type_card'] = $this->language->get('entry_payment_type_card');
    $data['entry_payment_type_halva'] = $this->language->get('entry_payment_type_halva');
    $data['entry_payment_type_erip'] = $this->language->get('entry_payment_type_erip');
    $data['entry_test_mode'] = $this->language->get('entry_test_mode');
    $data['entry_test_mode_help'] = $this->language->get('entry_test_mode_help');
    $data['entry_expiry'] = $this->language->get('entry_expiry');
    $data['entry_expiry_help'] = $this->language->get('entry_expiry_help');
    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['tab_general'] = $this->language->get('tab_general');

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      =>  $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/begateway/payment/begateway', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => ' :: '
    );

    $data['action'] = $this->url->link('extension/begateway/payment/begateway.save', 'user_token=' . $this->session->data['user_token'], true);

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']  . '&type=payment', true);

    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();

    $data['languages'] = [];

    foreach ($languages as $lang) {

        if ((bool)$lang['status']) {

            $opt_heading_title = 'payment_begateway_heading_title_' . $lang['code'];
            $opt_method_title = 'payment_begateway_method_title_' . $lang['code'];

            $lang['entry_heading_title_help'] = $this->language->get('entry_heading_title_help');
            $lang['entry_method_title_help'] = $this->language->get('entry_method_title_help');
            $lang['entry_heading_title'] = $this->language->get('entry_heading_title');
            $lang['entry_method_title'] = $this->language->get('entry_method_title');

            if ($this->language->get('code') != $lang['code']) {
                $this->language->load('extension/begateway/payment/begateway', '', $lang['code']);
            }

            $data[$opt_heading_title] = !empty($this->config->get($opt_heading_title)) ? $this->config->get($opt_heading_title) : $this->language->get('payment_begateway_heading_title');
            $data[$opt_method_title] = !empty($this->config->get($opt_method_title)) ? $this->config->get($opt_method_title) : $this->language->get('payment_begateway_method_title');
            
            $this->load->language('extension/begateway/payment/begateway');

            $lang['heading_title'] = $data[$opt_heading_title];
            $lang['method_title'] = $data[$opt_method_title];

            $data['languages'] []= $lang;
        }
    }

    $data['payment_begateway_companyid'] = $this->config->get('payment_begateway_companyid');
    $data['payment_begateway_encyptionkey'] = $this->config->get('payment_begateway_encyptionkey');
    $data['payment_begateway_publickey'] = $this->config->get('payment_begateway_publickey');
    $data['payment_begateway_domain_payment_page'] = $this->config->get('payment_begateway_domain_payment_page');
	$data['payment_begateway_payment_type'] = $this->config->get('payment_begateway_payment_type');
	$data['payment_begateway_erip_service_no'] = $this->config->get('payment_begateway_erip_service_no');
    $data['payment_begateway_completed_status_id'] = $this->config->get('payment_begateway_completed_status_id');
    $data['payment_begateway_failed_status_id'] = $this->config->get('payment_begateway_failed_status_id');
    $data['payment_begateway_expiry'] = $this->config->get('payment_begateway_expiry');

    $this->load->model('localisation/order_status');

    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    $data['payment_begateway_status'] = $this->config->get('payment_begateway_status');
    $data['payment_begateway_test_mode'] = $this->config->get('payment_begateway_test_mode');
    $data['payment_begateway_geo_zone_id'] = $this->config->get('payment_begateway_geo_zone_id');

    $this->load->model('localisation/geo_zone');

    $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    $data['payment_begateway_sort_order'] = $this->config->get('payment_begateway_sort_order');
    $data['user_token'] = $this->session->data['user_token'];

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/begateway/payment/begateway', $data));
  }

  /**
     * save method
     *
     * @return void
     */
    public function save(): void {
        // loading begateway payment language
        $this->load->language('extension/begateway/payment/begateway');
        $json = [];
        $savedSettings = $this->model_setting_setting->getSetting('payment_begateway');

        // checking file modification permission
        if (!$this->user->hasPermission('modify', 'extension/begateway/payment/begateway')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $validate = $this->validate($this->request->post);

        if ($validate) {
            $json['error'] = $validate;
        }

        if (!$json) {
            $this->load->model('setting/setting');
            $mergedConfiguration = array_merge($savedSettings, $this->request->post);

            $this->model_setting_setting->editSetting('payment_begateway', $mergedConfiguration);
            $json['success'] = $this->language->get('text_success');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /** validate settings metod
     *
     * @return null|string
    */
    protected function validate($request) {
        if (empty(trim($request['payment_begateway_companyid'])))
          return $this->language->get('error_companyid');

        if (empty(trim($request['payment_begateway_encyptionkey'])))
          return $this->language->get('error_encyptionkey');

          if (empty(trim($request['payment_begateway_publickey'])))
          return $this->language->get('error_publickey');

        if (empty(trim($request['payment_begateway_domain_payment_page'])))
          return $this->language->get('error_domain_payment_page');

          if (empty($request['payment_begateway_expiry']) || intval($request['payment_begateway_expiry']) <= 0)
          return $this->language->get('error_expiry');

        return null;
    }
}
=======
<?php
namespace Opencart\Admin\Controller\Extension\Begateway\Payment;

require_once DIR_EXTENSION . 'begateway/system/library/utils.php';

use Begateway\Utils;

class Begateway extends \Opencart\System\Engine\Controller {
  private $error = array();

  public function index(): void {

    $this->load->language('extension/begateway/payment/begateway');
    
    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    $data['version'] = Utils::getModuleVersion();

    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_edit'] = $this->language->get('text_edit');

    $data['text_live_mode'] = $this->language->get('text_live_mode');
    $data['text_test_mode'] = $this->language->get('text_test_mode');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_all_zones'] = $this->language->get('text_all_zones');

    $data['entry_email'] = $this->language->get('entry_email');
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['entry_order_status_completed_text'] = $this->language->get('entry_order_status_completed_text');
    $data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
    $data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
    $data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');
    $data['entry_order_status_failed_text'] = $this->language->get('entry_order_status_failed_text');
    $data['entry_order_status_processing'] = $this->language->get('entry_order_status_processing');
    $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');
    $data['entry_companyid'] = $this->language->get('entry_companyid');
    $data['entry_companyid_help'] = $this->language->get('entry_companyid_help');
    $data['entry_encyptionkey'] = $this->language->get('entry_encyptionkey');
    $data['entry_encyptionkey_help'] = $this->language->get('entry_encyptionkey_help');
    $data['entry_publickey'] = $this->language->get('entry_publickey');
    $data['entry_publickey_help'] = $this->language->get('entry_publickey_help');
    $data['entry_domain_payment_page'] = $this->language->get('entry_domain_payment_page');
    $data['entry_domain_payment_page_help'] = $this->language->get('entry_domain_payment_page_help');
    $data['entry_payment_type'] = $this->language->get('entry_payment_type');
    $data['entry_payment_type_card'] = $this->language->get('entry_payment_type_card');
    $data['entry_payment_type_halva'] = $this->language->get('entry_payment_type_halva');
    $data['entry_payment_type_erip'] = $this->language->get('entry_payment_type_erip');
    $data['entry_test_mode'] = $this->language->get('entry_test_mode');
    $data['entry_test_mode_help'] = $this->language->get('entry_test_mode_help');
    $data['entry_expiry'] = $this->language->get('entry_expiry');
    $data['entry_expiry_help'] = $this->language->get('entry_expiry_help');
    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['tab_general'] = $this->language->get('tab_general');

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      =>  $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/begateway/payment/begateway', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => ' :: '
    );

    $data['action'] = $this->url->link('extension/begateway/payment/begateway.save', 'user_token=' . $this->session->data['user_token'], true);

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']  . '&type=payment', true);

    $this->load->model('localisation/language');
    $languages = $this->model_localisation_language->getLanguages();

    $data['languages'] = [];

    foreach ($languages as $lang) {

        if ((bool)$lang['status']) {

            $opt_heading_title = 'payment_begateway_heading_title_' . $lang['code'];
            $opt_method_title = 'payment_begateway_method_title_' . $lang['code'];

            $lang['entry_heading_title_help'] = $this->language->get('entry_heading_title_help');
            $lang['entry_method_title_help'] = $this->language->get('entry_method_title_help');
            $lang['entry_heading_title'] = $this->language->get('entry_heading_title');
            $lang['entry_method_title'] = $this->language->get('entry_method_title');

            if ($this->language->get('code') != $lang['code']) {
                $this->language->load('extension/begateway/payment/begateway', '', $lang['code']);
            }

            $data[$opt_heading_title] = !empty($this->config->get($opt_heading_title)) ? $this->config->get($opt_heading_title) : $this->language->get('payment_begateway_heading_title');
            $data[$opt_method_title] = !empty($this->config->get($opt_method_title)) ? $this->config->get($opt_method_title) : $this->language->get('payment_begateway_method_title');
            
            $this->load->language('extension/begateway/payment/begateway');

            $lang['heading_title'] = $data[$opt_heading_title];
            $lang['method_title'] = $data[$opt_method_title];

            $data['languages'] []= $lang;
        }
    }

    $data['payment_begateway_companyid'] = $this->config->get('payment_begateway_companyid');
    $data['payment_begateway_encyptionkey'] = $this->config->get('payment_begateway_encyptionkey');
    $data['payment_begateway_publickey'] = $this->config->get('payment_begateway_publickey');
    $data['payment_begateway_domain_payment_page'] = $this->config->get('payment_begateway_domain_payment_page');
	$data['payment_begateway_payment_type'] = $this->config->get('payment_begateway_payment_type');
	$data['payment_begateway_erip_service_no'] = $this->config->get('payment_begateway_erip_service_no');
    $data['payment_begateway_completed_status_id'] = $this->config->get('payment_begateway_completed_status_id');
    $data['payment_begateway_failed_status_id'] = $this->config->get('payment_begateway_failed_status_id');
    $data['payment_begateway_expiry'] = $this->config->get('payment_begateway_expiry');

    $this->load->model('localisation/order_status');

    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
    $data['payment_begateway_status'] = $this->config->get('payment_begateway_status');
    $data['payment_begateway_test_mode'] = $this->config->get('payment_begateway_test_mode');
    $data['payment_begateway_geo_zone_id'] = $this->config->get('payment_begateway_geo_zone_id');

    $this->load->model('localisation/geo_zone');

    $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    $data['payment_begateway_sort_order'] = $this->config->get('payment_begateway_sort_order');
    $data['user_token'] = $this->session->data['user_token'];

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/begateway/payment/begateway', $data));
  }

  /**
     * save method
     *
     * @return void
     */
    public function save(): void {
        // loading begateway payment language
        $this->load->language('extension/begateway/payment/begateway');
        $json = [];
        $savedSettings = $this->model_setting_setting->getSetting('payment_begateway');

        // checking file modification permission
        if (!$this->user->hasPermission('modify', 'extension/begateway/payment/begateway')) {
            $json['error'] = $this->language->get('error_permission');
        }

        $validate = $this->validate($this->request->post);

        if ($validate) {
            $json['error'] = $validate;
        }

        if (!$json) {
            $this->load->model('setting/setting');
            $mergedConfiguration = array_merge($savedSettings, $this->request->post);

            $this->model_setting_setting->editSetting('payment_begateway', $mergedConfiguration);
            $json['success'] = $this->language->get('text_success');
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    /** validate settings metod
     *
     * @return null|string
    */
    protected function validate($request) {
        if (empty(trim($request['payment_begateway_companyid'])))
          return $this->language->get('error_companyid');

        if (empty(trim($request['payment_begateway_encyptionkey'])))
          return $this->language->get('error_encyptionkey');

          if (empty(trim($request['payment_begateway_publickey'])))
          return $this->language->get('error_publickey');

        if (empty(trim($request['payment_begateway_domain_payment_page'])))
          return $this->language->get('error_domain_payment_page');

          if (empty($request['payment_begateway_expiry']) || intval($request['payment_begateway_expiry']) <= 0)
          return $this->language->get('error_expiry');

        return null;
    }
}
>>>>>>> dc130c1 (init)
