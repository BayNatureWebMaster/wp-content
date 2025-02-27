<?php
/**
 * Crate shortcodes.
 *
 * @link https://codex.wordpress.org/Shortcode_API
 *
 * @package Crate
 */

namespace CShop\Crate;

/**
 * Run crate_social_links as a shortcode
 */
function social_links_shortcode( $atts ) {

	$social_links = crate_get_social_links();

	ob_start();

	$count = count( $social_links );

	if ( 0 !== $count ) {

		echo '<ul class="social-links">';

		for ( $i = 0; $i < $count; $i++ ) {
			?>
			<?php
			$link_text = esc_attr( $social_links[ $i ]['link_text'] );
			if (false !== strpos($link_text, "Facebook")) {
				$social_network = "Facebook";
			}
			elseif (false !== strpos($link_text, "Twitter")) {
				$social_network = "Twitter";
			}
			elseif (false !== strpos($link_text, "Instagram")) {
				$social_network = "Instagram";
			}
			elseif (false !== strpos($link_text, "LinkedIn")) {
				$social_network = "LinkedIn";
			}
			$class = "Like-".$social_network;
			?>
			<li><a class=<?php echo $class ?> target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( $social_links[ $i ]['url'] ); ?>" alt="<?php echo esc_attr( $social_links[ $i ]['link_text'] ); ?>"><i class="fab fa-<?php echo esc_html( $social_links[ $i ]['service'] ); ?>"></i></a></li>
			<?php
		}

		echo '</ul>';

	}

	return ob_get_clean();
}
add_shortcode( 'social-links', __NAMESPACE__ . '\social_links_shortcode' );

/**
 * Site Button Shortcode
 */
function button_shortcode( $atts, $content = null ) {
	$a = shortcode_atts(
		array(
			'class' => null,
			'href' => null,
			'target' => '_self',
		), $atts
	);

	$button = null;
	$rel = null;

	if ( ! empty( $a['href'] ) ) {

		if ( '_blank' === (string) $a['target'] ) {
			$rel = 'rel="noopener noreferrer"';
		}

		$button = '<a ' . $rel . ' target="' . esc_attr( $a['target'] ) . '" href="' . esc_attr( $a['href'] ) . '" class="button">' . $content . '</a>';

		if ( $a['class'] ) {
			$button = '<a ' . $rel . ' target="' . esc_attr( $a['target'] ) . '" href="' . esc_attr( $a['href'] ) . '" class="button ' . esc_attr( $a['class'] ) . '">' . $content . '</a>';
		}
	}

	return $button;
}
add_shortcode( 'button', __NAMESPACE__ . '\button_shortcode' );


/**
 * Ad Shortcode
 */
function ad_shortcode( $atts, $content = null ) {
	// Double Click for WP plugin v 0.2 uses $Doubleclick as a ref to the object, v 0.3 uses $doubleclick
	// The following fix allows for either version to work
	global $doubleclick;
	global $DoubleClick;
	//echo "What is double click?<br> ";
	// bn_print_r($DoubleClick);

	if ( empty ( $DoubleClick ) ) {
		$DoubleClick = $doubleclick;
	}
 	$a = shortcode_atts(
		array(
			'id' => null,
			'sizes'     => '300x250',
			'lazyload'  => true,
		), $atts
	);
	// bn_print_r($DoubleClick);
	if ( ! empty( $a['id'] ) ) {
		//bn_print_r($a['id']);
		if ( strpos( $a['sizes'], ':' ) !== false ) {
			$sizes = explode( ',', $a['sizes'] );
			$breakpoints = array();
			foreach ( $sizes as &$size ) {
				$breakpoint = explode( ':', $size );
				$breakpoints[ $breakpoint[0] ] = $breakpoint[1];
			}
			$sizes = $breakpoints;
		} else {
			$sizes = $a['sizes'];
		}

		$args = array(
			'lazyLoad' => $a['lazyload'],
		);

		ob_start();

		$DoubleClick->place_ad( $a['id'], $sizes, $args );
		echo("<br>");
		return ob_get_clean();

	}//end if
}
add_shortcode( 'ad', __NAMESPACE__ . '\ad_shortcode', 10, 3 );

/**
 * Social Share Shortcode ( this replaces the legacy shortcode the old site was using )
 */
function social_media_share_buttons_shortcode( $atts, $content = null ) {


	ob_start();
		include( locate_template( 'template-parts/partials/share.php', false, false ) );
	return ob_get_clean();
}
add_shortcode( 'social_media_share_buttons', __NAMESPACE__ . '\social_media_share_buttons_shortcode', 10, 3 );
