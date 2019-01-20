<?php
require_once __DIR__ . '/../vendor/autoload.php';
//require '../src/FoxPHPsrv.php';

use elrenardo\FoxPHPsrv;

//Création default Serveur
$srv = new FoxPHPsrv( __DIR__, 'class');


//Ajout d'une data global ( indice, value)
$srv->addData('msg','hello world');


//Test rajout d'une route en mode AltoRouter
$srv->route('/', function( $data )
{
	echo 'HOME';
});

//Lancement ctrl
$srv->exec();