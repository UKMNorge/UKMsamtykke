<?php

use UKMNorge\Samtykke;

require_once('UKM/samtykke/write.class.php');
require_once('UKM/samtykke/prosjekt.class.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( $_GET['prosjekt'] == 'new' ) {
		$prosjekt = Samtykke\Write::createProsjekt( $_POST['tittel'] );
		$_GET['prosjekt'] = $prosjekt->getId();
	} else {
		$prosjekt = new Samtykke\Prosjekt( $_POST['id'] );
	}
	$prosjekt->setTittel( $_POST['tittel'] );
	$prosjekt->setSetning( $_POST['setning'] );
	$prosjekt->setVarighet( $_POST['varighet'] );
	$prosjekt->setBeskrivelse( $_POST['beskrivelse'] );
	
	Samtykke\Write::saveProsjekt( $prosjekt );
}

UKMsamtykke::addViewData('id', $_GET['prosjekt']);

if( is_numeric( $_GET['prosjekt'] ) ) {
    UKMsamtykke::addViewData('prosjekt', new Samtykke\Prosjekt( $_GET['prosjekt'] ) );
}