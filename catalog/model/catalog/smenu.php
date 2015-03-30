<?php
class ModelCatalogSmenu extends Model {	
	public function getSmenu($smenu_id, $parent=0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "smenu_items
			" . DB_PREFIX . "smenu_items LEFT JOIN " . DB_PREFIX . "smenu_links
				ON (" . DB_PREFIX . "smenu_items.smenu_item_id  = " . DB_PREFIX . "smenu_links.smenu_items_id) 
				WHERE 
					" . DB_PREFIX . "smenu_items.smenu_id = '" . (int)$smenu_id . "' 
					AND " . DB_PREFIX . "smenu_links.smenu_language_id = '" . (int)$this->config->get('config_language_id') . "'
					AND " . DB_PREFIX . "smenu_items.smenu_parent = '" . (int)$parent . "'
				ORDER BY `smenu_order`;");
		
		return $query->rows;
	}
}
?>