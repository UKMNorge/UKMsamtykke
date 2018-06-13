<?php
class samtykke_person {
	
	var $id = null;
	var $person = null;
	
	var $alder = null;
	var $kategori = null;
	var $p_id = null;
	var $mobil = null;
	var $status = null;
	var $foresatt = null;
	var $last_change = null;
	
	
	public function __construct( $person, $year ) {
		$this->person = $person;
		$row = $this->_createIfNotExists( $person, $year );
		$this->_populate( $row );
	}
	
	public static function getById( $samtykke_id ) {
		$sql = new SQL("
			SELECT `p_id`,`year`
			FROM `samtykke_deltaker`
			WHERE `id` = '#samtykke'",
			[
				'samtykke' => $samtykke_id,
			]
		);
		$row = $sql->run('array');
		
		if( !$row ) {
			throw new Exception('Beklager, kunne ikke finne samtykke #'. $samtykke_id );
		}
		
		$person = new person_v2( $row['p_id'] );
		return new samtykke_person( $person, $row['year'] );
	}
	
	public function getId() {
		return $this->id;
	}
	public function getAlder() {
		return $this->getPerson()->getAlderTall();
	}
	public function getKategori() {
		return $this->kategori;
	}
	public function getPerson() {
		return $this->person;
	}
	public function getNavn() {
		return $this->person->getNavn();
	}
	public function getMobil() {
		return $this->mobil;
	}
	public function getStatus() {
		return $this->status;
	}
	public function getForesatt() {
		return $this->foresatt;
	}
	public function getLastChange() {
		return $this->last_change;
	}


	private function _populate( $db_row ) {
		$this->id = $db_row['id'];
		$this->year = $db_row['year'];
		$this->kategori = new samtykke_person_kategori( $this->person );
		$this->p_id = $db_row['p_id'];
		$this->mobil = $db_row['mobil'];
		$this->status = new samtykke_person_status( 
			$db_row['status'], 
			$db_row['status_timestamp'], 
			$db_row['status_timestamp_ip']
		);
		$this->foresatt = new samtykke_person_foresatt( 
			$db_row['foresatt_navn'],
			$db_row['foresatt_mobil'],
			$db_row['foresatt_status'], 
			$db_row['foresatt_status_timestamp'],
			$db_row['foresatt_status_ip']
		);
		$this->last_change = $db_row['last_change'];
	}
	private function _createIfNotExists( $person, $year ) {
		$row = self::_load( $person, $year );
		
		if( !$res ) {
			$row = samtykke_person::create(
				$person,
				$year
			);
		}
		return $row;
	}
	
	private static function _load( $person, $year ) {
		$sql = new SQL("
			SELECT *
			FROM `samtykke_deltaker`
			WHERE `p_id` = '#p_id'
			AND `year` = '#season'",
			[
				'p_id' => $person->getId(),
				'season' => $year,
			]
		);
		return $sql->run('array');
	}
	
	
	
	public static function create( $person, $year ) {
		$kategori = new samtykke_person_kategori( $person );
		
		$sql = new SQLins('samtykke_deltaker');
		$sql->add('year', $year);
		$sql->add('kategori', $kategori->getId() );
		$sql->add('p_id', $person->getId() );
		$sql->add('mobil', $person->getMobil() );
#		$sql->add('status', 'ikke_sendt');
		$sql->run();
		
		$rad_id = $sql->insid();
		
		return self::_load( $person, $year );
	}
}