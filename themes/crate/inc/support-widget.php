<?php

class BN_Support_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'bn_support_widget',
			'description' => 'Displays ways to support.',
		);
		$control_ops = array(
			'width' => 400,
			'height' => 350,
		);
		parent::__construct( 'bn_support_widget', esc_html__( 'Bay Nature Support', 'crate' ), $widget_ops, $control_ops );

	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_text', $instance['title'] ) . $args['after_title'];
		}
		echo '<ol class="ways-to-support">';
		if ( ! empty( $instance['subscribe'] ) ) {
			echo '<li>';
				echo '<p><span>'. apply_filters( 'widget_title', $instance['subscribe'] ) .'</span></p>';
				echo '<a target="_self" href="/membership/" class="button subscribe_sidebar">'. __( 'Join', 'crate' ) .'</a>';
			echo '</li>';
		}
		if ( ! empty( $instance['donate'] ) ) {
			echo '<li>';
				echo '<p><span>'. apply_filters( 'widget_title', $instance['donate'] ) .'</span></p>';
				echo '<a target="_self" href="https://baynature.app.neoncrm.com/forms/donate" class="button button-alt donate-sidebar">'. __( 'Donate', 'crate' ) .'</a>';
				//echo '<a href="" class="button button-alt donate-sidebar">'. __( 'Donate', 'crate' ) .'</a>';
			echo '</li>';
		}
		if ( ! empty( $instance['newsletter'] ) ) {
			echo '<li>';
				echo '<p><span>'. apply_filters( 'widget_title', $instance['newsletter'] ) .'</span></p>';
				if ( ! empty( $instance['cta'] ) ) {
					echo '<p class="cta">';
						echo apply_filters( 'widget_text', $instance['cta'] );
					echo '</p>';
				}
				// BN LRT Upgrade to new sidebar shortcode - used to track in ga
				echo do_shortcode( '[subscribe_sidebar email_placeholder="Your email" button_text="Go"]' );
				//echo do_shortcode( '[subscribe email_placeholder="Your email" button_text="Go"]' );
			echo '</li>';
		}
		echo '</ol>';
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
			$title      = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'crate' );
			$subscribe  = ! empty( $instance['subscribe'] ) ? $instance['subscribe'] : esc_html__( '', 'crate' );
			$donate     = ! empty( $instance['donate'] ) ? $instance['donate'] : esc_html__( '', 'crate' );
			$newsletter = ! empty( $instance['newsletter'] ) ? $instance['newsletter'] : esc_html__( '', 'crate' );
			$cta        = ! empty( $instance['cta'] ) ? $instance['cta'] : esc_html__( '', 'crate' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'crate' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'subscribe' ) ); ?>"><?php esc_attr_e( 'Subscribe Text:', 'crate' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'subscribe' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'subscribe' ) ); ?>" type="text" value="<?php echo esc_attr( $subscribe ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'donate' ) ); ?>"><?php esc_attr_e( 'Donate Text:', 'crate' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'donate' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'donate' ) ); ?>" type="text" value="<?php echo esc_attr( $donate ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'newsletter' ) ); ?>"><?php esc_attr_e( 'Newsletter Text:', 'crate' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'newsletter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'newsletter' ) ); ?>" type="text" value="<?php echo esc_attr( $newsletter ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cta' ) ); ?>"><?php esc_attr_e( 'Call To Action:', 'crate' ); ?></label> 
			<textarea class="widefat" rows="4" cols="50" id="<?php echo esc_attr( $this->get_field_id( 'cta' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'cta' ) ); ?>" ><?php echo esc_html( $cta ); ?></textarea>
		</p>
	<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$allowed_html = array(
		'span' => array(),
		'br' => array(),
		);
		$instance['title']      = ( ! empty( $new_instance['title'] ) ) ? wp_kses( $new_instance['title'], $allowed_html ) : '';
		$instance['cta']        = ( ! empty( $new_instance['cta'] ) ) ? sanitize_textarea_field( $new_instance['cta'] ) : '';
		$instance['subscribe']  = ( ! empty( $new_instance['subscribe'] ) ) ? sanitize_text_field( $new_instance['subscribe'] ) : '';
		$instance['donate']     = ( ! empty( $new_instance['donate'] ) ) ? sanitize_text_field( $new_instance['donate'] ) : '';
		$instance['newsletter'] = ( ! empty( $new_instance['newsletter'] ) ) ? sanitize_text_field( $new_instance['newsletter'] ) : '';

		return $instance;
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'BN_Support_Widget' );
});
