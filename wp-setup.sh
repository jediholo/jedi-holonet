#!/bin/sh
#
# Wordpress setup script, using WP-CLI.
#

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
wp plugin install classic-editor embed-iframe google-calendar-events notification wonderm00ns-simple-facebook-open-graph-tags wptouch --activate
wp plugin activate jedi-plugins
wp theme activate jediholonet

# Configure widgets
wp widget reset sidebar-header sidebar-1 sidebar-2 sidebar-5
wp widget add jwidget_recent_posts sidebar-header 1 --title='Latest HoloNews' --number=5 --display_date=true --maxlength=22
wp widget add jwidget_recent_pages sidebar-header 2 --title='Latest Archives Articles' --number=5 --display_date=true --maxlength=22 --parent='archives'
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
wp post create --post_type=page --post_title='Temple' --post_status=publish --post_content='[rpmod-server jedi-temple-eu]'
wp post create --post_type=page --post_title='Archives' --post_status=publish
wp post create --post_type=page --post_title='Data' --post_status=publish
wp post create --post_type=page --post_title='About' --post_status=publish
residents_id=`wp post create --post_type=page --post_title='Residents' --post_status=publish --post_content='[rpmod-accounts community=JEDI]' --porcelain`
wp post create --post_type=page --post_title='Soh Raun' --post_status=publish --post_parent=$residents_id --post_content="Soh Raun's biography" --page_template='biography.php' --meta_input='{"username":"soh","species":"Human","title":"Former High Councilor"}'

# Create sample post
wp post delete $(wp post list --post_type=post --format=ids)
wp post create --post_type=post --post_title='Sample article' --post_status=publish --post_category=jedi --post_content='Sample HoloNews article in the Jedi category'
