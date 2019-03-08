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
	//echo 'HOME';
	echo file_get_contents( __DIR__ .'/www/home.html');
});


//Rajoute de la route path www/ vers /
$srv->routePath( '/', 'www/' );


//Lancement ctrl
$srv->exec();