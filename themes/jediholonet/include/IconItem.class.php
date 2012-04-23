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
		$this->setIcon();
	}
	
	public function __toString() {
		$item = "<img src=\"{$this->icon}\" title=\"{$this->title}\" alt=\"{$this->title}\" /><strong>";
		if (!empty($this->link)) {
			$rel = !empty($this->group) ? " rel=\"{$this->group}\"" : '';
			$boxed = $this->boxed ? ' class="lbpModal"' : '';
			$item .= "<a href=\"{$this->link}\" title=\"{$this->title}\"{$rel}{$boxed}>{$this->title}</a>";
		} else {
			$item .= $this->title;
		}
		$item .= "</strong><br />{$this->subtitle1}<br />{$this->subtitle2}";
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
		$iconUrl = get_bloginfo('stylesheet_directory') . '/images/icon_default.png';
		if (!empty($icon)) {
			if (substr($icon, 0, 7) == 'http://') {
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
