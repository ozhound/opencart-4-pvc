<?php
namespace Opencart\Admin\Model\Extension\OpencartPvc\Module;

class OpencartPvc extends \Opencart\System\Engine\Model {
    
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
}
