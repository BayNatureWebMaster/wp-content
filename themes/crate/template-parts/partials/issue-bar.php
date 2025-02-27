<!-- Add the Issue Bar -->
<?php

if ( is_singular() ) :

	$issue_key = get_post_meta( get_the_ID(), 'issue_key', true );
	// issue_key format = vYYnN where YY = last two numbers of a give year. EG: 21 => 2021, and N = 1 or 2 or 3 or 4 where 1 = Winter, 2 = Spring, 3 = Summer, and 4 = Fall
	$yy = substr( $issue_key , -4, 2);

	$year = "20".$yy;
	
	//echo $year;

	$n = substr( $issue_key , -1);
	switch ( $n ) {
		case "1":
			$season = "Winter ";
			$season2 = "winter";
			break;
		case "2":
			$season =  "Spring ";
			$season2 =  "spring";

			break;
		case "3":
			$season =  "Summer ";
			$season2 =  "summer";

			break;
		case "4":
			$season =  "Fall ";
			$season2 =  "fall";

			break;
	}
	$issueSeasonYear = $season . $year;
	$issueSeasonYear2 = $season2 . "-".$year;
	$issue_Link = "/magazine-archive/bay-nature-".$issueSeasonYear2;
//echo (" what is this ". $issue_Link);
	if ( is_in_magazine( get_the_id() ) ) {
	
		$issue_anchor_tag = '<a href="' . $issue_Link . '">' . $issueSeasonYear . '</a>';

		//wp_reset_postdata();

		?>
		<div id="issue-bar">
			<div class="content-issue-bar">
				<span class="source"><em>Bay Nature</em> magazine</span> &#9702; <span class="issue"><?php echo $issue_anchor_tag ?></span>
			</div>
		</div>
		<?php
	} //end if

endif;
?>
