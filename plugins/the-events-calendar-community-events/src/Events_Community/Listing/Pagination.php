<?php
/**
 * Pagination view model for Community Events listings.
 *
 * Holds pagination state and computes normalized items for the template.
 *
 * @since 5.0.10
 * @package TEC\Events_Community\Listing
 */

namespace TEC\Events_Community\Listing;

/**
 * Pagination view-model (no rendering, no strings).
 *
 * Responsibilities:
 * - Hold pagination state.
 * - Compute visible window and build a normalized list of items.
 * - Provide numbers needed for i18n summaries in the template.
 *
 * All text/labels MUST live in the template for translation.
 *
 * @since 5.0.10
 */
class Pagination {
	/**
	 * Total number of pages (>= 1).
	 *
	 * @since 5.0.10
	 *
	 * @var int
	 */
	private int $total_pages = 1;

	/**
	 * Current page (1-based).
	 *
	 * @since 5.0.10
	 *
	 * @var int
	 */
	private int $current_page = 1;

	/**
	 * Pages to show on each side of the current page.
	 *
	 * @since 5.0.10
	 *
	 * @var int
	 */
	private int $range = 3;

	/**
	 * URL resolver: function (int $page): string.
	 *
	 * @since 5.0.10
	 *
	 * @var callable
	 */
	private $url_resolver;

	/**
	 * Setup and create the pagination.
	 *
	 * @since 5.0.10
	 *
	 * @param int      $total_pages  Total pages (>=1).
	 * @param int      $current_page Current page (1-based).
	 * @param callable $url_resolver function (int $page): string.
	 * @param int      $range        Siblings shown on each side of current.
	 *
	 * @return $this
	 */
	public function create_pagination(
		int $total_pages,
		int $current_page,
		callable $url_resolver,
		int $range = 3
	) {
		$this->set_total_pages( $total_pages );
		$this->set_current_page( $current_page );
		$this->set_url_resolver( $url_resolver );
		$this->set_range( $range );

		return $this;
	}

	/* --------------------------- Setters / Getters --------------------------- */

	/**
	 * Set total pages.
	 *
	 * @since 5.0.10
	 *
	 * @param int $total_pages Total pages.
	 *
	 * @return $this
	 */
	public function set_total_pages( int $total_pages ): self {
		$this->total_pages = max( 1, $total_pages );
		$this->clamp_current_page();

		return $this;
	}

	/**
	 * Get total pages.
	 *
	 * @since 5.0.10
	 *
	 * @return int
	 */
	public function get_total_pages(): int {
		return $this->total_pages;
	}

	/**
	 * Set current page.
	 *
	 * @since 5.0.10
	 *
	 * @param int $current_page Current page.
	 *
	 * @return $this
	 */
	public function set_current_page( int $current_page ): self {
		$this->current_page = max( 1, $current_page );
		$this->clamp_current_page();

		return $this;
	}

	/**
	 * Get current page.
	 *
	 * @since 5.0.10
	 *
	 * @return int
	 */
	public function get_current_page(): int {
		return $this->current_page;
	}

	/**
	 * Set range.
	 *
	 * @since 5.0.10
	 *
	 * @param int $range Range.
	 *
	 * @return $this
	 */
	public function set_range( int $range ): self {
		$this->range = max( 0, $range );

		return $this;
	}

	/**
	 * Get range.
	 *
	 * @since 5.0.10
	 *
	 * @return int
	 */
	public function get_range(): int {
		return $this->range;
	}

	/**
	 * Set URL resolver.
	 *
	 * @since 5.0.10
	 *
	 * @param callable $url_resolver function (int $page): string.
	 *
	 * @return $this
	 */
	public function set_url_resolver( callable $url_resolver ): self {
		$this->url_resolver = $url_resolver;

		return $this;
	}

	/**
	 * Get URL resolver.
	 *
	 * @since 5.0.10
	 *
	 * @return callable
	 */
	public function get_url_resolver(): callable {
		return $this->url_resolver;
	}

	/* ------------------------------ Public API ------------------------------ */

	/**
	 * Quick check for whether pagination controls should be rendered.
	 *
	 * @since 5.0.10
	 *
	 * @return bool
	 */
	public function has_multiple_pages(): bool {
		return $this->total_pages > 1;
	}

	/**
	 * Visible window bounds as [start, end], clamped.
	 *
	 * @since 5.0.10
	 *
	 * @return int[] {0: start, 1: end}
	 */
	public function get_page_range(): array {
		$start = max( 1, $this->current_page - $this->range );
		$end   = min( $this->total_pages, $this->current_page + $this->range );

		return [ $start, $end ];
	}

