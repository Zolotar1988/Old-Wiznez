<?php

/**
 (c) DHTMLX Ltd, 2011
 Licensing: You allowed to use this component for free under GPL or you need to obtain Commercial/Enterprise license to use it in non-GPL project
 Contact: sales@dhtmlx.com
 **/



/**
 * @package Spreadsheet
 * @author DHTMLX LTD
 * @version 2.0
 */
/*
Plugin Name: Spreadsheet
Plugin URI: http://wordpress.org/extend/plugins/spreadsheet/
Description: dhtmlxSpreadsheet is based on the dhtmlxGrid JavaScript component which supports the most essential features for displaying and formatting tabular data
Author: DHTMLX LTD
Version: 2.0
Author URI: http://dhtmlx.com
*/

require_once(ABSPATH.WPINC.'/pluggable.php');
require_once(WP_PLUGIN_DIR.'/dhtmlxspreadsheet/spreadsheet_common.php');

global $wpdb, $sh_cfg;

/*! initialize configs
 */
$sh_cfg = new SpreadsheetCfg();
$sh_cfg->set('prefix', $wpdb->prefix.'dhx_');
$sh_cfg->set('plugin', WP_PLUGIN_URL.'/dhtmlxspreadsheet/');
$sh_cfg->set('connector', WP_PLUGIN_URL.'/dhtmlxspreadsheet/spreadsheet_data.php');
$sh_cfg->set('sheet', 1);

register_activation_hook(__FILE__, 'sh_activate');
add_filter('the_content', 'sh_check');
sh_load_dump();

function sh_check($content) {

	global $sh_cfg;
	$ver = phpversion();
	$ver_main = (int) substr($ver, 0, 1);
	if ( $ver_main < 5)
		return __('Installation error: Spreadsheet plugin requires PHP 5.x', 'spreadsheet');
	$content = preg_replace_callback($sh_cfg->get('pattern'), "sh_replace", $content);
	return $content;
}

function sh_replace($matches) {
	global $sh_cfg;
	$options = Array();
	if (isset($matches[1])) {
		$ms = Array();
		$matches[1] = html_entity_decode($matches[1]);
		preg_match_all($sh_cfg->get('pattern_param'), $matches[1], $ms);
		
		if (isset($ms[1])) {
			for ($i = 0; $i < count($ms[1]); $i++) {
				$options[trim($ms[1][$i])] = trim($ms[2][$i]);
			}
		}
	}

	$matches = explode(":",$matches[1]);
	foreach ($options as $name => $value) {
		$sh_cfg->set($name, $value);
	}
	if (!isset($options['height']))
		$sh_cfg->set('autoheight', 'true');

	$text = $sh_cfg->get_spreadsheet_client();
	return $text;
}


function sh_activate() {
	sh_load_dump();
}


function sh_init() {
	global $sh_cfg;
	return $sh_cfg->get_spreadsheet_client();
}


function sh_load_dump($drop = false) {
	global $wpdb, $sh_cfg;
	$query = "SELECT * FROM ".$wpdb->prefix."dhx_data";
	$result = $wpdb->query($query);
	if ($result == false) {
		$query = "SELECT * FROM ".$wpdb->prefix."data";
		$result = $wpdb->query($query);
		if ($result == false) {
			$queries = $sh_cfg->get('queries');
			for ($i = 0; $i < count($queries); $i++) {
				$query = str_replace('#__', $wpdb->prefix, $queries[$i]);
				$wpdb->query($query);
			}
		} else {
			// rename tables
			$tables_list = array("data", "header", "sheet", "triggers", "user");
			for ($i = 0; $i < count($tables_list); $i++)
				$wpdb->query("ALTER TABLE {$wpdb->prefix}{$tables_list[$i]} RENAME TO {$wpdb->prefix}dhx_{$tables_list[$i]}");
		}
	}
	$query = "SELECT sheetid FROM {$wpdb->prefix}dhx_triggers LIMIT 1";
	$res = $wpdb->query($query);
	if ($res === false) {
		// migrate call
		$cwd = getcwd();

		chdir(WP_PLUGIN_DIR.'/dhtmlxspreadsheet/codebase/php/');
		require_once('migrate.php');
		require_once('db_common.php');
		chdir($cwd);
		
		$wrapper = new MySQLDBDataWrapper($wpdb->dbh, null);
		$mig = new dhxMigrate($wrapper, $wpdb->prefix.'dhx_');
		$mig->update();
	}
}


?>