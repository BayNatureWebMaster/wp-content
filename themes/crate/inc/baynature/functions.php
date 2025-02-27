<?php
/**
 *Copy over relevant functions from legacy Bay Nature Responsive Theme
 *
 * @package Crate
 */

if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access not allowed' ); }
?>

<?php
// Disable use XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// Disable X-Pingback to header
add_filter( 'wp_headers', 'disable_x_pingback' );
function disable_x_pingback( $headers ) {
    unset( $headers['X-Pingback'] );

return $headers;
}



/****************************************************************************************************************************************************************
* EDIT-29 : Mutsun Article
*
*****************************************************************************************************************************************************************/
function article_text_block_shortcode( $atts ){

	$article_text_block_atts = shortcode_atts( array(
        'color' => '#000000',
        'background_color' => '#ffffff',
    ), $atts );

	$color = $article_text_block_atts['color'];
	$background_color = $article_text_block_atts['background_color'];
	return '<div class="content-article-text" style="color:'.$color.'; background-color:'.$background_color.';">';
}
add_shortcode( 'article_text_block', 'article_text_block_shortcode' );

function article_media_block_shortcode( $atts ){
	$article_media_block_atts = shortcode_atts( array(
        'color' => '#000000',
        'background_color' => '#ffffff',
        'align' =>'center',
    ), $atts );

	$color = $article_media_block_atts['color'];
	$background_color = $article_media_block_atts['background_color'];
	$text_align = $article_media_block_atts['align'];
	return '<div class="content-article-media-full"  style="color:'.$color.'; background-color:'.$background_color.'; margin: 0 auto; text-align:'.$text_align.'">';
}
add_shortcode( 'article_media_block', 'article_media_block_shortcode' );

// side content, limited width, color args and float arg
function article_side_block_shortcode( $atts ){
	$article_side_block_atts = shortcode_atts( array(
        'color' => '#000000',
        'background_color' => '#ffffff',
        'float'=>'left',
    ), $atts );

	$color = $article_side_block_atts['color'];
	$background_color = $article_side_block_atts['background_color'];
	$float = $article_side_block_atts['float'];
	return '<div class="content-article-media-side"  style="color:'.$color.'; background-color:'.$background_color.'; float:'.$float.';">';
}
add_shortcode( 'article_side_block', 'article_side_block_shortcode' );

function end_block_shortcode( $atts, $contents ){
	return '</div><!-- end article block -->';
}
add_shortcode( 'end_block', 'end_block_shortcode' );
 
function mutsun_event_shortcode () {
	$style = "<style> .mutsun_event { float:right; border:1px solid black; border-radius: 25px; min-width:300px; max-width:400px; padding:10px; margin: 6px; background-color:#ddd;}";
	$style .= " .mutsun_event_date { text-align:center; font-weight:bold;} ";
	$style .= ".mutsun_body_text {font-size:14px; color:#666;} ";
	$style .= ".mutsun_more_info {font-size:12px; color:fff;}</style>";
	$title = "<h4>Forum: Restoring Our Relations with Mother Earth</h4>";
	$date = "<div class='mutsun_event_date'>Thursday, April 14, 6:00 pm, Berkeley</div>";
	$body = "<div class='mutsun_body_text'>Join the Amah Mutsun Land Trust, Sempervirens Fund, and Bay Nature to learn more about the land trust and indigenous land stewardship. ";
	$body .= "With Valentin Lopez and Mary Ellen Hannibal. Thursday, April 14, 6:00 pm at the David Brower Center, 2150 Allston Way, Berkeley.</div>";
	$link = "<div class='mutsun_more_info'>More information at <a href='http://www.amahmutsun.org/land-trust'>www.amahmutsun.org/land-trust</a></div>";
	$html = "<div class='mutsun_event'>".$title.$date.$body.$link."</div>";
	return $style.$html;
}
add_shortcode( 'mutsun_event', 'mutsun_event_shortcode' );

