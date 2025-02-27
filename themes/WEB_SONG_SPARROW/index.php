<?php 
/********************************************************************
* Song Sparrow Interactive
* Dec 2015 : 
* Alison Hawkes / Laurence Tietz / BayNature
*********************************************************************/
$historicMap = "BayNatureSparrowMapMM_Historic_v7.png";
$contemporaryMap = "BayNatureSparrowMapMM_Contemorary_v7.png";
$menuBar_1 = "tab_historic_selected.png";
$menuBar_2 = "tab_contemporary_selected.png";
$landEndAnimation = "landsEndGif_v2.gif";
$batteryEastAnimation = "batteryEastGif_v2.gif";
$lakeMercedAnimation = "lakeMercedGif_v3.gif";

$c1Audio = "GRM_S.mp3";
$c2Image = "LakeMercedPair_v6.png";
$c2AAudio = "s_oyr_14c.mp3";
$c2BAudio = "s_wbr_14h.mp3";
$c3Image = "Battery-East-female_v4.png";
$c4Image = "MS_WR-LODU_v4.png";
$h1Audio = "SFdialect1969.mp3";
$h2Audio = "WCSP1969Presidio.mp3";
$h3Audio = "WCSP1969LakeMerced.mp3";

$c4Audio = "LODU_MS-WRc_14l_RS.mp3";
$title = "San Francisco Dialects, Then and Now";
$captionStr_C1 = "Each dot represents a territory where an individual male was recorded. Today white-crowned sparrows are found primarily in parks. Under the <span class='boldText'>'Historic Dialects'</span> tab, click the 'play' buttons to hear the different sparrow dialects that once inhabited San Francisco. Under the <span class='boldText'>'Contemporary Dialect'</span> tab, meet some of the individual birds by clicking on the pulsing circles.";
$captionStr_C2 = "<span class='boldText'>Lake Merced:</span>On top of a penguin statue sits S/WVR. Neighbor S/OYR perches in a nearby tree. And all day long these dueling males squabble at each other in sparrow-speak. Territories around the lake are small given the meager habitat and to hang on to what they have these bad boys have to be fairly aggressive.";
$captionStr_C3 = "<span class='boldText'>Battery East:</span>In the shadow of the Golden Gate Bridge, GRM/S and his mate are frequently spotted with mouthfuls of bugs. The digs may be noisy but this nurturing couple is clearly dedicated to their offspring. Papa sparrow even sings with a mouthful of food! ";
$captionStr_C4 = "<span class='boldText'>Lobos Dunes:</span>The restored dunes above Baker Beach must be one of the nicest of habitats in the city. MS/WR gets a bird's eye view of the entire valley leading to the shoreline. But he's a tough urban guy who no doubt has seen a thing or two from his perch next to a parking lot, and he is clearly miffed at being caught and banded for research purposes. ";

$title_C2 = "<span class='boldText'>Contemporary San Francisco dialect: Lake Merced birds</span>";
$title_C3 = "<span class='boldText'>Contemporary San Francisco dialect: Battery East bird</span>";
$title_C4 = "<span class='boldText'>Contemporary San Francisco dialect: Lobos Dunes bird</span>";


?>
<html>
<head></head>
<body onload="init();">

