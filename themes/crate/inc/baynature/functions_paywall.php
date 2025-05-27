<?php
/******************************************************************
 * * Paywall functions
 * *
 * * April 8 2025
 * *
 * ****************************************************************/
function show_first_paragraph ( $content_str ) {
	$str_1 = $content_str;
	// find the start of the first paragraph. It will have the drop cap class
	$p1 = strpos( $str_1 , "<p class=\"has-drop-cap\">");
	$sub_str = substr($str_1 , $p1);
	//echo "subs 1 = ".$sub_str;
	// locate the close paragraph tag
	$p2 = strpos( $sub_str , "</p>") + 4;
	//echo "the sub str leng = ". strlen( $sub_str ) . "<br>";
	//echo "p2 = ".$p2 . "<br>";
	//echo "sub str l = " .strlen($sub_str);
	$the_first_paragraph = substr( $sub_str , 0, $p2);
	//echo "the leng = ". strlen( $the_first_paragraph ) . "<br>";
	if ( strlen( $the_first_paragraph )  > 10) {
		echo $the_first_paragraph ;
		$remaining_str = substr($sub_str, $p2);
		return $remaining_str;
	}
	else {
		// if in the event there is no first paragraph show the excerpt
		echo get_the_excerpt() . "<br><br>";
		return "";
	}
}

function display_the_next_paragraph( $content_str , $searchFor ) {
	// find the next <p in the content str
	//$p1 = strpos( $content_str ,"<p");
	$p1 = strpos( $content_str , $searchFor);
	$sub_str = substr(  $content_str , $p1 );
	// find the end of the paragraph
	$p2 = strpos( $sub_str , "</p>") + 4;
	$the_paragraph = substr( $sub_str , 0, $p2);
	echo $the_paragraph;
	$remaining_str = substr($sub_str, $p2);
	return $remaining_str;
}

function display_member_login_message () {
	$member_login_message = get_field("paywall_login_message" , "option");
	$member_login_link =  get_field("paywall_login_link" , "option");
	$html = "<div class='container subscribe-wrap'>";
	$html .= "<div class='subscribe-content'>";
	$html .= "<div class='subscribe-message'>";
	$html .= "<p>".$member_login_message."</p>";
	$html .= "</div>";
	$html .= "<div class='subscribe-button'>";
	$html .= "<a class ='button button-large paywall-member-login' href='".$member_login_link."'>Member Login</a>";
	$html .="</div>";
	$html .="</div>";
	$html .="</div>";
	echo $html;
}
function display_become_a_member_message ( $contentType ) {
	$non_member_message = get_field("paywall_become_a_member_message" , "option");
	$become_a_member_link = get_field("paywall_become_a_member_link" , "option");
	$html = "<div class='container subscribe-wrap'>";
	$html .= "<h3>".get_pay_wall_heading( $contentType )."</h3>";
	$html .= "<div class='subscribe-content'>";
	$html .= "<div class='subscribe-message'>";
	$html .= "<p>".$non_member_message."</p>";
	$html .= "</div>";
	$html .= "<div class='subscribe-button'>";
	$html .= "<a class ='button button-large paywall-join-renew' href='".$become_a_member_link."'>Join / Renew</a>";
	$html .="</div>";
	$html .="</div>";
	$html .="</div>";
	echo $html;
}

function get_pay_wall_heading( $contentType ) {
	//echo " what is this ? ".$contentType;
	if (strcmp($contentType, "talks") === 0) {
		$member_content_heading = "To view this Bay Nature Talk ...";
	}
	else {
		$member_content_heading = get_field('paywall_greeting' , 'option');
	}
	$html = "<h3>".$member_content_heading."</h3>";
	return $html;
}


function show_member_login_message( $contentType ) {
	//$number_of_paragraphs = get_field("paywall_article_number_of_paragraphs" , "option");
	$content_str = get_the_content();
	$remaining_str = display_the_next_paragraph( $content_str , "<p class=\"has-drop-cap\">"); //show_first_paragraph( $content_str );
	if ( strcmp( $contentType , "article") === 0 ) {
		 //for ($i = 1; $i <= $number_of_paragraphs; $i++ ) {
		 	$remaining_str = display_the_next_paragraph( $remaining_str , "<p" );
		// }
	}
	display_become_a_member_message( $contentType );
	display_member_login_message();
}