function mutsun_tura_story_shortcode () {
	$color_1 = "#909090";
	$color_2 = "ffffff";
	$url = content_url() . '/WEB_MUSUN_VOCABULARY_MM/images/';
	$playButtonImage = $url."play-button.png";
	$pauseButtonImage = $url."pause-button.png";
	$stopButtonImage = $url."stop-button.png";

	$playButtonImageTag = '<img  src="'.$playButtonImage.'">';
	$pauseButtonImageTag = '<img src="'.$pauseButtonImage.'">';
	$stopButtonImageTag = '<img  src="'.$stopButtonImage.'">';

	$line = buildAudioPlayer("tura_story");
	$line .= "<div style='width:300px; height: 100px; margin-bottom:10px;'>";
		$line .= "<div id='audioLink_play' style='width:140px; height:75px; float:left; display:block'><a onclick='playstory()'>";
			$line .= "<div style='height:55px; display:block'>".$playButtonImageTag."</div>";
			$line .= "<div style='height:20px; display:block'>Play Audio</div></a>";
		$line .= "</div>";
		$line .= "<div id='audioLink_pause' style='width:140px; height:75px; float:left; display:none'><a onclick='pausestory()'>";
			$line .= "<div style='height:55px; display:block'>".$pauseButtonImageTag."</div>";
			$line .= "<div style='height:20px; display:block'>Pause Audio</div></a>";
		$line .= "</div>";
		$line .= "<div id='audioLink_stop' style='width:140px; height:75px; float:left;'><a onclick='restartstory()'>";
			$line .= "<div style='height:55px;'>".$stopButtonImageTag."</div>";
			$line .= "<div style='height:20px;'>Stop Audio</div></a>";
		$line .= "</div>";
	$line .= "</div>";
	$line .= "<p><div style='float:left;'>";
		$line .= "<span style='color:".$color_1.";'>There was an old widow woman with two sons. </span><br>";
		$line .= "<span style='color:".$color_2.";'>okse rootes wikic mukyukniS yuu uThin wak-tawrekma.   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>They didn’t have anything to eat.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>ekwena amman.   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>Every time these poor boys went to the river where other people were fishing, people would run them away. </span><br>";
		$line .= "<span style='color:".$color_2.";'>hiimi hiskan kocinokniSmak wattinis rummetka, aNNis amakma huynitis nuhu, amakma haahesis haysane. </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>So they went along looking for what other people had left behind.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>piinaway himmatis kocinokniSmak, himmanas witihte ammane.   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>They would find the thrown away fish bones and pull the nerve out of the backbone like a thread, and put these in a clamshell.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>haysa himmas huuyi TattYise, yuu haysa hiTros hurekse haccal TattYitkatum.  haysa uttus hurekse haSSantak, </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>They would go up and down the river doing this.  They would take this to their mother to eat.  </span><br>";
		$line .= "<span style='color:".$color_2.";'>yuu wattimpis-was aanantak, amSi haysa ammas-was.   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>One day when they came home they found that their mother had died.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>ara haysa hiwaanis rukkatka, haysa amma semmoSte.   </span><br<br>";

		$line .= "<span style='color:".$color_1.";'>They cried and cried.  They buried her.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>haysa warkatis.  piras-was haysa.   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>There was no one to care for them, and it seemed like they would die too.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>ekwena ama numan uTTasis kocinokniSmakse, ussi kuutis waate haysaya waate semmonis. </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>The younger brother said to his tawses riccas taknane, older brother, “We are living so sadly.  Let’s become like animals that fly in the sky.”   </span><br>";
		$line .= "<span style='color:".$color_2.";'>tawses riccas taknane, “makke Sollen TiiTi.  hiSSepuyuT makke hummusmak, numan Tawra Tarahtak.”   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>The older brother said “How do you think you can do that?”   </span><br>";
		$line .= "<span style='color:".$color_2.";'>taknan riccas “hinkasi holle-me-was?”  </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>The younger brother said “I can do it.  But you have to promise to do whatever I say to do.”   </span><br>";
		$line .= "<span style='color:".$color_2.";'>tawses riccas “holle-ka-was.  enohek, koc-ka howso-mes, nahay.”   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>It was like that every day.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>himah'a Tuuhis kaatYi. </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>But one day, the younger brother bathed himself, and he said “I will jump three times, and the third time, I will fly, and I will walk around in the sky.”   </span><br>";
		$line .= "<span style='color:".$color_2.";'>aru, tawses ereeSipus.  riccas-ak “culuna-ka kaphana, yuu kapnanwas, yetee-ka hummun, hintYe-ka Tarahtak.”     </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>He jumped three times, and on the third jump, he went thundering up into the sky.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>culus-ak kaphana, koc-ak kaphanwas culus, wattinis-ak Tarahtak, Turas-ak Tarahtak. </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>He said to his older brother “You do the same.”   </span><br>";
		$line .= "<span style='color:".$color_2.";'>tawses riccas wak-taknane “hiSSey menya kaatYi.” </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>His brother jumped, and on the third jump, he also went up into the sky, thundering but not as fast or loudly as his brother.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>wak-taknan culus, yuu koc-ak kaphanwas culus, wak ya wattinis Tarahtak, Turas wak ya Tarahtak.  enohek taknan hemtsos, yuu heeleSi wattinis.   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>He joined his brother up there.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>haysa himmemus Tarahtak.   </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>So it is that the younger brother thunders more violently and the older brother more softly.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>ussi tawses hiTeepu Tura, yuu taknan hemtso Tura. </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>Remember the fishermen who would not give them food?   </span><br>";
		$line .= "<span style='color:".$color_2.";'>moT-me hinwimi huynismak, numan ekwe hummis haysane ammane? </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>They thought the boys would be worthless.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>haysa pesyos kocinokniSmak ekwe miSSimak. </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>Now when it thundered, they clapped their hands and beg for forgiveness.   </span><br>";
		$line .= "<span style='color:".$color_2.";'>ney'a koc Turas, haysa hilsis, 'annam makkese!' </span><br><br>";

		$line .= "<span style='color:".$color_1.";'>When the Indian people hear the thunders, they say 'Listen, those are the brothers,' and one thunders more loudly and one more softly. </span><br>";
		$line .= "<span style='color:".$color_2.";'>koc mutsun amakma namti Turanmakse, ricca haysa 'namtiy, taknan yuu tawses,' yuu tawses hiTeepu Tura, yuu taknan hemtso Tura. </span><br><br>";
	$line .= "</div></p>";

	$script ="<script> function playstory(  ) { 
		var audioPlayer=document.getElementById('tura_story');
		var audioLink_play = document.getElementById('audioLink_play');
		var audioLink_pause = document.getElementById('audioLink_pause');
		
		audioLink_play.style.display = \"none\";
		audioLink_pause.style.display = \"block\";

		audioPlayer.volume = 1;
		audioPlayer.play(); 
	}</script>";

	$script .="<script> function pausestory(  ) { 
		var audioPlayer=document.getElementById('tura_story');
		var audioLink_play = document.getElementById('audioLink_play');
		var audioLink_pause = document.getElementById('audioLink_pause');
		
		audioLink_play.style.display = \"block\";
		audioLink_pause.style.display = \"none\";		

		audioPlayer.pause(); 
	}</script>";
	
	$script .="<script> function restartstory(  ) { 
		var audioPlayer=document.getElementById('tura_story');
		var audioLink_play = document.getElementById('audioLink_play');
		var audioLink_pause = document.getElementById('audioLink_pause');
		
		audioLink_play.style.display = \"block\";
		audioLink_pause.style.display = \"none\";
		audioPlayer.currentTime = 0; 
		audioPlayer.volume = 1;
		audioPlayer.pause(); 

	}</script>";
	return $line . 	$script;
}
add_shortcode( 'mutsun_tura_story', 'mutsun_tura_story_shortcode' );

