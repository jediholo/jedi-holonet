
<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
  <div id="searchform-query">
    <input type="text" value="<?php the_search_query(); ?>" name="s" id="s" />
  </div>
  <div id="searchform-submit">
    <input type="submit" id="searchsubmit" value="Search" />
  </div>
  <div class="clear"></div>
</form>
