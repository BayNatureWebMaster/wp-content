<?php
class BaseType {
    public function __construct() {
      add_action('init', array(&$this, 'init'), 0);
      add_action('save_post', array(&$this, 'save_post_hook'));
      //add_action('admin_menu', array(&$this, 'admin_menu'));
      //add_filter('pre_get_posts', array(&$this, 'query_post_type'));
    }

    function post_type_name(){
      throw new Exception("Required override");
    }

    function post_uppercase_name(){
      return ucwords(str_replace("-", " ", $this->post_type_name()));
    }

    function query_post_type($query) {
      if(is_category() || is_tag()) {
        $post_type = $query->get('post_type');

        if ($query->get('post_type') == 'nav_menu_item') {
          return;
        }

        if(is_array($post_type)){
          $post_type[] = $this->post_type_name();
        }else if($post_type){
          $post_type = $post_type;
        }else{
          $post_type = array('post',$this->post_type_name());
        }

        $query->set('post_type',$post_type);
        return $query;
      }
    }
    
    function init() {
      global $wpdb, $wp_rewrite, $current_user, $blog_id;

      $uppercase_name = $this->post_uppercase_name();
      $plural_uppercase_name = $uppercase_name."s";

      register_post_type($this->post_type_name(), array(
        'label' => $uppercase_name,
        'labels' => array(
          'name' => $plural_uppercase_name,
          'singular_name' => $uppercase_name,
          'add_new' => "Add New $uppercase_name",
          'add_new_item' => "Add New $uppercase_name",
          'edit_item' => "Edit $uppercase_name",
          'new_item' => "New $uppercase_name"
        ),
        'singular_label' => $uppercase_name,
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => true,
        'rewrite' => array("slug" => $this->post_type_name()),
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'page-attributes', 'post-formats'),
        'taxonomies' => array('category', 'post_tag', 'features') 
      ));
    }
    

    /* Override */
    function json_to_post_data($json){
      throw new Exception("Required override");
    }

    /* Override */
    function json_to_categories_data($json){
      return array();
    }

    /* Override */
    function json_to_taxonomy_data($json){
      return array();
    }
   
    /* Override */
    function json_to_custom_fields_data($json){
      return array();
    }

    /* Override */
    function json_to_images($json){
      return array();
    }

    /* Override */
    function json_to_featured_image($json){
      return false;
    }

    function completed_update(){

    }

    function create_featured_image($post_id, $image){
      if(!$image)
        return;

      $processed = $this->create_images($post_id, array($image));
      $attach_id = $processed[0];

      set_post_thumbnail($post_id, $attach_id);
    }

    function create_images($post_id, $images){
      require_once(ABSPATH . 'wp-admin/includes/image.php');
      $uploads = wp_upload_dir();
      $opt_processed_dump_directory = ABSPATH."dump";

      $attachments = get_posts(array(
          'post_type' => 'attachment',
          'posts_per_page' => -1,
          'post_parent' => $post_id
      ));

      if(isset($_POST["reset"])){
        foreach($attachments as $attachment){
          wp_delete_attachment($attachment->ID, true);
        }

        $attachments = array();
      }

      $processed_images = array();

      foreach($images as $image){
        foreach($attachments as $attachment){
          if(get_post_meta($attachment->ID, "original_name", true) == $image){
            wp_delete_attachment($attachment->ID, true);
          }
        }
 
        $path = $uploads["path"]."/".urldecode($image);
        $dirname = dirname($path);

        if(!is_dir($dirname)){
          mkdir($dirname);
        }

        copy($opt_processed_dump_directory."/images/".urldecode($image), $path);
        $wp_filetype = wp_check_filetype(basename($path), null );

        $attachment = array(
           'guid' => $uploads["url"]."/".$image, 
           'post_mime_type' => $wp_filetype['type'],
           'post_title' => preg_replace('/\.[^.]+$/', '', basename($path)),
           'post_content' => '',
           'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $path, $post_id );
        add_post_meta($attach_id, "original_name", $image, true);

        if($path != "/Users/james/Sites/baynature/wp-content/uploads/2012/06/book_d.jpg"){
          $attach_data = wp_generate_attachment_metadata( $attach_id, $path );
          wp_update_attachment_metadata( $attach_id, $attach_data );
        }

        $processed_images[] = $attach_id;
      }

      return $processed_images;
    }
     
    function create_categories($post_id, $data){
      wp_set_object_terms($post_id, $data, 'category', false);
    }

    function create_taxonomies($post_id, $data){
      foreach($data as $taxonomy_name => $values){
        wp_set_object_terms($post_id, $values, $taxonomy_name, false); 
      }
    }

    function create_custom_fields($post_id, $data) {
      foreach ($data as $k => $v) {
        add_post_meta($post_id, $k, $v, true);
      }
    }
    
    function save_post_hook($post_id){
      return $post_id;
    }
}
?>
