<?php
namespace Opencart\Catalog\Controller\Module;

class PvcFilter extends \Opencart\System\Engine\Controller {

    /* event: catalog/controller/*/before
       stores customer_group_id for later filtering */
    public function before(&$route, &$args): void {
        $gid = 0;
        if ($this->customer->isLogged()) {
            $gid = (int)$this->customer->getGroupId();
        }
        $this->registry->set('pvc_group_id', $gid);
    }

    /* event: catalog/model/catalog/product/getProducts/before
       appends visibility restriction to product filter */
    public function apply(&$route, &$args): void {

        $gid = (int)($this->registry->get('pvc_group_id') ?? 0);

        if (!isset($args[0]) || !is_array($args[0])) {
            return;
        }

        $constraint =
            "p.product_id IN (
                SELECT product_id
                FROM " . DB_PREFIX . "product_customer_group
                WHERE customer_group_id = " . $gid . "
            )";

        if (!empty($args[0]['filter'])) {
            $args[0]['filter'] .= " AND " . $constraint;
        } else {
            $args[0]['filter'] = $constraint;
        }
    }
}