function mutsum_vocabulary_mm_widget_shortcode( $atts, $contents ){
	$classes = array();
	$classes[] = ".multimedia-player-shell { min-width:300px; max-width:300px; background-color: #feefce; margin:10px; padding:1em; float:left;} ";
	//$classes[] = ".multimedia-ui-shell {float:left;}";
	$classes[] = ".multimedia-player-title { color:#0f0f0f; text-align:center; }";
	$classes[] = ".mutsun-vocab-user-interface { min-width:300px; max-width:400px; background-color: #ffffff; color:#f0f0f0; float:left; margin:0 auto; }";
	$classes[] = ".multimedia-player-dispay { width:100%; height:295px; background-color:#00ff00;}";
	$classes[] = ".multimedia-display-answer-field{ width:98%; height:55px; text-align:center; background-color:#dbd69c; padding:1%;}";
	$classes[] = ".multimedia-display-image-field{ width:100%; height:240px; text-align:center; background-color:#939393; transition:opacity 1s linear;}";
	$classes[] = ".multimedia-ui-button { width:94px; height:72px; border: 1px solid black; float:left; color:black; text-align:center; margin: 0 auto; padding:2px;}";
	$classes[] = ".instructions {color:#8b3a16;}";
	$classes[] = '.neon {color:#367941}';
	$classes[] = '.hidden-audio {display:none}';

	$wordList = array();
	$wordList[] = ["english"=>"Condor",		"mutsun"=>"\'wasaka\'", 	"audio"=>"wasaka.mp3",		"image"=>"condor_l.png" 			, "buttonImage"=>"condor.png"];
	$wordList[] = ["english"=>"Magpie",		"mutsun"=>"\'aTTaT\'", 		"audio"=>"aTTaT.mp3",		"image"=>"magpie_l.png" 			, "buttonImage"=>"magpie.png"];
	$wordList[] = ["english"=>"White Oak",	"mutsun"=>"\'arkeh\'", 		"audio"=>"arkeh.mp3",		"image"=>"white_oak_l.png" 			, "buttonImage"=>"white_oak.png"];
	$wordList[] = ["english"=>"Bear",		"mutsun"=>"\'ores\'", 		"audio"=>"ores.mp3",		"image"=>"Bear_l.png" 				, "buttonImage"=>"Bear.png"];
	$wordList[] = ["english"=>"Deer",		"mutsun"=>"\'tooTe\'", 		"audio"=>"tooTe.mp3",		"image"=>"deer_l.png" 				, "buttonImage"=>"deer.png"];
	$wordList[] = ["english"=>"Meadowlark",	"mutsun"=>"\'ciiritmin\'", 	"audio"=>"mutsun.mp3",		"image"=>"meadowlark_l.png" 		, "buttonImage"=>"meadowlark.png"];
	$wordList[] = ["english"=>"Sun",		"mutsun"=>"\'hismen\'", 	"audio"=>"hismen.mp3",		"image"=>"sun_l.png" 				, "buttonImage"=>"sun.png"];
	$wordList[] = ["english"=>"Moon",		"mutsun"=>"\'Tar\'", 		"audio"=>"Tar.mp3",			"image"=>"moon_l.png" 				, "buttonImage"=>"moon.png"];
	$wordList[] = ["english"=>"Stream",		"mutsun"=>"\'rumme\'", 		"audio"=>"rumme.mp3",		"image"=>"stream_l.png" 			, "buttonImage"=>"stream.png"]; // no audio for this one
	$wordList[] = ["english"=>"Water",		"mutsun"=>"\'sii\'", 		"audio"=>"sii.mp3",			"image"=>"water_l.png" 				, "buttonImage"=>"water.png"];
	$wordList[] = ["english"=>"Tule",		"mutsun"=>"\'rookos\'", 	"audio"=>"rookos.mp3",		"image"=>"tule_elk_l.png" 			, "buttonImage"=>"tule_elk.png"];
	$wordList[] = ["english"=>"Willow",		"mutsun"=>"\'paysu\'", 		"audio"=>"paysu.mp3",		"image"=>"willow_tree_l.png" 		, "buttonImage"=>"willow_tree.png"];
	//$wordList[] = ["english"=>"White Oak",	"mutsun"=>"\'arkeh\'", 		"audio"=>"arkeh.mp3",		"image"=>"white_oak_l.png" 			, "buttonImage"=>"white_oak.png"];
	//$wordList[] = ["english"=>"Oriole",		"mutsun"=>"\'soksokyan\'", 	"audio"=>"soksokyan.mp3",	"image"=>"placeholder_l.png" 		, "buttonImage"=>"placeholder.png"];

	$initialWord = $wordList[3];

	$css = "<style>";
	for ($i=0; $i < count($classes); $i++ ) {
		$css .= $classes[$i];
	}

	$css .= "</style>";
	$url = content_url() . '/WEB_MUSUN_VOCABULARY_MM/images/Bear_l.png';
	//$url = content_url() . '/WEB_MUSUN_VOCABULARY_MM/images/placeholder_l.png';

	$script ="<script> var audioPlayer = document.getElementById('ores'); function doIt( eWord, mWord , id , image ) { 
		var mainImage=document.getElementById('mainImage'); 
		audioPlayer=document.getElementById(id); var e=document.getElementById('wordTranslation'); 
		var text = 'The Mutsun word for <span class=\"neon\">';

		text +=  eWord;
		text += '</span>';
		text += ' is <span class=\"neon\">' + mWord + '</span>';
		e.innerHTML = text;
		//mainImage.style.opacity = 0;
		mainImage.src=image;
		audioPlayer.play(); 
		//mainImage.style.opacity = 100;
		//alert(id + audioPlayer); 
	}</script><script> function shout() { audioPlayer.play(); }</script>";

	$playerTitle = 'Mutsun Nature Words';
	$instructionText = 'Click on one of the images in the grid to hear the Mutsun language translation.';
	$openShell = '<div class="multimedia-player-shell">';
	$uiButtons = mutsun_ui_buttons( $wordList );
	$userInterface = '<div class="mutsun-vocab-user-interface">'.$uiButtons.'</div>';
	$placeHolderText = 'The Mutsun word for <span class="neon">Bear</span> is <span class="neon"> ores</span>';
	$displayImageAnswerField = '<div id="wordTranslation" class="multimedia-display-answer-field">'.$placeHolderText.'</div>';
	$placeholderImage = '<img id="mainImage" src="'.$url.'" height="10px" style="height:240px; width:100%">';
	$displayImageField = '<div  class="multimedia-display-image-field"><a onclick="shout()">'.$placeholderImage.'</a></div>';
	$display = '<div class="multimedia-player-dispay">'.$displayImageField.$displayImageAnswerField.'</div>';

	$title = '<div class="multimedia-player-title">'.$playerTitle.'</div>';
	$instructions = '<div class="instructions">'.$instructionText.'</div>';
	$closeShell = '</div>';
	//$url = content_url() . '/WEB_MUSUN_VOCABULARY_MM/audio/';

	//$fileName = $url.'aTTaT.mp3';
	//$audioPlayer = '<audio id="aTTaT"><source src="'.$fileName.'" type="audio/mpeg"><a href="'.$fileName.'">Click Here To Hear Audio</a></audio>';
 	$audioPlayers = buildAudioPlayer('aTTaT'); 		// Magpie
 	$audioPlayers .= buildAudioPlayer('ciiritmin');	// Meadowlark
 	$audioPlayers .= buildAudioPlayer('hismen');	// Sun
 	$audioPlayers .= buildAudioPlayer('ores'); 		// Bear
 	$audioPlayers .= buildAudioPlayer('rookos');	// Tule
 	$audioPlayers .= buildAudioPlayer('sii');		// Water
 	$audioPlayers .= buildAudioPlayer('Tar'); 		// Moon
 	$audioPlayers .= buildAudioPlayer('rumme');		// Stream
 	$audioPlayers .= buildAudioPlayer('tooTe'); 	// Deer
 	$audioPlayers .= buildAudioPlayer('wasaka');	// Condor
 	$audioPlayers .= buildAudioPlayer('paysu');		// Willow
 	$audioPlayers .= buildAudioPlayer('arkeh'); 	// White Oak
 	//$audioPlayers .= buildAudioPlayer('aTTaT');
 	//$audioPlayers .= buildAudioPlayer('aTTaT');


	$html = $openShell . $title.$instructions . $display  .$userInterface. $audioPlayers.$closeShell;
	return $css.$html.$script;
}
add_shortcode( 'mutsum_vocabulary_mm_widget', 'mutsum_vocabulary_mm_widget_shortcode' );

