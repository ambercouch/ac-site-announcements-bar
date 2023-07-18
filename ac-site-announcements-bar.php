<?php
/*
  Plugin Name: AC Site Announcement bar
  Plugin URI: https://github.com/ambercouch/ac-site-announcements-bar
  Description: Add an announcement bar to your site
  Version: 0.1
  Author: AmberCouch
  Author URI: http://ambercouch.co.uk
  Author Email: richard@ambercouch.co.uk
  Text Domain: acsab
  Domain Path: /lang/
  License:
  Copyright 2018 AmberCouch
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

defined('ABSPATH') or die('You do not have the required permissions');

add_action('init', 'acsab_init', 0);
function acsab_init(){
    if((! is_plugin_active('advanced-custom-fields/acf.php'))&& ( ! is_plugin_active('advanced-custom-fields-pro/acf.php')))
{
    // Define path and URL to the ACF plugin.
    define( 'ACSAB_ACF_PATH', 'inc/acf/' );
    define( 'ACSAB_ACF_URL', plugin_dir_url( __FILE__ ) . 'inc/acf/' );

    // Include the ACF plugin.
    require_once( ACSAB_ACF_PATH . 'acf.php' );

    // Customize the url setting to fix incorrect asset URLs.
    add_filter('acf/settings/url', 'acsab_acf_settings_url');
    function acsab_acf_settings_url( $url ) {
        return ACSAB_ACF_URL;
    }

    // (Optional) Hide the ACF admin menu item.
    //add_filter('acf/settings/show_admin', 'acsab_acf_settings_show_admin');
    function acsab_acf_settings_show_admin( $show_admin ) {
        //return false;
    }
}
}

// Include the testimonial custom fields.
require_once(  'lib/acf.php' );

// Include the testimonial custom post type.
require_once(  'lib/cpt.php' );


function acsab($atts){

}

//add_shortcode('ac_testimonials', 'ac_testimonail');





