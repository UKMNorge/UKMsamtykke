<?php
class samtykke_person_timestamp {
	var $timestamp = null;

	public function __construct( $timestamp ) {
		$this->timestamp = $timestamp;
	}
	
	public function __toString() {
		if( null == $this->timestamp ) {
			return 'ikke tilgjengelig';
		}
		return $this->timestamp->format('d.m.Y H:i:s');
	}
}