function mutsun_ui_buttons ( $wordList ) {
	$url = content_url() . '/WEB_MUSUN_VOCABULARY_MM/images/';
	$speakerImage = $url."speakerIcon.png";
	$speakerImageTag = '<img id="mainImage" src="'.$speakerImage.'>';

	$buttons = '';
	$nButtons = count($wordList);
	for ($i =0; $i < $nButtons; $i++) {
		$translation = $wordList[$i];
		//$words = "Mutsun : ". $translation['mutsun'] .", English : ". $translation['english'];
		$words = "The Mutsun word for <span >". $translation['english'] ."</span> is ". $translation['mutsun'];
		$eWord = $translation['english'];
		$mWord = $translation['mutsun'];

		$id = str_replace("\'","",$translation['mutsun']);
		$image = $translation["image"];
		$onClickFunction = "doIt('".$eWord."','".$mWord."','".$id."','".$url.$image."')";
		//$buttons .= '<a onclick="'.$onClickFunction.'"><div class="multimedia-ui-button">'.$translation["english"].'</div></a>';
		$buttonImage = $translation['buttonImage']; //str_replace(" ","_",$translation['english']);
		//$buttons .= '<a onclick="'.$onClickFunction.'"><div class="multimedia-ui-button"><img src="'.$url.$translation['english'].'.png"></div></a>';
		$buttons .= '<a onclick="'.$onClickFunction.'"><div class="multimedia-ui-button"><img src="'.$url.$buttonImage.'"></div></a>';
	}
	return $buttons;
}

