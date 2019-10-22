<?php

use UKMNorge\Database\SQL\Query;
use UKMNorge\Samtykke\Prosjekt;
use UKMNorge\Samtykke\Write;

require_once('UKM/Autoloader.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['save'] == 'prosjekt' ) {
	if( $_GET['prosjekt'] == 'new' ) {
		$prosjekt = Write::createProsjekt( $_POST['tittel'] );
		$_GET['prosjekt'] = $prosjekt->getId();
	} else {
		$prosjekt = new Prosjekt( $_POST['id'] );
	}
	$prosjekt->setTittel( $_POST['tittel'] );
	$prosjekt->setSetning( $_POST['setning'] );
	$prosjekt->setVarighet( $_POST['varighet'] );
	$prosjekt->setBeskrivelse( $_POST['beskrivelse'] );
	
	Write::saveProsjekt( $prosjekt );

}


if( isset( $_GET['prosjekt'] ) ) {
	$TWIGdata['id'] = $_GET['prosjekt'];
	
	if( is_numeric( $_GET['prosjekt'] ) ) {
		$TWIGdata['prosjekt'] = new Prosjekt( $_GET['prosjekt'] );
	}
} else {
	$sql = new Query("
		SELECT * 
		FROM `samtykke_prosjekt`
		ORDER BY `tittel` ASC"
	);
	$res = $sql->run();
	
	$prosjekter = [];
	while( $row = Query::fetch( $res ) ) {
		$prosjekter[] = new Prosjekt( $row );
	}
	
	$TWIGdata['prosjekter'] = $prosjekter;
}