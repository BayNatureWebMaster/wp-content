<?php 
	function sfsi_premium_prefetch_fb($post_id){

		$url = get_permalink($post_id);
		$prefetch_url = "https://graph.facebook.com/?id=".$url."&scrape=true";
		$fbSocial = new sfsiFacebookSocialHelper();
		$accessToken = $fbSocial->sfsi_get_fb_access_token();
		// var_dump($accessToken);
		if(""==$accessToken || $accessToken =="954871214567352|a780eb3d3687a084d6e5919585cc6a12"){
			$accessToken = "717046892087657|5621d0d130149133d5d323c5e6d04f4e";
		}

		$prefetch_url = $prefetch_url . "&access_token=" . $accessToken;

		$response = wp_remote_post($prefetch_url);

		if( is_array( $response ) && ! is_wp_error( $response ) ) {
			$body	=	$response['body'];
			return json_encode(array($body, $prefetch_url,$accessToken));
		}else{
			return false;
		}
	}

	add_action( 'save_post', 'sfsi_premium_prefetch_fb', 10,3 );