function buildAudioPlayer ( $audioToPlay ) {
	$url = content_url() . '/WEB_MUSUN_VOCABULARY_MM/audio/';
	$fileName = $url.$audioToPlay.'.mp3';
	$audioPlayer = '<audio  class="hidden-audio" id="'.$audioToPlay.'"><source src="'.$fileName.'" type="audio/mpeg"><a href="'.$fileName.'">Click Here To Hear Audio</a></audio>';
	return $audioPlayer;

}

/**
 * Register custom taxonomies
 */
function create_bn_taxonomies() {
	// Add new taxonomy "picks", "features", "habitats"
	register_taxonomy('picks', array('post', 'page', 'article', 'trail', 'tribe_events', 'video', 'park'),
	array(
		'hierarchical' => true,
		'label' => 'Bay Nature Picks',
		'query_var' => true,
		'rewrite' => true,
		'show_ui' => true
	));

	register_taxonomy('features', null,
	array(
		'hierarchical' => true,
		'label' => 'Features',
		'query_var' => true,
		'rewrite' => true
	));

	register_taxonomy('habitats', null,
	array(
		'hierarchical' => true,
		'label' => 'Habitats',
		'query_var' => true,
		'rewrite' => true
	));
}
add_action( 'init', 'create_bn_taxonomies', 0 );




/*********************************************************************************
* newsletter shortcodes: general, sidebar, footer and article
*
**********************************************************************************/
// general - used in the header
function subscribe_shortcode( ){
	$html = '<div class="textwidget">';
		$html .= newsletter_signup_form();
	$html .= '</div>';
	
	return $html;
}
add_shortcode( 'subscribe', 'subscribe_shortcode' );

// sidebar
function subscribe_sidebar_shortcode( ){
	$html = '<div class="textwidget">';
		$html .= side_bar_newsletter_signup_form();
	$html .= '</div>';
	
	return $html;
}
add_shortcode( 'subscribe_sidebar', 'subscribe_sidebar_shortcode' );


// article
function subscribe_article_shortcode () {
	/*
	$html = '<div class="textwidget">';
		$html .= 'Bay Nature’s email newsletter delivers local nature stories, hikes, and events to your inbox each week. Sign up today:<br>';
		$html .= article_newsletter_signup_form();
	$html .='</div>';
	*/
	$html ='<style>
.cta-box-responsive{
overflow:hidden;
padding:6.25%;
position:relative;
height:auto;
margin-left:10%;
margin-right:10%;
margin-bottom:4%;
background-color: #000000;
color:white;
border-style: solid;
border-color:black;
text-align: center;
}
.cta-link-color {
color: #faa61a;
}
.cta-box-image {
display: block;
padding-top:10px;
  margin-left: auto;
  margin-right: auto;
  width: 75px;
}
</style>
<div class="cta-box-responsive">
Bay Nature’s email newsletter delivers local nature stories, hikes, and events to your inbox each week. <br><a class="cta-link-color subscribe-cta" href="/sign-up-for-connections/">Sign up today!</a>
<div class="cta-box-image"><img src="https://baynature.org/wp-content/uploads/2023/03/Heron-Silhouette-150px-white.png"></div>
</div>';
	return $html;
}
add_shortcode( 'subscribe_article', 'subscribe_article_shortcode' );


// footer
function subscribe_footer_shortcode () {
	$html = ''; //'<div class="textwidget">';
		$html .= footer_newsletter_signup_form();
	$html .= ''; //'</div>';
	
	return $html;
}
add_shortcode( 'subscribe_footer' , 'subscribe_footer_shortcode' );

// NEW Newsletter Signup Forms without Tracking. All GA tags now managed via Google Tag Mananger

