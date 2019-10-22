<?php

use UKMNorge\Samtykke;

require_once('UKM/samtykke/write.class.php');
require_once('UKM/samtykke/request.class.php');
require_once('UKM/samtykke/prosjekt.class.php');

$sql = new SQL("
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
while( $row = SQL::fetch( $res ) ) {
    $requests[] = new Samtykke\Request( $row );
}
UKMsamtykke::addViewData('requests', $requests);
UKMsamtykke::addViewData('prosjekt', new Samtykke\Prosjekt( $_GET['prosjekt'] ) );