	/**
	 * Numbers for building an aria-label summary in the template.
	 *
	 * Example template usage:
	 *   sprintf( __( 'Page %1$s of %2$s', 'text-domain' ), number_format_i18n($cur), number_format_i18n($tot) )
	 *
	 * @since 5.0.10
	 *
	 * @return array{current:int,total:int}
	 */
	public function get_summary_numbers(): array {
		return [
			'current' => $this->current_page,
			'total'   => $this->total_pages,
		];
	}

	/**
	 * Build a normalized list of items to render.
	 *
	 * Each item:
	 *  - type:       'first'|'prev'|'number'|'ellipsis'|'next'|'last'
	 *  - page:       int|null
	 *  - url:        string|null
	 *  - current:    bool
	 *  - rel:        'prev'|'next'|null
	 *
	 * All human text (labels, aria-labels) belongs in the template.
	 *
	 * @since 5.0.10
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public function build_items(): array {
		if ( ! $this->has_multiple_pages() ) {
			return [];
		}

		[ $start, $end ] = $this->get_page_range();
		$resolve         = $this->url_resolver;

		$items = [];

		// First / Previous.
		if ( $this->current_page > 1 ) {
			if ( $start > 1 ) {
				$items[] = $this->create_control_item( 'first', 1, $resolve( 1 ), null );
			}
			$items[] = $this->create_control_item( 'prev', $this->current_page - 1, $resolve( $this->current_page - 1 ), 'prev' );
		}

		// Ellipsis before.
		if ( $start > 1 ) {
			$items[] = $this->create_ellipsis_item();
		}

		// Number window.
		for ( $i = $start; $i <= $end; $i++ ) {
			$items[] = [
				'type'    => 'number',
				'page'    => $i,
				'url'     => ( $i === $this->current_page ) ? null : $resolve( $i ),
				'current' => ( $i === $this->current_page ),
				'rel'     => null,
			];
		}

		// Ellipsis after.
		if ( $end < $this->total_pages ) {
			$items[] = $this->create_ellipsis_item();
		}

		// Next / Last.
		if ( $this->current_page < $this->total_pages ) {
			$items[] = $this->create_control_item( 'next', $this->current_page + 1, $resolve( $this->current_page + 1 ), 'next' );

			if ( $end < $this->total_pages ) {
				$items[] = $this->create_control_item( 'last', $this->total_pages, $resolve( $this->total_pages ), null );
			}
		}

		return $items;
	}

	/**
	 * Convenience factory using counts (e.g. WP_Query).
	 *
	 * @since 5.0.10
	 *
	 * @param int      $found_posts  Total number of posts found.
	 * @param int      $per_page     How many pages to display.
	 * @param int      $current_page Current Page.
	 * @param callable $url_resolver URL.
	 * @param int      $range        Range.
	 *
	 * @return self
	 */
	public static function from_counts(
		int $found_posts,
		int $per_page,
		int $current_page,
		callable $url_resolver,
		int $range = 3
	): self {
		// If per_page is 0, treat it as "show all posts on one page".
		if ( 0 === $per_page ) {
			$total_pages = 1;
		} else {
			$total_pages = (int) ceil( $found_posts / max( 1, $per_page ) );
			$total_pages = max( 1, $total_pages );
		}

		$pagination = new self();
		$pagination->create_pagination( $total_pages, $current_page, $url_resolver, $range );

		return $pagination;
	}

	/* ------------------------------- Internals ------------------------------ */

	/**
	 * Keep current_page within valid bounds.
	 *
	 * @since 5.0.10
	 *
	 * @return void
	 */
	private function clamp_current_page(): void {
		if ( $this->current_page > $this->total_pages ) {
			$this->current_page = $this->total_pages;
		}
	}

	/**
	 * Create a control item (first/prev/next/last).
	 *
	 * @since 5.0.10
	 *
	 * @param string      $type 'first'|'prev'|'next'|'last'.
	 * @param int         $page Page.
	 * @param string      $url  Url.
	 * @param string|null $rel  'prev'|'next'|null.
	 *
	 * @return array<string,mixed>
	 */
	private function create_control_item( string $type, int $page, string $url, ?string $rel ): array {
		return [
			'type'    => $type,
			'page'    => $page,
			'url'     => $url,
			'current' => false,
			'rel'     => $rel,
		];
	}

	/**
	 * Create an ellipsis item (purely presentational).
	 *
	 * @since 5.0.10
	 *
	 * @return array<string,mixed>
	 */
	private function create_ellipsis_item(): array {
		return [
			'type'    => 'ellipsis',
			'page'    => null,
			'url'     => null,
			'current' => false,
			'rel'     => null,
		];
	}
}
