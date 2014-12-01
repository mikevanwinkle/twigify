<?php
/*
	Plugin Name: Content Templates
	Plugin URI: http://wordpress.org/extend/plugins/content-templates
	Description: Allows you to create content templates that will conditionally override the content of a post or page or customer post type
	Author: Mike Van Winkle
	Version: 0.1-alpha
	Author URI: http://mikevanwinkle.com
	Text Domain: content-templates
	Domain Path: /lang
*/
define('CT_VERSION', '0.1-alpha');
define('SCRIPT_DEBUG', true);

// register autoloader
spl_autoload_register('__ct_autoload');
function __ct_autoload($class) {
	$class = str_replace("\\","/", $class);
	$class = __DIR__."/lib/$class.php";
	if (file_exists($class)) {
		require_once($class);
	}
}

// include symfony autoloader
require_once(plugin_dir_path(__FILE__).'/vendor/autoload.php');
require_once(plugin_dir_path(__FILE__).'/lib/meta-boxes.php');

// activation stuff
register_activation_hook( __FILE__, array('ContentTemplatesPlugin','activate'));

ContentTemplatesPlugin::instance();

class ContentTemplatesPlugin {
	static $instance;
	private $settings;
	private $slug = 'ct-options';
	private $views_dir = '/views';
	private $cache_dir = '/cache';
	private $version = '0.1';

	private function __construct() {
		$this->get_settings();
		$this->admin_init();
		add_action('admin_menu', array( $this, 'admin_menu' ));
		add_action('admin_init', array( $this, 'admin_init' ));
		add_action('init', array($this,'init'));
		add_action('add_meta_boxes', array('\ContentTemplates\Rules', 'add_meta_box'));
		add_action('save_post', array('\ContentTemplates\Rules', 'save_post'));
		add_action('the_content', array('\ContentTemplates\View','hook'), -1);
		add_action('add_admin_bar_menus', array('\ContentTemplates\AdminBar','modify'), 200);

	}

	static function activate() {
		if ( self::settings() ) {
		}
	}

	function admin_init() {

	}



	function admin_menu() {
		add_menu_page('Content Templates', 'Content Templates', 'administrator', 'content-templates', array($this, 'settings_page'), 'dashicons-media-code' );
	}

	function init() {
		$labels = array(
			'name'               => _x( 'Templates', 'post type general name', 'content-templates' ),
			'singular_name'      => _x( 'Template', 'post type singular name', 'content-templates' ),
			'menu_name'          => _x( 'Templates', 'admin menu', 'content-templates' ),
			'name_admin_bar'     => _x( 'Template', 'add new on admin bar', 'content-templates' ),
			'add_new'            => _x( 'Add New', 'template', 'content-templates' ),
			'add_new_item'       => __( 'Add New Template', 'content-templates' ),
			'new_item'           => __( 'New Template', 'content-templates' ),
			'edit_item'          => __( 'Edit Template', 'content-templates' ),
			'view_item'          => __( 'View Template', 'content-templates' ),
			'all_items'          => __( 'All Templates', 'content-templates' ),
			'search_items'       => __( 'Search Templates', 'content-templates' ),
			'parent_item_colon'  => __( 'Parent Templates:', 'content-templates' ),
			'not_found'          => __( 'No templates found.', 'content-templates' ),
			'not_found_in_trash' => __( 'No templates found in Trash.', 'content-templates' )
		);
		$params = array(
			'labels'		     => $labels,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'content-templates',
			'supports'					 => array('title','editor','custom-fields'),
		);
		register_post_type('ctemplates',$params);
	}

	static function settings() {
		$plugin = self::instance();
		$plugin->get_settings();
	}

	public function get_settings() {
		$this->settings = get_option($this->slug, false);
		if ( $this->settings ) {
			$this->settings = \ContentTemplates\Settings::test();
			update_option($this->slug, $this->settings);
		}
		return $this->settings;
	}

	public function view($file_or_string, $data) {
		$view = \ContentTemplates\View::instance();
		return $view->render($file_or_string, $data);
	}

	public function get($property) {
		return $this->$property;
	}

	static function settings_page() {
		$templates = new WP_Query(array('post_type'=>'ctemplates'));
		print_r($templates);
	}

	static function instance() {
		if ( !self::$instance ) {
			self::$instance = new Self();
		}
		return self::$instance;
	}

	static function get_views_dir() {
		$plugin = ContentTemplatesPlugin::instance();
		return $plugin->get('cache_dir');
	}

	static function get_cache_dir() {
		$plugin = ContentTemplatesPlugin::instance();
		return $plugin->get('cache_dir');
	}
}
