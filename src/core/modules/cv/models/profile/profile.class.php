<?php
namespace core\modules\cv\models\profile;
	
/**
 * Final survey_actual profile class.
 *
 * @final
 * @package 	personal
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 22 July 2017
 */
final class profile extends \core\app\classes\module_base\module_profile {
	
	public function __construct($address_book_id)
	{	
		parent::__construct();

        $this->setViewVariables('show_profile',true);
		$this->setViewJs('main');
		return false;
	}
	
}
?>