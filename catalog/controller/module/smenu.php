<?php  
class ControllerModuleSmenu extends Controller {
	public function index($setting) {
		static $module = 0;	
		
		$this->language->load('module/smenu'); 

		$data['heading_title'] = $setting['name'];
		
		$this->load->model('catalog/smenu');

		$data['smenus'] = array();

		$root_items = $this->model_catalog_smenu->getSmenu($setting['menu']);
		$routs=array(0 =>"/",1=>"information/contact", 2=>"account/return/add", 3=>"information/sitemap", 4=>"product/manufacturer", 5=>"account/voucher", 6=>"affiliate/account", 7=>"product/special", 8=>"account/account", 9=>"account/order", 10=>"account/wishlist", 11=>"account/newsletter", 12=>"account/newsletter");
		$path=array(1=>'information/information', 2=>'product/category', 3 =>'catalog/product', 4=>'information/sigallery');
		$path_url=array(1=>'information_id', 2=>'path', 3=>'path', 4=>'path_gallery');

		foreach ($root_items as $items) {
			$children_data=false;
			$childs = $this->model_catalog_smenu->getSmenu($items['smenu_id'], $items['smenu_item_id']);
			$active = 0;


			if ($items['type']==5) {
				$url=$items['type_name'];
			}
			elseif (($items['type']==6) AND ($items['type_id']!=0)) {
				$url=$this->url->link($routs[(int)$items['type_id']],"", 'SSL');
				if (isset($this->request->get['route']))
				{
					$active = ($this->request->get['route'] == $routs[(int)$items['type_id']])?'active':'';
				}
			}
			elseif (($items['type']==6) AND ($items['type_id']==0)) {
				$url="/";
				$active = (!$this->request->get)?1:0;
			}
			else {
				$url=$this->url->link($path[(int)$items['type']], "&".$path_url[(int)$items['type']]."=".$items['type_id'], 'SSL');
				if ((isset($this->request->get['route']))AND($this->request->get['route']==$path[(int)$items['type']]) AND (isset($this->request->get[$path_url[(int)$items['type']]])) AND ($this->request->get[$path_url[(int)$items['type']]]==(int)$items['type_id']))
					$active = 1;
			}

			if ($active) {
				foreach ($childs as $child) {
					if ($child['type']==5) {
						$url=$items['type_name'];
					}
					if ($child['type']==6) {
						$url=$this->url->link($routs[(int)$child['type']],"", 'SSL');
					}
					else {
						$url=$this->url->link($path[(int)$child['type']], "&".$path_url[(int)$child['type']]."=".$child['type_id'], 'SSL');
					}
					$children_data[] = array(
						'item_id'  => $child['smenu_item_id'],
						'href'     => $url,
						'name'     => $child['smenu_text'],
						'title'    => $child['smenu_title']
					);
				}
			}

			$data['items'][] = array(
				'item_id'        => $items['smenu_item_id'],
				'name'           => $items['smenu_text'],
				'title'          => $items['smenu_title'],
				'href'           => $url,
				'active'         => $active,
				'children'       => $children_data
			);
		}
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/smenu.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/smenu.tpl', $data);
		} else {
			return $this->load->view('default/template/module/smenu.tpl', $data);
		}
	}
}
?>