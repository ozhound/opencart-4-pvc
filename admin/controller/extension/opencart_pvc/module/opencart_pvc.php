<?php
namespace Opencart\Admin\Controller\Extension\OpencartPvc\Module;

class OpencartPvc extends \Opencart\System\Engine\Controller {

    public function install(): void {

        /* DB table */
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_customer_group` (
                product_id INT NOT NULL,
                customer_group_id INT NOT NULL,
                PRIMARY KEY (product_id, customer_group_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        /* register events */
        $this->load->model('setting/event');

        /* store group in registry */
        $this->model_setting_event->addEvent(
            'pvc_filter_before',
            'catalog/controller/*/before',
            'extension/opencart_pvc/module/pvc_filter.before'
        );

        /* filter products */
        $this->model_setting_event->addEvent(
            'pvc_product_filter',
            'catalog/model/catalog/product/getProducts/before',
            'extension/opencart_pvc/module/pvc_filter.apply'
        );

        /* inject product form */
        $this->model_setting_event->addEvent(
            'pvc_product_form',
            'admin/controller/catalog/product.form/after',
            'extension/opencart_pvc/event/opencart_pvc_form.form'
        );

        /* save handler */
        $this->model_setting_event->addEvent(
            'pvc_product_save',
            'admin/controller/catalog/product.save/before',
            'extension/opencart_pvc/event/opencart_pvc_save.saveGroups'
        );
    }


    public function uninstall(): void {

        /* DB table */
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_customer_group`");

        /* remove events */
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('pvc_filter_before');
        $this->model_setting_event->deleteEventByCode('pvc_product_filter');
        $this->model_setting_event->deleteEventByCode('pvc_product_form');
        $this->model_setting_event->deleteEventByCode('pvc_product_save');
    }


    public function index(): void {
        $this->response->setOutput('PVC module');
    }


    public function save(): void {
        $this->response->setOutput('saved');
    }
}
