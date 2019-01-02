<?php

$monstring = new monstring_v2( get_option('pl_id') );
$TWIGdata['monstring'] = $monstring;

$grupper = [
	'u13' => Kategorier::getById('u13'),
	'u15' => Kategorier::getById('u15'),
	'15o' => Kategorier::getById('o15')
];

foreach( $monstring->getInnslag()->getAll() as $innslag ) {
	foreach( $innslag->getPersoner()->getAll() as $person ) {
		$samtykke = new samtykke_person( $person, $monstring->getSesong() );

		$grupper[ $samtykke->getKategori()->getId() ]->personer[ $samtykke->getNavn() . '-'. $samtykke->getId() ] = $samtykke;
	}
}

foreach( $grupper as $gruppe ) {
	ksort( $gruppe->personer );
}

$TWIGdata['samtykker'] = $grupper;