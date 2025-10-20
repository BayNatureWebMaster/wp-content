<?php
/**
 * Community: Pagination controls
 *
 * Override in:
 *     [your-theme]/tribe/community/pagination.php
 *
 * @version 5.0.10
 * @since 5.0.10
 *
 * @var Pagination $pagination
 */

// Don't load directly.
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use TEC\Events_Community\Listing\Pagination;

if ( ! $pagination instanceof Pagination ) {
	return;
}

$nums  = $pagination->get_summary_numbers();
$items = $pagination->build_items();

if ( empty( $items ) ) {
	return;
}

// Navigation label is fully translatable here.
$aria_label = sprintf(
/* translators: 1: current page number, 2: total number of pages */
	__( 'Page %1$s of %2$s', 'tribe-events-community' ),
	number_format_i18n( $nums['current'] ),
	number_format_i18n( $nums['total'] )
);
?>
<nav class="tec-pagination" role="navigation" aria-label="<?php echo esc_attr( $aria_label ); ?>">
	<ul class="tec-pagination__list page-numbers">
		<?php foreach ( $items as $item ) : ?>
			<?php if ( 'ellipsis' === $item['type'] ) : ?>
				<li class="tec-pagination__item tec-pagination__item--dots" role="presentation">
					<span class="tec-pagination__dots dots" aria-hidden="true">&hellip;</span>
				</li>
				<?php continue; ?>
			<?php endif; ?>

			<?php if ( 'number' === $item['type'] && ! empty( $item['current'] ) ) : ?>
				<?php
				$current_href = call_user_func( $pagination->get_url_resolver(), (int) $item['page'] );
				$label        = sprintf(
				/* translators: %s: current page number */
					esc_html__( 'Page %s, current', 'tribe-events-community' ),
					number_format_i18n( (int) $item['page'] )
				);
				?>

				<li class="tec-pagination__item tec-pagination__item--current">
					<a class="tec-pagination__link"
						href="<?php echo esc_url( $current_href ); ?>"
						aria-current="page"
						aria-label="<?php echo esc_attr( $label ); ?>">
						<?php echo esc_html( number_format_i18n( (int) $item['page'] ) ); ?>
					</a>
				</li>
				<?php continue; ?>
			<?php endif; ?>

			<?php
			// Controls and non-current numbers share the same rendering path.
			$classes = 'tec-pagination__item tec-pagination__item--' . $item['type'];
			$label   = '';
			$icon    = '';

			switch ( $item['type'] ) {
				case 'first':
					$label = esc_html_x( 'First page', 'pagination control', 'tribe-events-community' );
					$icon  = '&laquo;';
					break;
				case 'prev':
					$label = esc_html_x( 'Previous page', 'pagination control', 'tribe-events-community' );
					$icon  = '&lsaquo;';
					break;
				case 'next':
					$label = esc_html_x( 'Next page', 'pagination control', 'tribe-events-community' );
					$icon  = '&rsaquo;';
					break;
				case 'last':
					$label = esc_html_x( 'Last page', 'pagination control', 'tribe-events-community' );
					$icon  = '&raquo;';
					break;
				default: // number (non-current).
					/* translators: %s: page number */
					$label = sprintf( esc_html__( 'Page %s', 'tribe-events-community' ), number_format_i18n( $item['page'] ) );
					$icon  = number_format_i18n( $item['page'] );
					break;
			}
			?>
			<li class="<?php echo esc_attr( $classes ); ?>">
				<a
					class="tec-pagination__link"
					href="<?php echo esc_url( $item['url'] ); ?>"
					<?php if ( ! empty( $item['rel'] ) ) : ?>
						rel="<?php echo esc_attr( $item['rel'] ); ?>"
					<?php endif; ?>
					aria-label="<?php echo esc_attr( $label ); ?>"
				>
					<?php if ( in_array( $item['type'], [ 'first', 'prev', 'next', 'last' ], true ) ) : ?>
						<span aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
					<?php else : ?>
						<?php echo esc_html( $icon ); ?>
					<?php endif; ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
