<?php

use UKMNorge\Samtykke;

require_once('UKM/samtykke/write.class.php');
require_once('UKM/samtykke/prosjekt.class.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['save'] == 'prosjekt' ) {
	if( $_GET['prosjekt'] == 'new' ) {
		$prosjekt = Samtykke\Write::createProsjekt( utf8_encode( $_POST['tittel'] ) );
		$_GET['prosjekt'] = $prosjekt->getId();
	} else {
		$prosjekt = new Samtykke\Prosjekt( $_POST['id'] );
	}
	$prosjekt->setTittel( utf8_encode( $_POST['tittel'] ) );
	$prosjekt->setSetning( utf8_encode( $_POST['setning'] ) );
	$prosjekt->setVarighet( utf8_encode( $_POST['varighet'] ) );
	$prosjekt->setBeskrivelse( utf8_encode( $_POST['beskrivelse'] ) );
	
	Samtykke\Write::saveProsjekt( $prosjekt );
}


if( isset( $_GET['prosjekt'] ) ) {
	$TWIGdata['id'] = $_GET['prosjekt'];
	
	if( is_numeric( $_GET['prosjekt'] ) ) {
		$TWIGdata['prosjekt'] = new Samtykke\Prosjekt( $_GET['prosjekt'] );
	}
} else {
	$sql = new SQL("
		SELECT * 
		FROM `samtykke_prosjekt`
		ORDER BY `tittel` ASC"
	);
	$res = $sql->run();
	
	$prosjekter = [];
	while( $row = SQL::fetch( $res ) ) {
		$prosjekter[] = new Samtykke\Prosjekt( $row );
	}
	
	$TWIGdata['prosjekter'] = $prosjekter;
}