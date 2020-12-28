<?php
require_once(__DIR__ . '/IconItem.class.php');

class JEDI_IconList {
	private $items = array();
	
	public function __toString() {
		$list = '';
		if (count($this->items) > 0) {
			$list = "<ul class=\"iconlist\">\n";
			foreach ($this->items as $item) {
				$list .= $item . "\n";
			}
			$list .= "</ul>\n";
			$list .= "<div class=\"clear\"></div>\n";
		}
		return $list;
	}
	
	public function addItem(JEDI_IconItem $item) {
		$this->items[] = $item;
	}
	
	public function removeItem(JEDI_IconItem $item) {
		if (($pos = array_search($item, $this->items)) !== false) {
			array_splice($this->items, $pos);
			return true;
		} else {
			return false;
		}
	}
}
