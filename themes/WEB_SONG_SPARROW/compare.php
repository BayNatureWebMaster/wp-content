<?php 
/********************************************************************
* Song Sparrow Interactive  :Compare Waveforms : Contemporary vs 1969
* Dec 2015 : 
* Alison Hawks / Laurence Tietz / BayNature
*********************************************************************/
?>
<style>
	.waveFormPanel {
		width:1000px;
		height:443px;
	}

	.show {
		display:block;
	}

	.hide {
		display:none;
	}

	.waveForm {
		width:1000px;
		height:443px;
	}

</style>
<body>
	<div class="waveFormPanel">
		<div class="waveForm show" id="waveform_1969">
			<img src="./images/1969_color_wAxis.png" />
		</div>
		<div class="waveForm hide" id="waveform_contemporary">
			<img src="./images/contemporary_color_wAxis.png" />
		</div>	
		<div class="waveForm hide" id="waveform_overlay">
			<img src="./images/overlay_color_wAxis.png" />
		</div>	
		<button onclick="toggleWaveForm('historic'); ">1969 Song</button> &nbsp; 
		<button onclick="toggleWaveForm('contemporary'); ">Contemporaty Song</button>&nbsp; 
		<button onclick="toggleWaveForm('overlay'); ">Combined / Overlay Song</button>
	</div>
</body>

<script>
	function toggleWaveForm ( show) {
        var waveContemporary = document.getElementById("waveform_contemporary");
        var wave1969 = document.getElementById("waveform_1969");
        var waveformOverlay = document.getElementById("waveform_overlay");
        switch ( show ) {
        	case "historic":
        		wave1969.style.display = "block";
        		waveContemporary.style.display = "none";
        		waveformOverlay.style.display = "none";
        		break;
        	case "contemporary":
        		wave1969.style.display = "none";
        		waveContemporary.style.display = "block";
        		waveformOverlay.style.display = "none";
        		break;
        	case "overlay":
        		wave1969.style.display = "none";
        		waveContemporary.style.display = "none";
        		waveformOverlay.style.display = "block";
        		break;
        }
        return false;
	}
</script>
<?php
 /*<iframe src="http://localhost/baynature/wp-content/WEB_SONG_SPARROW/compare.php" width="1000" height="433" frameborder="0"></iframe> */
 ?>
