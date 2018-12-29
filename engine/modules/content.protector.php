<?php
/*
=============================================
 Name      : MWS Content Protector v1.0
 Author    : Mehmet Hanoğlu ( MaRZoCHi )
 Site      : http://dle.net.tr/   (c) 2015
 License   : MIT License
=============================================
*/

if ( ! defined( 'DATALIFEENGINE' ) ) {
	die( "Hacking attempt!" );
}

$scripts = <<< HTML
<script>\$(document).ready(function(){\$("#content-protector").on("submit",function(){var n=\$(this).serializeArray();return ShowLoading(""),\$.post(dle_root+"engine/ajax/content.protector.ajax.php",n,function(n){"ok"==n?\$("input[name='page']")&&"showfull"==\$("input[name='page']").val()&&(console.log("_cp:Accepted"),newsid=\$("input[name='news']").val(),param=\$("input[name='param']").val(),""!=newsid&&""!=param?\$("#"+param).load(dle_root+"index.php?newsid="+newsid+" #"+param,function(){HideLoading("")}):DLEalert("Please refresh page","Info")):DLEalert(n,"Error"),console.log(n)}).done(function(){HideLoading("")}),!1})});</script>
HTML;

/*
$(document).ready( function() {
	$("#content-protector").on( 'submit', function() {
		var form = $(this).serializeArray();
		ShowLoading('');
		$.post( dle_root + "engine/ajax/content.protector.ajax.php", form, function( data ) {
			if ( data == "ok" ) {
				if ( $("input[name='page']") && $("input[name='page']").val() == "showfull" ) {
					console.log( "_cp:Accepted" );
					newsid = $("input[name='news']").val();
					param = $("input[name='param']").val();
					if ( newsid != '' && param != '' ) {
						$( "#" + param ).load( dle_root + "index.php?newsid=" + newsid + " #" + param, function() {
							HideLoading('');
						});
					} else {
						DLEalert( "Please refresh page", 'Info' );
					}
				}
			} else {
				DLEalert( data, 'Error' );
			}
			console.log( data );
		}).done( function( data ) {
			HideLoading('');
		});
		return false;
	});
});
*/

$hiddens = <<< HTML
<input type="hidden" name="news" value="{$newsid}" />
<input type="hidden" name="page" value="{$dle_module}" />
HTML;


function get_extime( $x ) {
	$t = substr( $x, -1 );
	$v = intval( substr( $x, 0, -1 ) );
	if ( $t == "m" ) {
		$c = 60;
	} else if ( $t == "h" ) {
		$c = 60*60;
	} else if ( $t == "d" ) {
		$c = 60*60*24;
	}
	return $v * $c;
}

function cookie_control( $name, $expire ) {
	if ( array_key_exists( $name, $_SESSION['_cp'] ) ) {
		if ( $expire != 0 && $expire != "" ) {
			if ( time() >= $_SESSION['_cp'][ $name ] + get_extime( $expire ) ) {
				unset( $_SESSION['_cp'][ $name ] );
				return false;
			}
		}
		return true;
	} else {
		return false;
	}
}


function content_control( $x ) {
	global $lang, $config, $tpl, $member_id, $dle_module, $newsid, $scripts, $hiddens;

	$param_string = trim( $x[ 1 ] );
	preg_match_all( "#([a-z]+)=['\"](.*?)['\"]#is", $param_string, $matches );

	$params = array( 'expire' => 0 );
	for ( $m = 0; $m < count( $matches[ 1 ] ); $m++ ) {
		$params[ $matches[ 1 ][ $m ] ] = $matches[ 2 ][ $m ];
	}

	$groups = explode( ",", $params['group'] );
	if ( in_array( $member_id['user_group'], $groups ) ) {

		if ( cookie_control( $params['id'], $params['expire'] ) ) {

			return "<div class=\"_cp_open\" id=\"" . $params['id'] . "\">" . $x[ 2 ] . "</div>";

		} else {

			$tpl->load_template( "content-protector.tpl" );

			if ( $config['version_id'] >= "10.5" ) {
				$recaptcha = "<div class=\"g-recaptcha\" data-sitekey=\"{$config['recaptcha_public_key']}\" data-theme=\"{$config['recaptcha_theme']}\"></div><script src='https://www.google.com/recaptcha/api.js?hl={$lang['wysiwyg_language']}' async defer></script>";
			} else {
				$recaptcha = '<script type="text/javascript">
				<!--
					var RecaptchaOptions = { theme: \''.$config['recaptcha_theme'].'\', lang: \''.$lang['wysiwyg_language'].'\' };
				//-->
				</script>
				<script type="text/javascript" src="//www.google.com/recaptcha/api/challenge?k='.$config['recaptcha_public_key'].'"></script>';
			}

			$tpl->set( '{recaptcha}', $recaptcha . $hiddens . "<input type=\"hidden\" name=\"param\" value=\"{$params['id']}\" />" );

			$tpl->compile( 'content-protector' );
			return "<div class=\"_cp\" id=\"" . $params['id'] . "\">" . $tpl->result[ 'content-protector' ] . $scripts . "</div>";
		}

	} else {
		return $x[ 2 ];
	}

}

?>