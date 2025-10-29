<?php
namespace Opencart\Admin\Controller\Extension\OpencartPvc\Module;

class OpencartPvc extends \Opencart\System\Engine\Controller {

    /**
     * install()
     * Called when module is installed in admin.
     * Creates DB table + registers catalog events.
     */
    public function install(): void {

        /* create mapping table product ↔ customer_group */
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_customer_group` (
                product_id INT NOT NULL,
                customer_group_id INT NOT NULL,
                PRIMARY KEY (product_id, customer_group_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        /* register catalog events */
        $this->load->model('setting/event');
        $this->log->write('PVC install() fired');


        /* store logged-in customer group before controllers run */
        $this->model_setting_event->addEvent(
            'pvc_filter_before',                     // internal code
            'catalog/controller/*/before',          // trigger
            'module/pvc_filter.before'              // callback route
        );
        $this->model_setting_event->addEvent(
            'pvc_product_save',
            'admin/controller/catalog/product.save/before',
            'module/opencart_pvc.saveGroups'
        );


        /* constrain product queries by customer group */
        $this->model_setting_event->addEvent(
            'pvc_product_filter',
            'catalog/model/catalog/product/getProducts/before',
            'module/pvc_filter.apply'
        );
        $this->model_setting_event->addEvent(
            'pvc_product_form',
            'admin/controller/catalog/product.form/after',
            'module/opencart_pvc.form'
        );

    }


    /**
     * uninstall()
     * Called when module is uninstalled in admin.
     * Drops DB table + unregisters events.
     */
    public function uninstall(): void {

        /* drop table */
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_customer_group`");

        /* unregister events */
        $this->load->model('setting/event');
        $this->model_setting_event->deleteEventByCode('pvc_filter_before');
        $this->model_setting_event->deleteEventByCode('pvc_product_filter');
        $this->model_setting_event->deleteEventByCode('pvc_product_form');
        $this->model_setting_event->deleteEventByCode('pvc_product_save');

    }


    /**
     * index()
     * Minimal stub to satisfy admin module UI routing.
     */
    public function index(): void {
        $this->response->setOutput('PVC module');
    }


    /**
     * save()
     * Placeholder for admin save logic if needed later.
     */
    public function save(): void {
        $this->response->setOutput('saved');
    }
}
