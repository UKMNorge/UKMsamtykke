<?php

$monstring = new monstring_v2( get_option('pl_id') );
$TWIGdata['monstring'] = $monstring;

$u13 = new stdClass();
$u13->id 			= 'u13';
$u13->navn 			= 'Under 13 år';
$u13->beskrivelse 	= 'Foresatte må ha bekreftet informasjonen';
$u13->personer 		= []; 

$u15 = new stdClass();
$u15->id			= 'u15';
$u15->navn			= 'Under 15 år';
$u15->beskrivelse 	= 'Foresatte må ha sett informasjonen';
$u15->personer 		= [];

$o15 = new stdClass();
$o15->id			= '15o';
$o15->navn			= '15 år eller eldre';
$o15->beskrivelse 	= 'Deltakeren bør ha sett informasjonen';
$o15->personer[]	= [];

$grupper = [
	'u13' => $u13,
	'u15' => $u15,
	'15o' => $o15
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