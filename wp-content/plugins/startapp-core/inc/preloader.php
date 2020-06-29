<?php
/**
 * Page Preloader
 *
 * @author 8guild
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Exit if Equip not installed
if ( ! defined( 'EQUIP_VERSION' ) ) {
	return;
}

if ( ! function_exists( 'startapp_is_preloader' ) ) :
	/**
	 * Check if Page Preloader is enabled in Theme Options
	 *
	 * @see startapp_add_general_options()
	 *
	 * @return bool
	 */
	function startapp_is_preloader() {
		$is_preloader = (bool) startapp_get_option( 'general_is_preloader', 0 );

		return $is_preloader;
	}
endif;

if ( ! function_exists( 'startapp_preloader_styles' ) ) :
	/**
	 * Add Page Preloader styles to <head>
	 *
	 * @hooked wp_print_styles
	 */
	function startapp_preloader_styles() {
		if ( ! startapp_is_preloader() || is_admin() ) {
			return;
		}

		$spinner       = esc_attr( startapp_get_option( 'general_preloader_spinner_type', 'spinner1' ) );
		$spinner_color = sanitize_hex_color( startapp_get_option( 'general_preloader_spinner_color', '#3f6bbe' ) );
		$screen_color  = sanitize_hex_color( startapp_get_option( 'general_preloader_screen_color', '#ffffff' ) );

		$css = "
		body {
			overflow-y: hidden;
		}
		.site-header,
		.site-header.navbar-lateral {
			opacity: 0;
			-webkit-transition: opacity .6s .9s;
			transition: opacity .6s .9s;
		}
		.page-wrap {
			opacity: 0;
			-webkit-transition: opacity .7s .9s;
			transition: opacity .7s .9s;

		}
		.site-header.loading-done,
		.site-header.navbar-lateral.loading-done,
		.page-wrap.loading-done {
			opacity: 1;
		}
		.loading-screen {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: {$screen_color};
			z-index: 9999;
			opacity: 1;
			visibility: visible;
			-webkit-transition: all .1s;
			transition: all 1s;
		}
		.loading-screen.loading-done {
			opacity: 0;
			visibility: hidden;
		}
		";

		switch ( $spinner ) {
			case 'spinner2':
				$css .= "
				.spinner-wrap {
				    position: absolute;
				    left: 50%;
				    top: 50%;
				    height: 50px;
				    width: 50px;
				    margin-top: -25px;
				    margin-left: -25px;
				    -webkit-transform: rotate(45deg);
				    transform: rotate(45deg);
				    -webkit-animation: spinner-wrap 1.5s infinite;
				    animation: spinner-wrap 1.5s infinite;
				}
				.spinner {
				    width: 25px;
				    height: 25px;
				    background-color: {$spinner_color};
				    float: left;
				}
				#spinner_one {
				    -webkit-animation: spinner_one 1.5s infinite;
				    animation: spinner_one 1.5s infinite;
				}
				#spinner_two {
				    -webkit-animation: spinner_two 1.5s infinite;
				    animation: spinner_two 1.5s infinite;
				}
				#spinner_three {
				    -webkit-animation: spinner_three 1.5s infinite;
				    animation: spinner_three 1.5s infinite;
				}
				#spinner_four {
				    -webkit-animation: spinner_four 1.5s infinite;
				    animation: spinner_four 1.5s infinite;
				}
				@-webkit-keyframes spinner-wrap {
				    100% { -webkit-transform: rotate(-45deg); }
				}
				@keyframes spinner-wrap {
				    100% {
				        transform:  rotate(-45deg);
				        -webkit-transform:  rotate(-45deg);
				    }
				}
				@-webkit-keyframes spinner_one {
				    25% { -webkit-transform: translate(0,-50px) rotate(-180deg); }
				    100% { -webkit-transform: translate(0,0) rotate(-180deg); }
				}
				@keyframes spinner_one {
				    25% {
				        transform: translate(0,-50px) rotate(-180deg);
				        -webkit-transform: translate(0,-50px) rotate(-180deg);
				    }
				    100% {
				        transform: translate(0,0) rotate(-180deg);
				        -webkit-transform: translate(0,0) rotate(-180deg);
				    }
				}
				@-webkit-keyframes spinner_two {
				    25% { -webkit-transform: translate(50px,0) rotate(-180deg); }
				    100% { -webkit-transform: translate(0,0) rotate(-180deg); }
				}
				@keyframes spinner_two {
				    25% {
				        transform: translate(50px,0) rotate(-180deg);
				        -webkit-transform: translate(50px,0) rotate(-180deg);
				    }
				    100% {
				        transform: translate(0,0) rotate(-180deg);
				        -webkit-transform: translate(0,0) rotate(-180deg);
				    }
				}
				@-webkit-keyframes spinner_three {
				    25% { -webkit-transform: translate(-50px,0) rotate(-180deg); }
				    100% { -webkit-transform: translate(0,0) rotate(-180deg); }
				}
				@keyframes spinner_three {
				    25% {
				        transform:  translate(-50px,0) rotate(-180deg);
				        -webkit-transform:  translate(-50px,0) rotate(-180deg);
				    }
				    100% {
				        transform: translate(0,0) rotate(-180deg);
				        -webkit-transform: translate(0,0) rotate(-180deg);
				    }
				}
				@-webkit-keyframes spinner_four {
				    25% { -webkit-transform: translate(0,50px) rotate(-180deg); }
				    100% { -webkit-transform: translate(0,0) rotate(-180deg); }
				}
				@keyframes spinner_four {
				    25% {
				        transform: translate(0,50px) rotate(-180deg);
				        -webkit-transform: translate(0,50px) rotate(-180deg);
				    }
				    100% {
				        transform: translate(0,0) rotate(-180deg);
				        -webkit-transform: translate(0,0) rotate(-180deg);
				    }
				}
				";
				break;

			case 'spinner3':
				$css .= "
				.spinner-wrap {
				    position: absolute;
				    top: 50%;
				    left: 50%;
				    width: 160px;
				    height: 160px;
				    margin: -80px 0 0 -80px;
				}
				.spinner {
				    width: 60px;
				    height: 60px;
				    margin: 50px auto 0 auto;
				    background-color: {$spinner_color};
				    -webkit-animation: animate 1s infinite ease-in-out;
				    animation: animate 1s infinite ease-in-out;
				}
				@-webkit-keyframes animate {
				    0% {
				        -webkit-transform: perspective(160px);
				    }
				    50% {
				        -webkit-transform: perspective(160px) rotateY(-180deg);
				    }
				    100% {
				        -webkit-transform: perspective(160px) rotateY(-180deg) rotateX(-180deg);
				    }
				}
				@keyframes animate {
				    0% {
				        transform: perspective(160px) rotateX(0deg) rotateY(0deg);
				        -webkit-transform: perspective(160px) rotateX(0deg) rotateY(0deg);
				    }
				    50% {
				        transform: perspective(160px) rotateX(-180deg) rotateY(0deg);
				        -webkit-transform: perspective(160px) rotateX(-180deg) rotateY(0deg);
				    }
				    100% {
				        transform: perspective(160px) rotateX(-180deg) rotateY(-180deg);
				        -webkit-transform: perspective(160px) rotateX(-180deg) rotateY(-180deg);
				    }
				}
				";
				break;

			case 'spinner4':
				$css .= "
				.spinner-wrap {
				    position: absolute;
				    left: 50%;
				    top: 50%;
				    height: 130px;
				    width: 130px;
				    margin-top: -65px;
				    margin-left: -65px;
				}
				.spinner {
				    width: 20px;
				    height: 20px;
				    background-color: {$spinner_color};
				    float: left;
				    margin: 55px 10px 0 10px;
				    border-radius: 50%;
				}
				#spinner_one {
				    -webkit-animation: spinner_one 1.5s infinite;
				    animation: spinner_one 1.5s infinite;
				}
				#spinner_two {
				    -webkit-animation: spinner_two 1.5s infinite;
				    animation: spinner_two 1.5s infinite;
				    -webkit-animation-delay: 0.25s;
				    animation-delay: 0.25s;
				}
				#spinner_three {
				    -webkit-animation: spinner_three 1.5s infinite;
				    animation: spinner_three 1.5s infinite;
				    -webkit-animation-delay: 0.5s;
				    animation-delay: 0.5s;
				}
				@-webkit-keyframes spinner_one {
				    75% {
				        -webkit-transform: scale(0);
				    }
				}
				@keyframes spinner_one {
				    75% {
				        transform: scale(0);
				        -webkit-transform: scale(0);
				    }
				}
				@-webkit-keyframes spinner_two {
				    75% {
				        -webkit-transform: scale(0);
				    }
				}
				@keyframes spinner_two {
				    75% {
				        transform: scale(0);
				        -webkit-transform: scale(0);
				    }
				}
				@-webkit-keyframes spinner_three {
				    75% {
				        -webkit-transform: scale(0);
				    }
				}
				@keyframes spinner_three {
				    75% {
				        transform: scale(0);
				        -webkit-transform: scale(0);
				    }
				}
				";
				break;

			case 'spinner5':
				$css .= "
				.spinner-wrap {
				    position: absolute;
				    left: 50%;
				    top: 50%;
				    height: 80px;
				    width: 60px;
				    margin-top: -45px;
				    margin-left: -35px;
				}
				.spinner {
				    width: 50px;
				    height: 8px;
				    margin-bottom: 15px;
				    background-color: {$spinner_color};
				    -webkit-animation: animate 0.8s infinite;
				    animation: animate 0.8s infinite;
				}
				#spinner_two {
				    -webkit-animation-delay: 0.2s;
				    animation-delay: 0.2s;
				}
				#spinner_four {
				    -webkit-animation-delay: 0.2s;
				    animation-delay: 0.2s;
				}
				@-webkit-keyframes animate {
				    50% {
				        -ms-transform: translate(50%, 0);
				        -webkit-transform: translate(50%, 0);
				        transform: translate(50%, 0);
				    }
				}
				@keyframes animate {
				    50% {
				        -ms-transform: translate(50%, 0);
				        -webkit-transform: translate(50%, 0);
				        transform: translate(50%, 0);
				    }
				}
				";
				break;

			case 'spinner6':
				$css .= "
				.spinner-wrap {
				    position: absolute;
				    left: 50%;
				    top: 50%;
				    height: 50px;
				    width: 130px;
				    margin-top: -25px;
				    margin-left: -65px;
				}
				.spinner {
				    width: 8px;
				    height: 50px;
				    margin-right: 5px;
				    background-color: {$spinner_color};
				    -webkit-animation: animate 1s infinite;
				    animation: animate 1s infinite;
				    float: left;
				}
				.spinner:last-child {
				    margin-right: 0;
				}
				.spinner:nth-child(10) {
				    -webkit-animation-delay: 0.9s;
				    animation-delay: 0.9s;
				}
				.spinner:nth-child(9) {
				    -webkit-animation-delay: 0.8s;
				    animation-delay: 0.8s;
				}
				.spinner:nth-child(8) {
				    -webkit-animation-delay: 0.7s;
				    animation-delay: 0.7s;
				}
				.spinner:nth-child(7) {
				    -webkit-animation-delay: 0.6s;
				    animation-delay: 0.6s;
				}
				.spinner:nth-child(6) {
				    -webkit-animation-delay: 0.5s;
				    animation-delay: 0.5s;
				}
				.spinner:nth-child(5) {
				    -webkit-animation-delay: 0.4s;
				    animation-delay: 0.4s;
				}
				.spinner:nth-child(4) {
				    -webkit-animation-delay: 0.3s;
				    animation-delay: 0.3s;
				}
				.spinner:nth-child(3) {
				    -webkit-animation-delay: 0.2s;
				    animation-delay: 0.2s;
				}
				.spinner:nth-child(2) {
				    -webkit-animation-delay: 0.1s;
				    animation-delay: 0.1s;
				}
				@-webkit-keyframes animate {
				    50% {
				        -ms-transform: scaleY(0);
				        -webkit-transform: scaleY(0);
				        transform: scaleY(0);
				    }
				}
				@keyframes animate {
				    50% {
				        -ms-transform: scaleY(0);
				        -webkit-transform: scaleY(0);
				        transform: scaleY(0);
				    }
				}
				";
				break;

			case 'spinner7':
				$css .= "
				.spinner-wrap {
				    position: absolute;
				    left: 50%;
				    top: 50%;
				    height: 200px;
				    width: 200px;
				    margin-top: -100px;
				    margin-left: -100px;
				    -ms-transform: rotate(-135deg);
				    -webkit-transform: rotate(-135deg);
				    transform: rotate(-135deg);
				}
				.spinner {
				    position: absolute;
				    border-top: 5px solid {$spinner_color};
				    border-bottom: 5px solid transparent;
				    border-left: 5px solid {$spinner_color};
				    border-right: 5px solid transparent;
				    border-radius: 50%;
				    -webkit-animation: animate 2s infinite;
				    animation: animate 2s infinite;
				}
				#spinner_one {
				    left: 75px;
				    top: 75px;
				    width: 50px;
				    height: 50px;
				}
				#spinner_two {
				    left: 65px;
				    top: 65px;
				    width: 70px;
				    height: 70px;
				    -webkit-animation-delay: 0.2s;
				    animation-delay: 0.2s;
				}
				#spinner_three {
				    left: 55px;
				    top: 55px;
				    width: 90px;
				    height: 90px;
				    -webkit-animation-delay: 0.4s;
				    animation-delay: 0.4s;
				}
				#spinner_four {
				    left: 45px;
				    top: 45px;
				    width: 110px;
				    height: 110px;
				    -webkit-animation-delay: 0.6s;
				    animation-delay: 0.6s;
				}
				@-webkit-keyframes animate {
				    50% {
				        -ms-transform: rotate(360deg) scale(0.8);
				        -webkit-transform: rotate(360deg) scale(0.8);
				        transform: rotate(360deg) scale(0.8);
				    }
				}
				@keyframes animate {
				    50% {
				        -ms-transform: rotate(360deg) scale(0.8);
				        -webkit-transform: rotate(360deg) scale(0.8);
				        transform: rotate(360deg) scale(0.8);
				    }
				}
				";
				break;

			case 'spinner1':
			default:
				$css .= "
				.spinner-wrap {
				    position: absolute;
				    left: 50%;
				    top: 50%;
				    height: 60px;
				    width: 60px;
				    margin-top: -30px;
				    margin-left: -30px;
				    -webkit-animation: spinner-wrap 1s infinite;
				    animation: spinner-wrap 1s infinite;
				}
				.spinner {
				    width: 20px;
				    height: 20px;
				    background-color: {$spinner_color};
				    float: left;
				    border-radius: 50%;
				    margin-right: 20px;
				    margin-bottom: 20px;
				}
				.spinner:nth-child(2n+0) { margin-right: 0; }
				#spinner_one {
				    -webkit-animation: spinner_one 1s infinite;
				    animation: spinner_one 1s infinite;
				}
				#spinner_two {
				    -webkit-animation: spinner_two 1s infinite;
				    animation: spinner_two 1s infinite;
				}
				#spinner_three {
				    -webkit-animation: spinner_three 1s infinite;
				    animation: spinner_three 1s infinite;
				}
				#spinner_four {
				    -webkit-animation: spinner_four 1s infinite;
				    animation: spinner_four 1s infinite;
				}
				@-webkit-keyframes spinner-wrap {
				    100% {
				        -ms-transform: rotate(360deg);
				        -webkit-transform: rotate(360deg);
				        transform: rotate(360deg);
				    }
				}
				@keyframes spinner-wrap {
				    100% {
				        -ms-transform: rotate(360deg);
				        -webkit-transform: rotate(360deg);
				        transform: rotate(360deg);
				    }
				}
				@-webkit-keyframes spinner_one {
				    50% {
				        -ms-transform: translate(20px,20px);
				        -webkit-transform: translate(20px,20px);
				        transform: translate(20px,20px);
				    }
				}
				@keyframes spinner_one {
				    50% {
				        -ms-transform: translate(20px,20px);
				        -webkit-transform: translate(20px,20px);
				        transform: translate(20px,20px);
				    }
				}
				@-webkit-keyframes spinner_two {
				    50% {
				        -ms-transform: translate(-20px,20px);
				        -webkit-transform: translate(-20px,20px);
				        transform: translate(-20px,20px);
				    }
				}
				@keyframes spinner_two {
				    50% {
				        -ms-transform: translate(-20px,20px);
				        -webkit-transform: translate(-20px,20px);
				        transform: translate(-20px,20px);
				    }
				}
				@-webkit-keyframes spinner_three {
				    50% {
				        -ms-transform: translate(20px,-20px);
				        -webkit-transform: translate(20px,-20px);
				        transform: translate(20px,-20px);
				    }
				}
				@keyframes spinner_three {
				    50% {
				        -ms-transform: translate(20px,-20px);
				        -webkit-transform: translate(20px,-20px);
				        transform: translate(20px,-20px);
				    }
				}
				@-webkit-keyframes spinner_four {
				    50% {
				        -ms-transform: translate(-20px,-20px);
				        -webkit-transform: translate(-20px,-20px);
				        transform: translate(-20px,-20px);
				    }
				}
				@keyframes spinner_four {
				    50% {
				        -ms-transform: translate(-20px,-20px);
				        -webkit-transform: translate(-20px,-20px);
				        transform: translate(-20px,-20px);
				    }
				}
				";
				break;
		}

        echo '<style type="text/css">', $css, '</style>';
        echo '<noscript><style>body{overflow-y: auto;} .page-wrap {opacity: 1;} .site-header, .site-header.navbar-lateral{opacity: 1;} .loading-screen {display: none;}</style></noscript>';

	}
