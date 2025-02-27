<?php
/*
Plugin Name: Bay Nature Featured Post Widget
Plugin URI: http://baynature.org
Description: Displays featured image and excerpt based on user-entered post ID
Author: Laura Schatzkin for Bay Nature
Version: 1.0
Author URI: http://baynature.org
*/


class BNFeaturedPostWidget extends WP_Widget
{
  function BNFeaturedPostWidget()
  {
    $widget_ops = array('classname' => 'BNFeaturedPostWidget', 'description' => 'Displays a post determined by user' );
    $this->WP_Widget('BNFeaturedPostWidget', 'Featured Post and Thumbnail', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array(  'postid' => '' ) );
	$postid = $instance['postid'];
?>
  
  <p><label for="<?php echo $this->get_field_id('postid'); ?>">Post ID: <input class="widefat" id="<?php echo $this->get_field_id('postid'); ?>" name="<?php echo $this->get_field_name('postid'); ?>" type="text" value="<?php echo attribute_escape($postid); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
	$instance['postid'] = $new_instance['postid'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
	$postid=$instance['postid'];
    echo $before_widget;
 
    // WIDGET CODE GOES HERE

	$args = array (
		'p' => $postid,
		'posts_per_page'         => '1',
		'post_type' => 'post'
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<div class="featured-grid">';
			echo '<div class="featured-date-grid">' .get_the_date().'</div>';
		  	if ( has_post_thumbnail() ) {		  
			  echo '
				  <div class="featured-image-grid">
					  <a href="'.get_permalink().'"><img src="' . get_thumb_url(get_thumbnail_src($post->ID),300,200) . '" alt="" /></a>
				  </div>
			  
			  ';
		  }	
		  echo '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';	
		  echo '<p>'. get_the_excerpt(). '</p>';	
		  echo '</div>';	
		
		}
	} else {
		echo '<h1> Sorry, no posts found</h1>';
	}
	wp_reset_postdata();		
 
    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("BNFeaturedPostWidget");') );?>