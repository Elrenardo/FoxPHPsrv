<?php


class class_test
{

	public function __construct( $data )
	{
		echo 'Construction class "class_test"<br/>';
	}


	public function main( $data, $params )
	{
		echo ' class_test -> main !<br/>';
		var_dump( $params );
	}
}