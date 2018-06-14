<?php

$monstring = new monstring_v2( get_option('pl_id') );
$TWIGdata['monstring'] = $monstring;

$personer = [];
foreach( $monstring->getInnslag()->getAll() as $innslag ) {
	foreach( $innslag->getPersoner()->getAll() as $person ) {
		$samtykke = new samtykke_person( $person, $monstring->getSesong() );
		
		if( $samtykke->getStatus()->getId() == 'ikke_sendt' ) {
			$samtykke->setAttr('melding', samtykke_sms::send('samtykke', $samtykke ));
		} else {
			$melding = $samtykke->getKommunikasjon()->get('samtykke');
			$samtykke->setAttr('melding', 'Samtykke-sms allerede sendt ('. $melding->getTimestamp() .')');
		}
		$personer[] = $samtykke;
	}
}

$TWIGdata['personer'] = $personer;