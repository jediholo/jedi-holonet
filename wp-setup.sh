#!/bin/sh
#
# Wordpress setup script, using WP-CLI.
#

# Install site
wp core install --url=www.dev.jediholo.net --title="JEDI HoloNet" --admin_user=admin --admin_password=admin --admin_email=admin@jediholo.net --skip-email

# Update if necessary
wp core update
wp core update-db
wp plugin update --all
wp theme update --all

# Set options
wp option update blogdescription 'The JEDI Order HoloNet'
wp option update date_format '$J.d'
wp option update time_format 'H:i'
wp option update timezone_string 'America/New_York'
wp option update posts_per_page '5'
wp option update permalink_structure '/%year%/%monthnum%/%postname%/'
wp option update default_comment_status 'closed'
wp option update default_ping_status 'closed'
wp option update comment_moderation '1'
wp option update comment_registration '1'
wp option update show_avatars '0'

# Configure plugins/theme
wp plugin uninstall akismet hello.php
wp plugin install classic-editor classic-widgets daggerhart-openid-connect-generic easy-wp-smtp embed-iframe google-calendar-events google-sitemap-generator notification redis-cache wonderm00ns-simple-facebook-open-graph-tags wp-crontrol wp-super-cache wp-piwik wptouch --activate
wp plugin activate jedi-plugins
wp theme activate jediholonet
wp redis enable

# Configure widgets
wp widget reset sidebar-header sidebar-1 sidebar-2 sidebar-5
wp widget add jwidget_recent_posts sidebar-header 1 --title='Latest HoloNews' --number=5 --show_date=true --maxlength=22
wp widget add jwidget_recent_pages sidebar-header 2 --title='Latest Archives Articles' --number=5 --show_date=true --maxlength=22 --parent='archives'
wp widget add search sidebar-1 1 --title='Search the HoloNet'
wp widget add jwidget_context sidebar-1 2
wp widget add jwidget_tracker sidebar-2 1 --title='JEDI Temple' --server='temple.jediholo.net:29070'
wp widget add jwidget_tracker sidebar-2 2 --title='JEDI Grounds' --server='grounds.jediholo.net:29071'
wp widget add jwidget_tracker sidebar-2 3 --title='JEDI Galaxy' --server='galaxy.jediholo.net:29073'
wp widget add archives sidebar-5 1 --title='HoloNews Archives' --count=1 --dropdown=1
wp widget add categories sidebar-5 2 --title='HoloNews Categories' --count=1 --hierarchical=0 --dropdown=0
wp widget add jwidget_pages sidebar-5 3 --title='Archives Categories' --sortby=menu_order --parent='archives' --depth=1

# Create categories
wp term create category 'Business'
wp term create category 'Entertainment and Arts'
wp term create category 'Galactic'
wp term create category 'Jedi'
wp term create category 'Regional'
wp term create category 'Science and Technology'
wp term create category 'Sports'

