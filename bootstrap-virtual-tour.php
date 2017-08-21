<?php

/**
 * Plugin Name: Bootstrap Virtual Tour
 * Plugin URI: https://github.com/tnchuntic/Bootstrap-Virtual-Tour
 * Description: Bootstrap Virtual Tour with ACF Admin Management
 * Version: 0.0.1
 * Author: Thomas Chuntic
 * Author URI: http://chuntic.com
 * License: 

  Copyright 2014  Thomas Chuntic  (email : tnchuntic@hotmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


defined('ABSPATH') or die("Direct access not permitted.");


define('BVT_VERSION', '0.1.0');



function init_bootstrap_virtual_tour(){
    if (function_exists('acf_add_options_page')) {
        acf_add_options_sub_page('Tour Settings');
    }
}
add_action('init', 'init_bootstrap_virtual_tour',100);

function add_bootstrap_virtual_tour_style() {
    wp_register_style('bootstrap-virtual-tour-style', plugins_url('/css/bootstrap-tour.css',  __FILE__), array(), BVT_VERSION, 'all');
    wp_enqueue_style('bootstrap-virtual-tour-style');
}

add_action('wp_enqueue_scripts', 'add_bootstrap_virtual_tour_style',100);

function add_bootstrap_virtual_tour_script() {
    wp_register_script('bootstrap-virtual-tour-script', plugins_url('/js/bootstrap-tour.min.js',  __FILE__), array(), BVT_VERSION, true);
    wp_enqueue_script('bootstrap-virtual-tour-script');
}

add_action('wp_enqueue_scripts', 'add_bootstrap_virtual_tour_script',100);

/* includes */

require_once dirname(__FILE__).'/inc/class-bootstrap-virtual-tour.php';

