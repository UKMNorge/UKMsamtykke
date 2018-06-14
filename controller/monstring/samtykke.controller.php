<?php
$samtykke = samtykke_person::getById( $_GET['id'] );

$message =  new stdClass();
if( $samtykke->getStatus()->getId() == 'ikke_sendt' ) {
	$message->level = 'success';
	$message->header = 'SMS sendt!';
	$message->body = '<div class="card">'. nl2br(samtykke_sms::send('samtykke', $samtykke )) .'</div>';
} else {
	$message->level = 'danger';
	$message->body = 'SMS ble ikke sendt, da det har blitt sendt tidligere!';
}

$TWIGdata['message'] = $message;

$VIEW = 'monstring/liste';
