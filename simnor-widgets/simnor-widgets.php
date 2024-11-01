<?php
/*
Plugin Name: Simnor Widgets
Plugin URI: http://simonmakes.com/
Description: A selection of widgets to use on your site including twitter, dribbble, latest posts, social links and an author list.
Version: 1.0
Author: Simon North
Author URI: http://simonmakes.com

/* Variables */
$sn_simnor_widgets_path = dirname(__FILE__);
$sn_simnor_widgets_main_file = dirname(__FILE__).'/simnor-widgets.php';
$sn_simnor_widgets_directory = plugin_dir_url($sn_simnor_widgets_main_file);
$sn_simnor_widgets_name = "Simnor Widgets";

/* Include Widgets */
include('widgets/widget-authors.php');
include('widgets/widget-social-links.php');
include('widgets/widget-twitter.php');
include('widgets/widget-dribbble.php');
include('widgets/widget-latest-posts.php');

/* Load Scripts */
function sn_simnor_widgets_add_front_scripts() {
	global $sn_simnor_widgets_directory;
	wp_enqueue_style('sn-simnor-widgets', $sn_simnor_widgets_directory.'includes/css/front.css');
}
add_action('init', 'sn_simnor_widgets_add_front_scripts');