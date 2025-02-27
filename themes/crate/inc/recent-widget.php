<?php

function register_bnrpwe() {


if ( class_exists( 'Recent_Posts_Widget_Extended' ) ) :

	class Bay_Nature_Recent_Widget extends Recent_Posts_Widget_Extended {

		/**
		 * Sets up the widgets.
		 *
		 */
		public function __construct() {
			/* Set up the widget options. */
			$widget_options = array(
				'classname'   => 'rpwe_widget recent-posts-extended',
				'description' => __( 'An advanced widget that gives you total control over the output of your site’s most recent Posts.', 'recent-posts-widget-extended' ),
				'customize_selective_refresh' => true
			);
			$control_options = array(
				'width'  => 750,
				'height' => 350
			);
			/* Create the widget. */
			WP_Widget::__construct(
				'bnrpwe_widget',
				__( 'Bay Nature Recent Posts Extended', 'crate' ),
				$widget_options,
				$control_options
			);
			$this->alt_option_name = 'bnrpwe_widget';
		}

		/**
		 * Outputs the widget based on the arguments input through the widget controls.
		 *
		 */
		public function widget( $args, $instance ) {
			extract( $args );
			$recent = self::bayn_get_recent_posts( $instance );
			if ( $recent ) {
				// Output the theme's $before_widget wrapper.
				echo $before_widget;
				// If both title and title url is not empty, display it.
				if ( ! empty( $instance['title_url'] ) && ! empty( $instance['title'] ) ) {
					echo $before_title . '<a href="' . esc_url( $instance['title_url'] ) . '" title="' . esc_attr( $instance['title'] ) . '">' . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . '</a>' . $after_title;
				// If the title not empty, display it.
				} elseif ( ! empty( $instance['title'] ) ) {
					echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
				}
				// Get the recent posts query.
				echo $recent;
				// Close the theme's widget wrapper.
				echo $after_widget;
			}
		}

		/**
		 * Generates the posts markup.
		 *
		 * @param  array  $args
		 * @return string|array The HTML for the random posts.
		 */
		function bayn_get_recent_posts( $args = array() ) {

			// Set up a default, empty variable.
			$html = '';

			// Merge the input arguments and the defaults.
			$args = wp_parse_args( $args, rpwe_get_default_args() );

			// Extract the array to allow easy use of variables.
			extract( $args );

			// Allow devs to hook in stuff before the loop.
			do_action( 'rpwe_before_loop' );

			// Display the default style of the plugin.
			if ( $args['styles_default'] === true ) {
				rpwe_custom_styles();
			}

			// If the default style is disabled then use the custom css if it's not empty.
			if ( $args['styles_default'] === false && ! empty( $args['css'] ) ) {
				echo '<style>' . $args['css'] . '</style>';
			}

			// Get the posts query.
			$posts = rpwe_get_posts( $args );

			if ( $posts->have_posts() ) :

				// Recent posts wrapper
				$html = '<div ' . ( ! empty( $args['cssID'] ) ? 'id="' . sanitize_html_class( $args['cssID'] ) . '"' : '' ) . ' class="rpwe-block ' . ( ! empty( $args['css_class'] ) ? '' . sanitize_html_class( $args['css_class'] ) . '' : '' ) . '">';

					$html .= '<ul class="rpwe-ul">';

						while ( $posts->have_posts() ) : $posts->the_post();

							// Thumbnails
							$thumb_id = get_post_thumbnail_id(); // Get the featured image id.
							$img_url  = wp_get_attachment_url( $thumb_id ); // Get img URL.

							// Display the image url and crop using the resizer.
							$image    = rpwe_resize( $img_url, $args['thumb_width'], $args['thumb_height'], true );

							// Start recent posts markup.
							$html .= '<li class="rpwe-li rpwe-clearfix">';

								if ( $args['thumb'] ) :

									// Check if post has post thumbnail.
									if ( has_post_thumbnail() ) :
										$html .= '<a class="rpwe-img" href="' . esc_url( get_permalink() ) . '"  rel="bookmark">';
											if ( $image ) :
												$html .= '<img class="' . esc_attr( $args['thumb_align'] ) . ' rpwe-thumb" src="' . esc_url( $image ) . '" alt="' . esc_attr( get_the_title() ) . '">';
											else :
												$html .= get_the_post_thumbnail( get_the_ID(),
													array( $args['thumb_width'], $args['thumb_height'] ),
													array(
														'class' => $args['thumb_align'] . ' rpwe-thumb the-post-thumbnail',
														'alt'   => esc_attr( get_the_title() )
													)
												);
											endif;
										$html .= '</a>';

									// If no post thumbnail found, check if Get The Image plugin exist and display the image.
									elseif ( function_exists( 'get_the_image' ) ) :
										$html .= get_the_image( array(
											'height'        => (int) $args['thumb_height'],
											'width'         => (int) $args['thumb_width'],
											'image_class'   => esc_attr( $args['thumb_align'] ) . ' rpwe-thumb get-the-image',
											'image_scan'    => true,
											'echo'          => false,
											'default_image' => esc_url( $args['thumb_default'] )
										) );

									// Display default image.
									elseif ( ! empty( $args['thumb_default'] ) ) :
										$html .= sprintf( '<a class="rpwe-img" href="%1$s" rel="bookmark"><img class="%2$s rpwe-thumb rpwe-default-thumb" src="%3$s" alt="%4$s" width="%5$s" height="%6$s"></a>',
											esc_url( get_permalink() ),
											esc_attr( $args['thumb_align'] ),
											esc_url( $args['thumb_default'] ),
											esc_attr( get_the_title() ),
											(int) $args['thumb_width'],
											(int) $args['thumb_height']
										);

									endif;

								endif;

								$html .= '<h3 class="rpwe-title"><a href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'recent-posts-widget-extended' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark">' . esc_attr( get_the_title() ) . '</a></h3>';

								if ( $args['date'] ) :
									$date = get_the_date();
									if ( $args['date_relative'] ) :
										$date = sprintf( __( '%s ago', 'recent-posts-widget-extended' ), human_time_diff( get_the_date( 'U' ), current_time( 'timestamp' ) ) );
									endif;
									$html .= '<time class="rpwe-time published" datetime="' . esc_html( get_the_date( 'c' ) ) . '">' . esc_html( $date ) . ' | ' . get_the_category()[0]->name . '</time>';
								elseif ( $args['date_modified'] ) : // if both date functions are provided, we use date to be backwards compatible
									$date = get_the_modified_date();
									if ( $args['date_relative'] ) :
										$date = sprintf( __( '%s ago', 'recent-posts-widget-extended' ), human_time_diff( get_the_modified_date( 'U' ), current_time( 'timestamp' ) ) );
									endif;
									$html .= '<time class="rpwe-time modfied" datetime="' . esc_html( get_the_modified_date( 'c' ) ) . '">' . esc_html( $date ) . ' | ' . get_the_category()[0]->name . '</time>';
								endif;

								if ( $args['comment_count'] ) :
									if ( get_comments_number() == 0 ) {
											$comments = __( 'No Comments', 'recent-posts-widget-extended' );
										} elseif ( get_comments_number() > 1 ) {
											$comments = sprintf( __( '%s Comments', 'recent-posts-widget-extended' ), get_comments_number() );
										} else {
											$comments = __( '1 Comment', 'recent-posts-widget-extended' );
										}
									$html .= '<a class="rpwe-comment comment-count" href="' . get_comments_link() . '">' . $comments . '</a>';
								endif;

								if ( $args['excerpt'] ) :
									$html .= '<div class="rpwe-summary">';
										$html .= wp_trim_words( apply_filters( 'rpwe_excerpt', get_the_excerpt() ), $args['length'], ' &hellip;' );
										if ( $args['readmore'] ) :
											$html .= '<a href="' . esc_url( get_permalink() ) . '" class="more-link">' . $args['readmore_text'] . '</a>';
										endif;
									$html .= '</div>';
								endif;

							$html .= '</li>';

						endwhile;

					$html .= '</ul>';

				$html .= '</div><!-- Generated by http://wordpress.org/plugins/recent-posts-widget-extended/ -->';

			endif;

			// Restore original Post Data.
			wp_reset_postdata();

			// Allow devs to hook in stuff after the loop.
			do_action( 'rpwe_after_loop' );

			// Return the  posts markup.
			return wp_kses_post( $args['before'] ) . apply_filters( 'rpwe_markup', $html ) . wp_kses_post( $args['after'] );

		}

	}

register_widget( 'Bay_Nature_Recent_Widget' );

endif;


}

add_action( 'widgets_init', 'register_bnrpwe', 99 );