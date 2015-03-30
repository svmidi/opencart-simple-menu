<?php  
class ControllerModuleSmenu extends Controller {
	public function index($setting) {
		static $module = 0;	
		
		$this->language->load('module/smenu'); 

		$data['heading_title'] = $setting['name'];
		
		$this->load->model('catalog/smenu');

		$data['smenus'] = array();

		$results = $this->model_catalog_smenu->getsmenu($setting['menu']);	

		/*foreach ($results as $result) {
			$data['smenus'][] = array(
					'title' => $result['smenu_title'],
					'text' => $result['smenu_text'],
					'link'  => $result['smenu_link']
				);
		}*/

		foreach ($results as $menu_item) {
			$children_data = array();

			$children = $this->model_catalog_smenu->getsmenu($setting['menu'], $menu_item['smenu_item_id']);

			foreach($children as $child) {
				$children_data[] = array(
					'title' => $child['smenu_title'],
					'text' => $child['smenu_text'],
					'link'  => $child['smenu_link']
				);
			}

			$data['smenus'][] = array(
				'title' => $menu_item['smenu_title'],
				'text' => $menu_item['smenu_text'],
				'link'  => $menu_item['smenu_link'],
				'children'    => $children_data
			);
		}
		echo "<pre>";
		print_r($data['smenus']);
		echo "</pre>";
	
		//$data['module'] = $module++;
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/smenu.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/smenu.tpl', $data);
		} else {
			return $this->load->view('default/template/module/smenu.tpl', $data);
		}
	}
}
?>