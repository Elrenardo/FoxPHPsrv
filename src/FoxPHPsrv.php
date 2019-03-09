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
		$this->data      = new \stdClass();

		//Create Routing
		$this->router = new \AltoRouter();

		//Set base Path
		$buffer = explode('/',$_SERVER['PHP_SELF']);
		array_pop( $buffer );//enlever le "index.php"
		$buffer = implode('/',$buffer );
		$buffer = substr($buffer,1);//enlever le premier "/"
		$this->router_path = $buffer.'/';

		//Si on est pas à la racine 
		if( $this->router_path != '/')
			$this->router->setBasePath( $this->router_path );

		//Ajout Apelle d'un CTRL dans un CTRL via l'api
		$this->addData('exec', function( $class, $method, $params=null )
		{
			$this->classExec( $class, $method, $params );
		});

		//Create Class Route
		$this->router->map( 'GET|POST', '/[*:class]/[*:method]/[*:params]?', function( $class, $method, $params )
		{
		    $this->classExec( $class, $method, $params );
		});
		//Si la route n'a pas de paramettre
		/*$this->router->map( 'GET|POST', '/[*:class]/[*:method]', function( $class, $method )
		{
		    $this->classExec( $class, $method, null );
		});*/
	}


	//==============================================
	//==============================================
	// 
	//==============================================
	/*public function __destruct()
	{
		$this->exec();
	}*/



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
	// Redirection route vers un dossier
	//==============================================
	public function routePath( $path, $dir )
	{
		//Route d'auto routage
		$this->router->map( 'GET|POST', $path.'?[**:file]?.*', function( $file ) use (&$dir)
		{
			//si aucun fichier selectionné
			if(gettype($file) != 'string')
				$file = 'index.html';


			//verifier que le fichier existe
			$path_file = $this->dir_main.'/'.$dir.'/'.$file;
			if( !file_exists( $path_file ))
			{
				header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
				exit('404 Not Found');
			}

			//Get header file
			$url = 'http://'.$_SERVER['SERVER_NAME'].'/'.$this->router_path.'/'.$dir.'/'.$file;
			$ret =  get_headers( $url );
			foreach ($ret as $key => $value){
				header($value);
			}

			//Afficher fichier
			print file_get_contents( $path_file );
		});
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
			return;
		}
		// no route was matched
		header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
		exit('404 Not Found');
	}



	//==============================================
	//==============================================
	//Ajouter un nouveau Date
	//==============================================
	public function addData( $name, $obj )
	{
		$this->data->{$name} = $obj;
	}



	//==============================================
	//==============================================
	//Fonction private exec class
	//==============================================
	private function classExec( $class, $method, $params = null )
	{
		$url = $this->dir_main.'/'.$this->dir_class.'/'.$class.'.php';

		//Verifier si le fichier class exist
	    if( file_exists($url))
	    {
	    	//Inclure le fichier
	    	require_once $url;
	    	if(!class_exists($class))
	    	{
	    		header( $_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Server Error');
	    		exit('500 Class name is not class name file');
	    	}

	    	//Verifier que la bariable soie bien un string
	    	if(gettype($params) != 'string')
	    		$params = null;

	    	//Création de la class
	    	$obj = new $class( $this->data );

	    	//verifier si la methode existe
	    	if(!method_exists($obj,$method))
	    	{
	    		header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Method Not Found');
	    		exit('404 Method Not Found');
	    	}

	    	//Exécution de la methode du controlleur
	    	return $obj->{$method}( $this->data, $params );
	    }
	    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Class Not Found');
	    exit('404 Class Not Found');
	}
}
