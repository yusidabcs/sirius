<?php
namespace core\app\classes\paginate;

final class paginate
{
	private $_session_name = '';

	private $_sql = '';
	
	private $_join = '';
	private $_where = '';
	private $_orderby = '';
	private $_search_type = '';
	private $_search_text = '';

	private $_page_on;
	private $_total_records;
	private $_total_pages;
	private $_page_start_record;
	private $_page_last_record;
	private $_previous_page;
	private $_next_page;
	private $_last_page;
	private $_pagination_number;
		
	public function __construct($db_location,$table,$distinct=false)
	{
		//set the db & register so it can be used in this class
		$this->db = \core\app\drivers\db\db_mysql::getInstance($db_location);
		
		//check to make table exists
		if(!$this->db->tableExists($table))
		{
			$msg = "The table '{$table}' does not exist in the '{$db_location}' database!";
	    	throw new \RuntimeException($msg);
		}
		
		//set up the begining of the sql statement
		if($distinct)
		{
			$this->_sql = "SELECT COUNT(DISTINCT `{$distinct}`) FROM `{$table}` ";
		} else {
			$this->_sql = "SELECT COUNT(*) FROM `{$table}` ";
		}
		
		//set the pagination
		$this->_pagination_number = PAGINATION_NUMBER;
		
		return;
	}
	
	public function setSessionInfo($module_id,$model,$model_id)
	{
		//set the session name
		$this->_session_name = $module_id.'_'.$model.'_'.$model_id;
		
		//set page On
		if( isset($_SESSION['paginate'][$this->_session_name]['page_on']) && $_SESSION['paginate'][$this->_session_name]['page_on'] > 0 )
		{
			$this->_page_on = $_SESSION['paginate'][$this->_session_name]['page_on'];
		} else {
			$this->_page_on = 1;
		}
		
		//set join
		if( !empty($_SESSION['paginate'][$this->_session_name]['join']))
		{
			$this->_join = $_SESSION['paginate'][$this->_session_name]['join'];
		} else {
			$this->_join = '';
		}
		
		//set where
		if( !empty($_SESSION['paginate'][$this->_session_name]['where']))
		{
			$this->_where = $_SESSION['paginate'][$this->_session_name]['where'];
		} else {
			$this->_where = '';
		}
		
		//set orderby
		if( !empty($_SESSION['paginate'][$this->_session_name]['orderby']))
		{
			$this->_orderby = $_SESSION['paginate'][$this->_session_name]['orderby'];
		} else {
			$this->_orderby = '';
		}
		
		//set search type
		if( !empty($_SESSION['paginate'][$this->_session_name]['search_type']))
		{
			$this->_search_type = $_SESSION['paginate'][$this->_session_name]['search_type'];
		} else {
			$this->_search_type = '';
		}
		
		//set search text
		if( !empty($_SESSION['paginate'][$this->_session_name]['search_text']))
		{
			$this->_search_text = $_SESSION['paginate'][$this->_session_name]['search_text'];
		} else {
			$this->_search_text = '';
		}
		
		return;
	}
	
	public function setPageOn($page_on)
	{
		settype($page_on, 'integer');
		
		if($page_on > 0)
		{
			$this->_page_on = $page_on;
		} else {
			$msg = "Pagination error - page on must be a number greater than 0'!";
	    	throw new \RuntimeException($msg);
		}

		return;
	}
	
	public function setJoin($join_statement)
	{
		//used only if the JOIN will make a difference to the count
		$this->_join =  $join_statement;
		return;
	}
	
	public function setWhere($where_statement)
	{
		$this->_where = $where_statement;
		return;
	}
	
	public function setOrderby($orderby_statement)
	{
		$this->_orderby = $orderby_statement;
		return;
	}
	
	public function setSearchType($search_type)
	{
		$this->_search_type = $search_type;
		return;
	}
	
	public function setSearchText($search_text)
	{
		$this->_search_text = $search_text;
		return;
	}
	
	//just in case we every want over-write the default
	public function setPagination($pagination_number) 
	{
		settype($pagination_number, 'integer');
		
		if($pagination_number > 0)
		{
			$this->_pagination_number = $pagination_number;
		}
		
		return;
	}

	public function getSqlStatement()
	{
		//make the statement from the pieces
		$statement = $this->_sql.' '.$this->_join.' '.$this->_where.' '.$this->_orderby;
		//remove any returns and multiple spaces
		$sql = preg_replace('/[ \t]+/', ' ', preg_replace('/\s*$^\s*/m', ' ', $statement));
		return $sql;
	}
	
	public function getPageOn()
	{
		return $this->_page_on;
	}
	
	public function getTotalRecords()
	{
		return $this->_total_records;
	}
	
	public function getTotalPages()
	{
		return $this->_total_pages;
	}
	
	public function getPageStartRecord()
	{
		return $this->_page_start_record;
	}
	
	public function getPageLastRecord()
	{
		return $this->_page_last_record;
	}
	
	public function getStartPage()
	{
		return $this->_start_page;
	}
	
	public function getPreviousPage()
	{
		return $this->_previous_page;
	}
	
