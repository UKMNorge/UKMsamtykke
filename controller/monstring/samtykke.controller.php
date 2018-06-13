<?php

/**
interface samtykke_sms_melding_interface {
	static function getMelding();
	static function template_values();
}
**/
class samtykke_sms_samtykke  {
	public static function getTemplateDefinition() {
		return [
			'navn' 		=> 'Personens navn',
			'mobil' 	=> 'Personens mobilnummer (8 sifre, integer)',
			'alder'	 	=> 'Personens alder',
			'kategori'	=> 'Navn på kategorien personen inngår i (under 15 osv)',
		];
	}
	
	public static function getMelding() {
		return 'Hei %navn! Viktig info til deg. Les mer her https://samtykke.ukm.no/eula/?mobil=%mobil';
	}
}



class samtykke_sms {
	
	public static function send( $melding_id, $samtykke ) {
		$melding = self::getMelding( $melding_id, $samtykke );
		return $melding ;
	}
	
	
	public static function getMelding( $id, $samtykke ) {
		$data = self::getBasicData( $samtykke );
		switch( $id ) {
			case 'samtykke':
				return self::prepare( $data, samtykke_sms_samtykke::getMelding(), samtykke_sms_samtykke::getTemplateDefinition() );
			break;
		}
		
		throw new Exception('Systemet støtter ikke meldingen `'. $id .'`');
	}
	
	public static function getBasicData( $samtykke ) {
		return [
			'navn' => $samtykke->getNavn(),
			'mobil' => $samtykke->getMobil(),
			'alder' => $samtykke->getAlder(),
			'kategori' => $samtykke->getKategori()->getNavn(),
			'kategori_id' => $samtykke->getKategori()->getId(),
		];
	}
	
	public static function prepare( $values, $melding, $template_definition ) {
		$replace = [];
		foreach( array_keys( $template_definition ) as $replace_key ) {
			if( isset( $values[ $replace_key ] ) ) {
				$replace[ '%'.$replace_key ] = $values[ $replace_key ];
			}
		}
		return str_replace( array_keys($replace), $replace, $melding );
	}

}

$samtykke = samtykke_person::getById( $_GET['id'] );

	
$message =  new stdClass();
$message->level = 'warning';
$message->body = 'Beklager, jeg prøvde '. samtykke_sms::send('samtykke', $samtykke );


$TWIGdata['message'] = $message;

$VIEW = 'monstring/liste';
