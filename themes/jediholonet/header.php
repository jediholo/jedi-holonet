<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<?php if (is_home()) : ?><meta name="description" content="JEDI is a thriving Star Wars role-playing community offering a complete Jedi curriculum to its members, in an immersive universe set after 250 ABY." /><?php endif; ?>
<title><?php echo get_full_title(); ?></title>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
<!--[if lt IE 7]>
<link rel="stylesheet" href="//static.jediholo.net/css/ie6.css" type="text/css" />
<script src="//static.jediholo.net/js/IE9.js" type="text/javascript">IE7_PNG_SUFFIX='.png';</script>
<![endif]-->
<!--[if lt IE 9]>
<script src="//static.jediholo.net/js/DD_roundies_0.0.2a-min.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>

<body>

<!--[if lt IE 7]><div style="height: 59px; position: relative;"><a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." /></a></div><![endif]-->

<div id="mainContainer">

<!-- Header -->
<div class="headerContainer" id="header-<?php echo get_root_name(); ?>">
  <h1><?php bloginfo('name'); ?></h1>
  <img id="logo" src="<?php bloginfo('stylesheet_directory'); ?>/images/header_logo.png" alt="JEDI Holonet Logo" />
  <ul id="mainNav">
    <li id="mainNav-holonet" class="active"><a href="//www.jediholo.net">HoloNet</a></li>
    <li id="mainNav-comport"><a href="//comport.jediholo.net">Comport</a></li>
    <li id="mainNav-rpmod"><a href="//rpmod.jediholo.net">RPMod</a></li>
  </ul>
  <div id="headerFrame"></div>
</div>

<!-- Navigation -->
<div id="navContainer">
  <h2>Navigation</h2>
  <div id="headerJoinLeft"></div>
  <div id="headerJoinRight"></div>
  <div id="navFrame"></div>
  <ul class="navList">
<?php
make_nav_button_by_name(null, 'Galactic Holonews &amp; Holonews Archives');
make_nav_button_by_name('temple', 'Temple Information, Laws and Tips');
make_nav_button_by_name('archives', 'Temple Historical Archives');
make_nav_button_by_name('residents', 'Jedi of the Temple');
make_nav_button_by_name('data', 'Files and Landing Requirements');
make_nav_button_by_name('about', 'Discover more about JEDI');
//make_nav_button('//comport.jediholo.net', 'comport', 'Comport', 'Communications Port');
?>
  </ul>
  <div id="clock"></div>
</div>

<!-- Content -->
<div id="contentContainer">

  <!-- Content frame -->
  <div id="contentFrameTop">
    <div id="contentFrameTopLeft"></div>
    <div id="contentFrameTopSpacer"></div>
    <div id="contentFrameTopRight"></div>
  </div>
  <div id="contentFrameCornerTopLeft"></div>
  <div id="contentFrameCornerTopRight"></div>
  <div id="contentFrameLeft">
    <div id="contentFrameLeftTop"></div>
    <div id="contentFrameLeftSpacer"></div>
    <div id="contentFrameLeftBottom"></div>
  </div>
  <div id="contentFrameRight">
    <div id="contentFrameRightTop"></div>
    <div id="contentFrameRightSpacer"></div>
    <div id="contentFrameRightBottom"></div>
  </div>
  <div id="contentFrameCornerBottomLeft"></div>
  <div id="contentFrameCornerBottomRight"></div>
  <div id="contentFrameBottom">
    <div id="contentFrameBottomLeft"></div>
    <div id="contentFrameBottomSpacer"></div>
    <div id="contentFrameBottomRight"></div>
  </div>

  <!-- Actual content -->
  <div id="content">
