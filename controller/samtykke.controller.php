<?php

use UKMNorge\Samtykke;

require_once('UKM/samtykke/write.class.php');
require_once('UKM/samtykke/request.class.php');
require_once('UKM/samtykke/prosjekt.class.php');

UKMsamtykke::addViewData('id', $_GET['prosjekt']);
UKMsamtykke::addViewData('prosjekt', new Samtykke\Prosjekt( $_GET['prosjekt'] ) );