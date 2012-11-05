<?php
/*
Plugin Name: Notes, for WordPress
Plugin URI: http://drewsymo.com/wordpress/notes
Description: A simple note-taking app for WordPress
Author: Drew Morris
Version: 1
Author URI: http://drewsymo.com
*/

// CSS, JS Enqueue

function notes_scripts() {

	// Enqueue JS
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-form' );
	wp_enqueue_script( 'notes', plugins_url('notes.js', __FILE__) );

	// Enqueue CSS
	wp_enqueue_style( 'notes', plugins_url('notes.css', __FILE__) );

}
add_action( 'admin_enqueue_scripts', 'notes_scripts' );

// Install Notes

function notes_install() {

	global $wpdb;
	$table_name = $wpdb->prefix . "notes";
     
    // Create the notes table
    $sql = $wpdb->query( $wpdb->prepare( "CREATE TABLE $table_name (note_text text NOT NULL); " ) );
 
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
 
 	// Insert Default Data
	$wpdb->insert( $table_name, array( 'note_text' => 'No notes have been created, yet.' ) );

}

// Query Notes Table

function notes_query() {

	global $wpdb;
	$table_name = $wpdb->prefix . "notes";

	// Query the notes table
	$note = $wpdb->get_row( $wpdb->prepare( "SELECT note_text FROM $table_name") );

	// Print the notes table data
	foreach ( $note as $notes ) {
		return esc_attr($notes); 
	}

}

// Admin Bar HTML

if (is_admin()) {

function mytheme_admin_bar_render() {

    global $wp_admin_bar;
	global $wpdb;

	// Create HTML
	$table_name = $wpdb->prefix . "notes";

	if(isset($_POST['update'])) {
		$note_data = $_POST['note_data'];
		$wpdb->query( $wpdb->prepare( " UPDATE $table_name SET note_text = '%s' ", esc_attr($note_data) ) );
	}

	$html = "<div id=\"wp-notes-container\">";
		$html .= "<form id=\"wp-notes\" method=\"POST\">";
			$html .= "<textarea class=\"widefat\" id=\"note_data\" name=\"note_data\">".notes_query()."</textarea>";
			$html .= "<div class=\"controls\"><input class=\"button-primary\" type=\"submit\" value=\"Save Notes\" id=\"update\" name=\"update\"></div>";
		$html .= "</form>";
		$html .= "<div id=\"success\">Notes saved.</div>";
	$html .= "</div>";

	// Admin Bar Hook
    $wp_admin_bar->add_menu( array(
        'id' => 'notes',
        'title' => __('Notes'),
        'href' => ''
    ) );

   $wp_admin_bar->add_menu( array(
        'parent' => 'notes',
        'id' => 'note_textarea',
        'title' => $html,
        'href' => FALSE
    ) );

}
// and we hook our function via
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );

}

// Register Plugin on Activation

register_activation_hook(__FILE__,'notes_install');


?>