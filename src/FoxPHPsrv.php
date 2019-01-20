<?php

namespace elrenardo;
class FoxPHPsrv
{
	//Altorouter
	private $router;
	//Router path in serveur
	private $router_path;
	//Position des class Srv
	private $dir_class;
	//Path serveur
	private $dir_main;
	//Data partagé
	private $data;

	//==============================================
	//==============================================
	// Constructeur
	//==============================================
	public function __construct( $dir_main, $dir_class )
	{
		$this->dir_main  = $dir_main;
		$this->dir_class = $dir_class;
		$this->data      = [];

		//Create Routing
		$this->router = new \AltoRouter();

		//Set base Path
		$buffer = explode('/',$_SERVER['PHP_SELF']);
		array_pop( $buffer );//enlever le "index.php"
		$buffer = implode('/',$buffer );
		$buffer = substr($buffer,1);//enlever le premier "/"
		$this->router_path = $buffer.'/';

		$this->router->setBasePath( $this->router_path );


		//Create Class Route
		$this->router->map( 'GET|POST', '/[*:class]/[*:method]/[*:params]', function( $class, $method, $params )
		{
		    $this->classExec( $class, $method, $params );
		});
		//Si la route n'a pas de paramettre
		$this->router->map( 'GET|POST', '/[*:class]/[*:method]', function( $class, $method )
		{
		    $this->classExec( $class, $method, null );
		});
	}



	//==============================================
	//==============================================
	// Ajouter une route manuel
	//==============================================
	public function route( $path, $callback )
	{
		$this->router->map( 'GET|POST', $path, $callback);
	}




	//==============================================
	//==============================================
	// Exécuter la route
	//==============================================
	public function exec()
	{
		//Get Router Use
		$match = $this->router->match();

		// call closure or throw 404 status
		if( $match && is_callable( $match['target'] ) )
		{
			$match['params']['data'] = $this->data;//Add DATA A LA ROUTE
			call_user_func_array( $match['target'], $match['params'] ); 
		}
		else
		{
			// no route was matched
			header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
		}
	}



	//==============================================
	//==============================================
	//Ajouter un nouveau Date
	//==============================================
	public function addData( $name, $obj )
	{
		$this->data[ $name ] = $obj;
	}



	//==============================================
	//==============================================
	//Fonction private exec class
	//==============================================
	private function classExec( $class, $method, $params )
	{
		$url = $this->dir_main.'/'.$this->dir_class.'/'.$class.'.php';

	    if( file_exists($url))
	    {
	    	require $url;
	    	$obj = new $class( $this->data );
	    	if( method_exists($obj,$method))
	    		$obj->{$method}( $this->data, $params );
	    	else
	    		header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Method Not Found');
	    }
	    else
	    	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Class Not Found');
	}
}