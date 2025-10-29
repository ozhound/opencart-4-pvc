<?php
namespace Opencart\Admin\Controller\Module;

class OpencartPvc extends \Opencart\System\Engine\Controller {

    public function install(): void {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_customer_group` (
                product_id INT NOT NULL,
                customer_group_id INT NOT NULL,
                PRIMARY KEY (product_id, customer_group_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function uninstall(): void {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_customer_group`");
    }

    public function index(): void {
        // point to module page
        $this->response->setOutput('PVC module');
    }

    public function save(): void {
        // placeholder
        $this->response->setOutput('saved');
    }
}
