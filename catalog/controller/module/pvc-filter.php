<?php
namespace Opencart\Catalog\Controller\Module;

class PvcFilter extends \Opencart\System\Engine\Controller {

    // event target: catalog/controller/*/before
    public function before(&$route, &$args): void {

        $group_id = 0;
        if ($this->customer->isLogged()) {
            $group_id = (int)$this->customer->getGroupId();
        }

        // store for model access
        $this->registry->set('pvc_group_id', $group_id);
    }
}
