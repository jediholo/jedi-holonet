<?php

/**************/
/* Shortcodes */
/**************/

function jedi_search_shortcode() {
	$query = get_search_query();
	$url = get_bloginfo('url');
	return <<<EOF
<form method="get" id="searchform" action="$url/">
  <input type="text" value="$query" name="s" id="s" />
  <input type="submit" id="searchsubmit" value="Search" />
  <div style="clear: both;"></div>
</form>
EOF;
}
