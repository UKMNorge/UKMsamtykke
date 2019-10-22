<?php

use UKMNorge\Database\SQL\Query;
use UKMNorge\Samtykke\Prosjekt;
use UKMNorge\Samtykke\Request;

require_once('UKM/Autoloader.php');

$sql = new Query("
    SELECT * 
    FROM `samtykke_request`
    WHERE `prosjekt` = '#prosjekt'
    ",
    [
        'prosjekt' => $_GET['prosjekt']
    ]
);
$res = $sql->run();
$requests = [];
while( $row = Query::fetch( $res ) ) {
    $requests[] = new Request( $row );
}
UKMsamtykke::addViewData('requests', $requests);
UKMsamtykke::addViewData('prosjekt', new Prosjekt( $_GET['prosjekt'] ) );