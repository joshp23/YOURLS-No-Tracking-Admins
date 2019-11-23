<?php
/*
Plugin Name: 	No Tracking Admins
Plugin URI: 	https://github.com/joshp23/YOURLS-No-Tracking-Admins
Description:	Disables tracking for authenticated users
Version: 		0.0.1
Author:			Josh Panter
Author URI:		https://unfettered.net
*/ 

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// hook into normal redirects
yourls_add_action('redirect_shorturl', 'nta_core');
function nta_core() {
	if( !yourls_is_API() && nta_is_valid_user() ) {
		// No logging
		yourls_add_filter( 'shunt_update_clicks', 	function( $x, $y ) { return true; } );
		yourls_add_filter( 'shunt_log_redirect', 	function( $x, $y ) { return true; } );
	}
}
function nta_is_valid_user() {
	$valid = defined( 'YOURLS_USER' ) ? true : false;
	if ( !$valid ) {
		if ( isset( $_REQUEST['username'] ) && isset( $_REQUEST['password'] )
		&&  !empty( $_REQUEST['username'] ) && !empty( $_REQUEST['password']  ) )
			$valid = yourls_check_username_password();
		elseif ( isset( $_COOKIE[ yourls_cookie_name() ] ) )
			$valid = yourls_check_auth_cookie();
	}
	return $valid;
}
