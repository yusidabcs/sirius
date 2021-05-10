<?php

	//check get information
	if( isset($_GET['f']) && !empty($_GET['f']) )
	{		
		require_once 'main.php';
        require_once('vendor/autoload.php');

		//ok get the right class
		$module = strtolower(trim($_GET['f']));
		$ajax_module_dir = DIR_MODULES.'/'.$module.'/ajax';
		
		if( is_dir($ajax_module_dir) )
		{
			    
			    //find the right file ... if in doubt use 'main'
			if(!empty($_GET['o']))
			{
				$options_a = explode('/', $_GET['o']);
				
				$filename = $options_a[0];
				array_shift($options_a);
				$fileOptions = $options_a;
			} else {
				$filename = 'main';
				$fileOptions = array();
			}
			session_start();

			spl_autoload_extensions(spl_autoload_extensions().",.class.php,.ajax.php");
			spl_autoload_register();

			$ajax_file = $ajax_module_dir.'/'.$filename.'.ajax.php';
			
			//now run the ajax file
			if(is_readable($ajax_file))
			{
				$ajax_ns = NS_MODULES.'\\'.strtolower(trim($_GET['f'])).'\\ajax\\'.$filename;

				define('DIR_MODULE',DIR_MODULES.'/'.$module);
				define('MODULE',$module);

				try {
					$ajax_class_obj = new $ajax_ns($fileOptions);
					$out = $ajax_class_obj->run();
					echo $out;
				} catch (Exception $e) {
					//process the error
					$htmlOutput = new \core\app\classes\html\htmlmsg($e,DEBUG);
					header("HTTP/1.0 500 Internal Error");
					echo $htmlOutput->getHtmlOutput();
					exit();
				}
			} else if(DEBUG) {
				echo "Hmmm, no module ajax file ({$ajax_file}) found; very strange.";
			}	
			
		} else if(DEBUG) {
			echo "Hmmm, no module ajax directory ({$ajax_module_dir}) found; very strange.";
		}
		
	} else if(DEBUG) {
		echo 'God said "let there be light" but you are still in the dark :-(';
	}
	
	exit();	
?>