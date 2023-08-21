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


// Include the custom post types.
require_once(  'lib/cpt.php' );

// Include the custom fields.
require_once(  'lib/acf.php' );

function act_add_footer_styles() {
    wp_register_style('act_styles', plugin_dir_url( __FILE__ ) . 'assets/css/acsab-styles.css', array(), filemtime(plugin_dir_path( __FILE__ ) . 'assets/css/acsab-styles.css') );
    wp_enqueue_style('act_styles');
    wp_register_style('acsab-animate', plugin_dir_url( __FILE__ ) . 'assets/css/animate.compat.css', array(), filemtime(plugin_dir_path( __FILE__ ) . 'assets/css/animate.compat.css') );
    wp_enqueue_style('acsab-animate');
}
add_action( 'wp_footer',  'act_add_footer_styles'  );


function acsab($atts){
    $output = '';
    global $wp_query;
    $temp_q = $wp_query;
    $wp_query = null;
    $wp_query = new WP_Query();
    $wp_query->query(array(
        'post_type' => 'acsab-announcement',
        'post_status' => 'publish',
        'showposts' => 1,
    ));
    if (have_posts()) :
        $linkSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M448 279.196V464c0 26.51-21.49 48-48 48H48c-26.51 0-48-21.49-48-48V112c0-26.51 21.49-48 48-48h248a24 24 0 0 1 16.97 7.029l16 16C344.09 102.149 333.382 128 312 128H64v320h320V295.196c0-6.365 2.529-12.47 7.029-16.971l16-16C422.148 247.106 448 257.814 448 279.196zM576 37.333C576 16.715 559.285 0 538.667 0H380c-15.464 0-28 12.536-28 28v17.885c0 15.766 13.011 28.424 28.772 27.989l67.203-1.906L199.09 319.09c-9.429 9.363-9.457 24.605-.061 34.001l23.879 23.879c9.396 9.396 24.639 9.369 34.001-.06l247.122-248.885-1.906 67.203c-.434 15.76 12.224 28.772 27.99 28.772H548c15.464 0 28-12.536 28-28V37.333z"/></svg>';

        // Escaping the CSS
        $output .= '<style>';
        $output .= esc_html('body{margin-top:32px;}');
        $output .= '</style>';

        while (have_posts()):
            the_post();
            $announcement = wp_kses_post(get_field('announcement_text'));
            $announcementMob = wp_kses_post(get_field('announcement_text_mobile'));

            $linkUrl = esc_url(get_field('announcement_link_url'));
            $linkText = esc_html(get_field('announcement_link_text'));

            $announcementBtn = "<span class='c-btn--announcement' >";
            $announcementBtn .= "<a href='".$linkUrl."'>" . $linkText . "</a>";
            $announcementBtn .= "</span>";

            $announcementMobHtml = "<a href='".$linkUrl."'>";
            $announcementMobHtml .= $announcementMob.$linkSvg;
            $announcementMobHtml .= "</a>";

            ob_start();
            ?>

          <div class="c-acsab-announcement-bar ">
            <div class="c-acsab-announcement-bar__content  u-d-b--wide u-d-b--desk u-d-b--tablet u-d-n--mobile u-d-n--tiny animated backInRight">
                <?php echo wpautop($announcement . ' ' . $announcementBtn); ?>
            </div>
            <div class="c-acsab-announcement-bar__content--mobile u-d-n--wide u-d-n--desk u-d-n--tablet u-d-b--mobile u-d-b--tiny animated backInRight">
                <?php echo wpautop($announcementMobHtml); ?>
            </div>
          </div>

            <?php
            $output .= ob_get_contents();
            ob_end_clean();
        endwhile;
    endif;

    $wp_query = $temp_q;

    echo $output;
}

add_action('wp_body_open', 'acsab', 0);

add_action('wp_body_open', 'acsab', 0);





