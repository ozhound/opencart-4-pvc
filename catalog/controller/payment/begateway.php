<?php
namespace Opencart\Catalog\Controller\Extension\Begateway\Payment;

require_once DIR_EXTENSION . 'begateway/system/library/vendor/autoload.php';
require_once DIR_EXTENSION . 'begateway/system/library/utils.php';

use Begateway\Utils;

class Begateway extends \Opencart\System\Engine\Controller {
  public function __construct($registry)
	{
		parent::__construct($registry);

        \BeGateway\Settings::$checkoutBase = 'https://' . $this->config->get('payment_begateway_domain_payment_page');
        \BeGateway\Settings::$shopId = $this->config->get('payment_begateway_companyid');
        \BeGateway\Settings::$shopKey = $this->config->get('payment_begateway_encyptionkey');
        \BeGateway\Settings::$shopPubKey = $this->config->get('payment_begateway_publickey');
	}

  public function index(): string {
    $this->language->load('extension/begateway/payment/begateway');
    $this->load->model('checkout/order');

    $data['button_confirm'] = $this->language->get('button_confirm');
    $data['confirm_url'] = $this->url->link('extension/begateway/payment/begateway|confirm', 'language=' . $this->config->get('config_language'), true);

    return $this->load->view('extension/begateway/payment/begateway', $data);
  }

  public function generateToken(){

    $token = new \BeGateway\GetPaymentToken;

    $this->load->model('checkout/order');
    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    $order_amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

    $token->setTrackingId($order_info['order_id']);
    $token->money->setCurrency($order_info['currency_code']);
    $token->money->setAmount($order_amount);
    $token->setDescription($this->language->get('text_order'). ' ' .$order_info['order_id']);

    $token->customer->setFirstName(strlen($order_info['payment_firstname']) > 0 ? $order_info['payment_firstname'] : null);
    $token->customer->setLastName(strlen($order_info['payment_lastname']) > 0 ? $order_info['payment_lastname'] : null);
    $token->customer->setCountry(strlen($order_info['payment_iso_code_2']) > 0 ? $order_info['payment_iso_code_2'] : null);
    $token->customer->setCity(strlen($order_info['payment_city']) > 0 ? $order_info['payment_city'] : null);
    $token->customer->setPhone(strlen($order_info['telephone']) > 0 ? $order_info['telephone'] : null);
    $token->customer->setZip(strlen($order_info['payment_postcode']) > 0 ? $order_info['payment_postcode'] : null);
    $token->customer->setAddress(strlen($order_info['payment_address_1']) > 0 ? $order_info['payment_address_1'] : null);
    $token->customer->setEmail(strlen($order_info['email']) > 0 ? $order_info['email'] : null);

    if (in_array($token->customer->getCountry(), array('US', 'CA'))) {
        $token->customer->setState($order_info['payment_zone_code']);
    }

    $callback_url = $this->url->link('extension/begateway/payment/begateway|webhook', '', 'SSL');
    $callback_url = str_replace('0.0.0.0', 'webhook.begateway.com:8443', $callback_url);
    
    $token->setSuccessUrl($this->url->link('extension/begateway/payment/begateway|return', '', 'SSL'));
    $token->setDeclineUrl($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), 'SSL'));
    $token->setFailUrl($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), 'SSL'));
    $token->setCancelUrl($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), 'SSL'));
    $token->setNotificationUrl($callback_url);

    $token->setExpiryDate(date("c", intval($this->config->get('payment_begateway_expiry') * 60 + time() + 1)));

    $token->setLanguage($this->language->get('code'));

    $token->additional_data->setPlatformData('OpenCart v' . VERSION);
    $token->additional_data->setIntegrationData('BeGateway Payment Extension v' . Utils::getModuleVersion(false));

    $pm_type = $this->config->get('payment_begateway_payment_type');

    if ($pm_type['card'] == 1) {
        $cc = new \BeGateway\PaymentMethod\CreditCard;
        $token->addPaymentMethod($cc);
    }

    if ($pm_type['halva'] == 1) {
        $halva = new \BeGateway\PaymentMethod\CreditCardHalva;
        $token->addPaymentMethod($halva);
    }

    if ($pm_type['erip'] == 1) {

        $erip_data = array(
            'order_id' => $order_info['order_id'],
            'account_number' => ltrim($order_info['order_id']),
            'service_info' => array($token->getDescription())
        );

        if (strlen($this->config->get('payment_begateway_erip_service_no')) > 0) {
            $erip_data['service_no'] = $this->config->get('payment_begateway_erip_service_no');
        }

        $erip = new \BeGateway\PaymentMethod\Erip($erip_data);
        $token->addPaymentMethod($erip);
    }

    if (intval($this->config->get('payment_begateway_test_mode')) == 1) {
        $token->setTestMode(true);
    }

    $response = $token->submit();

    return $response;
  }

  public function return() {
    $this->load->model('checkout/order');
    $this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), 'SSL'));
  }

  public function webhook() {

    $webhook = new \BeGateway\Webhook;

    $this->log->write("Webhook received: " . $webhook->getRawResponse());

    $this->load->model('checkout/order');

    $order_id = $webhook->getTrackingId();
    $id = $webhook->getUid();
    $message = $webhook->getMessage();

    $order_info = $this->model_checkout_order->getOrder($order_id);

    if (!$webhook->isAuthorized())
        die('Not authorized');

    if (!$order_info)
        die('No order');

    // $this->model_checkout_order->addHistory($order_id, $this->config->get('config_order_status_id'));

    if ($webhook->isSuccess()) {
        $this->model_checkout_order->addHistory(
            $order_id,
            $this->config->get('payment_begateway_completed_status_id'), 
            "ID: $id Processor message: $message", true);
    } elseif ($webhook->isFailed()) {
        $this->model_checkout_order->addHistory(
            $order_id,
            $this->config->get('payment_begateway_failed_status_id'), 
            "UID: $id. Fail reason: $message", true);
    }
  }

  /**
     * confirm
     *
     * @return json|string
     */
    public function confirm(): void
    {
        // loading example payment language
        $this->load->language('extension/begateway/payment/begateway');
        $json = [];
        if (!isset($this->session->data['order_id'])) {
            $json['error'] = $this->language->get('error_order');
        }
        
        if (!isset($this->session->data['payment_method']) || $this->session->data['payment_method']['code'] != 'begateway.begateway') {
            $json['error'] = $this->language->get('error_payment_method');
        }

        if (!$json) {

            $this->load->model('checkout/order');
            $response = $this->generateToken();

            if ($response->isSuccess()) {
                $this->model_checkout_order->addHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'));
                $json['redirect'] = $response->getRedirectUrl();
            } else {
                $json['error'] = $this->language->get('error_get_transaction');
                $json['redirect'] = $this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true);
            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
