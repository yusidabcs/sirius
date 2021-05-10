<?php
namespace core\modules\interview\models\common;

/*
 * Final interview/db class.
 *
 * @final
 * 
 */
final class pool_db extends \core\app\classes\module_base\module_db {
	
	public function __construct()
	{
        parent::__construct('local'); //sets up db connection to use local database and user_id as global protected variables
		return;
	}
	
	/**
	 * getSpeedyPoolTotal function.
	 * 
	 * Simple Total of all people 
	 *
	 * @access public
	 * @return integer
	 */
	public function getSpeedyPoolTotal()
	{
		$speedy_pool_total = 0;
		
		$sql = "SELECT
        				COUNT(`status`)
        		FROM 
        				`job_application`
        		WHERE 
        				`job_application`.`status` = 'hired'		
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($speedy_pool_total);
        $stmt->execute();
        $stmt->fetch();
        $stmt->close();
        
		return $speedy_pool_total;
	}
	
	/**
	 * getSpeedyPoolArray function.
	 * 
	 * Produces an array of the full current speedy pool by speedy job code. I included the category id so it can be matched as required.
	 *
	 * @access public
	 * @return array
	 */
	public function getSpeedyPoolArray()
    {
	    $speedy_pool_array = array();
        
        $sql = "SELECT
        				`job_application`.`job_speedy_code`,
        				`job_speedy`.`job_title`,
        				`job_speedy`.`job_speedy_category_id`
        		FROM 
        				`job_application`
        		LEFT JOIN
        				`job_speedy`
        		ON
        				`job_application`.`job_speedy_code` = `job_speedy`.`job_speedy_code`
				LEFT JOIN
        				`job_speedy_category`
        		ON
        				`job_speedy`.`job_speedy_category_id` = `job_speedy_category`.`job_speedy_category_id`	
        		WHERE 
        				`job_application`.`status` = 'hired'        						
           		GROUP BY 
        				`job_speedy`.`job_speedy_code`
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_result($job_speedy_code,$job_title,$job_speedy_category_id);
        $stmt->execute();
        $stmt->store_result();
        while($stmt->fetch())
        {
	        $numbers_array = getSpeedyPoolArraybyJobSpeedyCode($job_speedy_code);

	        $speedy_pool_array[$job_speedy_code] = array(
				'job_title' => $job_title,
				'category_id' => $job_speedy_category_id,
				'total' => $numbers_array['total'],
				'male' => $numbers_array['male'],
				'female' => $numbers_array['female'],
				'not_specified' => $numbers_array['not_specified']
			);


        };
        $stmt->free_result();
        $stmt->close();

        return $speedy_pool_array;
    }

	/**
	 * getSpeedyPoolArraybyJobSpeedyCode function.
	 *
	 * Gives the numbers in the pool based on the speedy code
	 *
	 * @access public
	 * @param string $job_speedy_code
	 * @return array
	 */
	public function getSpeedyPoolArraybyJobSpeedyCode($job_speedy_code)
    {
	    $numbers_array = array();

        $sql = "SELECT
        				COUNT(`job_application`.`status`) as 'total',
        				COUNT(CASE WHEN `address_book_per`.`sex` = 'male' THEN 1 ELSE NULL END ) as 'male',
        				COUNT(CASE WHEN `address_book_per`.`sex` = 'female' THEN 1 ELSE NULL END ) as 'female',
        				COUNT(CASE WHEN (
        								   `address_book_per`.`sex` = 'not specified' 
        								OR `address_book_per`.`sex` = NULL 
        								OR `address_book_per`.`sex` = ''
        								
        								) THEN 1 ELSE NULL END ) as 'not specified'
        		FROM 
        				`job_application`	
        		LEFT JOIN
        				`address_book_per`
        		ON
        				`job_application`.`address_book_id` = `address_book_per`.`address_book_id`
        		WHERE 
        				`job_application`.`status` = 'hired'        						
           		AND 
        				`job_application`.`job_speedy_code` = ?
        		";

        $stmt = $this->db->prepare($sql);
        $stmt->bind('i',$job_speedy_code);
        $stmt->bind_result($total,$male,$female,$not_specified);
        $stmt->execute();
        if($stmt->fetch())
        {
	        $numbers_array = array(
		        'total' => $total,
		        'male' => $male,
		        'female' => $female,
		        'not_specified' => $not_specified
	        );
        };

        $stmt->close();

        return $numbers_array;
    }

