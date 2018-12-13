<?php

use UKMNorge\Samtykke;

require_once('UKM/samtykke/write.class.php');
require_once('UKM/samtykke/request.class.php');
require_once('UKM/samtykke/prosjekt.class.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( $_GET['samtykke'] == 'request' ) {
		$VIEW = 'samtykke/request';

		// Sørg for at prosjektet er låst
		$prosjekt = new Samtykke\Prosjekt( $_GET['prosjekt'] );
		Samtykke\Write::lockProsjekt( $prosjekt );

		// Meldingen som skal sendes
		$melding = utf8_encode( $_POST['melding-common'] );
		
		// Lenker
		$lenker = [];
		$lenker[] = [
			'type'	=> $_POST['type-1'],
			'url'	=> $_POST['url-1'],
		];

		$mottakere = [];
		for( $i=1; $i<11; $i++ ) {
			if( !empty( $_POST['fornavn-'. $i] ) && !empty( $_POST['mobil-'. $i ] ) ) {
				$mottaker = Samtykke\Write::createRequest( 
					$prosjekt, 
					$melding,
					$lenker,
					utf8_encode( $_POST['fornavn-'. $i ] ),
					utf8_encode( $_POST['etternavn-'. $i ] ),
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
		$sql = new SQL("
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
		while( $row = SQL::fetch( $res ) ) {
			$requests[] = new Samtykke\Request( $row );
		}
		$TWIGdata['requests'] = $requests;
	}
}