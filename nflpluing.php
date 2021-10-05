<?php
/*
Plugin Name: NFL Teams
Plugin URI: https://github.com/Juanacv/nfl-teams
Description: Shows NFL Teams
Version: 1.0
Author: Juan Antonio Calderón Valverde
Author URI: https://github.com/Juanacv
License: GPL
*/
if (!defined('ABSPATH')) die();

define("NFL_FILE",__FILE__);
define("NFL_PLUGIN_DIR",plugin_dir_path(NFL_FILE));
define("NFL_PLUGIN_URL", plugin_dir_url(NFL_FILE));
define("NFL_PLUGIN_NAME","NFL_TEAMS");
define("NFL_TAG","nfl-teams");
define("API_URL","https://delivery.oddsandstats.co/team_list/NFL.JSON?api_key=74db8efa2a6db279393b433d97c2bc843f8e32b0");

/**
 * Function called during plugin activation
 */
function nfl_activate_plugin() {
    require_once NFL_PLUGIN_DIR.'includes/class-nfl-teams-activator.php';
    NFL_ACTIVATOR::activate();
}

/**
 * Function called during plugin deactivation
 */
function nfl_deactivate_plugin() {
    require_once NFL_PLUGIN_DIR.'includes/class-nfl-teams-deactivator.php';
    NFL_DEACTIVATOR::deactivate();
}
/* Hooks */
register_activation_hook(NFL_FILE,"nfl_activate_plugin");
register_deactivation_hook(NFL_FILE,"nfl_deactivate_plugin");

/**
 * Calling plugin functionality, to draw the page layout with NFL teams
 */
function plugin_run() {
    require_once NFL_PLUGIN_DIR.'public/class-nf-teams-layout.php';
    $nfl_page_layout = new NFL_TEAMS_LAYOUT(API_URL,'1.0',NFL_PLUGIN_NAME);
    require_once NFL_PLUGIN_DIR.'includes/class-nfl-teams-register.php';
    NFL_REGISTER::addShortcode(NFL_TAG,$nfl_page_layout,'buildLayout');
    NFL_REGISTER::addAction('wp_enqueue_scripts',$nfl_page_layout,'enqueueScripts');
    NFL_REGISTER::addAction('wp_enqueue_scripts',$nfl_page_layout,'enqueueStyles');
}

plugin_run();

?>