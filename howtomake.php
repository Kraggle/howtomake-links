<?php

/**
 * Plugin Name: How to Make - Links
 * Plugin URI: https://howtomakemoneyfromhomeuk.com/
 * Description: This is a plugin that automatically creates internal links throughout your wordpress site.
 * Version: 0.1
 * Author: Kraggle
 * Author URI: https://kragglesites.com/
 **/


define('KS_JUICE_PLUGIN_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('KS_JUICE_PLUGIN_URI', plugins_url('', __FILE__));

require_once KS_JUICE_PLUGIN_DIR . '/php/create-tables.php';

class KS_Link_Juice {

	function __construct() {
		$this->name = 'How to Make - Links';
		$this->version = 0.1;

		$this->slug = str_replace(' ', '-', strtolower($this->name));

		add_action('admin_menu', array($this, 'add_menu'));
	}

	function add_menu() {
		$menuSlug = "ks-{$this->slug}";
		$slugDash = "{$menuSlug}-dashboard";
		$capability = 'administrator';

		include_once KS_JUICE_PLUGIN_DIR . 'includes/variables.php';
		$this->menuIcon = $menuIcon;

		add_menu_page($this->name, $this->name, $capability, $slugDash, array($this, 'dashboard_page'), $menuIcon64);
		wp_enqueue_script('ks-admin-js', KS_JUICE_PLUGIN_URI . '/script/ks-admin.js', array('jquery'), $this->version);

		add_submenu_page($slugDash, "Dashboard", "Dashboard", $capability, $slugDash, array($this, 'dashboard_page'));
		add_submenu_page($slugDash, "Keywords", "Keywords", $capability, "{$menuSlug}-keywords", array($this, 'keywords_page'));
		add_submenu_page($slugDash, "Statistics", "Statistics", $capability, "{$menuSlug}-stats", array($this, 'stats_page'));
		add_submenu_page($slugDash, "Settings", "Settings", $capability, "{$menuSlug}-settings", array($this, 'settings_page'));
	}

	function page_before($type) {
		wp_enqueue_style('ks-admin-css', KS_JUICE_PLUGIN_URI . '/style/ks-admin.css', array(), $this->version);

		global $title;
		printf(
			'<div class="ks-box">
				<div class="ks-head">
					<div class="ks-icon">%1$s</div>
					<span class="ks-title">%2$s</span>
					<span class="ks-sub">%3$s</span>
				</div>
				<div class="ks-content %4$s">',
			$this->menuIcon,
			$this->name,
			$title,
			$type
		);
	}

	function page_after() {
		echo '</div>
			<div class="ks-footer">
				<div class="ks-progress">
					<div class="ks-progress-back"></div>
					<div class="ks-progress-number"></div>
				</div>
			</div>
		</div>';
	}

	function dashboard_page() {
		$this->page_before('dashboard');

		$this->page_after();
	}

	function keywords_page() {
		$this->page_before('keywords');

		for ($i = 1; $i < 25; $i++) {

			printf(
				'<div class="ks-setting-box">
					<span class="ks-name">Keywords</span>
					<span class="ks-desc">Comma separated keywords and strings.</span>
					<input class="ks-input" type="text" placeholder="keyword, and a string"/>
					<span class="ks-name">Hyperlink</span>
					<span class="ks-desc">A single hyperlink excluding the domain.</span>
					<input class="ks-input" type="text" placeholder="/some/path/to/a/post"/>
				</div>',
				$i
			);
		}

		$this->page_after();
	}

	function stats_page() {
		$this->page_before('stats');

		$this->page_after();
	}

	function settings_page() {
		$this->page_before('settings');

		$settings = json_decode(file_get_contents(KS_JUICE_PLUGIN_DIR . '/includes/settings.json'));

		foreach ($settings->settings as $box) {

			$wrap = $settings->wrap;
			$part = "<{$wrap->element}";

			// Add the attributes
			foreach ($wrap->attrs as $attr => $value) {
				$part .= " {$attr}='$value'";
			}

			$part .= ">";
			$part .= $this->create_elements($box->elements);
			$part .= "</{$wrap->element}>";

			echo $part;
		}



		$this->page_after();
	}

	function create_elements($els, $part = '') {

		foreach ($els as $el) {
			// Start the element
			$part .= "<{$el->element}";

			// Add the attributes
			foreach ($el->attrs as $attr => $value) {
				$part .= " {$attr}='$value'";
			}

			// Close the element
			if (in_array($el->element, ['img', 'input', 'br', 'hr', 'meta'])) {
				$part .= " />";
				continue;
			} else
				$part .= ">";

			// Add text if it exists
			if ($el->text) $part .= $el->text;

			// Add children if they exist
			if ($el->children)
				$part .= $this->create_elements($el->children);

			// Close the element
			$part .= "</{$el->element}>";
		}

		return $part;
	}
}

function ks_link_juice_init_plugin() {
	new KS_Link_Juice();
}
add_action('init', 'ks_link_juice_init_plugin');
