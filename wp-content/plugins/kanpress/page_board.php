<?php

require_once 'util.php';

global $wpdb;

$validation = array( );

/*
 * Create new task
 */
if ( isset( $_POST[ 'resumen' ] ) ) {

	$uid = get_current_user_id();

	//Sanitize data
	//$wpdb->escape_by_ref($_POST['resumen']);
	//$wpdb->escape_by_ref($_POST['descripcion']);

	$priority = intval( $_POST[ 'prioridad' ] );
	if ( $priority < 0 || $priority > 2 )
		$priority = 1;

	//Form validation
	if ( empty( $_POST[ 'resumen' ] ) || strlen( $_POST[ 'resumen' ] ) < 10 ) {
		$validation[ 'resumen' ] = __( 'You must write a summary with at least 10 characters', 'kanpress' );
	}

	if ( !count( $validation ) ) {

		//Thanks to Teclis for the fix
		$wpdb->insert( $wpdb->prefix . 'kanpress_task', array(
				'proposed_by' => $uid,
				'assigned_to' => null,
				'revised_by' => null,
				'term_id' => $_POST[ 'categoria' ],
				'post_id' => null,
				'priority' => $priority,
				'status' => 0,
				'summary' => $_POST[ 'resumen' ],
				'description' => $_POST[ 'descripcion' ],
				'time_proposed' => date( 'Y-m-d H:i:s' ),
				'time_assigned' => null,
				'time_done' => null ) );

		$_POST = array( );

		//Redirects to itself to avoid re-sending the form
		die( '<meta http-equiv="refresh" content="0;url=' . $_SERVER[ 'REQUEST_URI' ] . '" />' );
	}
}

/*
 * Assign or change task status
 */
if ( isset( $_POST[ 'assign' ] ) ) {

	/* @todo use TABLE_TASK constant */
	$wpdb->update( $wpdb->prefix . 'kanpress_task', array(
			'assigned_to' => $_POST[ 'user' ] ), array( 'task_id' => $_POST[ 'taskId' ] ) );
}

/*
 * Load kanban board
 */

$select = "SELECT * FROM " . TABLE_TASK . " "
				. "JOIN " . $wpdb->prefix . "terms ON " . TABLE_TASK . ".term_id = " . $wpdb->prefix . "terms.term_id "
				. "WHERE status < 3 "
				. "ORDER BY time_proposed DESC";

$tasks = $wpdb->get_results( $select, ARRAY_A );

$tasks_proposed = array( );
$tasks_assigned = array( );
$tasks_pending = array( );

//Fetch all active tasks
foreach ( $tasks as &$t ) {

	$t[ 'summary' ] = stripslashes( $t[ 'summary' ] );
	$t[ 'description' ] = stripslashes( $t[ 'description' ] );

	//Fetch the display name for the reporter
	$t[ 'user_proposed' ] = get_userdata( intval( $t[ 'proposed_by' ] ) )->data->display_name;

	//Fetch the display name for the assigned author
	if ( intval( $t[ 'assigned_to' ] ) > 0 ) {
		$t[ 'user_assigned' ] = get_userdata( intval( $t[ 'assigned_to' ] ) )->data->display_name;
	}

	//Fetch the display name for the revising author
	if ( intval( $t[ 'revised_by' ] ) > 0 ) {
		$t[ 'user_revised' ] = get_userdata( intval( $t[ 'revised_by' ] ) )->data->display_name;
	}

	//Fetch the linked post
	if ( intval( $t[ 'post_id' ] ) > 0 ) {
		$t[ 'post' ] = get_post( $t[ 'post_id' ] );
	}

	//Classify each task into proposed, assigned and pendant
	switch ( $t[ 'status' ] ) {
		case 0:
			$tasks_proposed[ ] = $t;
			break;
		case 1:
			$tasks_assigned[ ] = $t;
			break;
		case 2:
			$tasks_pending[ ] = $t;
			break;
	}
}

$sql = <<<SQL
SELECT
	{$wpdb->prefix}terms.term_id, 
	{$wpdb->prefix}terms.name 
FROM
	{$wpdb->prefix}terms 
JOIN
	{$wpdb->prefix}term_taxonomy USING(term_id)
WHERE
	{$wpdb->prefix}term_taxonomy.taxonomy = 'category' 
ORDER BY
	name ASC
SQL;

$categories = $wpdb->get_results( $sql, ARRAY_A );

//Prepare array for <select>
$categories = array_attribute_value( $categories );

//Get all the users
$users = array( );
$users_from_wp = get_users();
foreach ( $users_from_wp as $u ) {
	$users[ $u->ID ] = $u->display_name;
}

wp_enqueue_style( 'kanpress', KANPRESS . '/static/kanpress.css', array( 'wp-jquery-ui-dialog' ), '0.3.12' );

wp_enqueue_script( 'kanpress-board', KANPRESS . '/static/board.js', array( 'jquery-ui-droppable', 'jquery-ui-dialog' ), '0.3.10' );

// Pass data to JS
wp_localize_script( 'kanpress-board', 'KanpressData', array(
		'baseUrl' => plugins_url() . '/' . basename( __DIR__ ),
		'showPopupOnLoad' => !empty( $validation ),
		'l10n' => array(
				'Propose new article' => __( 'Propose new article', 'kanpress' ),
				'Do you really want to remove this task?' => __( 'Do you really want to remove this task?\nThis can\'t be undone', 'kanpress' ),
				'Creating...' => __( 'Creating...', 'kanpress' ),
				'Assign task' => __( 'Assign task', 'kanpress' ),
				'Asignada a' => __( 'Assigned to', 'kanpress' ),
				'Confirm Removal' => __( 'Confirm Removal' )
		)
) );

require 'html_task.php';
include 'page_board.view.php';