function newsletter_signup_form () {
	return "<a href='/sign-up-for-connections/' class='header_newsletter' 
		style='display:inline-block; margin:0; padding:1.3rem 1.6rem; min-width:11rem; border-radidus:0; background-color:#a26300!important;color: #fff!important;
	 	text-align: center;text-decoration: none;text-transform: uppercase;letter-spacing: .05rem;font-weight: 500;font-size: 1.3rem;font-family: ff-tisa-sans-web-pro,sans-serif;
	 	-webkit-transition: all .2s ease; transition: all .2s ease; -webkit-appearance: none; -moz-appearance: none; appearance: none;' width='60px'>Get Our Newsletter</a>";
}
function side_bar_newsletter_signup_form () {
	return "<a href='/sign-up-for-connections/' class='sidebar_newsletter'
		style='display:inline-block; margin:0; padding:1.3rem 1.6rem; min-width:11rem; border-radidus:0; background-color:#a26300!important;color: #fff!important;
	 	text-align: center;text-decoration: none;text-transform: uppercase;letter-spacing: .05rem;font-weight: 500;font-size: 1.3rem;font-family: ff-tisa-sans-web-pro,sans-serif;
	 	-webkit-transition: all .2s ease; transition: all .2s ease; -webkit-appearance: none; -moz-appearance: none; appearance: none;'
		width='60px'>Sign Up!</a>";
}
function article_newsletter_signup_form () {
	return "<a href='/sign-up-for-connections/' class='article_newsletter' width='60px'>Sign Up!</a>";
}

function footer_newsletter_signup_form () {
	//subscribe_footer
	return "<a href='/sign-up-for-connections/' class='footer_newsletter'
	 	style='display:inline-block; margin:0; padding:1.3rem 1.6rem; min-width:11rem; border-radidus:0; background-color:#a26300!important;color: #fff!important;
	 	text-align: center;text-decoration: none;text-transform: uppercase;letter-spacing: .05rem;font-weight: 500;font-size: 1.3rem;font-family: ff-tisa-sans-web-pro,sans-serif;
	 	-webkit-transition: all .2s ease; transition: all .2s ease; -webkit-appearance: none; -moz-appearance: none; appearance: none;'
	 	width='60px'>Sign Up!</a>";
}

function get_thumbnail_src($id, $type = 'large') {
	$image = wp_get_attachment_image_src(get_post_thumbnail_id($id), $type);
	return $image[0];
}
function get_thumb_url($src, $w = '', $h = '', $zc = 1) {
	return get_bloginfo('template_directory') . '/timthumb.php?src=' . $src . ( ($w) ? '&amp;w=' . $w : '') . ( ($h) ? '&amp;h=' . $h : '') . '&amp;zc=' . $zc;
}

/**************************************************************************************************
*
* Song Sparrow Short Code :
*
***************************************************************************************************/
function songSparrow_shortcode( $atts, $contents ){
	$contentDir = content_url();  
	$iFrame .= '<div style="margin: 20px auto;">';
	$iFrame .= '<iframe style="margin: 0 auto; display: block;" src="'.$contentDir.'/WEB_SONG_SPARROW/index.php" width="650" height="970" frameborder="0"></iframe></div>';
	return $iFrame;

}
add_shortcode( 'song_sparrow', 'songSparrow_shortcode' );

/**************************************************************************************************
*
* Song Sparrow Compare Short Code : 
*
***************************************************************************************************/
function compareSongSparrow_shortcode( $atts, $contents ){
	$contentDir = content_url();  
	$iFrame = '<div style="margin: 20px auto;"><iframe style="margin: 0 auto; display: block;" src="'.$contentDir.'/WEB_COMPARE_SONG/index.php" width="680" height="340" frameborder="0"></iframe></div>';
	return $iFrame;

}
add_shortcode( 'compare_song_sparrow', 'compareSongSparrow_shortcode' );

/**************************************************************************************************
*
* Song Sparrow Compare Stack Versio Short Code : 
*
***************************************************************************************************/
function compareStackSongSparrow_shortcode( $atts, $contents ){
	$contentDir = content_url();  
	$iFrame = '<div style="margin: 20px auto;"><iframe style="margin: 0 auto; display: block;" src="'.$contentDir.'/WEB_COMPARE_SONG/compare_stack.php" width="680" height="360" frameborder="0"></iframe></div>';
	return $iFrame;

}
add_shortcode( 'compare_stack_song_sparrow', 'compareStackSongSparrow_shortcode' );


//Giving Editors Access to Gravity Forms
function add_grav_forms(){
	$role = get_role('editor');
	$role->add_cap('gform_full_access');
}
add_action('admin_init','add_grav_forms');


function bn_print_r ($theObj) {
	echo("<pre>");
	print_r($theObj);
	echo("</pre>");
}

/************************************************************************************************
*
* Google Anaylitcs Event JS
*************************************************************************************************/
function wpb_adding_scripts() {
 	wp_register_script('bn_ga_events', get_template_directory_uri().'/bn_ga_events.js', array('jquery'),'1.1', true);
	wp_enqueue_script('bn_ga_events');
}
  
//add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts' ); 

