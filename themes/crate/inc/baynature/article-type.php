<?php
class BayNatureArticlePlugin {
    public function __construct() {
	add_action('init', array(&$this, 'init'), 0);
        //add_filter('pre_get_posts', array(&$this, 'query_post_type'));
    }

    function query_post_type($query) {
      if(is_category() || is_tag()) {
          $post_type = $query->get('post_type');

          if ($query->get('post_type') == 'nav_menu_item') {
            return;
          }

          if(is_array($post_type)){
            $post_type[] = "article";
          }else if($post_type){
            $post_type = $post_type;
          }else{
            $post_type = array('post','article');
          }

          $query->set('post_type',$post_type);
          return $query;
        }

    }
    
    function init() {
      global $wpdb, $wp_rewrite, $current_user, $blog_id;
 $rewrite = array(
        'slug'                       => 'article',
        'with_front'                 => true,
        'hierarchical'               => true,
      );

      register_post_type('article', array(
		'label' => 'Articles',
        'labels' => array(
          'name' => 'Articles',
          'singular_name' => 'Article',
          'add_new' => "Add New Article",
          'add_new_item' => "Add New Article",
          'edit_item' => "Edit Article",
          'new_item' => "New Article"
        ),
        'singular_label' => 'Article',
        'public' => true,
        'show_ui' => true,
        'has_archive' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array("slug" => 'articles'),
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'page-attributes', 'post-formats'),
        'show_in_rest' => true,
        'taxonomies' => array('category', 'post_tag', 'picks') ,
		'yarpp_support' => true,'rewrite'=>$rewrite
      ));
    }
}

new BayNatureArticlePlugin();

?>
