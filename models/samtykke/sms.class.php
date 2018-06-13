<?php
	
class samtykke_sms {
	const LINK = 'https://samtykke.ukm.no/eula/?id=%link_id';
	const LINK_FORESATT = 'https://samtykke.ukm.no/eula/?id=%link_id&foresatt=true';
	
	public static function send( $melding_id, $samtykke ) {
		$melding = self::getMelding( $melding_id, $samtykke );
		
		self::insertMelding( $samtykke, $melding, $melding_id );
		
		if( $melding_id == 'samtykke_foresatt' ) {
			self::updateForesattSamtykke( $samtykke, $melding_id );
		} else {
			self::updateSamtykke( $samtykke, $melding_id );
		}
		
		return $melding ;
	}
	
	public static function getMelding( $id, $samtykke ) {
		$data = self::getBasicData( $samtykke );
		switch( $id ) {
			case 'samtykke':
				if( $samtykke->getKategori()->getId() == 'o15' ) {
					return self::prepare( $data, samtykke_sms_samtykke::getMelding(), samtykke_sms_samtykke::getTemplateDefinition() );
				}
				return self::prepare( $data, samtykke_sms_samtykke_u15::getMelding(), samtykke_sms_samtykke_u15::getTemplateDefinition() );
			break;
			case 'samtykke_foresatt':
				$data['fornavn'] = $samtykke->getPerson()->getFornavn();
				$data['status'] = $samtykke->getStatus()->getId() == 'godkjent' ? 'er greit': 'ikke er greit';
				return self::prepare( $data, samtykke_sms_foresatt::getMelding(), samtykke_sms_foresatt::getTemplateDefinition());
		}
		
		throw new Exception('Systemet støtter ikke meldingen `'. $id .'`');
	}
	
	public static function getBasicData( $samtykke ) {
		return [
			'link_id' => $samtykke->getMobil().'-'.$samtykke->getId(),
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
	
	public static function insertMelding( $samtykke, $melding, $type ) {
		$sql = new SQLins('samtykke_deltaker_kommunikasjon');
		$sql->add('samtykke_id', $samtykke->getId());
		$sql->add('mobil', $samtykke->getMobil());
		$sql->add('type', $type);
		$sql->add('melding', $melding);
		$res = $sql->run();
	}
	
	public static function updateSamtykke( $samtykke, $melding_id ) {
		$samtykke->setStatus('ikke_sett', $_SERVER['HTTP_CF_CONNECTING_IP']);
		$samtykke->persist();
	}
	
	public static function updateForesattSamtykke( $samtykke, $melding_id ) {
		$samtykke->setForesattStatus('ikke_sett', $_SERVER['HTTP_CF_CONNECTING_IP']);
		$samtykke->persist();
	}

}