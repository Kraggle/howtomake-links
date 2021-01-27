<?php

if (!defined('ROOT')) {
	define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
}

require_once ROOT . 'wp-load.php';

global $wpdb;

// $query = "FROM 
// 		{$wpdb->prefix}posts 
// 	WHERE 
// 		`post_type` = '{$_GET['type']}' AND
// 		`post_status` = 'publish'
// 	ORDER BY 
// 		`post_date` DESC";

// if (filter_var($_GET['getCount'], FILTER_VALIDATE_BOOLEAN))
// 	$return->count = $wpdb->get_results("SELECT COUNT(*) AS `count` $query", OBJECT)[0]->count;

// $page = $_GET['page'] * 3;
// $results = $wpdb->get_results("SELECT `ID`, `post_content` $query", OBJECT);

// foreach ($results as $post) {
// }
