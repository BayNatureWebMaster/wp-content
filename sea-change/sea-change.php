<?php
//define page name for navigation
$thisPage='sea';
$PageTitle='Bay Nature | Sea of Troubles';
// Bring in WP functionality on this non-WP page
include_once ("../../wp-load.php");
// start html here
include("../../wp-content/sea-change/parts/header.php");

?>
<video loop poster="../../wp-content/sea-change/assets/waves.jpg" id="bgvid" autoplay> <!-- this is where autoplay should be -->
	<source src="../../wp-content/sea-change/assets/waves.mp4" type="video/mp4">
</video>
<div class="row hold" id="landing">
	<div class="contain">
		<?php
		$sea_query = new WP_Query( array( 'pagename' => 'sea-of-troubles' ) );
		while ( $sea_query->have_posts() ) :
			$sea_query->the_post();
			echo "<h1>". get_the_title() . "</h1>";
			echo "<p>";
				echo the_content();
			echo "</p>";
		endwhile;
		?>
		<div class="scroll">
			<small>SCROLL</small><br/>
			<a href="#read"><div class="arrow-down"></div></a>
		</div>
	</div>

</div>
<div class="row" id="read">
	<!-- Cover Photo -->
	<?php
	$lurks_query = new WP_Query( array( 'name' => 'what-lurks-beneath','post_type' => 'article') );
	while ( $lurks_query->have_posts() ) :
		$lurks_query->the_post();
		echo "<div class='cover-photo-shell'>";
			echo "<img src='./assets/DSC_0107.jpg'>";
		echo "</div>";

		echo "<div class='contain'>";
			// add tile and subtitle here
			echo "<div class='lurks-banner-2a' style='color:#000000'>";
				echo "<h1>". get_the_title() . "</h1>";
				echo "<h3>" .get_post_meta($post->ID, "subtitle", true) . "</h3>";
			echo "</div>";
			//
			echo "<p>";
				echo "<div style='max-width:650px; margin-left:10%;'>";
					echo "<h5 style='color:#0f0f0f'><em>by ";
						echo get_post_meta($post->ID, "byline", true);
					echo "</em></h5>";
					echo the_content();
				echo "</div>";
			echo "</p>";
			endwhile;
			?>
		</div>
</div>

<div class="row" id="watch-desc">
	<div class="contain">
		<?php
		$sea_query = new WP_Query( array( 'pagename' => 'sea-of-troubles' ) );
		while ( $sea_query->have_posts() ) :
			$sea_query->the_post();
			echo "<h2>Watch</h2>";
			echo "<p>";
			echo the_field( 'watch_text');
			echo "</p>";
		?>
	</div>
</div>
	<div class="row" id="watch">
		<div class="contain">
			<?php
				echo "<div class='watch-video'>";
				$watch = get_post_meta($post->ID, "watch", true);
				echo wp_oembed_get( 'https://vimeo.com/' . $watch );
				echo "</div>";
			endwhile;
			?>
		</div>

		<?php
		// Get the video URL and put it in the $video variable
		$videoID = get_post_meta($post->ID, 'video_url', true);
		// Echo the embed code via oEmbed
		echo wp_oembed_get( 'http://www.youtube.com/watch?v=' . $videoID );
		?>

</div>
	<?php include("../../wp-content/sea-change/parts/act.php"); ?>
	<?php include("../../wp-content/sea-change/parts/about.php"); ?>

	<?php include("../../wp-content/sea-change/parts/footer.php"); ?>
