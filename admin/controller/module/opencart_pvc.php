<?php
namespace Opencart\Admin\Controller\Extension\OpencartPvc\Module;

class OpencartPvc extends \Opencart\System\Engine\Controller {
    
    public function index(): void {
        // Call install method first time the module is accessed
        $this->checkAndInstall();
        
        $this->load->language('extension/opencart_pvc/module/opencart_pvc');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/opencart_pvc/module/opencart_pvc', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['save'] = $this->url->link('extension/opencart_pvc/module/opencart_pvc.save', 'user_token=' . $this->session->data['user_token']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

        $data['module_opencart_pvc_status'] = $this->config->get('module_opencart_pvc_status');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/opencart_pvc/module/opencart_pvc', $data));
    }

    private function checkAndInstall(): void {
        // Check if table exists, if not create it
        $query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "product_customer_group'");
        
        if (!$query->num_rows) {
            $this->install();
        }
    }

public function install(): void {
    $this->load->model('extension/opencart_pvc/module/opencart_pvc');
    $this->model_extension_opencart_pvc_module_opencart_pvc->install();
}

public function uninstall(): void {
    $this->load->model('extension/opencart_pvc/module/opencart_pvc');
    $this->model_extension_opencart_pvc_module_opencart_pvc->uninstall();
}

    public function save(): void {
        $this->load->language('extension/opencart_pvc/module/opencart_pvc');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/opencart_pvc/module/opencart_pvc')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('module_opencart_pvc', $this->request->post);
            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}

