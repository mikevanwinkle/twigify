<?php
namespace ContentTemplates;

use \ContentTemplates\Rules;
use \ContentTemplatesPlugin as Plugin;
use \Twig_Autoloader,
  Twig_Loader_String,
  Twig_Loader_Filesystem,
  Twig_Environment;

class View {
  private $cache;
  private static $instance;

  public function __construct() {
    require_once __DIR__.'/../../vendor/twig/twig/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();
  }

  // prevents duplicate instances
  static public function instance() {
    if ( null === self::$instance ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function render($content, $data) {
    if (is_numeric($post)) {
      get_post($post);
    }
    if (file_exists(__DIR__.$this->views_dir."/$content")) {
      return $this->render_from_file($content, $data);
    } else {
      return $this->render_from_string($content, $data);
    }
  }

  private function render_from_file($file, $data) {
    $loader = new Twig_Loader_Filesystem(__DIR__.Plugin::get_views_dir());
    $twig = new Twig_Environment($loader, array('cache'=> __DIR__.Plugin::get_cache_dir()));
    $template = $twig->loadTemplate($file_or_string);
    $rendered = $template->render($data);
    return $rendered;
  }

  private function render_from_string($string, $data) {
    $loader = new Twig_Loader_String();
    $twig = new Twig_Environment($loader);
    $rendered = $twig->render($string, $data);
    return $rendered;
  }

  public static function hook($content,$post=null) {
    global $post;
    if (is_admin())
      return $content;
    $hash = md5($content);
    $cache_content = wp_cache_get($hash, 'post');
    if ( $cache_content ) {
      return $cache_content;
    }
    $template_id = get_post_meta(get_the_ID(), 'ct_override_template', true);
    $view = View::instance();
    $data = array();
    $metas = get_post_custom($post->ID);
    foreach ((array)$post as $key => $value) {
      $data[$key] = $value;
    }
    foreach ($metas as $key => $value) {
      if (count($value) < 2) {
        $value = $value[0];
      }
      $data[$key] = $value;
    }
    // first render the post_content
    $data['post_content'] = $view->render_from_string($post->post_content, $data);
    // now render the other templates
    $templates = apply_filters('content_templates_default', array(), $data);
    if( is_numeric($template_id) ) {
      $templates[] = get_post($template_id);
    }

    foreach ($templates as $template) {
      $content = $view->render_from_string($template->post_content, $data);
    }

    $content = html_entity_decode($content);
    wp_cache_set($hash, $content, 'post');
    return $content;
  }

}
