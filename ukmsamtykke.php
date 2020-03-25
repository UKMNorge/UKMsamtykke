<?php
/* 
Plugin Name: UKM Samtykke
Plugin URI: http://www.ukm-norge.no
Description: Innhenting av samtykke
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://mariusmandal.no
*/

use UKMNorge\Wordpress\Modul;

require_once('UKM/Autoloader.php');

class UKMsamtykke extends Modul {
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
            'dashicons-welcome-view-site',
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