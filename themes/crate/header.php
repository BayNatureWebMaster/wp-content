<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Crate
 * BN GTM Container Added 12/10/2019
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<!-- ADD GTM Here -->
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TXQX2JC');</script>
<!-- End Google Tag Manager -->
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png" />
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-57x57.png" />
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-72x72.png" />
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-76x76.png" />
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-114x114.png" />
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-120x120.png" />
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-144x144.png" />
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-152x152.png" />
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon-180x180.png" />
<script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>

<script>
	(function(d) {
	var config = {
		kitId: 'knt2fsi',
		scriptTimeout: 3000,
		async: true
	},
	h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
	})(document);
</script>

<style type="text/css">
	.wf-loading h1,
	.wf-loading h2,
	.wf-loading h3,
	.wf-loading nav a,
	.wf-loading p {
		/* Hide the blog title and post titles while web fonts are loading */
		visibility: hidden;
	}
</style>
<!-- call wp_head() to do the rest -->
<?php wp_head(); ?>

<script type="text/javascript">
	function clickSubmitNewsletterSignUp ( origin ) {
}
</script>
</head>

<body <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TXQX2JC"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="body-wrapper">

	<?php get_template_part( 'template-parts/header-svg' ); ?>

	<div class="site-search-form">
		<div class="close-search"><span class="visuallyhidden"><?php esc_html_e( 'Close Search', 'crate' ); ?></span></div>
		<?php get_search_form( true ); ?>
	</div>

	<header class="site-header" role="banner" itemscope="itemscope" itemtype="http://schema.org/WPHeader">

		<?php
			$header_bg = NULL;
			if ( get_field( 'header_left_image', 'option' ) || get_field( 'header_right_image', 'option' ) ) {
				$header_bg = 'style="background: url( '. get_field( 'header_left_image', 'option' ) .') bottom left no-repeat, url( '. get_field( 'header_right_image', 'option' ) .') top right no-repeat"';
			}
		?>
		<div class="header-main-wrap" <?php echo $header_bg; ?>>
			<div class="container header-main">

				<div class="header-top">

					<?php // This logo component contains microdata that will produce a generated logo in Google search results. ?>
					<div itemscope itemtype="http://schema.org/Organization" class="site-logo">
						<a itemprop="url" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php echo wp_get_attachment_image( get_field( 'site_logo', 'option' ), 'full', false , array( 'itemprop' => 'logo', 'alt' => 'Logo' ) ); ?>
							<span class="visuallyhidden"><?php bloginfo( 'name' ); ?></span>
						</a>
					</div>

					<?php if ( get_field( 'header_callout_area', 'option' ) ) { ?>
						<div class="header-call-out <?php echo esc_attr( 'visibility-' . get_field( 'header_callout_visibility', 'option' ) ); ?>">
							<?php echo get_field( 'header_callout_area', 'option' ); ?>
						</div>
					<?php } ?>

					<nav class="utility-nav">
						<?php  wp_nav_menu( array(
							'theme_location' => 'utility',
							'container'	     => false,
						) ); ?>
					</nav>

					<nav class="mobile-nav">
						<?php  wp_nav_menu( array(
							'theme_location' => 'mobile',
							'container'	     => 'div',
						) ); ?>
						<button type="button" class="menu-toggle"><span class="visuallyhidden"><?php esc_html_e( 'Menu', 'crate' ); ?></span><span class="menu-bar" aria-hidden="true"></span><span class="menu-bar" aria-hidden="true"></span><span class="menu-bar" aria-hidden="true"></span></button>
					</nav>

				</div>

			</div>
		</div>


		<div class="primary-nav">
			<div class="container">
				<?php // This is the primary nav. ?>
				<nav class="primary-nav-container" role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
					<?php wp_nav_menu( array(
						'theme_location' => 'primary',
						'container'      => false,
					) ); ?>
				</nav>
			</div>
		</div>

	</header>

	<div class="site-content">