<!-- CSS Styles -->
<style>

    .popup_c1 {
        position:absolute;
        left:250px;
        top:660;
        width:50%;
        height:60px;
    }

    .popup_c2 {
        position:absolute;
        left:62px;
        top:250;
        width:500px;
        height:390px;
    }

    .audioPlayer_c2A {
        position:absolute;
        left:50px;
        top:270;
        width:50px;
        height:30px;
    }

    .audioPlayer_c2B {
        position:absolute;
        left:340px;
        top:270;
        width:50px;
        height:30px;
    }

    .popup_c3 {
        position:absolute;
        left:62px;
        top:250px;
        width:500px;
        height:485px;
    }

    .audioPlayer_c3 {
        position:absolute;
        left:150px;
        top:380;
        width:250px;
        height:30px;
    }

    .popup_c4 {
        position:absolute;
        left:62px;
        top:150px;
        width:500px;
        height:600px;
    }
    
    .audioPlayer_c4 {
        position:absolute;
        left:130px;
        top:470;
        width:250px;
        height:30px;
    }
    
    .caption_c2 {
        text-align:left;
        margin:10px;
        position:absolute;
        left:0px;
        top:300px;
        font-family: sans-serif;
        font-size:12px;
        color:#607384;
        width:490px;
    }
    
    .caption_c3 {
        text-align:left;
        margin:10px;
        position:absolute;
        left:0px;
        top:413px;
        font-family: sans-serif;
        font-size:12px;
        color:#607384;
        width:490px;
    }

    .caption_c4 {
        text-align:left;
        margin:12px;
        position:absolute;
        left:0px;
        top:490px;
        font-family: sans-serif;
        font-size:12px;
        color:#607384;
    }

    .title {
        text-align:left;
        margin:0px;
        font-family: sans-serif;
        font-size:16px;
        color:#607384;
    }
    .title_c3 {
        text-align:left;
        margin:10px;
        position:absolute;
        left:0px;
        top:0px;
        font-family: sans-serif;
        font-size:12px;
        color:#607384;
        width:360px;
    }
    .popup_h1 {
        position:absolute;
        left:370px;
        top:480px;
        width:50px;
        height:60px;
    }

    .popup_h2 {
        position:absolute;
        left:380px;
        top:330;
        width:50px;
        height:60px;
    }
    
    .popup_h3 {
        position:absolute;
        left:350px;
        top:780px;
        width:50px;
        height:60px;
    }
    
    .hidden {
        display:none;
    }
    .show {
        display.block;
    }
    .songSparrow {
        height:792px;
        width:612px;
    }
    .uiPanel {
        float:right;
        width:50%;
        height:10%;
        text-align : right;
        color:#00ff00;
        margin-left:10px;
        margin-right: 10px;
        
    }
    
    .mapPanel {
        float:left;
        width:100%;
        height:100%;
    }

a.textBody:visited  {	
	color:#000000;
	text-decoration:none;
}
a.textBody:hover  {	
	color:#000000;
	text-decoration:none;
        border:none;
}


a.textBody:link  {	
	color:#f0f0f0;
	text-decoration:underline;
        border:none;
}

.pageCaption {
    height:80px;
    width:100%;
    font-family: sans-serif;
    font-size:14px;
    color:#607384;
}
.credit {
    height:30px;
    width:100%;
    font-family: sans-serif;
    font-size:14px;
    color:#607384;
}
.gps_ring {
    border: 3px solid #fff;
    -webkit-border-radius: 100px;
    height: 30px;
    width: 30px;
    
    -webkit-animation: pulsate 1s ease-out;
    -webkit-animation-iteration-count: infinite; 
    -moz-animation: pulsate 1s ease-out;
    -moz-animation-iteration-count: infinite; 
    opacity: 0.0
}
@-webkit-keyframes pulsate {
    0% {-webkit-transform: scale(0.1, 0.1); opacity: 0.0;}
    50% {opacity: 1.0;}
    100% {-webkit-transform: scale(1.2, 1.2); opacity: 0.0;}
}

.lakeMercedHotSpot {
        position:absolute;
        left:165px;
        top:850px;
        width:100px;
        height:100px;
    }

.landsEndHotSpot {
        position:absolute;
        left:30px;
        top:310px;
        width:100px;
        height:100px;
    }

.batteryEastHotSpot {
        position:absolute;
        left:230px;
        top:150px;
        width:100px;
        height:100px;
}

.clickMe {
    cursor : pointer;
}

.boldText {
    font-weight: bold;
}

.was_audio {
    width: 200px;
    height: 30px;
}
.tab_left {
    width:306px;
    height:40px;
    position : absolute;
    left:10px;
    top:106px;
}
.tab_right {
    width:306px;
    height:40px;
    position : absolute;
    left:316;
    top:106px;
}

.cHotSpot_1 {
    width:250px;
    height:80px;
    position : absolute;
    left:230px;
    top:560px;
}

.c_lakeMercedHotSpot {
    position : absolute;

    left:150px;
    top:809px;
    width:100px;
    height:100px;
}

