<?php
//Class de TEST

class class_test
{

	//Constructeur
	public function __construct( $data )
	{
		echo 'Construction class "class_test"<br/>';
	}

	//Test Params
	public function main( $data, $params )
	{
		echo ' class_test -> main !<br/>';
		var_dump( $params );
	}

	//Affiche hello
	public function hello( $data, $params )
	{
		echo 'Hello World';
	}

	//Appelle CTRL
	public function print( $data, $params )
	{
		$data['API::exec']('class_test','hello');
	}
}