/***************************************************************************************
 * The unlock_paywall function is called by the paywall template. This function
 * examines the utm_campaogn paramater to determin if it contains either the Master Key
 * or the Staff Share Key. If the Master Key is present this function returns true.
 * If the Staff Share Key is present and the expiration date has not past this function
 * returns true. Otherwise this function returns false.
 * 
 * 
 * April 9 2025
 * 
 * *************************************************************************************
function unlock_paywall () {
	echo "paywall test";
	if ( is_cookie_key_set() ) {
		return true;
	}
	if ( is_master_key_set() ) {
		return true;
	}
	if ( is_staff_share_key_set() ) {
		return true;
	}
	return false;
}

function is_master_key () {
	$master_key = get_field('master_key' , 'option');
	if (isset($_GET['utm_campaign'])) {
  		$utm_campaign = $_GET['utm_campaign'];		
		if ( str_contains( $utm_campaign , $master_key ) ) {
			set_cookie_key();
		}
	}

}
add_action( 'init', 'is_master_key', 0 );

function set_cookie_key() {
	$master_key = get_field('master_key' , 'option');
	$name = "PW_KEY";
	$eTime = time()+60*60*24;
	setcookie( $name,$master_key, $eTime,"","",false,false);
}

function is_cookie_key_set() {
	echo "test cookie";
	$master_key = get_field('master_key' , 'option');
	if (isset($_COOKIE['PW_KEY'])) {
		$pw_key = htmlspecialchars($_COOKIE["PW_KEY"]);
		echo "show cookie =".$pw_key;
		if ( str_contains( $pw_key , $master_key ) ) {
  			return true;
  		}
	}
	return false;
}

function is_master_key_set () {
	$master_key = get_field('master_key' , 'option');
	if (isset($_GET['utm_campaign'])) {
  		$pw_key = $_GET['utm_campaign'];
  		if ( str_contains( $pw_key , $master_key ) ) {
  			return true;
  		}
	}
	return false;
}
*/
function is_staff_share_key_set () {
// get the keys
	$limited_access_key = get_field('staff_sharing_key' , 'option' );
	// get the expiration date associated with the staff sharing key
	$max_day = 30;
	$limitted_access_expiration_year = get_field( 'sharing_key_expiration_year' , 'option' );
	$limitted_access_expiration__month =  (
		(get_field( 'sharing_key_expiration_month' , 'option' ) > 12) ? 12 : get_field( 'sharing_key_expiration_month' , 'option'));
	return false;
}
	/*
	switch ($limitted_access_expiration__month) {
		case 1:
			// jan - 31
		case 3:
			// march - 31
		case 5:
			// may - 31
		case 7:
			// july - 31
		case 8:
			// august - 31
		case 10:
			// october - 31
		case 12:
			// december - 31
			$max_day = 31;
			break;
		case 9:
			// sept - 30
		case 4:
			// april - 30
		case 11:
			// november - 30
		case 6:
			// june - 30
			$max_day = 30;
			break;
		case 2:
			// feb - 28
			$max_day = 28;
			break;

	}
	$limitted_access_expiration__day =  
		((get_field( 'sharing_key_expiration_day' , 'option' ) > $max_day) ? $max_day : get_field( 'sharing_key_expiration_day' , 'option' ));

	//echo "la_key = " .$limited_access_key ."<br>";
	//echo "la_year = " .$limitted_access_expiration_year ."<br>";
	//echo "la_month = " .$limitted_access_expiration__month ."<br>";
	//echo "la_day = " .$limitted_access_expiration__day ."<br>";
	//echo "max day = " .$max_day ."<br>";


	// get the current month, day, and year
	$date_now = date("m/d/y");
	$date_now_array = explode("/", $date_now);
	$now_month = intval($date_now_array[0]);
	$now_day = intval($date_now_array[1]);
	$now_year = intval($date_now_array[2]);;
	
	// test to see if the Staff Sharing Key is present
	if ( str_contains( $utm_campaign , $limited_access_key )) {
		// the the Staff Sharing key is present - next test if the key has expired
		if ( $now_year > $limitted_access_expiration_year ) {
			// year expired: lock content
			return false;
		} else {
			if ( $now_year < $limitted_access_expiration_year ) {
				// expiration date is next year : Unlock content
				return true;
			} else {
				// is the expriation year before the current year?
				if ( $now_year > $limitted_access_expiration_year ) {
					// expiration year is in the past : lock content
					return false;
				}
				// the expiration year = the current year - has the expriation month past?
				if ( $now_month > $limitted_access_expiration__month ) {
					// month past expiration: lock content
					return false;
					} else {
					// see if we are currently in the expriation month
					if ( $now_month < $limitted_access_expiration__month ) {
						// month not here yet Unlock content
						return true;
					}
					else {
						// same month - examine day
						if ( $limitted_access_expiration__day >  $now_day) {
							// day not here yet Unlock content";
							return true;
						}
						else {
							// day is past expiration: lock content
							return false;
							}
						}

					}
				}
			}
		}
	}
	return false;
}

*/
function unlock_paywall(  ) {
	// get the keys
	$master_key = get_field('master_key' , 'option');
	//echo "master key = ".$master_key ."<br>";
	$limited_access_key = get_field('staff_sharing_key' , 'option' );
	// get the expiration date associated with the staff sharing key
	$max_day = 30;
	$limitted_access_expiration_year = get_field( 'sharing_key_expiration_year' , 'option' );
	$limitted_access_expiration__month =  (
		(get_field( 'sharing_key_expiration_month' , 'option' ) > 12) ? 12 : get_field( 'sharing_key_expiration_month' , 'option'));
	switch ($limitted_access_expiration__month) {
		case 1:
			// jan - 31
		case 3:
			// march - 31
		case 5:
			// may - 31
		case 7:
			// july - 31
		case 8:
			// august - 31
		case 10:
			// october - 31
		case 12:
			// december - 31
			$max_day = 31;
			break;
		case 9:
			// sept - 30
		case 4:
			// april - 30
		case 11:
			// november - 30
		case 6:
			// june - 30
			$max_day = 30;
			break;
		case 2:
			// feb - 28
			$max_day = 28;
			break;

	}
	$limitted_access_expiration__day =  
		((get_field( 'sharing_key_expiration_day' , 'option' ) > $max_day) ? $max_day : get_field( 'sharing_key_expiration_day' , 'option' ));

	//echo "la_key = " .$limited_access_key ."<br>";
	//echo "la_year = " .$limitted_access_expiration_year ."<br>";
	//echo "la_month = " .$limitted_access_expiration__month ."<br>";
	//echo "la_day = " .$limitted_access_expiration__day ."<br>";
	//echo "max day = " .$max_day ."<br>";


	// get the current month, day, and year
	$date_now = date("m/d/y");
	$date_now_array = explode("/", $date_now);
	$now_month = intval($date_now_array[0]);
	$now_day = intval($date_now_array[1]);
	$now_year = intval($date_now_array[2]);;
	// determine if the Master Key is present
	if (isset($_GET['utm_campaign'])) {
  		$utm_campaign = $_GET['utm_campaign'];
		} else {
  		//Handle the case where there is no utm_campaign parameter
		return false;
	}
	if ( str_contains( $utm_campaign , $master_key ) ) {
		return true;
	} else {
		// test to see if the Staff Sharing Key is present
		if ( str_contains( $utm_campaign , $limited_access_key )) {
			// the the Staff Sharing key is present - next test if the key has expired
			if ( $now_year > $limitted_access_expiration_year ) {
				// year expired: lock content
				return false;
			} else {
				if ( $now_year < $limitted_access_expiration_year ) {
					// expiration date is next year : Unlock content
					return true;
				} else {
					// is the expriation year before the current year?
					if ( $now_year > $limitted_access_expiration_year ) {
						// expiration year is in the past : lock content
						return false;
					}
					// the expiration year = the current year - has the expriation month past?
					if ( $now_month > $limitted_access_expiration__month ) {
						// month past expiration: lock content
						return false;
					} else {
						// see if we are currently in the expriation month
						if ( $now_month < $limitted_access_expiration__month ) {
							// month not here yet Unlock content
							return true;
						}
						else {
							// same month - examine day
							if ( $limitted_access_expiration__day >  $now_day) {
								// day not here yet Unlock content";
								return true;
							}
							else {
								// day is past expiration: lock content
								return false;
							}
						}

					}
				}
			}
		}
	}
	return false;
}
?>