.c_batteryEastHotSpot {
    position : absolute;

    left:217px;
    top:139px;
    width:100px;
    height:100px;
}

.c_landsEndHotSpot {
    position : absolute;

    left:10px;
    top:291px;
    width:100px;
    height:70px;
}

.lakeMercedCloseBoxHotSpot {
    position : absolute;
    top:0px;
    left:460px;
    width:40px;
    height:30px;
}


.batteryEastCloseBoxHotSpot {
    position : absolute;
    top:0px;
    left:460px;
    width:40px;
    height:30px;
}


.landsEndCloseBoxHotSpot {
    position : absolute;
    top:0px;
    left:460px;
    width:40px;
    height:30px;
}

.lakeMercedAudioButtonLeftHotSpot {
    position : absolute;
    top:270px;
    left:10px;
    width:40px;
    height:30px;
}

.lakeMercedAudioButtonRightHotSpot {
    position : absolute;
    top:270px;
    left:290px;
    width:40px;
    height:30px;
    
}

.historicAudioHotSpot_1 {
    position : absolute;

    top:270px;
    left:360px;
    width:200px;
    height:60px;

}

.historicAudioHotSpot_2 {
    position : absolute;

    top:420px;
    left:380px;
    width:200px;
    height:60px;

}

.historicAudioHotSpot_3 {
    position : absolute;

    top:700px;
    left:360px;
    width:200px;
    height:60px;

}

.batteryEastAudioButtonHotSpot {
    position : absolute;
    top:375px;
    left:10px;
    width:40px;
    height:30px;
}

.landsEndAudioButtonHotSpot {
    position : absolute;
    top:470px;
    left:50px;
    width:40px;
    height:30px;
}

</style>

<!-- Map UI -->
<div class="title boldText"><?php echo($title); ?></div>
<div class="pageCaption">
    <?php echo ($captionStr_C1); ?>
</div>
<div class="songSparrow">
    <div class="tab clickMe"><img src = "./images/<?php echo($menuBar_1); ?>" id="menu"></div>
    <div class="tab_left clickMe" id="historicTab"></div><div class="tab_right clickMe" id="contemporaryTab"></div>
    <div class="mapPanel">
        <div id="contemorary" class="hidden">
            <img src="./images/<?php echo($contemporaryMap); ?>">
            <!-- UI Hotspots -->
            <div class="cHotSpot_1 clickMe" id="c_hotspot_1"></div> 

            <?php /* <div class="lakeMercedHotSpot gps_ring hidden clickMe" id="cHotspot_1"></div> */ ?>
            <div class="c_lakeMercedHotSpot clickMe" id="cHotspotLakeMerced"><img src="./images/<?php echo($lakeMercedAnimation); ?>"></div>

            <?php /* <div class="batteryEastHotSpot gps_ring hidden clickMe" id="cHotspot_3"></div> */ ?>
            <div class="c_batteryEastHotSpot clickMe" id="cHotspotBatterEast"><img src="./images/<?php echo($batteryEastAnimation); ?>"></div>

            <?php /* <div class="landsEndHotSpot gps_ring hidden clickMe" id="cHotspot_2"></div> */ ?>
            <div class="c_landsEndHotSpot clickMe" id="cHotspotLandsEnd"><img src="./images/<?php echo($landEndAnimation); ?>"></div>
        </div>
        <div id="historic" class="show">
            <img src="./images/<?php echo($historicMap); ?>" >  
            <!-- ui hotspots -->
            <div class="historicAudioHotSpot_1 clickMe" id="historicAudio_1"></div>  
            <div class="historicAudioHotSpot_2 clickMe" id="historicAudio_2"></div>
            <div class="historicAudioHotSpot_3 clickMe" id="historicAudio_3"></div>
        </div>
    </div> 
    <div class="credit">
        <p>Illustration by <a href="http://www.mroycartography.com" target="_blank">Molly Roy.</a></p>
    </div>  
</div>



