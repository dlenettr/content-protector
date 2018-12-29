<?php
/*
=============================================
 Name      : MWS Content Protector v1.0
 Author    : Mehmet Hanoğlu ( MaRZoCHi )
 Site      : http://dle.net.tr/   (c) 2015
 License   : MIT License
=============================================
*/

@error_reporting ( E_ALL ^ E_WARNING ^ E_NOTICE );
@ini_set ( 'display_errors', true );
@ini_set ( 'html_errors', false );
@ini_set ( 'error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE );

define( 'DATALIFEENGINE', true );
define( 'ROOT_DIR', substr( dirname(  __FILE__ ), 0, -12 ) );
define( 'ENGINE_DIR', ROOT_DIR . '/engine' );

include ENGINE_DIR . '/data/config.php';

date_default_timezone_set ( $config['date_adjust'] );

if ( $config['http_home_url'] == "" ) {
	$config['http_home_url'] = explode( "engine/ajax/content.protector.ajax.php", $_SERVER['PHP_SELF'] );
	$config['http_home_url'] = reset( $config['http_home_url'] );
	$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];
}

require_once ENGINE_DIR . '/classes/mysql.php';
require_once ENGINE_DIR . '/data/dbconfig.php';
require_once ENGINE_DIR . '/modules/functions.php';
require_once ENGINE_DIR . '/classes/recaptcha.php';
dle_session();
require_once ENGINE_DIR . '/modules/sitelogin.php';
require_once ROOT_DIR . '/language/' . $config['langs'] . '/website.lng';

if ( $config['version_id'] >= "10.5" ) {

	if ( $_POST['g-recaptcha-response'] && $_POST['param'] ) {
		$param = $db->safesql( $_POST['param'] );
		if ( empty( $param ) ) die( "Error: Parameter not defined" );
		$reCaptcha = new ReCaptcha( $config['recaptcha_private_key'] );
		$resp = $reCaptcha->verifyResponse( get_ip(), $_POST['g-recaptcha-response'] );
		if ( $resp === null OR ! $resp->success ) {
			echo "Error: Not confirmed. You may bot";
		} else {
			if ( ! array_key_exists( '_cp', $_SESSION ) && ! is_array( $_SESSION['_cp'] ) ) {
				$_SESSION[ '_cp' ] = array( $param => time() );
			} else {
				$_SESSION[ '_cp' ][ $param ] = time();
			}
			echo "ok";
		}
	} else echo "Error: There are missing values";


} else {

	if ( $_POST['recaptcha_response_field'] && $_POST['recaptcha_challenge_field'] && $_POST['param'] ) {
		$param = $db->safesql( $_POST['param'] );
		$resp = recaptcha_check_answer( $config['recaptcha_private_key'], get_ip(), $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field'] );
		if ( ! $resp->is_valid ) {
			echo "Error: Not confirmed. You may bot";
		} else {
			if ( ! array_key_exists( '_cp', $_SESSION ) && ! is_array( $_SESSION['_cp'] ) ) {
				$_SESSION[ '_cp' ] = array( $param => time() );
			} else {
				$_SESSION[ '_cp' ][ $param ] = time();
			}
			echo "ok";
		}
	} else echo "Error: There are missing values";

}

?>