<?php
$samtykke = samtykke_person::getById( $_GET['id'] );
	
$message =  new stdClass();
$message->level = 'warning';
$message->body = 'Beklager, jeg prøvde '. samtykke_sms::send('samtykke', $samtykke );


$TWIGdata['message'] = $message;

$VIEW = 'monstring/liste';