<!-- POP UP Contemporary Content -->
<div class="hidden popup_c1" id="audioPopUp_C1">
    <audio  id="audioC1">
        <source src="./audio/<?php echo($c1Audio); ?>" type="audio/mpeg">
        <a href="./audio/<?php echo($c1Audio); ?>">Click Here To Hear Audio</a>
    </audio>
</div>

<div class="hidden popup_c2" id="photoPopUp_C2">
    <img src = "./images/<?php echo($c2Image); ?>">
        <div class="lakeMercedCloseBoxHotSpot clickMe" id="lakeMercedCloseBox"></div>
        <div class="lakeMercedAudioButtonLeftHotSpot clickMe" id="lakeMercedAudioButtonLeft"></div>
        <div class="lakeMercedAudioButtonRightHotSpot clickMe" id="lakeMercedAudioButtonRight"></div>
        <div class="caption_c2"><?php echo($captionStr_C2); ?></div>
        <div class="title_c3"><?php echo($title_C2); ?></div>
        <div class="audioPlayer_c2A">
            <audio id="lmAudioLeft">
                <source src="./audio/<?php echo($c2AAudio); ?>" type="audio/mpeg">
                <a href="./audio/<?php echo($c2AAudio); ?>">Click Here To Hear Audio</a>
            </audio>
        </div>
        <div class="audioPlayer_c2B">
            <audio id="lmAudioRight">
                <source src="./audio/<?php echo($c2BAudio); ?>" type="audio/mpeg">
                <a href="./audio/<?php echo($c2BAudio); ?>">Click Here To Hear Audio</a>
            </audio>
        </div>
</div>
<!-- Battery East Popup -->
<div class="hidden popup_c3" id="photoPopUp_C3">
    <img src = "./images/<?php echo($c3Image); ?>" >
        <div class="batteryEastCloseBoxHotSpot clickMe" id="batterEastCloseBox"></div>
        <div class="batteryEastAudioButtonHotSpot clickMe" id="batteryEastAudioButton"></div>

        <div class="caption_c3"><?php echo($captionStr_C3); ?></div>
        <div class="title_c3"><?php echo($title_C3); ?></div>
        <div class="audioPlayer_c3">
            <audio  id="audioC3">
                <source src="./audio/<?php echo($c1Audio); ?>" type="audio/mpeg">
                <a href="./audio/<?php echo($c1Audio); ?>">Click Here To Hear Audio</a>
            </audio>
        </div>
</div>

<div class="hidden popup_c4" id="photoPopUp_C4">
    <img src = "./images/<?php echo($c4Image); ?>" >
        <div class="landsEndCloseBoxHotSpot clickMe" id="landsEndCloseBox"></div>
        <div class="landsEndAudioButtonHotSpot clickMe" id="landsEndAudioButton"></div>

        <div class="caption_c4"><?php echo($captionStr_C4); ?></div>
        <div class="title_c3"><?php echo($title_C4); ?></div>

        <div class="audioPlayer_c4">
            <audio  id="audioC4">
                <source src="./audio/<?php echo($c4Audio); ?>" type="audio/mpeg">
                <a href="./audio/<?php echo($c4Audio); ?>">Click Here To Hear Audio</a>
            </audio>   
        </div>
</div>

<!-- POP UP Historic Content -->
<div class="hidden popup_h1" id="audioPopUp_H1">
    <audio  id="audioH1">
        <source src="./audio/<?php echo($h1Audio); ?>" type="audio/mpeg">
        <a href="./audio/<?php echo($h1Audio); ?>">Click Here To Hear Audio</a>
    </audio>
</div>

<div class="hidden popup_h2" id="audioPopUp_H2">
    <audio  id="audioH2">
        <source src="./audio/<?php echo($h2Audio); ?>" type="audio/mpeg">
        <a href="./audio/<?php echo($h2Audio); ?>">Click Here To Hear Audio</a>
    </audio>
</div>

<div class="hidden popup_h3" id="audioPopUp_H3">
    <audio  id="audioH3">
        <source src="./audio/<?php echo($h3Audio); ?>" type="audio/mpeg">
        <a href="./audio/<?php echo($h3Audio); ?>">Click Here To Hear Audio</a>
    </audio>
</div>