    /**
     * getSpeedyPoolWithDemandDatatable function.
     *
     * Produces an array of the full current speedy pool by speedy job code. I included the category id so it can be matched as required.
     *
     * @access public
     * @return array
     */
    public function getSpeedyPoolWithDemandDatatable()
    {

        $this->validateRequest($_POST);
        $request = $_POST;
        $table = 'job_speedy';
        $primaryKey = 'job_speedy.job_speedy_code';

        $columns = array(
            array('db' => 'job_speedy.job_speedy_code', 'dt' => 'job_speedy_code'),
            array('db' => 'job_speedy.job_title', 'dt' => 'job_title'),
            array('db' => 'COUNT(`job_application`.`status`)', 'as' => 'count', 'dt' => 'count'),
            array('db' => 'demand.demand', 'dt' => 'demand'),
            array('db' => 'demand.allocated', 'dt' => 'allocated'),
        );

        $limit = $this->limit($request, $columns);
        $order = $this->order($request, $columns);

        $join = 'LEFT JOIN
        				`job_application`
        		ON
        				`job_application`.`job_speedy_code` = `job_speedy`.`job_speedy_code` AND `job_application`.`status` = \'hired\'
				LEFT JOIN
        				`job_speedy_category`
        		ON
        				`job_speedy`.`job_speedy_category_id` = `job_speedy_category`.`job_speedy_category_id`
        				
        		LEFT JOIN (
        		
        			SELECT
						`job_master`.`job_speedy_code`,
						SUM(`job_demand_master`.`demand`) as demand,
        				COUNT(`job_demand_allocation`.`job_demand_master_id`) AS `allocated`
                    FROM 
                            `job_demand_master`
                    LEFT JOIN
                            `job_master`
                    ON
                            `job_master`.`job_master_id` = `job_demand_master`.`job_master_id`
                    LEFT JOIN
                            `job_demand_allocation`
                    ON
                            `job_demand_master`.`job_demand_master_id` = `job_demand_allocation`.`job_demand_master_id`
                    WHERE 
                            `job_demand_master`.`expiry_on` >= NOW()
                    AND
                            `job_master`.`job_speedy_code` IN (
                                SELECT
                                    `job_speedy_code`
                                FROM
                                    `job_speedy`
                            )
                   GROUP BY
                            `job_master`.`job_speedy_code`
        		
        		) `demand` on `demand`.`job_speedy_code` = `job_speedy`.`job_speedy_code` ';

        $where = $this->filter($request, $columns, $bindings);

        $having =' HAVING (COUNT(`job_application`.`status`) > 0 OR `demand`.`demand` > 0 )';
        $group = ' GROUP BY
        				job_speedy.job_speedy_code ';


        $qry1 = "SELECT " . implode(", ", self::pluck($columns, 'db')) . "
             FROM `$table`
             $join
             $where
             $group
             $having
             $order
			 $limit";
			 
        $data = $this->db->query_array($qry1);
        // Data set length after filtering
        $qry = "SELECT COUNT(`job_application`.`status`) as total, `demand`.`demand`
			 FROM   `$table`
			  $join
             $where
             $group
             $having
             $order";
		$resFilterLength = $this->db->query_array($qry);
		$recordsFiltered = 0;

		if (count($resFilterLength) > 0) {
			$recordsFiltered = $resFilterLength[0]['total'];
		}

        // Total data set length
        $qry = "SELECT COUNT(`job_application`.`status`) as total, `demand`.`demand`
             FROM   `$table`  $join
             $group";
		$resTotalLength = $this->db->query_array($qry);
		
		$recordsTotal = 0;

		if (count($resTotalLength) > 0) {
			$recordsTotal = $resTotalLength[0]['total'];
		}

        return array(
            "draw" => isset ($request['draw']) ?
                intval($request['draw']) :
                0,
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $this->data_output($columns, $data),
        );
    }

}
?>