<?php
// Don't load directly
defined( 'WPINC' ) or die;

/**
 * My Events Column for Title Display
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/columns/title.php
 *
 * @link https://evnt.is/1ao4 Help article for Community & Tickets template files.
 *
 * @since 4.5
 * @since 4.8.2 Updated template link.
 *
 * @version 4.8.2
 */

$community = tribe( 'community.main' );

$canEdit   = $community->user_can_edit_their_submissions($event->ID);
$canView   = ( get_post_status( $event->ID ) == 'publish' || $canEdit );
$canDelete = $community->user_can_delete_their_submissions($event->ID);
if ( $canEdit ) {
	?>
	<span class="title">
		<a href="<?php echo esc_url( tribe_community_events_edit_event_link( $event->ID ) ); ?>">
			<?php echo get_the_title( $event ); ?>
		</a>
	</span>
	<?php
} else {
	echo get_the_title( $event );
}
?>
<div class="row-actions">
	<?php
	if ( $canView ) {
		?>
		<span class="view">
			<?php // @todo redscar - move tribe_get_event_link? ?>
			<a href="<?php echo function_exists( 'tribe_get_event_link' ) ? tribe_get_event_link( $event->ID ) : get_post_permalink( $event->ID ); ?>"><?php esc_html_e( 'View', 'tribe-events-community' ); ?></a>
		</span>
		<?php
	}

	if ( $canEdit ) {
		echo tribe( 'community.main' )->getEditButton( $event, __( 'Edit', 'tribe-events-community' ), '<span class="edit wp-admin events-cal"> |', '</span> ' );
	}

	if ( $canDelete ) {
		echo tribe( 'community.main' )->getDeleteButton( $event );
	}
	do_action( 'tribe_events_community_event_list_table_row_actions', $event );
	?>
</div>
