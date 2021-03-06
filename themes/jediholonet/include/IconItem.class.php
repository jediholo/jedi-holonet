<?php
class IconItem {
	private $group;
	private $icon;
	private $title;
	private $link;
	private $subtitle1;
	private $subtitle2;
	private $boxed = true;
	
	public function __construct($title) {
		$this->setTitle($title);
	}
	
	public function __toString() {
		$style = empty($this->icon) ? '' : " style=\"background-image: url('{$this->icon}')\"";
		$item = "<span class=\"img\"{$style} title=\"{$this->title}\"></span>";
		$item .= "<strong>{$this->title}</strong>";
		if (!empty($this->link)) {
			$rel = !empty($this->group) ? " rel=\"{$this->group}\"" : '';
			$boxed = $this->boxed ? ' class="lbpModal"' : '';
			$item = "<a href=\"{$this->link}\" title=\"{$this->title}\"{$rel}{$boxed}>{$item}</a>";
		}
		$item .= "<br />{$this->subtitle1}";
		$item .= "<br />{$this->subtitle2}";
		$item = "<li class=\"iconitem\">{$item}</li>";
		return $item;
	}
	
	public function getGroup() {
		return $this->group;
	}

	public function setGroup($group) {
		$this->group = $group;
	}
		
	public function getIcon() {
		return $this->icon;
	}
	
	public function setIcon($icon = null) {
		$iconUrl = null;
		if (!empty($icon)) {
			if (substr($icon, 0, 7) == 'http://' || substr($icon, 0, 8) == 'https://') {
				$iconUrl = $icon;
			} else {
				$iconAtt = get_attachment_by_name($icon);
				if ($iconAtt !== null) {
					$iconUrl = wp_get_attachment_url($iconAtt->ID);
				}
			}
		}
		$this->icon = $iconUrl;
	}
	
	public function getTitle() {
		return $this->title;
	}
	
	public function setTitle($title) {
		$this->title = $title;
	}
	
	public function getLink() {
		return $this->link;
	}
	
	public function setLink($link) {
		$this->link = $link;
	}
	
	public function getSubtitle1() {
		return $this->subtitle1;
	}
	
	public function setSubtitle1($subtitle1) {
		$this->subtitle1 = $subtitle1;
	}
	
	public function getSubtitle2() {
		return $this->subtitle2;
	}
	
	public function setSubtitle2($subtitle2) {
		$this->subtitle2 = $subtitle2;
	}
	
	public function getBoxed() {
		return $this->boxed;
	}
	
	public function setBoxed($boxed) {
		$this->boxed = $boxed ? true : false;
	}
}
