<?php

use UKMNorge\Samtykke;
use UKMNorge\Samtykke\Prosjekt;
use UKMNorge\Samtykke\Write;
use SMS;
use UKMNorge\Database\SQL\Query;
use UKMNorge\Samtykke\Request;

require_once('UKM/Autoloader.php');
require_once('UKM/sms.class.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( $_GET['samtykke'] == 'request' ) {
		$VIEW = 'samtykke/request';

		// Sørg for at prosjektet er låst
		$prosjekt = new Prosjekt( $_GET['prosjekt'] );
		Write::lockProsjekt( $prosjekt );

		// Meldingen som skal sendes
		$melding = $_POST['melding-common'];
		
		// Lenker
		$lenker = [];
		$lenker[] = [
			'type'	=> $_POST['type-1'],
			'url'	=> $_POST['url-1'],
		];

		$mottakere = [];
		for( $i=1; $i<11; $i++ ) {
			if( !empty( $_POST['fornavn-'. $i] ) && !empty( $_POST['mobil-'. $i ] ) ) {
				$mottaker = Write::createRequest( 
					$prosjekt, 
					$melding,
					$lenker,
					$_POST['fornavn-'. $i ],
					$_POST['etternavn-'. $i ],
					$_POST['mobil-'. $i ]
				);
				$mottakere[] = $mottaker;
				
				$TWIGdata['mottakere'] = $mottakere;
				
				// SEND SMS
				if( UKM_HOSTNAME == 'ukm.dev' ) {
					echo '<h3>SMS-debug</h3>'.
						'<b>TEXT: </b>'. $mottaker->getMelding() .' <br />'.
						'<b>TO: </b>'. $mottaker->getMobil();
					$mottaker->setAttr('sent', true );
				} else {
					require_once('UKM/sms.class.php');
					$sms = new SMS('samtykke', get_current_user_id() );
					$sms->text( $mottaker->getMelding() )
						->to( $mottaker->getMobil() )
						->from('UKMNorge')
						->ok()
						;
					$report = $sms->report();
					if( is_numeric( $report ) ) {
						$mottaker->setAttr('sent', true );
					} else {
						$mottaker->setAttr('sent', false );
						$mottaker->setAttr('sendError', $report );
					}
				}
			}
		}
	}
}



if( isset( $_GET['samtykke'] ) ) {
	$TWIGdata['id'] = $_GET['samtykke'];
	if( $_GET['samtykke'] == 'new' ) {
		$VIEW = 'samtykke/form';
	} else {
		$sql = new Query("
			SELECT * 
			FROM `samtykke_request`
			WHERE `prosjekt` = '#prosjekt'
			",
			[
				'prosjekt' => $_GET['prosjekt']
			]
		);
		$sql->charset('UTF-8');
		$res = $sql->run();
		$requests = [];
		while( $row = Query::fetch( $res ) ) {
			$requests[] = new Request( $row );
		}
		$TWIGdata['requests'] = $requests;
	}
}

UKMsamtykke::addViewData('id', $_GET['prosjekt']);
UKMsamtykke::addViewData('prosjekt', new Prosjekt( $_GET['prosjekt'] ) );