</body>
</html>
<!-- Javascript Interactive -->
<script>
   var currentPopup = null;
    function showHistoricMap () {

        report('H');
        toggleMap('H');
    }

    function showContemporaryMap () {

        report('C');
        toggleMap('C');
    }

    function init () {
        var eleCMap = document.getElementById("contemporaryTab");
        var eleHMap = document.getElementById("historicTab");

        var popup_C1_map = document.getElementById("c_hotspot_1");
        var popup_C2_map = document.getElementById("cHotspotLakeMerced");
        var popup_C3_map = document.getElementById("cHotspotBatterEast");
        var popup_C4_map = document.getElementById("cHotspotLandsEnd");

        var popup_H1_map = document.getElementById("historicAudio_1");
        var popup_H2_map = document.getElementById("historicAudio_2");
        var popup_H3_map = document.getElementById("historicAudio_3");

        // close box on popups
        var c2CloseBox = document.getElementById("lakeMercedCloseBox");
        var c3CloseBox = document.getElementById("batterEastCloseBox");
        var c4CloseBox = document.getElementById("landsEndCloseBox");

        // pulse hotspots
        //var c1Hotspot = document.getElementById("cHotspot_1");
        //var c2Hotspot = document.getElementById("cHotspot_2");
        //var c3Hotspot = document.getElementById("cHotspot_3");

        var lakeMercedAudioLeft = document.getElementById("lakeMercedAudioButtonLeft");
        var lakeMercedAudioRight = document.getElementById("lakeMercedAudioButtonRight");
        var batteryEastAudioButton = document.getElementById("batteryEastAudioButton");
        var landsEndAudioButton = document.getElementById("landsEndAudioButton");
        report("init " + popup_C2_map);
        // Add the listeners for the Menu Bar - Historic and Contemporary Maps
        eleCMap.addEventListener("click", function() { showContemporaryMap() }, false);
        eleHMap.addEventListener("click", function() { showHistoricMap() }, false);

        // Add the listeners for the popups associated with the Contemporary Map
        popup_C1_map.addEventListener("click" , function() { showPopUp('C1') }, false); // audio player 
        popup_C2_map.addEventListener("click" , function() { showPopUp('C2') }, false); // image 
        popup_C3_map.addEventListener("click" , function() { showPopUp('C3') }, false); // image
        popup_C4_map.addEventListener("click" , function() { showPopUp('C4') }, false); // double image

        // Close Box for each of the popups
        c2CloseBox.addEventListener("click" , function() { showPopUp('C2') }, false);
        c3CloseBox.addEventListener("click" , function() { showPopUp('C3') }, false);
        c4CloseBox.addEventListener("click" , function() { showPopUp('C4') }, false);

        // pulsing circles - same function as 
 /*       c1Hotspot.addEventListener("click" , function() { showPopUp('C2') }, false);
        c2Hotspot.addEventListener("click" , function() { showPopUp('C4') }, false);    
        c3Hotspot.addEventListener("click" , function() { showPopUp('C3') }, false);
*/
        // add the listenewars for the popups associated with the Historic Map
        popup_H1_map.addEventListener("click" , function() { showPopUp('H1') }, false);
        popup_H2_map.addEventListener("click" , function() { showPopUp('H2') }, false);
        popup_H3_map.addEventListener("click" , function() { showPopUp('H3') }, false);

        lakeMercedAudioLeft.addEventListener("click", function() { playLMAudio("L")  }, false);
        lakeMercedAudioRight.addEventListener("click", function() { playLMAudio("R") } , false);
        batteryEastAudioButton.addEventListener("click", function() { playBatteryEastAudio() }, false);
        landsEndAudioButton.addEventListener("click", function() { playLandsEndAudio() }, false);
/**/
        report("end init");

    }

    function playBatteryEastAudio () {
        var batteryEastAudio = document.getElementById("audioC3");
        batteryEastAudio.play();
    }

    function playLandsEndAudio() {
        var landsEndAudio = document.getElementById("audioC4");
        landsEndAudio.play();

    }

    function playLMAudio ( who ) {
        var playAudioLeft = document.getElementById("lmAudioLeft");
        var playAudioRight = document.getElementById("lmAudioRight");
        report( who);
        if (who === "L") {
            playAudioLeft.play();
        }
        else {
            playAudioRight.play();
        }
    }
