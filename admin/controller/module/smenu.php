<?php
class ControllerModulesmenu extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('module/smenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('smenu', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}
			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_menu'] = $this->language->get('entry_menu');
		$data['button_control'] = $this->language->get('button_control');

		$data['button_control_href'] = $this->url->link('catalog/smenu', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/smenu', 'token=' . $this->session->data['token'], 'SSL')
		);

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('module/smenu', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('module/smenu', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], 'SSL');
		}
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['modules'] = array();

		if (isset($this->request->post['smenu_module'])) {
			$data['modules'] = $this->request->post['smenu_module'];
		} elseif ($this->config->get('smenu_module')) { 
			$data['modules'] = $this->config->get('smenu_module');
		}	

		$this->load->model('catalog/smenu');

		$data['menus'] = $this->model_catalog_smenu->getSmenus();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('module/smenu.tpl', $data));
	}
	
	public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "smenu` (
			`smenu_id` int(11) NOT NULL AUTO_INCREMENT,
			`smenu_status` tinyint(1) NOT NULL,
			`name` tinytext NOT NULL,
			PRIMARY KEY (`smenu_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "smenu_items` (
			`smenu_item_id` int(11) NOT NULL AUTO_INCREMENT,
			`smenu_order` int(11) NOT NULL,
			`smenu_parent` int(11) NOT NULL,
			`smenu_id` int(11) NOT NULL,
			`type` int(11) NOT NULL,
			`type_id` int(11) NOT NULL,
			`type_name` text NOT NULL,
		PRIMARY KEY (`smenu_item_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "smenu_links` (
			`smenu_items_id` int(11) NOT NULL,
			`smenu_text` varchar(64) NOT NULL,
			`smenu_title` varchar(64) NOT NULL,
			`smenu_language_id` int(11) NOT NULL,
			`smenu_id` int(11) NOT NULL,
		UNIQUE KEY `smenu_items_id_3` (`smenu_items_id`,`smenu_language_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
    }
	
	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "smenu");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "smenu_items");
		$this->db->query("DROP TABLE IF EXISTS " . DB_PREFIX . "smenu_links");
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/smenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>