/************************************************************************************************
*
* Display Category Name assocaited with Post
*************************************************************************************************/
function bn_display_category_name () {
	//bn_print_r( get_the_category(get_the_ID()) ); 
 	$cat_array =  get_the_category(get_the_ID());
 	$term_obj = $cat_array[0];
 	//bn_print_r($term_obj);
 	$main_cat_name = $term_obj->name;
 	$html = '<p style="color:#afafaf; font-size:2.5rem; margin-bottom:30px; font-family:ff-tisa-sans-web-pro,sans-serif">'.$main_cat_name.'</p>';
 	echo $html;
}  

/** Co-authors in RSS and other feeds

/wp-includes/feed-rss2.php uses the_author(), so we selectively filter the_author value
*/
function db_coauthors_in_rss( $the_author ) {
	if ( !is_feed() || !function_exists( 'coauthors') ) {
		return $the_author;
	}
	else {
		return coauthors( null, null, null, null, false );
	}
}
add_filter( 'the_author', 'db_coauthors_in_rss');

add_filter('xmlrpc_methods', function ( $methods ) {
	unset($methods['pingback.ping']);
	return $methods;
});

// [open_sidebar]
function open_sidebar_shortcode( $atts ){

	$sidebar_colors = shortcode_atts( array(
        'color' => '#EEEEEE',
        'background_color' => '#0b505c',
    ), $atts );

	$html= '<span style="width: 380px; background-color: #0b505c; float: right; color: #EEEEEE; font-family: ff-tisa-sans-web-pro,sans-serif; font-weight: 100; padding: 1.5em; margin-left: 2em; margin-bottom: 1em;">';
	$html= '<span style="width: 380px; background-color: '.$sidebar_colors['background_color'].'; float: right; color: '.$sidebar_colors['color'].'; font-family: ff-tisa-sans-web-pro,sans-serif; font-weight: 100; padding: 1.5em; margin-left: 2em; margin-bottom: 1em;">';
	return $html;
}
add_shortcode( 'open_sidebar', 'open_sidebar_shortcode' );
// [close_sidebar]
function close_sidebar_shortcode () {
	$html = '</span>';
	return $html;
}
add_shortcode( 'close_sidebar', 'close_sidebar_shortcode' );


/*********************************************************
*
*
*/
function currentIssueRenderPosts( $key  ) {
//$key = "v23n2"; //$product->get_sku();
global $wpdb;
global $post;
$querystr = "
SELECT wposts.*
FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

WHERE (wposts.post_status = 'publish')
AND wposts.ID = wpostmeta.post_id
AND wpostmeta.meta_key = 'issue_key'
AND wpostmeta.meta_value = '$key'
AND post_type = 'article'
ORDER BY wpostmeta.meta_value DESC
";
render_current_issue_content ( $querystr , true , $key  );
//bn_print_r( $querystr );
//future
$querystr = "
SELECT wposts.*
FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta

WHERE (wposts.post_status = 'future')
AND wposts.ID = wpostmeta.post_id
AND wpostmeta.meta_key = 'issue_key'
AND wpostmeta.meta_value = '$key'
AND post_type = 'article'
ORDER BY wpostmeta.meta_value DESC
";
render_current_issue_content ( $querystr , false , $key  );
//bn_print_r( $querystr );
}


