public function install(): void {
    $this->load->model('extension/opencart_pvc/module/opencart_pvc');
    $this->model_extension_opencart_pvc_module_opencart_pvc->install();
}

public function uninstall(): void {
    $this->load->model('extension/opencart_pvc/module/opencart_pvc');
    $this->model_extension_opencart_pvc_module_opencart_pvc->uninstall();
}