endif;

add_action( 'wp_print_styles', 'startapp_preloader_styles', 5, 0 );

if ( ! function_exists( 'startapp_preloader_scripts' ) ) :
	/**
	 * Add Page Preloader scripts to <head>
	 *
	 * @hooked wp_print_scripts
	 */
	function startapp_preloader_scripts() {
		if ( ! startapp_is_preloader() || is_admin() ) {
			return;
		}

		?>
		<script type="text/javascript">
			(function () {
				window.onload = function () {
					var body = document.querySelector( "body" );
					var header = body.querySelector( ".site-header" );
					var preloader = body.querySelector( ".loading-screen" );
					var page = body.querySelector( ".page-wrap" );
					body.style.overflowY = "auto";
					preloader.classList.add( "loading-done" );
					header.classList.add( "loading-done" );
					page.classList.add( "loading-done" );
				};
			})();
		</script>
		<?php
	}
endif;

add_action( 'wp_print_scripts', 'startapp_preloader_scripts', 5, 0 );

if ( ! function_exists( 'startapp_the_preloader' ) ) :
	/**
	 * Displays Page Preloader Markup
	 *
	 * @hooked startapp_header_before
	 * @see    header.php
	 */
	function startapp_the_preloader() {
		if ( ! startapp_is_preloader() ) {
			return;
		}

		$type = esc_attr( startapp_get_option( 'general_preloader_spinner_type', 'spinner1' ) );
		switch($type) {
			case 'spinner2':
				$spinner = '
				<div class="spinner-wrap">
					<div class="spinner" id="spinner_one"></div>
					<div class="spinner" id="spinner_two"></div>
					<div class="spinner" id="spinner_three"></div>
					<div class="spinner" id="spinner_four"></div>
				</div>
				';
				break;

			case 'spinner3':
				$spinner = '
				<div class="spinner-wrap">
					<div class="spinner"></div>
				</div>
				';
				break;

			case 'spinner4':
				$spinner = '
				<div class="spinner-wrap">
					<div class="spinner" id="spinner_one"></div>
					<div class="spinner" id="spinner_two"></div>
					<div class="spinner" id="spinner_three"></div>
				</div>
				';
				break;

			case 'spinner5':
				$spinner = '
				<div class="spinner-wrap">
					<div class="spinner" id="spinner_one"></div>
					<div class="spinner" id="spinner_two"></div>
					<div class="spinner" id="spinner_three"></div>
					<div class="spinner" id="spinner_four"></div>
				</div>
				';
				break;

			case 'spinner6':
				$spinner = '
				<div class="spinner-wrap">
					<div class="spinner"></div>
					<div class="spinner"></div>
					<div class="spinner"></div>
					<div class="spinner"></div>
					<div class="spinner"></div>
					<div class="spinner"></div>
					<div class="spinner"></div>
					<div class="spinner"></div>
					<div class="spinner"></div>
					<div class="spinner"></div>
				</div>';
				break;

			case 'spinner7':
				$spinner = '
				<div class="spinner-wrap">
					<div class="spinner" id="spinner_one"></div>
					<div class="spinner" id="spinner_two"></div>
					<div class="spinner" id="spinner_three"></div>
					<div class="spinner" id="spinner_four"></div>
				</div>';
				break;

			case 'spinner1':
			default:
				$spinner = '
				<div class="spinner-wrap">
					<div class="spinner" id="spinner_one"></div>
					<div class="spinner" id="spinner_two"></div>
					<div class="spinner" id="spinner_three"></div>
					<div class="spinner" id="spinner_four"></div>
				</div>
				';
				break;
		}

		$spinner = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $spinner );

		printf( '<div class="loading-screen">%s</div>', $spinner );
	}
endif;

add_action( 'startapp_header_before', 'startapp_the_preloader', 1 );
