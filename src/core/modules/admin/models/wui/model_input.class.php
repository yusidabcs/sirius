<?php
namespace core\modules\admin\models\wui;

/**
 * Final model_input class.
 * 
 * @final
 * @package 	admin
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class model_input extends \core\app\classes\module_base\module_model_input {

	protected $model_name = 'wui';
	
	//my variables
	protected $redirect;
	protected $nextModel;
	
	public function __construct()
	{
		parent::__construct();
		return;
	}
	
	protected function processPost()
	{
		if( !empty($_POST['action']) )
		{
			switch ($_POST['action']) 
			{
				case 'update_site_interface':
			        $this->_update_site_interface();
			        break;
			        
			    default:
			       die("Don't do anything yet!");
			}
		} else {
			die("Don't act yet!");
		}
				
		return;
	}
	
	private function _update_site_interface()
	{
		//check nav
		$update_array['nav']['useTopNav'] = isset($_POST['useTopNav']) ? $_POST['useTopNav'] : 0;
		$update_array['nav']['topNavBar'] = isset($_POST['topNavBar']) ? $_POST['topNavBar'] : '';
		$update_array['nav']['useBottomNav'] = isset($_POST['useBottomNav']) ? $_POST['useBottomNav'] : 0;
		$update_array['nav']['bottomNavBar'] = isset($_POST['bottomNavBar']) ? $_POST['bottomNavBar'] : '';
		$update_array['nav']['mainColour'] = isset($_POST['mainColour']) ? $_POST['mainColour'] : '';
		
		//check data
		$update_array['link']['useInTopNav'] = isset($_POST['useInTopNav']) ? $_POST['useInTopNav'] : 0;
		$update_array['link']['useInBottomNav'] = isset($_POST['useInBottomNav']) ? $_POST['useInBottomNav'] : 0;
		$update_array['link']['facebook'] = isset($_POST['facebook']) ? $_POST['facebook'] : '';
		$update_array['link']['twitter'] = isset($_POST['twitter']) ? $_POST['twitter'] : '';
		$update_array['link']['instagram'] = isset($_POST['instagram']) ? $_POST['instagram'] : '';
		$update_array['link']['youtube'] = isset($_POST['youtube']) ? $_POST['youtube'] : '';
		
		//system config
		$iniFile = DIR_SECURE_INI.'/site_interface.ini';
		
		$writeIni = new \iow\app\classes\ini\write_ini();
		$writeIni->write_php_ini($update_array, $iniFile);
		
		return;
	}

	
}
?>