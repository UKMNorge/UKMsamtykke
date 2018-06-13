<?php
	
	
/**
 * SAMTYKKE FOR DE UNDER 15
**/	
class samtykke_sms_samtykke_u15 extends samtykke_sms_samtykke {
	public static function getMelding() {
		return 'Hei! Vi må ha mobilnummeret til dine foreldre/foresatte. '.
			'Trykk på lenken og legg inn nummeret sånn at vi kan sende dem viktig informasjon om UKM-festivalen. '."\r\n ".
			samtykke_sms::LINK;
	}
}

/**
 * SAMTYKKE FOR DE FRA 15 OG OPP
**/
class samtykke_sms_samtykke  {
	public static function getTemplateDefinition() {
		return [
			'link_id'	=> 'Lenke-ID for samtykkeskjema',
			'navn' 		=> 'Personens navn',
			'mobil' 	=> 'Personens mobilnummer (8 sifre, integer)',
			'alder'	 	=> 'Personens alder',
			'kategori'	=> 'Navn på kategorien personen inngår i (under 15 osv)',
		];
	}
	
	public static function getMelding() {
		return 'Hei %navn! Om det ikke er ønskelig at vi tar bilder og/eller film av deg, må vi ha beskjed.' ."\r\n".
			'Les mer og gi oss beskjed om du synes dette er greit eller ugreit på lenken nedenfor.' ."\r\n ".
			samtykke_sms::LINK;
	}
}

class samtykke_sms_foresatt {
	public static function getTemplateDefinition() {
		return [
			'link_id'	=> 'Lenke-ID for voksnes samtykkeskjema',
			'navn'		=> 'Deltakerens navn',
			'alder'		=> 'Deltakerens alder',
			'kategori'	=> 'Navn på kategorien personen inngår i (under 15 osv)',
			'status'	=> 'Deltakerens tilbakemelding på skjemaet (er greit / er ikke greit)',
			'fornavn'	=> 'Deltakerens fornavn',
		];
	}
	
	public static function getMelding() {
		return 'Hei! Om det ikke er ønskelig at vi tar bilder og/eller film av %navn, må vi ha beskjed. '. "\r\n ".
			'%fornavn har selv sagt at det %status.'. "\r\n ".
			'Les mer og gi oss beskjed på lenken nedenfor. '. "\r\n ".
			samtykke_sms::LINK_FORESATT;
	}
}
