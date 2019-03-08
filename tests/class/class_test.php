<?php
//Class de TEST

class class_test
{

	//Constructeur
	public function __construct( &$api )
	{
		echo 'Construction class "class_test"<br/>';
	}

	//Test Params
	public function main( &$api, $params )
	{
		echo ' class_test -> main !<br/>';
		var_dump( $params );
	}

	//Affiche hello
	public function hello( &$api, $params )
	{
		echo 'Hello World';
	}

	//Appelle CTRL
	public function print( &$api, $params )
	{
		($api->exec)('class_test','hello',null);
	}
}