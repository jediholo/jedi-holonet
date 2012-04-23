<?php
/*
Template Name: Biography
*/
require_once(TEMPLATEPATH . '/include/AccountInfo.inc.php');
?>
<?php if (!isAjax()) : ?>
<?php get_header(); ?>

    <!-- Content header bar -->
    <div id="contentHeader">
      <!-- Page title -->
      <h2><?php echo get_full_title(true); ?></h2>
      
<?php if (get_root_name() != 'residents') : ?>
      <!-- Navigation -->
      <div class="navigation">
        <ul>
<?php wp_list_pages('title_li=&depth=1&child_of='.get_root_id()); ?>
        </ul>
      </div>
<?php endif; ?>

      <div class="clear"></div>
    </div>

<?php else : ?>
  <!-- Actual content -->
  <div class="content">
<?php endif; ?>

<?php if (have_posts()) : while (have_posts()) : the_post();

$custom = get_post_custom();

$username = $custom['username'][0];
$rank = $custom['rank'][0];
$image = $custom['image'][0];
$title = $custom['title'][0];
$homeworld = $custom['homeworld'][0];
$mentors = $custom['mentors'][0];
$species = $custom['species'][0];
$padawans = $custom['padawans'][0];
$abilities = $custom['abilities'][0];
$reputation = $custom['reputation'][0];
$biography = get_the_content();

if (!empty($abilities)) {
	$abilities = wptexturize(wpautop($abilities));
}
if (!empty($reputation)) {
	$reputation = wptexturize(wpautop($reputation));
}

$imageUrl = null;
if (empty($image) && !empty($username)) $image = $username . '_bio';
if (!empty($image)) {
	if (substr($image, 0, 7) == 'http://') {
		$imageUrl = $image;
	} else {
		$imageAtt = get_attachment_by_name($image);
		if ($imageAtt !== null) {
			$imageUrl = wp_get_attachment_url($imageAtt->ID);
		}
	}
}
?>
    <!-- Post #<?php the_ID(); ?> -->
    <div class="post" id="post-<?php the_ID(); ?>">
      <div class="entry">
        <h3><?php the_title(); ?></h3>

        <div class="tabContainer">
          <ul class="tabList" style="width: 500px;">
            <li style="width: 31%;"><a href="#overview">Overview</a></li>
            <li style="width: 31%;"><a href="#biography">Biography</a></li>
            <li style="width: 31%;"><a href="#account">Account Info</a></li>
          </ul>
          <div class="clear"></div>

          <!-- Overview: picture, homeworld, species, etc. -->
          <div id="overview" class="tabContent">
            <?php if (!empty($rank) || !empty($imageUrl)) : ?>
            <div class="box" style="text-align: center;">
              <?php if (!empty($imageUrl)) : ?>
              <img src="<?php echo $imageUrl; ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" style="max-width: 550px;"/><br />
              <?php endif; ?>
              <?php if (!empty($rank)) : ?>
              <strong><?php echo $rank; if (!empty($title)) echo " - " . $title; ?></strong>
              <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="box">
              <div class="alignleft" style="width: 47%;">
                <?php if (!empty($homeworld)) : ?>
                <p><strong>Homeworld: </strong> <?php echo $homeworld; ?></p>
                <?php endif; ?>
                <?php if (!empty($mentors)) : ?>
                <p><strong>Mentor(s): </strong> <?php echo $mentors; ?></p>
                <?php endif; ?>
              </div>
              <div class="alignright" style="width: 47%;">
                <?php if (!empty($species)) : ?>
                <p><strong>Species: </strong> <?php echo $species; ?></p>
                <?php endif; ?>
                <?php if (!empty($padawans)) : ?>
                <p><strong>Padawan(s): </strong> <?php echo $padawans; ?></p>
                <?php endif; ?>
              </div>
              <div class="clear"></div>
            </div>
          </div>

          <!-- Biography: abilities, reputation, bio -->
          <div id="biography" class="tabContent">
            <?php if (!empty($abilities)) : ?>
            <h3>Abilities</h3>
            <div class="box">
              <?php echo $abilities; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($reputation)) : ?>
            <h3>Reputation</h3>
            <div class="box">
              <?php echo $reputation; ?>
            </div>
            <?php endif; ?>

            <?php if (!empty($biography)) : ?>
            <h3>Biography</h3>
            <div class="box">
              <?php the_content(); ?>
            </div>
            <?php endif; ?>
          </div>

          <!-- Account Information: generated from RPMod -->
          <div id="account" class="tabContent">
            <h3>Account Information</h3>
            <div class="box">
              <?php if (!empty($username)) : ?>
                <?php printAccountInfo($username); ?>
              <?php else : ?>
                <p>No account information available.</p>
              <?php endif; ?>
            </div>
          </div>

        </div>

      </div>
    </div>
<?php endwhile; endif; ?>

    <?php edit_post_link('Edit this page', '<p class="postmetadata">', '</p>'); ?>

<?php if (!isAjax()) : ?>
<?php get_footer(); ?>
<?php else : ?>
  </div><!-- End of content -->
  <script type="text/javascript">initTabs();</script>
<?php endif; ?>