<?php
namespace core\modules\job_application\models\listjob;

/**
 * Final model class.
 *
 * @final
 * @extends		module_model
 * @package 	profile
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 July 2017
 */
final class model extends \core\app\classes\module_base\module_model {

	protected $model_name = 'listjob';
	protected $processPost = true;
	
	public function __construct()
	{
		parent::__construct();
        return;
	}
	
	//required function
	protected function main()
	{
		$this->authorize();
		$this->mode = 'personal';
		$this->address_book_id = '';
		// check if there is option id, if there is then it should be applying for another person
		if(!empty($this->page_options[0]))
		{
			if (is_numeric($this->page_options[0]))
			{
				//check user security level        
				if((isset($_SESSION['entity']) &&  $_SESSION['entity']['user_security_level'] >= $this->system_register->getModuleSecurityLevel(MODULE,'security_admin')) || ( isset($_SESSION['user_security_level']) && $this->system_register->getModuleSecurityLevel(MODULE,'security_admin') <= $_SESSION['user_security_level'] ))
 				{
					$param_id = $this->page_options[0];
					$address_book_db = \core\modules\address_book\models\common\address_book_db::getInstance();
					if ($address_book_db->checkAddressID($param_id))
					{
						$this->mode = 'recruitment';
						$this->address_book_id = $param_id;
					}else{	
						$msg = "No data found with that address book id";
						throw new \RuntimeException($msg);
					}
					
				}else{
					$msg = "Only admin can access this feature!";
					throw new \RuntimeException($msg);
				}
			}else{
				$msg = "Wrong address book id parameter format";
				throw new \RuntimeException($msg);
			}
		}
		
		$category_db = new \core\modules\job\models\common\job_category_db();


        $job_db = new \core\modules\job\models\common\db();
		$this->categories = $category_db->getAll();

		$profil_common = new \core\modules\personal\models\common\common;
		$ab_id = ($this->mode == 'recruitment')? $this->address_book_id : $_SESSION['address_book_id'];
		$qualification = $profil_common->checkJobQualification($ab_id);
		$demand = $job_db->getJobWithDemand();
		if(count($demand)>0) {
			$str_demand = implode("','",$demand);
			$str_demand = "'".$str_demand."'";
			$jobs = $job_db->getCustomizedAllJobSpeedy($qualification,$str_demand);
		} else {
			$jobs=array();
		}
        $this->jobs = [];
		$this->visible = [];
		foreach ($this->categories as $index => $category) 
		{
            $this->jobs[$category['job_speedy_category_id']] = array_filter($jobs, function ($job) use ($category){
					
                    return $job['job_speedy_category_id'] == $category['job_speedy_category_id'];
				});
			//check if there is job associated with category
			if ( !empty($this->jobs[$category['job_speedy_category_id']]) )
			{
				$files = $category_db->getFiles($category['job_speedy_category_id']);
				foreach ($files as $file)
				{
					$this->categories[$index]['banner'] = $file['model_code'] == 'banner' ? $file['filename'] : '';
				}
				//set available job categories index
				$this->visible[] = $this->categories[$index]['job_speedy_category_id'];
				$this->visible[] = $this->categories[$index]['parent_id'];
			}
		}
		//remove categories which doesn't have jobs data
		$this->categories = array_filter($this->categories,function ($category){
			return in_array($category['job_speedy_category_id'],$this->visible);
		});
		$this->jobs = array_filter($this->jobs);
		$this->defaultView();
		return;
	}
	
	protected function defaultView()
	{
		$this->view_variables_obj->setViewTemplate('listjob');
		return;
	}
	
	//required function
	protected function setViewVariables()
	{
		$this->view_variables_obj->useDatatable();
		$this->view_variables_obj->addViewVariables('address_book_id', $this->address_book_id);
		$this->view_variables_obj->addViewVariables('mode', $this->mode);

        $this->view_variables_obj->addViewVariables('categories', $this->categories);
        $this->view_variables_obj->addViewVariables('jobs', $this->jobs);
        $this->view_variables_obj->addViewVariables('baseURL', $this->baseURL);

        return;
	}
		
}
?>