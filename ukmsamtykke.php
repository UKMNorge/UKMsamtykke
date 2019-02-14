<?php
/* 
Plugin Name: UKM Samtykke
Plugin URI: http://www.ukm-norge.no
Description: Innhenting av samtykke
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://mariusmandal.no
*/

require_once('UKM/wp_modul.class.php');

class UKMsamtykke extends UKMWPmodul {
    public static $action = 'prosjekter';
    public static $path_plugin = null;

    /**
     * Register hooks
     */
    public static function hook() {
        add_action('network_admin_menu', ['UKMsamtykke','meny']);
    }

    /**
     * Add menu
     */
    public static function meny() {
        $page = add_menu_page(
            'UKM Norge Samtykke', 
            'Samtykke', 
            'superadmin', 
            'UKMsamtykke',
            ['UKMsamtykke','renderAdmin'], 
            '//ico.ukm.no/check-menu.png',
            401
        );
        add_action( 
            'admin_print_styles-' . $page, 
            ['UKMsamtykke', 'scripts_and_styles']
        );
    }

    public static function scripts_and_styles() {
        $path = str_replace('http:','https:', WP_PLUGIN_URL);
        wp_enqueue_style('UKMwp_dashboard_css');
        wp_enqueue_script('WPbootstrap3_js');
        wp_enqueue_style('WPbootstrap3_css');
        wp_enqueue_style('UKMsamtykke_css', plugin_dir_url( __FILE__ ) .'ukmsamtykke.css' );
        wp_enqueue_script('UKMsamtykke_js', plugin_dir_url( __FILE__ ) .'ukmsamtykke.js' );
    }
}

## HOOK MENU AND SCRIPTS
UKMsamtykke::init( __DIR__ );
UKMsamtykke::hook();


/*
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
	
	echo TWIG( $VIEW .'.html.twig', $TWIGdata, dirname(__FILE__), true);
}
*/