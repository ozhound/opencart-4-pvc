<?php
namespace Opencart\Admin\Controller\Module;

class OpencartPvcForm extends \Opencart\System\Engine\Controller {

    public function form(&$route, &$data): void {

        /* fetch groups */
        $this->load->model('customer/customer_group');
        $groups = $this->model_customer_customer_group->getCustomerGroups();

        /* fetch assigned */
        $pid = $data['product_id'] ?? 0;
        $assigned = [];

        if ($pid) {
            $q = $this->db->query(
                "SELECT customer_group_id
                 FROM " . DB_PREFIX . "product_customer_group
                 WHERE product_id=" . (int)$pid
            );
            $assigned = array_column($q->rows, 'customer_group_id');
        }

        $data['pvc_groups']   = $groups;
        $data['pvc_assigned'] = $assigned;

        /* render injected twig */
        $data['pvc_html'] = $this->load->view('module/opencart_pvc_form', $data);
    }
}
