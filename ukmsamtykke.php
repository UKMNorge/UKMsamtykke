<?php
/* 
Plugin Name: UKM Samtykke
Plugin URI: http://www.ukm-norge.no
Description: Innhenting av samtykke
Author: UKM Norge / M Mandal 
Version: 0.1 
Author URI: http://mariusmandal.no
*/

add_action('network_admin_menu', 'UKMsamtykke_menu');

function UKMsamtykke_menu() {
	$page = add_menu_page('UKM Norge Samtykke', 'Samtykke', 'superadmin', 'UKMsamtykke','UKMsamtykke', '//ico.ukm.no/check-menu.png',401);

	add_action( 'admin_print_styles-' . $page, 'UKMsamtykke_scripts_and_styles' );
}


function UKMsamtykke() {
#	$blog_id = get_blog_id_from_url( UKM_HOSTNAME, '/samtykke/' );
#	var_dump( $blog_id );
	
	$TWIGdata = [
		'page' => $_GET['page'],
	];
	
	try {
		require_once('controller/prosjekt.controller.php');
		if( isset( $_GET['samtykke'] ) ) {
			$VIEW = 'samtykke/prosjekt';
			require_once('controller/samtykke.controller.php');
		} elseif( isset( $_GET['prosjekt'] ) ) {
			$VIEW = 'prosjekt/form';
		} else {
			$VIEW = 'prosjekt/liste';
		}
	} catch( Exception $e  ) {
		$TWIGdata['message'] = $e->getMessage();
		$TWIGdata['code'] = $e->getCode();
		$VIEW = 'exception';
	}
	
#	require_once('controller/layout.controller.php');
#	require_once('controller/list_'. $TWIGdata['tab_active'] .'.controller.php' );
	
	echo TWIG( $VIEW .'.html.twig', $TWIGdata, dirname(__FILE__), true);
#	echo TWIGjs_simple( dirname(__FILE__) );
}

function UKMsamtykke_scripts_and_styles() {
	wp_enqueue_style('UKMwp_dashboard_css');
	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
	wp_enqueue_style('UKMsamtykke_css', WP_PLUGIN_URL . '/UKMsamtykke/ukmsamtykke.css' );
	wp_enqueue_script('UKMsamtykke_js', WP_PLUGIN_URL . '/UKMsamtykke/ukmsamtykke.js' );

}
