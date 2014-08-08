<?php

/**
 (c) DHTMLX Ltd, 2011
 Licensing: You allowed to use this component for free under GPL or you need to obtain Commercial/Enterprise license to use it in non-GPL project
 Contact: sales@dhtmlx.com
 **/




error_reporting(0);
require_once('../../../wp-config.php');
define('WP_USE_THEMES', true);
require_once("./codebase/php/grid_cell_connector.php");

$res = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_NAME, $res);

get_currentuserinfo();
if (isset($current_user->roles[0])) {
	$usertype = $current_user->roles[0];
} else {
	$usertype = 'guest';
}

$available_to = Array(
	'administrator' => true,
	'editor' => true
);

$conn = new GridCellConnector($res, $table_prefix."dhx_");
if (!isset($available_to[$usertype]) || $available_to[$usertype] == false)
	$conn->set_read_only(true);
$conn->render();

?>