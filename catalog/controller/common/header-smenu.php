<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
		$data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$data['icon'] = '';
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');
		$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		$this->load->model('catalog/smenu');
		$data['categories'] = array();
		$root_items = $this->model_catalog_smenu->getSmenu(0);
		$routs=array(0 =>"/",1=>"information/contact", 2=>"account/return/add", 3=>"information/sitemap", 4=>"product/manufacturer", 5=>"account/voucher", 6=>"affiliate/account", 7=>"product/special", 8=>"account/account", 9=>"account/order", 10=>"account/wishlist", 11=>"account/newsletter", 12=>"account/newsletter");
		$path=array(1=>'information/information', 2=>'product/category', 3 =>'catalog/product', 4=>'information/sigallery', 7=>'catalog/categories', 0=>'');
		$path_url=array(1=>'information_id', 2=>'path', 3=>'path', 4=>'path_gallery', 0=>'', 7=>'');

		foreach ($root_items as $items) {
			$children_data=false;
			$childs = $this->model_catalog_smenu->getSmenu($items['smenu_id'], $items['smenu_item_id']);
			$active = 0;

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
					'item_id'        => $child['smenu_item_id'],
					'href'           => $url,
					'name'           => $child['smenu_text'],
					'title'          => $child['smenu_title']
				);
			}

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
			elseif ($items['type']==7) {
				$url=$this->url->link($path[(int)$items['type']],'','SSL');
				$active = (!$this->request->get)?1:0;
			}
			else {
				$url=$this->url->link($path[(int)$items['type']], "&".$path_url[(int)$items['type']]."=".$items['type_id'], 'SSL');
				//if ((isset($this->request->get['route']))AND($this->request->get['route']==$path[(int)$items['type']]) AND (isset($this->request->get[$path_url[(int)$items['type']]])))
				if ((isset($this->request->get['route']))AND($this->request->get['route']==$path[(int)$items['type']]) AND (isset($this->request->get[$path_url[(int)$items['type']]])) AND ($this->request->get[$path_url[(int)$items['type']]]==(int)$items['type_id']))
				
					$active = 1;
			}

			$data['categories'][] = array(
				'item_id'        => $items['smenu_item_id'],
				'order'          => $items['smenu_order'],
				'name'           => $items['smenu_text'],
				'title'          => $items['smenu_title'],
				'href'           => $url,
				'active'         => $active,
				'children'       => $children_data,
				'column'         => 1
			);
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}