	public function getNextPage()
	{
		return $this->_next_page;
	}
	
	public function getLastPage()
	{
		return $this->_last_page;
	}
	
	public function getPaginationNumber()
	{
		return $this->_pagination_number;
	}
	
	public function getJoin()
	{
		return $this->_join;
	}
	
	public function getWhere()
	{
		return $this->_where;
	}
	
	public function getOrderby()
	{
		return $this->_orderby;
	}
	
	public function getSearchType()
	{
		return $this->_search_type;
	}
	
	public function getSearchText()
	{
		return $this->_search_text;
	}
	
	public function getPaginationInfo()
 	{
	 	$this->processPaginationInfo();
	 	
	 	$out = array();
	 	
	 	$out['join'] = $this->_join;
	 	$out['where'] = $this->_where;
	 	$out['orderby'] = $this->_orderby;
	 	$out['search_type'] = $this->_search_type;
	 	$out['search_text'] = $this->_search_text;
	 	
	 	$out['page_on'] = $this->_page_on;
	 	$out['total_records'] = $this->_total_records;
	 	$out['total_pages'] = $this->_total_pages;
	 	$out['page_start_record'] = $this->_page_start_record;
	 	$out['page_last_record'] = $this->_page_last_record;
	 	$out['start_page'] = $this->_start_page;
	 	$out['previous_page'] = $this->_previous_page;
	 	$out['next_page'] = $this->_next_page;
	 	$out['last_page'] = $this->_last_page;
	 	$out['pagination_number'] = $this->_pagination_number;
	 	
	 	//add in the link to the standard paginate nav view file
	 	$navFile = DIR_BASE.'/core/app/classes/paginate/paginate_std_nav.view.php';
	 	$out['paginate_standard_nav_file'] = $navFile;
	 	
	 	//add in the link to the standard paginate search view file
	 	$searchFile = DIR_BASE.'/core/app/classes/paginate/paginate_std_search.view.php';
	 	$out['paginate_standard_search_file'] = $searchFile;
	 	
	 	//required for the search bar
	 	$paginateJsFile = '/core/app/classes/paginate/paginate.js';
	 	$out['paginate_JS_File'] = $paginateJsFile;
	 	
	 	return $out;
 	}
 	
	private function processPaginationInfo()
	{
		if(empty($this->_session_name))
		{
			$msg = "Pagination error - you need to set the session name first'!";
	    	throw new \RuntimeException($msg);
		}
		
		//run the query
		$sql = $this->getSqlStatement();
		
		$stmt = $this->db->prepare($sql);
		$stmt->bind_result($full_record_count);
		$stmt->execute();
		$stmt->store_result();
		$stmt->fetch();
		$stmt->free_result();
		$stmt->close();
		
		//total records
		$this->_total_records = $full_record_count;
		
		//total pages
		$this->_total_pages = ceil($full_record_count/$this->_pagination_number);
		
		//If page on is higher than the total pages then just reset to page 1
		if($this->_page_on > $this->_total_pages)
		{
			$this->_page_on = 1;
		}
		
		//start and last page record
		if($full_record_count < $this->_pagination_number)
		{
			$this->_page_last_record = $full_record_count -1;
			$this->_page_start_record = 0;
		} else {
			if($this->_total_pages == $this->_page_on)
			{
				$this->_page_last_record = $full_record_count - 1;
				$this->_page_start_record = $this->_pagination_number * $this->_page_on - $this->_pagination_number;
			} else {				
				$this->_page_last_record = $this->_pagination_number * $this->_page_on - 1;
				$this->_page_start_record = $this->_page_last_record - $this->_pagination_number + 1;
			}
		}

		//first
		if($this->_page_on > 1)
		{
			$this->_start_page = 1;
		} else {
			$this->_start_page = false;
		}
		
		//prev	
		$previous = $this->_page_on - 1;
		if($previous > 1)
		{
			$this->_previous_page = $previous;
		} else {
			$this->_previous_page = false;
		}
		
		//next
		$next = $this->_page_on + 1;
		
		if($next < $this->_total_pages)
		{
			$this->_next_page = $next;
		} else {
			$this->_next_page = false;
		}
		
		//last
		if($this->_total_pages == $this->_page_on)
		{
			$this->_last_page = false;
		} else {
			$this->_last_page = $this->_total_pages;
		}
		
		//set the session so we remember for the future
		$_SESSION['paginate'][$this->_session_name]['page_on'] = $this->_page_on;
		$_SESSION['paginate'][$this->_session_name]['join'] = $this->_join;
		$_SESSION['paginate'][$this->_session_name]['where'] = $this->_where;
		$_SESSION['paginate'][$this->_session_name]['orderby'] = $this->_orderby;
		$_SESSION['paginate'][$this->_session_name]['search_type'] = $this->_search_type;
		$_SESSION['paginate'][$this->_session_name]['search_text'] = $this->_search_text;
		
		return;			
	}
	
	//clear this pagination information
	private function _clearSession()
	{	
		unset($_SESSION['paginate'][$this->_session_name]);
		return;
	}
		
}
?>