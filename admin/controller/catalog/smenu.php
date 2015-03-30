<?php 
class ControllerCatalogsmenu extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/smenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/smenu');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/smenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/smenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_smenu->addsmenu($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/smenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/smenu');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_smenu->editsmenu($this->request->get['smenu_id'], $this->request->get['menuItem'], $this->request->post);
			$this->request->post;
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/smenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/smenu');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $smenu_id) {
				$this->model_catalog_smenu->deletesmenu($smenu_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			$this->response->redirect($this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->getList();
	}

	public function deleteitem() {
		$this->language->load('catalog/smenu');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/smenu');
		$json['error']=1;
		if (isset($this->request->post['id'])) {
			$this->model_catalog_smenu->deleteitem($this->request->post['id']);
			$json['respond'] = $this->request->post['id'];
			$json['error']=0;
			$this->session->data['success'] = $this->language->get('text_success');
		}
		$this->response->setOutput(json_encode($json));
	}

	public function getType() {
		$this->language->load('catalog/smenu');
		$json['error']=1;
		if (isset($this->request->post['id'])) {
			if ($this->request->post['id']==1) {
				$this->load->model('catalog/information');
				$informations = $this->model_catalog_information->getInformations();
				$return='<select id="end" class="form-control">';
				foreach ($informations as $information) {
					$return.='<option value="'.$information['information_id'].'">'.$information['title'].'</option>';
				}
				$return.='</select>';
			}
			elseif ($this->request->post['id']==2) {
				$this->load->model('catalog/category');
				$categories = $this->model_catalog_category->getCategories();
				$return='<select id="end" class="form-control">';
				foreach ($categories as $category) {
					$return.='<option value="'.$category['category_id'].'">'.$category['name'].'</option>';
				}
				$return.='</select>';

			}
			elseif ($this->request->post['id']==3) {
				$this->load->model('catalog/product');
				$products = $this->model_catalog_product->getProducts();
				$return='<select id="end" class="form-control">';
				foreach ($products as $product) {
					$return.='<option value="'.$product['product_id'].'">'.$product['name'].'</option>';
				}
				$return.='</select>';
			}
			elseif ($this->request->post['id']==4) {
				$this->load->model('catalog/sigallery');
				$gallerys = $this->model_catalog_sigallery->getSigallerys();
				$return='<select id="end" class="form-control">';
				foreach ($gallerys as $gallery) {
					$return.='<option value="'.$gallery['sigallery_id'].'">'.$gallery['title'].'</option>';
				}
				$return.='</select>';
			}
			elseif ($this->request->post['id']==4) {
				$return='<input type="text" id="end" vlaue="" class="form-control">';
			}
			else
			{
				$return='<select id="end" class="form-control">
				<option value="0">'.$this->language->get('option_main').'</option>
				<option value="1">'.$this->language->get('option_contact').'</option>
				<option value="2">'.$this->language->get('option_returns').'</option>
				<option value="3">'.$this->language->get('option_map').'</option>
				<option value="4">'.$this->language->get('option_brands').'</option>
				<option value="5">'.$this->language->get('option_gift').'</option>
				<option value="6">'.$this->language->get('option_affiliates').'</option>
				<option value="7">'.$this->language->get('option_special').'</option>
				<option value="8">'.$this->language->get('option_account').'</option>
				<option value="9">'.$this->language->get('option_history').'</option>
				<option value="10">'.$this->language->get('option_wishlist').'</option>
				<option value="11">'.$this->language->get('option_newsletter').'</option>
				<option value="12">'.$this->language->get('option_cart').'</option>
				</select>';
			}

			$json['respond'] = $this->request->post['id'];
			$json['result'] = $return;
			$json['error']=0;
		}
		$this->response->setOutput(json_encode($json));
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . $url, 'SSL'),
		);

		$data['add'] = $this->url->link('catalog/smenu/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/smenu/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['smenus'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$smenu_total = $this->model_catalog_smenu->getTotalsmenus();

		$results = $this->model_catalog_smenu->getsmenus($filter_data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/smenu/update', 'token=' . $this->session->data['token'] . '&smenu_id=' . $result['smenu_id'] . $url, 'SSL')
			);

			$data['smenus'][] = array(
				'smenu_id'  => $result['smenu_id'],
				'name'      => $result['name'],	
				'selected'  => isset($this->request->post['selected']) && in_array($result['smenu_id'], $this->request->post['selected']),				
				'action'    => $action
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_status'] = $this->language->get('column_status');
		$data['column_text'] = $this->language->get('column_text');
		$data['column_sort'] = $this->language->get('column_sort');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');



		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_text'] = $this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . '&sort=smenu_text' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $smenu_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/smenu_list.tpl', $data));
	}

	protected function getForm() {
		$this->document->addScript('view/javascript/jquery/nested/jquery-ui.min.js');
		$this->document->addScript('view/javascript/jquery/nested/jquery.mjs.nestedSortable.js');
		$this->document->addStyle('view/stylesheet/css/jquery-ui.css');
		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_smenu'] = $this->language->get('entry_smenu');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['column_title'] = $this->language->get('column_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_list'] = $this->language->get('text_list');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title'] = $this->language->get('entry_title');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');
		$data['button_remove'] = $this->language->get('button_remove');
		$data['button_showchilds'] = $this->language->get('button_showchilds');
		$data['button_showform'] = $this->language->get('button_showform');

		$data['option_type'] = $this->language->get('option_type');
		$data['option_article'] = $this->language->get('option_article');
		$data['option_category'] = $this->language->get('option_category');
		$data['option_product'] = $this->language->get('option_product');
		$data['option_system'] = $this->language->get('option_system');
		$data['text_modal'] = $this->language->get('text_modal');

		$routs=array(0 =>"/",1=>"information/contact", 2=>"account/return/add", 3=>"information/sitemap", 4=>"product/manufacturer", 5=>"account/voucher", 6=>"affiliate/account", 7=>"product/special",
 8=>"route=account/account", 9=>"route=account/order", 10=>"route=account/wishlist", 11=>"route=account/newsletter", 12=>"route=account/newsletter");

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$data['get_data']=$this->request->get;
		$data['post_data']=$this->request->post;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . $url, 'SSL'),
		);

		if (!isset($this->request->get['smenu_id'])) { 
			$data['action'] = $this->url->link('catalog/smenu/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/smenu/update', 'token=' . $this->session->data['token'] . '&smenu_id=' . $this->request->get['smenu_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/smenu', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['smenu_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$smenu_info = $this->model_catalog_smenu->getsmenu($this->request->get['smenu_id']);
			$root_items = $this->model_catalog_smenu->getItems($this->request->get['smenu_id']);
			$data['last'] = $this->model_catalog_smenu->getLastsmenu();
			foreach ($root_items as $items) {
				//echo $items['item_id'];
				$children_data=false;
				$childs = $this->model_catalog_smenu->getItems($this->request->get['smenu_id'], $items['item_id']);
				foreach ($childs as $child) {
					$children_data[] = array(
						'item_id'        => $child['item_id'],
						'order'          => $child['order'],
						'type'           => $child['type'],
						'type_id'        => $child['type_id'],
						'type_name'      => $child['type_name'],
						'description'    => $child['smenu_item_description']
					);
				}
				$data['tree'][] = array(
					'item_id'        => $items['item_id'],
					'order'          => $items['order'],
					'description'    => $items['smenu_item_description'],
					'type'           => $items['type'],
					'type_id'        => $items['type_id'],
					'type_name'      => $items['type_name'],
					'childs'         => $children_data
				);
			}
		}
		else
		{
			$data['tree'] = array();
		}
			/*echo '<pre>';
			print_r($data['tree']);
			echo '</pre>';*/
		//$data['name']=$smenu_info;
		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($smenu_info)) {
			$data['name'] = $smenu_info['name'];
		} else {
			$data['name'] = '';
		}

		/*if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($smenu_info)) {
			$data['status'] = $smenu_info['status'];
		} else {
			$data['status'] = true;
		}*/

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/smenu_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/smenu')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/smenu')) {
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