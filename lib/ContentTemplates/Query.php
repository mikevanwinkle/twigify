<?php
namespace ContentTemplates;
use \WP_Query;

class Query {

  function posts($query_string) {
    global $wpdb;
    $foundposts = new WP_Query($query_string);
    //print_r($foundposts->query_vars);
    if ( $foundposts->have_posts() ) {
      return $foundposts->get_posts();
    }
  }

}
