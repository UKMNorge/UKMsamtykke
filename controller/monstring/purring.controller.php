<?php
$samtykke = samtykke_person::getById( $_GET['id'] );

$message =  new stdClass();


/**
 * Vi dealer med foresatte
**/
if( isset( $_GET['foresatt'] ) && $_GET['foresatt'] == 'true' ) {
	if( in_array( $samtykke->getForesatt()->getStatus()->getId(), ['ikke_sett', 'ikke_svart'] ) ) {
		$message->level = 'success';
		$message->header = 'Purre-SMS sendt! <img src="//ico.ukm.no/leek-32.png" height="32" />';
		$message->body = '<div class="card">'. nl2br(samtykke_sms::send('purring_foresatt', $samtykke )) .'</div>';
	} else {
		$message->level = 'danger';
		$message->body = 'SMS ble ikke sendt, da det har blitt sendt tidligere!';
	}
}
/**
 * Vi dealer med deltakere
**/
else {
	if( in_array( $samtykke->getStatus()->getId(), ['ikke_sett', 'ikke_svart'] ) ) {
		$message->level = 'success';
		$message->header = 'Purre-SMS sendt! <img src="//ico.ukm.no/leek-32.png" height="32" />';
		$message->body = '<div class="card">'. nl2br(samtykke_sms::send('purring', $samtykke )) .'</div>';
	} else {
		$message->level = 'danger';
		$message->body = 'SMS ble ikke sendt, da det har blitt sendt tidligere!';
	}
}



$TWIGdata['message'] = $message;

$VIEW = 'monstring/liste';