function render_current_issue_content ( $querystr , $showTitle , $key ) {
global $wpdb;
global $post;
?>
<style type="text/css">
.issue-content { grid-template-columns: repeat(2,1fr); grid-gap:40px 30px; display: grid;}
/* fixes */
.featured-date-grid { margin-right: 10px; }
.featured-grid p small { font-size: 12px; line-height: 12px; color: #afafaf; }
.featured-title-grid h4 { margin: 0px; font-style: normal; }
.featured-title-grid { font-style: italic; }
.featured-author-grid { margin: 0 0 8px 0; color: #afafaf;}
.featured-author-grid .author { color: #7a7a7a;}
.featured-image-grid { margin: 0; padding: 0 0 1em 0; }
.section-three { padding: 0; }
.section-three-area-one { overflow: scroll; }

@media (min-width: 650px) {
  .woocommerce.single-product main.container .issue-content {
    -ms-grid-columns:(1fr)[3];
    grid-template-columns: repeat(2,1fr);
    grid-gap: 40px 30px
  }
}
</style>
<?php
$pageposts = $wpdb->get_results($querystr, OBJECT);
$count = 0;
$percent_similarity = 0;
$threshold = 80;
//echo "<img width='640' height='828' src='".$cover."'>";
//echo "<div class='product'><p>".$description."</p></div>";
//echo "<h3>".$title."</h3>";
if ($pageposts): ?>
<?php if ($showTitle) { ?>
<h2 class="issue-content-title"><?php echo $issueTitle; ?> Issue Content</h2>
<?php } ?>
<div class="issue-content">
 <?php global $post; ?>
 <?php foreach ($pageposts as $post): ?>
   <?php setup_postdata($post);
$subtitle = get_field('subtitle');
$excerpt = get_the_excerpt();
similar_text($excerpt, $subtitle, $percent_similarity);
   $is_draft = get_post_status() == "future" ? true:false;
   //bn_print_r(get_post_status());
   $article = $is_draft ? (get_the_title()):'<a href="'.get_permalink().'">'.get_the_title().'</a>';
$count++;
echo '<div class="featured-grid" style="' . ($count % 3 == 0 ? 'clear: right':('margin-right: 35px;'.($count % 3 == 1 ? ' clear: left;':''))) . '">';
if ( has_post_thumbnail() && $is_draft === false) {  
   echo '
  <div class="featured-image-grid">
 <a href="'.get_permalink().'"><img src="' . get_the_post_thumbnail_url($post->ID) . '" alt="" /></a>
  </div>
 
   ';
  }else {
  echo '<div class="featured-image-grid">
 <img src="' . get_the_post_thumbnail_url($post->ID) . '" alt="" />
  </div>';
  }
echo '<div class="featured-title-grid"><h4>' . $article . '</h4></div>';
echo '<div class="featured-title-grid">'.($percent_similarity >= $threshold ? '':$subtitle).'</div>';
echo '<div class="featured-author-grid"><small>by ';
echo '<span class="author">';
coauthors_posts_links();
echo '</span> | <span class="date">'.get_the_date().'</span></small></div>';
 echo '</div>';
 endforeach; ?>
 </div> <!-- issue content -->
<?php endif; ?>
<?php wp_reset_postdata();
}

/*
called by the issue archive template -
*/
function render_issue_archive_issues () {
	?>
<style type="text/css">
.issue-content { grid-template-columns: repeat(3,1fr); grid-gap:40px 30px; display: grid;}
/* fixes */
.featured-date-grid { margin-right: 10px; }
.featured-grid p small { font-size: 10px; line-height: 12px; color: #afafaf; }
.featured-title-grid h4 { margin: 0px; font-style: normal; }
.featured-title-grid { font-style: italic; }
.featured-author-grid { margin: 0 0 8px 0; color: #afafaf;}
.featured-author-grid .author { color: #7a7a7a;}
.featured-image-grid { margin: 0; padding: 0 0 1em 0; }
.section-three { padding: 0; }
.section-three-area-one { overflow: scroll; }

@media (min-width: 650px) {
  .woocommerce.single-product main.container .issue-content {
    -ms-grid-columns:(1fr)[3];
    grid-template-columns: repeat(2,1fr);
    grid-gap: 40px 30px
  }
}
</style>
<div class="issue-content">
<?php
global $post;
global $wp_query;
//     'no_found_rows'  => true, // no pagination necessary so improve efficiency of loop
$paged = 1;
$child_pages = new WP_Query( array(
    'post_type'      => 'page', // set the post type to page
    'posts_per_page' => 200, // number of posts (pages) to show
    'post_parent'    => 222465, // enter the post ID of the parent page
    'paged' => $paged,
    'orderby'		=> 'menu_order',	
    'order' => 'DESC',
) );
$count = 0;
if ( $child_pages->have_posts() ) : while ( $child_pages->have_posts() ) : $child_pages->the_post();
    // Do whatever you want to do for every page. the_title(), the_permalink(), etc...
    $count++;
	echo '<div class="featured-grid" style="max-width: 190px;  ' . ($count % 3 == 0 ? 'clear: right':('margin-right: 35px;'.($count % 3 == 1 ? ' clear: left;':''))) . '">';
	echo ('<a href="'.get_permalink().'">');
	$season_year = the_title();
	echo $season_year;
	//echo ( substr( the_title(),10 ) );
	//$issueKey = get_field('current_issue_key');
	?>
	<div class="featured-image-grid"><img  src=
	<?php 
	echo (the_post_thumbnail()); 
	echo '</a><br>';
	?>
	</div>
	</div>
	<?php

	//echo (( the_post_thumbnail() ) . "<br>");
endwhile; endif;  
/*
if (function_exists('the_posts_pagination')) {
	echo (' pagination function exists - call it ');
	bn_print_r($child_pages->max_num_pages);
	the_posts_pagination(array(
	'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
    'total'        => $child_pages->max_num_pages,
    'current'      => max( 1, get_query_var( 'paged' ) ),
    'mid_size'  => 2,
    'prev_text' => __( 'Back', 'textdomain' ),
    'next_text' => __( 'Onward', 'textdomain' ),
    'format'       => '?paged=%#%',
    'show_all'     => false,
    'type'         => 'plain',
    'end_size'     => 2,
    'mid_size'     => 1,
    'prev_next'    => true,
    'prev_text'    => sprintf( '<i></i> %1$s',
            apply_filters( 'my_pagination_page_numbers_previous_text',
            __( 'Newer Posts', 'text-domain' ) )
        ),
    'next_text'    => sprintf( '%1$s <i></i>',
            apply_filters( 'my_pagination_page_numbers_next_text',
            __( 'Older Posts', 'text-domain' ) )
        ),
    'add_args'     => false,
    'add_fragment' => '',

    // Custom arguments not part of WP core:
    'show_page_position' => false, // Optionally allows the "Page X of XX" HTML to be displayed.
));
	//the_post_navigation();
}
else {
	echo('pagination function does not exist');
}
*/
?>
</div>
<?php
wp_reset_postdata();
}