# Create main pages
wp post delete $(wp post list --post_type=page --format=ids)
temple_id=$(wp post create --post_type=page --post_title='Temple' --post_status=publish --porcelain)
wp post create --post_type=page --post_title='Servers' --post_status=publish --post_parent=$temple_id --post_content='[rpmod-server jedi-temple]' --menu_order=1
wp post create --post_type=page --post_title='Rules' --post_status=publish --post_parent=$temple_id --menu_order=2
wp post create --post_type=page --post_title='Joining JEDI' --post_status=publish --post_parent=$temple_id --menu_order=3
archives_id=$(wp post create --post_type=page --post_title='Archives' --post_status=publish --post_content='[search]' --porcelain)
archives_history_id=$(wp post create --post_type=page --post_title='History' --post_status=publish --post_parent=$archives_id --page_template='subpageslist.php' --menu_order=1 --meta_input='{"auto_plural":"false","collapsable_groups":"false","default_group":"Historical Periods","group_by":"group","sort_column":"post_date","subtitle1_key":"subtitle"}' --porcelain)
wp post create --post_type=page --post_title='History entry' --post_status=publish --post_parent=$archives_history_id --meta_input='{"subtitle":"Subtitle"}'
archives_documents_id=$(wp post create --post_type=page --post_title='Documents' --post_status=publish --post_parent=$archives_id --page_template='subpageslist.php' --menu_order=2 --meta_input='{"collapsable_groups":"false","group_by":"group","order_by":"[\"Resources\",\"Lightsaber & Combat Theory\"]","sort_column":"menu_order,post_title","subtitle1_key":"author"}' --porcelain)
wp post create --post_type=page --post_title='Documents entry' --post_status=publish --post_parent=$archives_documents_id --meta_input='{"group":"Resources","author":"Author"}'
archives_persons_id=$(wp post create --post_type=page --post_title='Persons' --post_status=publish --post_parent=$archives_id --page_template='subpageslist.php' --menu_order=3 --meta_input='{"auto_plural":"true","collapsable_groups":"true","default_group":"Noteworthy Person","group_by":"rank","order_by":"[\"Jedi Master\",\"Jedi Knight\",\"Adept\",\"Padawan Learner\",\"Padawan\",\"Initiate\",\"Outpost Staff Member\"]","subtitle1_key":"species","subtitle2_key":"title"}' --porcelain)
wp post create --post_type=page --post_title='Persons entry' --post_status=publish --post_parent=$archives_persons_id --meta_input='{"rank":"Jedi Master","species":"Human","title":"High Councilor"}'
archives_galaxy_id=$(wp post create --post_type=page --post_title='Galaxy' --post_status=publish --post_parent=$archives_id --page_template='subpageslist.php' --menu_order=4 --meta_input='{"auto_plural":"true","collapsable_groups":"false","default_group":"Miscellaneous Item","group_by":"group","order_by":"[\"Faction\",\"Planet\",\"Government\",\"Organization\",\"Political Figure\"]","subtitle1_key":"subtitle"}' --porcelain)
wp post create --post_type=page --post_title='Galaxy entry' --post_status=publish --post_parent=$archives_galaxy_id --meta_input='{"group":"Planet","subtitle":"Subtitle"}'
archives_jedi_id=$(wp post create --post_type=page --post_title='Jedi' --post_status=publish --post_parent=$archives_id --page_template='subpageslist.php' --menu_order=5 --meta_input='{"auto_plural":"false","collapsable_groups":"false","default_group":"Miscellaneous","group_by":"group","order_by":"[\"Primary Facilities\",\"Support Facilities\",\"Jedi Service Corps\",\"Historical Facilities\",\"Historical Padawan Clans\"]","sort_column":"post_date","subtitle1_key":"subtitle"}' --porcelain)
wp post create --post_type=page --post_title='Jedi entry' --post_status=publish --post_parent=$archives_jedi_id --meta_input='{"group":"Primary Facilities","subtitle":"Subtitle"}'
residents_id=$(wp post create --post_type=page --post_title='Residents' --post_status=publish --post_content='[rpmod-accounts community=JEDI]' --meta_input='{"show_nav":"false"}' --porcelain)
wp post create --post_type=page --post_title='Soh Raun' --post_status=publish --post_parent=$residents_id --post_content="Soh Raun's biography" --page_template='biography.php' --meta_input='{"username":"soh","species":"Human","title":"Former High Councilor"}'
wp post create --post_type=page --post_title='Data' --post_status=publish
about_id=$(wp post create --post_type=page --post_title='About' --post_status=publish --porcelain)
wp post create --post_type=page --post_title='Promoting JEDI' --post_status=publish --post_parent=$about_id --menu_order=1
wp post create --post_type=page --post_title='Clan History' --post_status=publish --post_parent=$about_id --menu_order=2
wp post create --post_type=page --post_title='Legal' --post_status=publish --post_parent=$about_id --menu_order=3

# Create sample posts
wp post delete $(wp post list --post_type=post --format=ids)
wp post create --post_type=post --post_title='Sample article #1' --post_status=publish --post_category='business' --post_content='Sample HoloNews article in the Business category'
wp post create --post_type=post --post_title='Sample article #2' --post_status=publish --post_category='entertainment-and-arts' --post_content='Sample HoloNews article in the Entertainment and Arts category'
wp post create --post_type=post --post_title='Sample article #3' --post_status=publish --post_category='galactic' --post_content='Sample HoloNews article in the Galactic category'
wp post create --post_type=post --post_title='Sample article #4' --post_status=publish --post_category='jedi' --post_content='Sample HoloNews article in the Jedi category'
wp post create --post_type=post --post_title='Sample article #5' --post_status=publish --post_category='regional' --post_content='Sample HoloNews article in the Regional category'
wp post create --post_type=post --post_title='Sample article #6' --post_status=publish --post_category='science-and-technology' --post_content='Sample HoloNews article in the Science and Technology category'
wp post create --post_type=post --post_title='Sample article #7' --post_status=publish --post_category='sports' --post_content='Sample HoloNews article in the Sports category'
