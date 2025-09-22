<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<!--[if lt IE 9]>
<script>
	document.createElement('video');
</script>
<![endif]-->
<html lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head profile="http://gmpg.org/xfn/11">
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="google-site-verification" content="wZru6SBj3IQFqJkpqYLG2dtkHNX9_C7wZmTucb76y80" />
    
    <title><?php $PageTitle ?></title>

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); echo '?' . filemtime( get_stylesheet_directory() . '/style.css'); ?>" type="text/css" media="screen, projection" />
    <link rel="stylesheet" href="../../wp-content/sea-change/css/sea.css" type="text/css" media="screen,projection"/>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style-print.css" type="text/css" media="print" />

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <?php wp_head(); ?>

    <link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/images/favicon.ico" />
    <script type="text/javascript">

    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-32668307-1', 'auto');
    ga('send', 'pageview');
    ga('require', 'displayfeatures');

    /* Track outbound links in Google Analytics */
    (function($) {

        "use strict";

        // current page host
        var baseURI = window.location.host;

        // click event on body
        $("body").on("click", function(e) {

            // abandon if link already aborted or analytics is not available
            if (e.isDefaultPrevented() || typeof ga !== "function") return;

            // abandon if no active link or link within domain
            var link = $(e.target).closest("a");
            if (link.length != 1 || baseURI == link[0].host) return;

            // cancel event and record outbound link
            e.preventDefault();
            var href = link[0].href;
            ga('send', {
                'hitType': 'event',
                'eventCategory': 'outbound',
                'eventAction': 'link',
                'eventLabel': href,
                'hitCallback': loadPage
            });

            // redirect after one second if recording takes too long
            setTimeout(loadPage, 1000);

            // redirect to outbound page
            function loadPage() {
                document.location = href;
            }

        });

    })(jQuery); // pass another library here if required

    </script>
    <script type="text/javascript" src="//use.typekit.net/ywc6ibk.js"></script> 
    <script type="text/javascript">try{Typekit.load();}catch(e){ }</script>
</head>
<?php
//echo "sea". $thisPage;
if ($thisPage == "sea") {
    echo "<body class='sea-change'>";
} else { // what lurks beneath
    echo "<body class='lurks'>";
}
?>
    <div id="shell">
            <!-- header -->
            <div class="header">
                <?php if ($thisPage == "sea") { ?>
                <div class="navigation">
                    <span class="logo" style="z-index: 100;">
                        <a href="<?php bloginfo('url'); ?>" title="Bay Nature">
                            <img src="../../wp-content/sea-change/assets/bay_nature_logo_slate.png" />
                       </a>
                    </span>
                    
                    <ul>
                        <li class="read-nav"><a href="#read">read</a></li>
                        <li class="watch-nav"><a href="#watch">watch</a></li>
                        <li class="act-nav"><a href="#act">act</a></li>
                        <li class="about-nav"><a href="#about">about</a></li>
                    </ul>
                </div>
                <?php } //end nav if statement?>
            </div>

    <!-- END header-->
    <!-- main -->
    <div id="main">