/* */
/**/
    //alert("hello 5");
  /*   */
    function toggleMap ( who ) {
        var mapContemporary = document.getElementById("contemorary");
        var mapHistoric = document.getElementById("historic");
        var menu = document.getElementById("menu");

        report( who );
        //alert(who);
        if ("H" === who) {
            hideAllPopups("C");
            mapHistoric.style.display = "block";

            mapContemporary.style.display = "none";
            menu.src = "./images/<?php echo($menuBar_1); ?>";
        }
        else {
            hideAllPopups("H");
            mapHistoric.style.display = "none";

            mapContemporary.style.display = "block";
            menu.src = "./images/<?php echo($menuBar_2); ?>";

        }
        return false;
    }

    function showPopUp ( who ) {
        var popup;
        var playAudio = false;
        report( who );
        //hideAllPopups("C");
        //hideAllPopups("H");
        switch (who) {
            case "C1" :
                popup = document.getElementById("audioPopUp_C1");
                playAudio = document.getElementById("audioC1");
                break;
            case "H1":
                popup = document.getElementById("audioPopUp_H1");
                playAudio = document.getElementById("audioH1");
                break;
            case "H2":
                popup = document.getElementById("audioPopUp_H2");
                playAudio = document.getElementById("audioH2");
                break;
            case "H3":
                popup = document.getElementById("audioPopUp_H3");
                playAudio = document.getElementById("audioH3");
                break;
            
            case "C2":
                popup = document.getElementById("photoPopUp_C2");
                break;
            case "C3":
                popup = document.getElementById("photoPopUp_C3");
                playAudio = document.getElementById("audioC3");
                break;
            case "C4":
                popup = document.getElementById("photoPopUp_C4");
                playAudio = document.getElementById("audioC4");
                break;
        }
        if ("block" === popup.style.display) {
            popup.style.display = "none";
        }
        else {
            popup.style.display = "block";
            //if (currentPopup !== null) {
            //    currentPopup.style.display = "none";
            //}
            //currentPopup = popup;
            if (playAudio) {
                playAudio.play();
            }
        }
        return false;
    }
    
    function hideAllPopups ( map ) {
        var popup;
        //alert(map);
        switch ( map ) {
            case "H":
                popup = document.getElementById("audioPopUp_H1");
                popup.style.display = "none";
                popup = document.getElementById("audioPopUp_H2");
                popup.style.display = "none";
                popup = document.getElementById("audioPopUp_H3");
                popup.style.display = "none";
                <?php /*
                hotspot = document.getElementById("cHotspot_1");
                hotspot.style.display = "block";
                hotspot = document.getElementById("cHotspot_2");
                hotspot.style.display = "block";
                hotspot = document.getElementById("cHotspot_3");
                hotspot.style.display = "block";
                */ ?>

                break;
            case "C":
                popup = document.getElementById("audioPopUp_C1");
                popup.style.display = "none";
                popup = document.getElementById("photoPopUp_C2");
                popup.style.display = "none";
                popup = document.getElementById("photoPopUp_C3");
                popup.style.display = "none";
                popup = document.getElementById("photoPopUp_C4");
                popup.style.display = "none";
                <?php /*
                hotspot = document.getElementById("cHotspot_1");
                hotspot.style.display = "none";
                hotspot = document.getElementById("cHotspot_2");
                hotspot.style.display = "none";
                hotspot = document.getElementById("cHotspot_3");
                hotspot.style.display = "none";
                */ ?>
                break;
        }
        return false;
    }

    function report ( what ) {
        //alert( what );
    }
   /* */
</script>

<?php
/*
 *<iframe src="http://localhost:8080/wordpress/wp-content/WEB_SONG_SPARROW/index.php" width="612" height="800"></iframe>
 *<iframe src="http://localhost/baynature/wp-content/WEB_SONG_SPARROW/index.php" width="620" height="870" frameborder="0"></iframe>
 */
?>