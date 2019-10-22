<?php
use UKMNorge\Samtykke;

require_once('UKM/samtykke/write.class.php');
require_once('UKM/samtykke/prosjekt.class.php');

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

UKMsamtykke::addViewData('prosjekter', $prosjekter);