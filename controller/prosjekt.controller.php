<?php

require_once('UKM/samtykke/write.class.php');
require_once('UKM/samtykke/prosjekt.class.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['save'] == 'prosjekt' ) {
	if( $_GET['prosjekt'] == 'new' ) {
		$prosjekt = write_samtykke::createProsjekt( $_POST['tittel'] );
		$_GET['prosjekt'] = $prosjekt->getId();
	} else {
		$prosjekt = new samtykke_prosjekt( $_POST['id'] );
	}
	$prosjekt->setTittel( $_POST['tittel'] );
	$prosjekt->setSetning( $_POST['setning'] );
	$prosjekt->setVarighet( $_POST['varighet'] );
	$prosjekt->setBeskrivelse( $_POST['beskrivelse'] );
	
	write_samtykke::saveProsjekt( $prosjekt );

}


if( isset( $_GET['prosjekt'] ) ) {
	$TWIGdata['id'] = $_GET['prosjekt'];
	
	if( is_numeric( $_GET['prosjekt'] ) ) {
		$TWIGdata['prosjekt'] = new samtykke_prosjekt( $_GET['prosjekt'] );
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
		$prosjekter[] = new samtykke_prosjekt( $row );
	}
	
	$TWIGdata['prosjekter'] = $prosjekter;
}