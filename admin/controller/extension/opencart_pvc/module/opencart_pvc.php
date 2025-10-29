<?php
namespace Opencart\Admin\Controller\Extension\OpencartPvc\Module;

class OpencartPvc extends \Opencart\System\Engine\Controller {

    /* install(): create DB table only */
    public function install(): void {

        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_customer_group` (
                product_id INT NOT NULL,
                customer_group_id INT NOT NULL,
                PRIMARY KEY (product_id, customer_group_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    /* uninstall(): drop table only */
    public function uninstall(): void {

        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_customer_group`");
    }

    /* index(): stub */
    public function index(): void {
        $this->response->setOutput('PVC module');
    }
}
