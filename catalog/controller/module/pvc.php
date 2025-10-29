<?php
namespace Opencart\Admin\Controller\Module;

class Pvc extends \Opencart\System\Engine\Controller {

    public function install(): void {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_customer_group` (
                product_id INT NOT NULL,
                customer_group_id INT NOT NULL,
                PRIMARY KEY (product_id, customer_group_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // optional event registration here
    }

    public function uninstall(): void {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_customer_group`");

        // optional event unregister
    }

    public function index(): void {
        // optional module settings ui
        $this->response->setOutput('pvc module');
    }

    public function save(): void {
        // optional module settings save
    }
}
