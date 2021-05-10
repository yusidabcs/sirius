<?php
namespace core\modules\personal\models\profile;
	
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
		
		//need to know the personal link
		$menu_register = \core\app\classes\menu_register\menu_register::getInstance();
		$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
		$personal_db = new \core\modules\personal\models\common\db;
		$data_verification = $personal_db->getVerificationHistory($address_book_id);

		$personal_link_id = $menu_register->getModuleLink('personal');

		$avatar = ($address_book_db->getAddressBookAvatarDetails($address_book_id));
		$address = ($address_book_db->getAddressBookAddressDetails($address_book_id));
		$pots = ($address_book_db->getAddressBookPotsDetails($address_book_id));
		if(count($avatar) == 0){
            $this->setViewVariables('show_profile',false);
            return;
        }
		if(count($address) == 0){
            $this->setViewVariables('show_profile',false);
            return;
        }
		if(count($pots) == 0){
            $this->setViewVariables('show_profile',false);
            return;
        }

		if(empty($personal_link_id))
		{
			$msg = "Please set a page in menu for personal to continue!";
			throw new \RuntimeException($msg);
		}
		
		$personal_link = '/'.$personal_link_id.'/';
		
		$this->setViewVariables('personal_link',$personal_link);
		
		$profile_common = new \core\modules\personal\models\common\common;
		$profile_info = $profile_common->getProfileInfo($address_book_id);
		
		$this->setViewVariables('profile_info',$profile_info);
		$this->setViewVariables('data_verification',$data_verification);
		$this->setViewVariables('show_profile',true);
		$this->setViewJs('main');
		return;
	}
	
}
?>