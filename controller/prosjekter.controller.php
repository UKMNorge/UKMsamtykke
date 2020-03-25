<?php

use UKMNorge\Database\SQL\Query;
use UKMNorge\Samtykke\Prosjekt;

require_once('UKM/Autoloader.php');

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

UKMsamtykke::addViewData('prosjekter', $prosjekter);