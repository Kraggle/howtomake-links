<?php

global $ks_db_version;
$ks_db_version = '0.1';

function ks_create_tables() {

	global $wpdb, $ks_db_version;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$wpdb->prefix}link_juice_keywords (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		keywords text NOT NULL,
		link varchar(255) NOT NULL,
		PRIMARY KEY (id),
		UNIQUE (link)
	) $charset_collate;";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	add_option('ks_db_version', $ks_db_version);
}

register_activation_hook(__FILE__, 'ks_create_tables');

function ks_update_db_check() {
	global $ks_db_version;
	if (get_site_option('ks_db_version') != $ks_db_version) {
		ks_create_tables();
	}
}
add_action('plugins_loaded', 'ks_update_db_check');
