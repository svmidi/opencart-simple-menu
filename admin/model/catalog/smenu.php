<?php
class ModelCatalogsmenu extends Model {
	public function addsmenu($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "smenu SET 
			`name` = '" .  $data['name'] . "'");
		$smenu_id = $this->db->getLastId();
		foreach ($data['smenu_item'] as $smenu_item) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "smenu_items SET 
				`smenu_parent` = '" .  (int)$smenu_item['parent'] . "', 
				`smenu_id` = '" .  (int)$smenu_id . "', 
				`smenu_order` = '" .  (int)$smenu_item['order'] . "'");
			$item_id = $this->db->getLastId();
			foreach ($smenu_item['smenu_item_description'] as $lang => $smenu_link) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "smenu_links SET 
					`smenu_items_id` = '" . (int)$item_id . "', 
					`smenu_text` = '" .  $this->db->escape($smenu_link['text']) . "', 
					`smenu_id` = '" .  (int)$smenu_id . "', 
					`smenu_title` = '" .  $this->db->escape($smenu_link['title']) . "', 
					`smenu_language_id` = '".$lang."'");
			}
		}
	}

	public function editsmenu($smenu_id, $sort_data, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "smenu SET 
			`name` = '" .  $data['name'] . "'
		WHERE `smenu_id` = '" . (int)$smenu_id . "'");
		$i=0;
		foreach ($sort_data as $id_item => $smenu_item) {
			$i++;
			$this->db->query("INSERT INTO `" . DB_PREFIX . "smenu_items` (`smenu_item_id`, `smenu_parent`,`smenu_id`,`smenu_order`, `type`,`type_id`, `type_name`) 
				VALUES ('". $id_item ."', '" . (int)$smenu_item . "', '" . (int)$smenu_id . "', '" . $i . "', '". $data['smenu_item'][$id_item]['type']."', '". $data['smenu_item'][$id_item]['type-id']."', '". $data['smenu_item'][$id_item]['type-name']."')
			ON DUPLICATE KEY 
				UPDATE 
					`smenu_parent` = '" .  (int)$smenu_item . "', 
					`smenu_order` = '" . $i . "', 
					`type` = '". $data['smenu_item'][$id_item]['type']."',
					`type_id` = '". $data['smenu_item'][$id_item]['type-id']."',
					`type_name` = '". $data['smenu_item'][$id_item]['type-name']."';");

			foreach ($data['smenu_item'][$id_item]['smenu_item_description'] as $lang => $smenu_link) {
				
				$this->db->query("INSERT INTO `" . DB_PREFIX . "smenu_links` (
						`smenu_items_id`, 
						`smenu_text`, 
						`smenu_id`, 
						`smenu_title`, 
						`smenu_language_id`
					) 
					VALUES (
						'" . (int)$id_item . "', 
						'" .  $this->db->escape($smenu_link['text']) . "', 
						'" .  (int)$smenu_id . "', 
						'" .  $this->db->escape($smenu_link['title']) . "', 
						'".$lang."'
					)
				ON DUPLICATE KEY UPDATE 
					`smenu_text` = '" .  $this->db->escape($smenu_link['text']) . "', 
					`smenu_title` = '" .  $this->db->escape($smenu_link['title']) . "';");
			}
		}
	}

	public function deletesmenu($smenu_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "smenu WHERE `smenu_id` = '" . (int)$smenu_id . "';");
		$this->db->query("DELETE FROM " . DB_PREFIX . "smenu_items WHERE `smenu_id` = '" . (int)$smenu_id . "';");
		$this->db->query("DELETE FROM " . DB_PREFIX . "smenu_links WHERE `smenu_id` = '" . (int)$smenu_id . "';");
	}

	public function deleteitem($smenu_item_id) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "smenu_items WHERE `smenu_item_id` = '" . (int)$smenu_item_id . "' OR `smenu_parent` = '" . (int)$smenu_item_id . "';");
			$this->db->query("DELETE FROM " . DB_PREFIX . "smenu_links WHERE `smenu_items_id` = '" . (int)$smenu_item_id . "';");
		}

	public function getsmenu($smenu_id) {			
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "smenu WHERE `smenu_id` = '" . (int)$smenu_id . "';");
		return $query->row;
	}

	public function getsmenus($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "smenu";

		$sort_data = array(
			'name',
			'status'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY `name`";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}					

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}		

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getItems($smenu_id, $parent=0) {
		$smenu_link_data = array();

		$smenu_link_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "smenu_items 
			WHERE `smenu_id` = '" . (int)$smenu_id . "' AND `smenu_parent` = '" . (int)$parent . "'
			ORDER BY  `smenu_order` ASC;");

		foreach ($smenu_link_query->rows as $smenu_links) {
			$smenu_link_description_data = array();

			$smenu_link_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "smenu_links WHERE `smenu_items_id` = '" . (int)$smenu_links['smenu_item_id'] . "'");

			foreach ($smenu_link_description_query->rows as $smenu_link_description) {			
				$smenu_link_description_data[$smenu_link_description['smenu_language_id']] = array(
					'title' => $smenu_link_description['smenu_title'],
					'text' => $smenu_link_description['smenu_text']
					);
			}

			$smenu_link_data[] = array(
				'smenu_item_description' => $smenu_link_description_data,
				'parent'                 => $smenu_links['smenu_parent'],
				'item_id'                => $smenu_links['smenu_item_id'],
				'menu_id'                => $smenu_links['smenu_id'],
				'type'                   => $smenu_links['type'],
				'type_id'                => $smenu_links['type_id'],
				'type_name'              => $smenu_links['type_name'],
				'order'                  => $smenu_links['smenu_order']
			);
		}

		return $smenu_link_data;
	}

	public function getTotalsmenus() {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM " . DB_PREFIX . "smenu");
		return $query->row['total'];
	}

	public function getLastsmenu() {
		$smenu_max = $this->db->query("SHOW TABLE STATUS FROM `".DB_DATABASE."` LIKE '" . DB_PREFIX . "smenu_items';");
		return $smenu_max->rows[0]['Auto_increment'];
	}	
	
}
?>