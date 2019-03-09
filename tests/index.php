<?php
require_once __DIR__ . '/../vendor/autoload.php';
//require '../src/FoxPHPsrv.php';

use elrenardo\FoxPHPsrv;

//Création default Serveur
$srv = new FoxPHPsrv( __DIR__, 'class');


//Ajout d'une data global ( indice, value)
$srv->addData('msg','hello world');


//Test rajout d'une route en mode AltoRouter
$srv->route('/test', function( $data )
{
	//echo 'HOME';
	echo file_get_contents( __DIR__ .'/test.html');
});


//Rajoute de la route path www/ vers /
$srv->routePath( '/dir', 'www/' );

//Rajoute de la route path / vers www2/ ( /!\ La racine '/' dois toujours etre en dernier )
$srv->routePath( '/', 'www2/' );


//Lancement ctrl
$srv->exec();