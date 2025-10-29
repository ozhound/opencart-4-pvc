<?php
namespace Opencart\Admin\Controller\Module;

class OpencartPvcSave extends \Opencart\System\Engine\Controller {

    public function saveGroups(&$route, &$args): void {

        $pid = $args['product_id'] ?? 0;
        if (!$pid) {
            return;
        }

        $groups = $this->request->post['product_customer_group'] ?? [];

        /* clear assigned */
        $this->db->query(
            "DELETE FROM " . DB_PREFIX . "product_customer_group
             WHERE product_id=" . (int)$pid
        );

        /* insert new */
        foreach ($groups as $gid) {
            $this->db->query(
                "INSERT IGNORE INTO " . DB_PREFIX . "product_customer_group
                 SET product_id=" . (int)$pid . ",
                     customer_group_id=" . (int)$gid
            );
        }
    }
}
